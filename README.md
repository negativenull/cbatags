CBAtags
=============

Custom tagging engine for PHP or Python

What is a tag
================
A tag is a simple text block that is representive of what *should* be there, but is not generated/replaced until page load.

```
[tag:youtube("http://youtu.be/lxNORk0vKd0")]
[tag:helloWorld()]
```

The above tags would get rendered as:
```html
<iframe width="560" height="315" src="//www.youtube.com/embed/lxNORk0vKd0" frameborder="0" allowfullscreen></iframe>
Hello World
```

Usage (php)
================
```php
include 'tag.php';
$content = 'this is a test [tag:youtube("http://youtu.be/lxNORk0vKd0")] this is a test';
$tag = new Tag();
$newcontent = $tag->processTag($content);
echo $newcontent;
```

Usage (python)
================
```python
#!/usr/bin/python
from tag import Tag

tags = Tag('tag')
content = 'this is a test [tag:youtube("http://youtu.be/lxNORk0vKd0")] this is a test'
print tags.processTags(content)
```


More complex example
=========================
```php
include 'tag.php';
$content = '
this is a test [tagger:youtube("http://youtu.be/lxNORk0vKd0")] this is a test <br />
[tag:todaysDate()] <br />
[tag:shortDate()] <br />
[tag:helloWorld()] <br />
[tag:displayLine("25px")] <br />
[tag:displaySquare("25px", "60px")] <br />
';
$tag = new Tag($tagstring='tagger');
echo $tag->processTag($content);
```

```python
#!/usr/bin/python
from tag import Tag

tags = Tag('tagger')
content = '''
[tagger:youtube("http://youtu.be/lxNORk0vKd0",300,200)] 
[tagger:helloWorld()] 
[tagger:displayLine("25px")] 
[tagger:displaySquare("25px", 99px)] 
'''
print tags.processTags(content)
```

Notice I am using "tagger" instead of "tag" here.  That is customizable.


Creating new tags
=============
php:
```php
private function helloWorld($args=array()) {
    return $this->replace("Hello World");
}
```
python
```python
def helloWorld(self, args):	
		return self.replace("Hello World");
```



Simple example with 1 arg
================
php
```php
private function displayLine($args=array()) {
    $line = "<div style='width:".$args[0].";height:0px;border-bottom:#000 1px solid;' ></div>";
    return $this->replace($line);
}
```
python
```python
def displayLine(self, args):
		line = "<div style='width:%s;height:0px;border-bottom:#000 1px solid;' ></div>"%args[0]
		return self.replace(line)
```
The tag would look like: [tag:displayLine(25px)] or [tag:displayLine("25px")]

Simple example with 2 args
================
php
```php
private function displaySquare($args=array()) {
    $square = "<div style='width:".$args[0].";height:".$args[1].";border:#000 1px solid;' ></div>";
    return $this->replace($square);
}
```
python
```python
def displaySquare(self, args):
		square = "<div style='width:%s;height:%s;border:#000 1px solid;' ></div>"%(args[0],args[1])
		return self.replace(square)
```

The tag would look like: [tag:displaySquare(25px, 25px)] or [tag:displaySquare("25px", "25px")]


Use case #1
=======
Coders rarely are good designers, and designers rarely can code.  Merging code with design can be a serious challenge.  This allows a designer to create a templete with hints as to what needs to go where, and then let developers create that dynamic content.

For example, a designer could use a tag like this: [tag:thumbcalendar()].  The designer wouldn't know or care what happens there, but can place the calendar there.  The developer would then make that tag to generate a thumbnail calendar.  

Also, if a designer wanted to rearrange a page, all they would have to do is move the tag, and the developers doesn't need to care.



Use case #2
=======
Customers of websites often have areas of content they can edit (through TinyMCE or the like).  That allows them to generate HTML content.  The problem is that content is fairly static.  What if a customer/user wanted to drop a thumbnail calender in line in their editable area?  Generally, they couldn't.  With these tags, there is no reason the USER can't place a tag in their code for thumbnail calendar  [tag:thumbcalendar()] but since they are not calling a PHP function directly, it is safe.  All they do is provided by the developers.


