taggingengine
=============

Custom tagging engine for PHP
taggingengine
=============

Custom tagging engine for PHP


Usage
================
```php
include 'tag.php';
$content = 'this is a test [tag:youtube("http://youtu.be/lxNORk0vKd0")] this is a test';
$tag = new Tag();
$newcontent = $tag->processTag($content);
echo $newcontent;
```

What is going on
=======================
You store the tag as a tag in the database ([tag:youtube("http://youtu.be/lxNORk0vKd0")] in this case).
On page render, you run the processTags function and all tags get replaced with real content.



More complex example that shows multiple tags in one string (most likely loaded from the database or read from a template file)
=========================
```php
include 'tag.php';
$content = '
this is a test [tag:youtube("http://youtu.be/lxNORk0vKd0")] this is a test <br />
[tag:todaysDate()] <br />
[tag:shortDate()] <br />
[tag:helloWorld()] <br />
[tag:displayLine("25px")] <br />
[tag:displaySquare("25px", "60px")] <br />
';
$tag = new Tag();
echo $tag->processTag($content);
```
