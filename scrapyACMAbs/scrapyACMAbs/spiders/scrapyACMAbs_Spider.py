from scrapy.spiders import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.loader import XPathItemLoader

import urlparse

from scrapy.http import Request

import json

class scrapyACMAbsSpider(BaseSpider):

    name = "scrapyACMAbs"

    def __init__(self, search=''):

        self.id = search

        self.start_urls = ["http://dl.acm.org/citation.cfm?id=" + search + "&preflayout=flat"]

    allowed_domains = ["dl.acm.org"]

    def parse(self, response):

        def encodeString(text):

            if(text != []):
                text = text[0].encode('utf-8').strip("[]").strip("u").strip("''")
            else:
                text = "N/A"

            return text

        abstract = encodeString(response.xpath("//div[contains(@class, 'layout')]/div[2]/div//text()").extract())

        with open('./abstract.json', 'w') as outfile:
            json.dump({'abstract': abstract}, outfile)
