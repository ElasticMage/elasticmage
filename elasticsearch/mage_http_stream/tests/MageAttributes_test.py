
from ..src.MageAttributes import MageAttributes

from nose.tools import *
import unittest

import fudge

class MageAttributes_(unittest.TestCase):
	def setUp(self):
		conn = fudge.Fake('pymysql')
		conn.provides('cursor').returns_fake()
		self.attr = MageAttributes(conn)

	def test_if_it_gets_label_from_eav_attribute_table(self):
		cur = (self.attr.cur.provides('execute')
			.returns_fake()
			.provides('fetchone')
			.returns(('my_attribute',)))
		eq_(self.attr.getLabel('42'), 'my_attribute')

	def test_if_it_caches_returned_value(self):
		cur = (
			self.attr.cur.provides('execute')
				.returns_fake()
				.provides('fetchone')
				.returns(('my_attribute',))
				.next_call()
				.returns(None)
		)
		eq_(self.attr.getLabel('42'), 'my_attribute')
		eq_(self.attr.getLabel('42'), 'my_attribute')
