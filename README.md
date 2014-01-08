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
You store the tag as a tag in a database or template file ([tag:youtube("http://youtu.be/lxNORk0vKd0")] in this case).
On page render, you run the processTags function and all tags get replaced with real content.



More complex example that shows multiple tags in one string 
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


Creating new tags
=============
Create a function that looks like this:
```php
private function helloWorld($args=array()) {
    return $this->replace("Hello World");
}
```
This would replace the tag [tag:helloWorld()] with "Hello World" (no quotes)

If you want parameters passed into the function, use the $args array parameter

simple example with 1 arg
```php
private function displayLine($args=array()) {
    $line = "<div style='width:".$args[0].";height:0px;border-bottom:#000 1px solid;' ></div>";
    return $this->replace($line);
}
```
The tag would look like: [tag:displayLine(25px)] or [tag:displayLine("25px")]

Simple example with 2 args
```php
private function displaySquare($args=array()) {
    $square = "<div style='width:".$args[0].";height:".$args[1].";border:#000 1px solid;' ></div>";
    return $this->replace($square);
}
```
The tag would look like: [tag:displaySquare(25px, 25px)] or [tag:displaydisplaySquareLine("25px", "25px")]
