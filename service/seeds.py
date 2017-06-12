import os.path

class seeds():

    SEEDS_FILE = "seeds.txt"
    READ_MODE = "r"

    @staticmethod
    def __getFileSeeds():
        with open(os.path.dirname(__file__) + "/../" + seeds.SEEDS_FILE, seeds.READ_MODE) as file:
            return file.read().splitlines()

    @staticmethod
    def getSeeds():
        return seeds.__getFileSeeds()