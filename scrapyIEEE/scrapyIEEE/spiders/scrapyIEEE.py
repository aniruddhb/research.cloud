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

    def __init__(self, search='', searchType=''):
        self.start_urls = ["http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=" + search]
        self.searchType = searchType

    allowed_domains = []

    def parse(self, response):

        xs = Selector(response)
        if (self.searchType == 'paper'):
            details = xs.xpath('//document/arnumber/text()')
            self.yieldPaper(details)
        elif (self.searchType == 'abstract'):
            titles = xs.xpath('//document/title/text()')
            authors = xs.xpath('//document/authors/text()')
            conferences = xs.xpath('//document/pubtitle/text()')
            abstracts = xs.xpath('//document/abstract/text()')
            ids = xs.xpath('//document/arnumber/text()')
            self.yieldAbstract(abstracts, ids, titles, authors, conferences)


    def yieldPaper(self, details):
        for content in details.extract():

            content = "http://ieeexplore.ieee.org/stamp/stamp.jsp?arnumber=" + content

            yield Request(
                # meta = {
                #       'dont_redirect': True,
                #       'handle_httpstatus_list': [302]
                # },
                url=response.urljoin(content),
                callback=self.get_pdf_link
            )

    def yieldAbstract(self, abstracts, ids, titles, authors, conferences):

        with open('./metadata.json', mode='w') as f:
            json.dump([], f)

        with open('./metadata.json') as f:
            data = json.load(f)

        for i in range (0, 25):
            title = titles[i].extract()
            abstract = abstracts[i].extract()
            author = authors[i].extract()
            conference = conferences[i].extract()
            if (abstract[0] == '<'):
                abstract = "No abstract available"
            data.append({str(ids[i].extract()): {'title': title, 'author': author, 'conference': conference, 'publisher': 'IEEE', 'abstract': abstract}})

        with open('./metadata.json', mode='w') as f:
            json.dump(data, f)


    def get_pdf_link(self, response):

        downloadLink = str(response.url)
        start = downloadLink.find('=')
        end = downloadLink.find('&', start)
        if (start != -1 and end != -1):
            pdfTitle = downloadLink[start+1:end]
        elif (start != -1):
            pdfTitle = downloadLink[start+1:len(downloadLink)]
        else:
            start = downloadLink.find('/', 34)
            end = downloadLink.find('/', start)
            pdfTitle = downloadLink[start+1:end]

        pdf = response.xpath('//frame[2]/@src')

        for content in pdf.extract():

            yield Request(
                # meta = {
                #       'dont_redirect': True,
                #       'handle_httpstatus_list': [302]
                # },
                url=response.urljoin(content),
                callback=self.save_pdf
            )

    def save_pdf(self, response):

        downloadLink = str(response.url)
        start = downloadLink.rfind('arnumber=')
        end = downloadLink.find('&', start)
        if (start != -1 and end != -1):
            pdfTitle = downloadLink[start+9:end]
        elif (start != -1):
            pdfTitle = downloadLink[start+1:len(downloadLink)]
        else:
            start = downloadLink.find('/', 34)
            end = downloadLink.find('/', start)
            pdfTitle = downloadLink[start+1:end]

        with open("../../pdf/" + pdfTitle + ".pdf", 'wb') as f:
            f.write(response.body)
