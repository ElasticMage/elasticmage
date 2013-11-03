
from ..src.MageAttributes import MageAttributes

from nose.tools import *
import unittest

import fudge

class MageAttributes_(unittest.TestCase):
    def setUp(self):
        self.cur = fudge.Fake('pymysql.cursor')
        self.cur.provides('close')
        conn = fudge.Fake('pymysql')
        conn.provides('cursor').returns(self.cur)
        self.attr = MageAttributes(conn)

    def test_if_it_gets_label_from_eav_attribute_table(self):
        self.cur.provides('execute').returns_fake()
        self.cur.provides('fetchone').returns(('my_attribute',))
        eq_(self.attr.getLabel('42'), 'my_attribute')

    def test_if_it_caches_returned_value(self):
        self.cur.provides('execute').returns_fake()
        self.cur.provides('fetchone').returns(('my_attribute',)).next_call().returns(None)
        eq_(self.attr.getLabel('42'), 'my_attribute')
        eq_(self.attr.getLabel('42'), 'my_attribute')

    def test_if_it_gets_elasticsearch_field_mapping_type_for_attribute(self):
        self.cur.provides('execute').returns_fake()
        self.cur.provides('fetchone').returns(('text', 'textarea'))
        eq_(self.attr.getMappingType('42'), 'string')

    def test_if_it_gets_list_of_all_attributes_for_entity(self):
        pass

    def test_if_it_removes_item_from_cache(self):
        self.cur.provides('execute').returns_fake()
        self.cur.provides('fetchone').returns(('my_attribute',)).times_called(2)
        eq_(self.attr.getLabel('42'), 'my_attribute')
        self.attr.removeCacheByTagId('42')
        eq_(self.attr.getLabel('42'), 'my_attribute')

    def test_if_it_finds_multi_field_applicable_attributes(self):
        self.cur.provides('execute').returns_fake()
        (self.cur.provides('fetchone').returns(('1', '1', '1', '1'))
            .next_call().returns(('1','0','0','0'))
            .next_call().returns(('0','1','0','0')))

        eq_(self.attr.isMultiField('42'), True)
        eq_(self.attr.isMultiField('43'), False)
        eq_(self.attr.isMultiField('44'), True)
