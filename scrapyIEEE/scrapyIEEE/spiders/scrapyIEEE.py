from scrapy.spiders import BaseSpider
from scrapy.selector import Selector
from scrapy.contrib.loader import XPathItemLoader
import os

import json
import time
import datetime
from dateutil import relativedelta

class scrapyIEEESpider(BaseSpider):

    name = "scrapyIEEE"

    def __init__(self, search=''):
        self.start_urls = ["http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=" + search]

    allowed_domains = ["http://ieeexplore.ieee.org/"]

    def parse(self, response):

        xs = Selector(response)
        #details = xs.xpath("//document/pdf")
        details = xs.xpath('document/pdf/text()')

        for content in details.extract():
            print content
