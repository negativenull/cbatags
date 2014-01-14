#!/usr/bin/python
from tag import Tag

tags = Tag('tag')
content = '''
[tag:youtube("http://youtu.be/lxNORk0vKd0",300,200)] 
[tag:helloWorld()] 
[tag:displayLine("25px")] 
[tag:displaySquare("25px", 99px)] 
[tag:todaysDate()] 
[tag:shortDate()] 
'''
print tags.processTags(content)

