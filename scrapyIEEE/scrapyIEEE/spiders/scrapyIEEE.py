from scrapy.spiders import BaseSpider
from scrapy.selector import Selector
from scrapy.contrib.loader import XPathItemLoader
import os

import json
import datetime
from dateutil import relativedelta

class scrapyIEEESpider(BaseSpider):

    name = "scrapyIEEE"

    def __init__(self, search=''):
        self.start_urls = ["http://ieeexplore.ieee.org/search/searchresult.jsp?newsearch=true&queryText=" + search]

    allowed_domains = ["http://ieeexplore.ieee.org/Xplore/home.jsp"]

    def parse(self, response):

        hxs = HtmlXPathSelector(response)

        titles = hxs.xpath("//a[contains(@class, 'ng-binding ng-scope')]/text()").extract()

        print titles

        #
        # title = []
        # link = []
        # date = []
        # time = []
        # department =  []
        # eventType = []
        # location = []
        #
        # # converting to standard date function
        # def AppendDates(text):
        #
        #     text = text.split()
        #     lst = iter(text)
        #
        #     # ignore dis!
        #     next(lst)
        #
        #     month = ConvertMonth(next(lst))
        #     day = next(lst)[:2]
        #     year = next(lst)[2:]
        #
        #     print str(0) + str(month) + '/' + str(day) + '/' + str(year)
        #
        #     date.append(str(0) + str(month) + '/' + str(day) + '/' + str(year))
        #
        #     if(len(text) == 10):
        #
        #         # ignore dis!
        #         next(lst)
        #
        #         times = next(lst) + next(lst) + next(lst) + next(lst) + next(lst)
        #
        #         time.append(times)
        #
        #     # edge case without the time
        #     else:
        #
        #         time.append('N/A')
        #
        # def ConvertMonth(x):
        #     return {
        #
        #         'Jan': 1,
        #         'Feb': 2,
        #         'Mar': 3,
        #         'Apr': 4,
        #         'May': 5,
        #         'Jun': 6,
        #         'Jul': 7,
        #         'Aug': 8,
        #         'Sep': 9,
        #         'Oct': 10,
        #         'Nov': 11,
        #         'Dec': 12,
        #
        #     }[x]
        #
        # def encodeString(text):
        #
        #     if(text != []):
        #         text = text[0].encode('utf-8').strip("[]").strip("u").strip("''")
        #     else:
        #         text = "N/A"
        #
        #     return text
        #
        # eventTitle = hxs.xpath("//h3")
        # for eventTitle in eventTitle:
        #
        #     convertTitle = eventTitle.xpath("a/text()").extract()
        #     title.append(encodeString(convertTitle))
        #
        #     convertLink = eventTitle.xpath("a/@href").extract()
        #     link.append(encodeString(convertLink))
        #
        #
        # eventStats = hxs.xpath("//div[contains(@id, 'events')]/ul/li")
        #
        # for eventStats in eventStats:
        #
        #     parseDate = str(eventStats.xpath("div[contains(@class, 'event_stats')]/p/strong/text()").extract()).strip("[]").strip("u").strip("''")
        #     AppendDates(parseDate)
        #
        #     convertDepartment = eventStats.xpath("div[contains(@class, 'event_stats')]/p[2]/text()").extract()
        #     department.append(encodeString(convertDepartment))
        #
        #     convertEventType = eventStats.xpath("div[contains(@class, 'event_stats')]/p[3]/text()").extract()
        #     eventType.append(encodeString(convertEventType))
        #
        #     convertLocation = eventStats.xpath("p[contains(text(), 'Location: ')]/a/text()").extract()
        #     location.append(encodeString(convertLocation))
        #
        # prefixURL = "http://viterbi.usc.edu/news/events/"
