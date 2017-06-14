import yaml

class crawlerBitConfiguration():

    # Configuration for open the config.yaml file
    CONFIGURATION_FILE_NAME = "config.yaml"
    FILE_LOAD_TYPE = "r"

    # Tags in the configuration file
    PROJECT_CONFIGURATION_KEY = "PROJECT_CONFIGURATION"
    CRAWLER_NAME_KEY = "crawler_name"

    SEEDS_CONFIGURATION_KEY = "SEEDS_CONFIGURATION"
    SEEDS_FILE_NAME_KEY = "file_name"

    DATABASE_CONFIGURATION_KEY = "DATABASE_CONFIGURATION"
    DATABASE_TYPE_KEY = "type"
    DATABASE_NAME_KEY = "name"

    @staticmethod
    def getCrawlerName():
        configuration = crawlerBitConfiguration.__getConfigurationFile()
        configuration = configuration.get(crawlerBitConfiguration.PROJECT_CONFIGURATION_KEY)

        return configuration.get(crawlerBitConfiguration.CRAWLER_NAME_KEY)

    @staticmethod
    def getSeedsFileName():
        configuration = crawlerBitConfiguration.__getConfigurationFile()
        configuration = configuration.get(crawlerBitConfiguration.SEEDS_CONFIGURATION_KEY)

        return configuration.get(crawlerBitConfiguration.SEEDS_FILE_NAME_KEY)

    @staticmethod
    def getDatabaseConfigurations():
        configuration = crawlerBitConfiguratio.__getConfigurationFile()

        return configuration.get(crawlerBitConfiguration.DATABASE_CONFIGURATION_KEY)

    @staticmethod
    def __getConfigurationFile():
        file = open(crawlerBitConfiguration.CONFIGURATION_FILE_NAME, crawlerBitConfiguration.FILE_LOAD_TYPE)

        with file as stream:
            try:
                return yaml.load(stream)
            except yaml.YAMLError as execution:
                print(execution)