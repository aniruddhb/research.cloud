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

        self.number = number
        self.counter = 0

        if 20 > int(number):
            self.start_urls = ["http://dl.acm.org/results.cfm?query=" + search + "&start=0&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"]
        elif 20 < int(number) < 40:
            self.start_urls = ["http://dl.acm.org/results.cfm?query=" + search + "&start=0&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score",
                               "http://dl.acm.org/results.cfm?query=" + search + "&start=20&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"]
        elif 40 < int(number) < 60:
            self.start_urls = ["http://dl.acm.org/results.cfm?query=" + search + "&start=0&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score",
                               "http://dl.acm.org/results.cfm?query=" + search + "&start=20&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score",
                               "http://dl.acm.org/results.cfm?query=" + search + "&start=40&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"]

    allowed_domains = ["dl.acm.org"]

    def parse(self, response):

        print self.number

        for content in response.xpath('//div[contains(@class, "details")]/div[contains(@class, "ft")]/a'):

            url_content = str(content.xpath('@href').extract()).strip("[]").strip("u").strip("''")
            title = str(content.xpath('@name').extract()).strip("[]").strip("u").strip("''")

            print url_content

            if 'FullTextMp4' not in title:
                if self.counter < int(self.number):
                    yield Request(
                        url=response.urljoin(url_content),
                        callback=self.save_pdf
                    )
                    self.counter += 1

        print self.counter

    def save_pdf(self, response):

        path = response.url.split('/')[-1]
        self.logger.info('Saving PDF %s', path)

        downloadLink = str(path)
        start = downloadLink.find('id=')
        end = downloadLink.find('&', start)
        pdfTitle = downloadLink[start:end]

        with open("../pdfs/" + pdfTitle + ".pdf", 'wb') as f:
            f.write(response.body)
