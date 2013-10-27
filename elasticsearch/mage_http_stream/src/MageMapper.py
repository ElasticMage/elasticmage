

class MageMapper(object):

	def __init__(self, attributes = None):
		self.attributes = attributes

	def map(self, data):
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

