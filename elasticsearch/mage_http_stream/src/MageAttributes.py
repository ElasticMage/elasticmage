
class MageAttributes(object):

	def __init__(self, conn):
		self.conn = conn
		self.cur = conn.cursor()
		self.cache = {}

	def getLabel(self, attr_id):
		try:
			return self.cache[attr_id]
		except:
			return self.__retrieve_value(attr_id)

	def __retrieve_value(self, attr_id):
		res = self.cur.execute("SELECT `attribute_code` FROM `eav_attribute` WHERE `attribute_id` = {0}".format(attr_id))
		try:
			data = res.fetchone()[0]
			self.cache[attr_id] = data
			return data
		except:
			return None
