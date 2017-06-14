import scrapy
import sqlite3

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
        url = url[0]

        title = response.xpath("//title/text()").extract_first()
        time  = datetime.now().strftime(CrawlerBit.TIME_FORMAT)

        content = ''.join(response.xpath("//body").extract()),
        content = content[0]

        #TODO: Save elements in database
        #https://stackoverflow.com/questions/22799990/beatifulsoup4-get-text-still-has-javascript

        # GO HORSE ... GO!!!!

        print("------------------------------------")
        print (url)
        print (title)
        print (time)
        #print (content)
        print("------------------------------------")

        database = sqlite3.connect('crawlerbit.db')
        connection = database.cursor()
        connection.execute("CREATE TABLE IF NOT EXISTS raw_crawler_data (id integer PRIMARY KEY AUTOINCREMENT, url text NOT NULL, content text NOT NULL, time datetime NOT NULL);")

        connection.execute("INSERT INTO raw_crawler_data (url, content, time) VALUES (?, ?, ?)", (url, content, time))
        database.commit()

        connection.close()
        database.close()

        # yield {
        #    "url": response.url,
        #    "title":  response.xpath("//title/text()").extract_first(),
        #    "time": datetime.now().strftime(CrawlerBit.TIME_FORMAT)
        # }

        extractor = LinkExtractor()
        links = extractor.extract_links(response)

        for link in links:
            yield scrapy.Request(
                link.url,
                callback = self.parse
            )