

class MageMapper(object):

	def __init__(self, attributes = None):
		self.attributes = attributes

	def map(self, data):
		try:
			data["id"] = data["doc"]["entity_id"]
		except:
			pass

		if data["table"] != "catalog_product_entity":
			label = self.attributes.getLabel(data["doc"]["entity_type_id"], data["doc"]["attribute_id"])
			data["table"] = "catalog_product_entity"
			data["action"] = "update"
			doc = {}
			doc["entity_id"] = data["doc"]["entity_id"]
			doc[label] = data["doc"]["value"]
			data["doc"] = doc


		return data
