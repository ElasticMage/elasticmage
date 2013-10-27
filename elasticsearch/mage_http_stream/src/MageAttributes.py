
class MageAttributes(object):

    def __init__(self, conn):
        self.conn = conn
        self.cache = {}

    def getLabel(self, attr_id):
        try:
            return self.cache[attr_id]
        except:
            return self.__retrieve_value(attr_id)

    def __retrieve_value(self, attr_id):
        try:
            cur = self.conn.cursor()
            cur.execute("SELECT `attribute_code` FROM `eav_attribute` WHERE `attribute_id` = {0}".format(attr_id))
            data = cur.fetchone()[0]
            cur.close()
            self.cache[attr_id] = data
            return data
        except Exception as e:
            print "could not retr attr label for ", attr_id, e
            return None
