
class MageAttributes(object):

	def __init__(self, conn):
		self.conn = conn
		self.cur = conn.cursor()

	def getLabel(self, attr_id):
		res = self.cur.execute("SELECT `attribute_code` FROM `eav_attribute` WHERE `attribute_id` = {0}".format(attr_id))
		try:
			return res.fetchone()[0]
		except:
			return None
