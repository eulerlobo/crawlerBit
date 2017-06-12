import scrapy
import datetime

from service.seeds import seeds
from scrapy.linkextractors import LinkExtractor
from crawlerBitConfiguration import crawlerBitConfiguration

class CrawlerBit(scrapy.Spider):
    name = crawlerBitConfiguration.getCrawlerName()
    start_urls = seeds.getSeeds()

    def parse(self, response):

        yield {
            'url': response.url,
            'content': response.xpath("//body").extract(),
            'time': datetime.datetime.now()
        }

        extractor = LinkExtractor()
        links = extractor.extract_links(response)

        for link in links:
            yield scrapy.Request(
                link.url,
                callback = self.parse
            )