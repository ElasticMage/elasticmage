import hashlib

class MageAttributes(object):

    # 'backend_type': 'frontend_type': 'elasticsearch_type'
    attribute_map = {
        "text": {
            "_default": {
                "elastic_type": "string",
                "null_value": None
            }
        },
        "varchar": {
            "_default": {
                "elastic_type": "string",
                "null_value": None
            }
        },
        "int": {
            "_default": {
                "elastic_type": "integer",
                "null_value": 0
            },
            "boolean": {
                "elastic_type": "boolean"
            },
            "select": {
                "elastic_type": "string",
                "null_value": None
            }
        },
        "decimal": {
            "_default": {
                "elastic_type": "float",
                "null_value": 0.0
            }
        },
        "datetime": {
            "_default": {
                "elastic_type": "date",
                "format": "YYYY-MM-dd HH:mm:ss"
            }
        },
        "static": {
            "_default": {
                "elastic_type": "string"
            },
            "date": {
                "elastic_type": "date",
                "format": "YYYY-MM-dd HH:mm:ss"
            }
        }
    }

    def __init__(self, conn):
        self.conn = conn
        self.cache = {}

    def getLabel(self, attr_id):
        query = "SELECT `attribute_code` FROM `eav_attribute` WHERE `attribute_id` = {0}".format(attr_id)
        return self.__retrieve_value(query, attr_id)[0]

    def __retrieve_value(self, query, cache_tag_id = None, fetchall = None):
        key = hashlib.md5(query).hexdigest()
        try:
            return self.cache[cache_tag_id][key]
        except KeyError:
            if cache_tag_id and not cache_tag_id in self.cache:
                self.cache[cache_tag_id] = {}

            try:
                cur = self.conn.cursor()
                cur.execute(query)
                if fetchall:
                    data = cur.fetchall()
                else:
                    data = cur.fetchone()
                cur.close()
                if cache_tag_id:
                    self.cache[cache_tag_id][key] = data
                return data
            except Exception as e:
                print "Unable to run query: '{0}' got:\n\n{1}".format(query, e)
                return None

    def __get_attribute_types(self, attr_id):
        query = "SELECT `backend_type`, `frontend_type` FROM `eav_attribute` WHERE `attribute_id` = {0}".format(attr_id)
        return self.__retrieve_value(query, attr_id)

    def getMappingType(self, attr_id):
        data = self.__get_attribute_types(attr_id)

        # Default to string
        elastic_type = 'string'
        try:
            elastic_type = self.attribute_map[data[0]][data[1]]['elastic_type']
        except KeyError:
            try:
                elastic_type = self.attribute_map[data[0]]['_default']['elastic_type']
            except KeyError:
                pass

        return elastic_type

    def removeCacheByTagId(self, cache_tag_id):
        try:
            del self.cache[cache_tag_id]
        except:
            pass

    def __get_attribute_multi(self, attr_id):
        query = "SELECT `is_searchable`, `is_filterable`, `is_filterable_in_search`, `used_for_sort_by` FROM `catalog_eav_attribute` WHERE `attribute_id` = {0}".format(attr_id)
        return self.__retrieve_value(query, attr_id)

    def isSearchable(self, attr_id):
        return True if self.__get_attribute_multi(attr_id)[0] == '1' else False

    def isFilterable(self, attr_id):
        return True if self.__get_attribute_multi(attr_id)[1] == '1' else False

    def isFilterableInSearch(self, attr_id):
        return True if self.__get_attribute_multi(attr_id)[2] == '1' else False

    def isSortable(self, attr_id):
        return True if self.__get_attribute_multi(attr_id)[3] == '1' else False

    def isMultiField(self, attr_id):
        if (self.isFilterable(attr_id) or
            self.isSortable(attr_id) or
            self.isFilterableInSearch(attr_id)):
            return True
        return False

    def getAttributesForEntity(self, entity_type_id):
        query = "SELECT `attribute_id` FROM `eav_attribute` WHERE `entity_type_id` = {0}".format(entity_type_id)
        data = self.__retrieve_value(query, None, True)
        return [item[0] for item in data]

