
from ..src.MageMapper import MageMapper

from nose.tools import *
import unittest

import fudge


class MageMapper_(unittest.TestCase):
    def test_if_it_skips_non_product_data(self):
        eq_(
            MageMapper().map({
                "action" : "insert",
                "table" : "core_config",
                "doc" : {"sku" : "testSku"}
            }),
            None
        )

    def test_if_it_keeps_document_for_the_main_product_table(self):
        eq_(
            MageMapper().map({
                "action" : "insert",
                "table" : "catalog_product_entity",
                "doc" : {"sku" : "testSku"}
            })["doc"],
            {
                "sku" : "testSku"
            }
        )

            
    def test_if_it_adds_item_id(self):
        eq_(
            MageMapper().map({
                "action" : "insert",
                "table" : "catalog_product_entity",
                "doc" : {
                    "entity_id" : "12",
                    "sku" : "testSku"
                }
            })["id"],
            "12"
        )

    def test_if_it_converts_magento_eav_to_main_document_structure(self):
        attributes = (fudge.Fake('MageAttributes')
            .provides('getLabel')
            .with_args('42')
            .returns('my_attribute')
        )
        eq_(
            MageMapper(attributes).map({
                "action" : "insert",
                "table" : "catalog_product_entity_int",
                "doc" : {
                    "entity_id" : "12",
                    "entity_type_id" : "4242",
                    "attribute_id" : "42",
                    "value" : "9",
                }
            }),
            {
                "action" : "update",
                "table" : "catalog_product_entity",
                "id" : "12",
                "doc" : {
                    "entity_id" : "12",
                    "my_attribute" : "9"
                }
            }
        )

    def test_if_it_reforms_category_relation(self):
        eq_(
            MageMapper().map({
                "action" : "insert",
                "table" : "catalog_category_product",
                "doc" : {
                    "category_id" : "12",
                    "product_id" : "42",
                    "position" : "9"
                }
            }),
            {
                "action" : "update",
                "table" : "catalog_product_entity",
                "id" : "42",
                "doc" : {
                    "categories" : [ "12" ],
                    "category_pos" : {"12" : "9"}
                }
            }
        )
