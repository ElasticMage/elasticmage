#!/usr/bin/env python
#
# Update a redis server cache when an evenement is trigger
# in MySQL replication log
#

from pymysqlreplication import BinLogStreamReader
from pymysqlreplication.row_event import *

mysql_settings = {'host': '127.0.0.1', 'port': 3306, 'user': 'root', 'passwd': 'root', 'db':'elasticmage'}

import json

import pymysql

from MageMapper import MageMapper
from MageAttributes import MageAttributes

import requests

def default(obj):
    """Default JSON serializer."""
    import calendar, datetime

    if isinstance(obj, datetime.datetime):
        if obj.utcoffset() is not None:
            obj = obj - obj.utcoffset()
        millis = int(
            calendar.timegm(obj.timetuple()) * 1000 +
            obj.microsecond / 1000
        )
        return millis
    if isinstance(obj, decimal.Decimal):
        return str(obj)



class Streamer(object):
    def __init__(self, mapper):
        self.mapper = mapper
        self.stream = BinLogStreamReader(connection_settings = mysql_settings, only_events = [DeleteRowsEvent, WriteRowsEvent, UpdateRowsEvent], blocking = True, resume_stream = True)


    def index(self):
        def content():
            for binlogevent in self.stream:
                for row in binlogevent.rows:
                    data = None
                    if isinstance(binlogevent, DeleteRowsEvent):
                        data = {
                          "action": "delete",
                          "table": binlogevent.table,
                          "doc": row["values"]
                        }
                    elif isinstance(binlogevent, UpdateRowsEvent):
                        data = {
                          "action": "update",
                          "table": binlogevent.table,
                          "doc": row["after_values"]
                        }
                    elif isinstance(binlogevent, WriteRowsEvent):
                        data = {
                          "action": "insert",
                          "table": binlogevent.table,
                          "doc": row["values"]
                        }
                    if data is not None:
                        data = self.mapper.map(data)
                    if data is not None:
                        # yield json.dumps(data, default=default) + "\n"
                        yield data

        return content()

    index.exposed = True
    index._cp_config = {"response.stream": True}


if __name__ == "__main__":
    connection = pymysql.connect(**mysql_settings)
    for event in Streamer(MageMapper(MageAttributes(connection))).index():
		if event["action"] == "insert":
			requests.put("http://localhost:9200/magehack/product/"+str(event["id"]), json.dumps(event["doc"], default=default))
		elif event["action"] == "update":
			r = requests.get("http://localhost:9200/magehack/product/"+str(event["id"]))
			if r.status_code == 200:
				orig = json.loads(r.text)
				if orig["exists"]:
				    event["doc"] = dict(orig["_source"].items() + event["doc"].items())
			requests.put("http://localhost:9200/magehack/product/"+str(event["id"]), json.dumps(event["doc"], default=default))
#			print "update: ", event
# 			requests.put("http://localhost:9200/magehack/product/"+str(event["id"])+"/_update", json.dumps({"doc":event["doc"], "doc_as_upsert" : "true", "retry_on_conflict": 100}, default=default))


