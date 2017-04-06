from scrapy.spiders import BaseSpider
from scrapy.selector import Selector
from scrapy.contrib.loader import XPathItemLoader
import os

import json
import time
import datetime
from dateutil import relativedelta

from scrapy.http import Request

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

            print "ll"

            yield Request(
                url=response.urljoin(content),
                callback=self.save_pdf
            )

    def save_pdf(self, response):

        print "ll"

        path = response.url.split('/')[-1]
        self.logger.info('Saving PDF %s', path)

        downloadLink = str(path)
        start = downloadLink.find('=')
        pdfTitle = downloadLink[start:downloadLink.len()-1]

        if "pdf" in str(path):
            with open(pdfTitle + ".pdf", 'wb') as f:
                f.write(response.body)
