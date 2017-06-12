import scrapy

from datetime import datetime
from service.seeds import seeds
from scrapy.linkextractors import LinkExtractor
from crawlerBitConfiguration import crawlerBitConfiguration

class CrawlerBit(scrapy.Spider):

    TIME_FORMAT = "%Y-%m-%d %H:%M:%S"

    name = crawlerBitConfiguration.getCrawlerName()
    start_urls = seeds.getSeeds()

    def parse(self, response):
        url = response.url,
        title = response.xpath("//title/text()").extract_first()
        time  = datetime.now().strftime(CrawlerBit.TIME_FORMAT)
        content = response.xpath("//body").extract(),

        #TODO: Save elements in database

        yield {
            "url": response.url,
            "title":  response.xpath("//title/text()").extract_first(),
            "time": datetime.now().strftime(CrawlerBit.TIME_FORMAT)
        }

        extractor = LinkExtractor()
        links = extractor.extract_links(response)

        for link in links:
            yield scrapy.Request(
                link.url,
                callback = self.parse
            )