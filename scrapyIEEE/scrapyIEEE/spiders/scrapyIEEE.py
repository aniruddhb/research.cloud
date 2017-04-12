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

    allowed_domains = []

    def parse(self, response):

        xs = Selector(response)
        details = xs.xpath('//document/arnumber/text()')

        for content in details.extract():

            content = "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=" + content

            yield Request(
                url=response.urljoin(content),
                callback=self.save_pdf
            )

    def save_pdf(self, response):

        downloadLink = str(response.url)
        start = downloadLink.find('=')
        if (start != -1):
            pdfTitle = downloadLink[start+1:len(downloadLink)]
        else:
            start = downloadLink.find('/', 34)
            end = downloadLink.find('/', start)
            pdfTitle = downloadLink[start+1:end]

        with open("../../pdf/" + pdfTitle + ".pdf", 'wb') as f:
            f.write(response.body)
