import re
import csv
import StringIO


class Tag:
    tagstring = 'tag'
    content = ''
    tag = ''

    def __init__(self, tagstring):
        self.tagstring = tagstring

    def processTags(self, content):
        newcontent = content
        self.content = content
        tagstringoffset = len(self.tagstring) + 2

        pattern = re.compile('(\[' + self.tagstring + ':[a-zA-Z]*\([^\[\]]*\)\])')
        for tag in re.findall(pattern, content):
            self.tag = tag
            tagpattern = re.compile(".*:(.*)\((.*)\)")
            matches = re.search(tagpattern, tag)
            funcstr = matches.groups(0)[0]
            paramstring = matches.groups(0)[1]
            reader = csv.reader(StringIO.StringIO(paramstring), delimiter=',', skipinitialspace=True)
            params = []
            for row in reader:
                params = row
            try:

                func = getattr(self, funcstr)
            except AttributeError:
                0  # do nothing
            else:

                newcontent = func(params)

            self.content = newcontent
        return newcontent

    def replace(self, newstring):
        return self.content.replace(self.tag, newstring, 1)

    # *************************************************
    #** Example Tag Functions ************************
    #*************************************************
    # Notes:
    # Very simple example function
    #
    def helloWorld(self, args):
        return self.replace("Hello World");

    #
    # This would replace the tag [tag:helloWorld()] with Hello World
    #

    #
    # if you want parameters passed into the function, use the $args array parameter
    # simple example with 1 arg
    #
    def displayLine(self, args):
        line = "<div style='width:%s;height:0px;border-bottom:#000 1px solid;' ></div>" % args[0]
        return self.replace(line)

    #
    # The tag would look like: [tag:displayLine(25px)] or [tag:displayLine("25px")]
    #
    # simple example with 2 args
    #
    def displaySquare(self, args):
        square = "<div style='width:%s;height:%s;border:#000 1px solid;' ></div>" % (args[0], args[1])
        return self.replace(square)

    #
    # The tag would look like: [tag:displaySquare(100px,100px)] or [tag:displaySquare("100px","100px")]
    #

    def todaysDate(self, args):
        import time

        return self.replace(time.strftime('%A %B %d, %Y'))

    def shortDate(self, args):
        import time

        return self.replace(time.strftime("%m/%d/%Y"))

    def comment(self, args):
        return self.replace('')

    def youtube(self, args):
        url = args[0]
        width = 560
        height = 315
        if (len(args) == 3):
            width = args[1]
            height = args[2]

        from urlparse import urlparse

        o = urlparse(url)
        path = o.path

        vidcontent = '<iframe width="' + width + '" height="' + height + '" src="//www.youtube.com/embed' + path + '" frameborder="0" allowfullscreen></iframe>'

        return self.replace(vidcontent)
