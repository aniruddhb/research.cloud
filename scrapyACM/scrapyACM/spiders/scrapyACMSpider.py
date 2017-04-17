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

import json

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

    with open('./metadata.json', mode='w') as f:
        json.dump([], f)

    def parse(self, response):

        def encodeString(text):

            if(text != []):
                text = text[0].encode('utf-8').strip("[]").strip("u").strip("''")
            else:
                text = "N/A"

            return text

        with open('./metadata.json') as f:
            data = json.load(f)

        for content in response.xpath('//div[contains(@class, "details")]'):
            title = encodeString(content.xpath('./div[contains(@class, "title")]/a/text()').extract())

            authors = []
            for names in content.xpath('.//div[contains(@class, "authors")]/a'):
                authors.append(encodeString(names.xpath('./text()').extract()))

            conference = encodeString(content.xpath('./div[contains(@class, "source")]/span[@style="padding-left:10px"]/text()').extract())

            paperID = encodeString(content.xpath('./div[contains(@class, "title")]/a/@href').extract())

            start = paperID.find('id=')
            end = paperID.find('&', start)
            paperID = paperID[start:end]
            paperID = paperID[3:]

            entry = {'title': title, 'authors': authors, 'conference': conference, 'publisher': 'ACM', 'paperID': paperID}
            data.append(entry)

        with open('./metadata.json', mode='w') as f:
            json.dump(data, f)

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
