
class MageIndexMapper(object):

    def __init__(self, attributes = None):
        self.attributes = attributes

    def buildProductIndexMap(self):
    	entity_type_id = 10
    	mapping = {
    		'mappings':{
    			"product":{
    				"properties": {}
    			}
    		}
    	}

    	attrs = self.attributes.getAttributesForEntity(entity_type_id)

    	for attr in attrs:
    		label = self.attributes.getLabel(attr)
    		e_type = self.attributes.getMappingType(attr)
    		is_search = self.attributes.isSearchable(attr)
    		
    		mapping['mappings']['product']['properties'][label] = {
				"type": e_type,
				"index": "analyzed" if is_search else "not_analyzed"
    		}

    	return mapping
