

class MageMapper(object):

    def __init__(self, attributes = None):
        self.attributes = attributes

    def map(self, data):
        if data["table"] == "catalog_category_product":
            return self.__map_catagory_relations(data)

        if not data["table"].startswith("catalog_product_entity"):
            return None

        try:
            data["id"] = data["doc"]["entity_id"]
        except:
            pass

        if data["table"] != "catalog_product_entity":
            return self.__map_eav_child_table(data)

        return data

    def __map_eav_child_table(self, data):
        try:
            label = self.attributes.getLabel(data["doc"]["attribute_id"])
            data["table"] = "catalog_product_entity"
            data["action"] = "update"
            doc = {}
            doc["entity_id"] = data["doc"]["entity_id"]
            doc[label] = data["doc"]["value"]
            data["doc"] = doc
            return data
        except:
            print "unsupported data", data

    def __map_catagory_relations(self, data):
        ret = {}
        ret["action"] = "update"
        ret["table"] = "catalog_product_entity"
        ret["id"] = data["doc"]["product_id"]
        ret["doc"] = {
            "categories": [ data["doc"]["category_id"] ],
            "category_pos": { data["doc"]["category_id"] : data["doc"]["position"] }
        }
        return ret
