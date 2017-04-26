from scrapy.spiders import BaseSpider
from scrapy.selector import HtmlXPathSelector
from scrapy.contrib.loader import XPathItemLoader

import urlparse
import webbrowser

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
        bibtext = response.xpath("//meta[@name='citation_abstract_html_url']/@content")[0].extract()

        count = 0
        for c in bibtext:
            if(c.isdigit()):
                bibtext = bibtext[count:]
                break
            else:
                count+=1

        parent_id = ""
        for c in bibtext:
            if not c.isdigit():
                break
            parent_id += c

        bibtext_url = "http://dl.acm.org/downformats.cfm?id=" + self.id + "&parent_id=" + parent_id + "&expformat=bibtex"
        webbrowser.open(bibtext_url)  # Open bibtext

        with open('./abstract.json', 'w') as outfile:
            json.dump({'abstract': parent_id}, outfile)
