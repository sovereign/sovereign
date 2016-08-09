def wrap(list):
    return [ '"' + x + '"' for x in list]

class FilterModule(object):
    def filters(self):
        return {
            'wrap': wrap
        }