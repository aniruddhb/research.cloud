from scrapy.spiders import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.loader import XPathItemLoader

from scrapy.loader import ItemLoader
from scrapyACM.items import scrapyACMItem

import urlparse

from scrapy.http import Request

import os
import json
import datetime
from dateutil import relativedelta

class scrapyACMSpider(BaseSpider):

    name = "scrapyACM"

    def __init__(self, search='', number=''):
        print number
        self.start_urls = ["http://dl.acm.org/results.cfm?query=" + search + "&start=0&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"]

    allowed_domains = ["dl.acm.org"]

    # start_urls = ["http://dl.acm.org/results.cfm?query=" + researcher + "&start=0&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"]

    def parse(self, response):

        for content in response.xpath('//div[contains(@class, "details")]/div[contains(@class, "ft")]/a/@href').extract():
            #  if content.endswith('.pdf'):
            yield Request(
                url=response.urljoin(content),
                callback=self.save_pdf
            )

    def save_pdf(self, response):
        path = response.url.split('/')[-1]
        self.logger.info('Saving PDF %s', path)
        with open(path, 'wb') as f:
            f.write(response.body)
