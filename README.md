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
Notice I am using "tagger" instead of "tag" here.  That is customizable.


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

Simple example with 1 arg
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
The tag would look like: [tag:displaySquare(25px, 25px)] or [tag:displaySquare("25px", "25px")]


Use case #1
=======
Coders rarely are good designers, and designers rarely can code.  Merging code with design can be a serious challenge.  This allows a designer to create a templete with hints as to what needs to go where, and then let developers create that dynamic content.

For example, a designer could use a tag like this: [tag:thumbcalendar()].  The designer wouldn't know or care what happens there, but can place the calendar there.  The developer would then make that tag to generate a thumbnail calendar.  

Also, if a designer wanted to rearrange a page, all they would have to do is move the tag, and the developers doesn't need to care.



Use case #2
=======
Customers of websites often have areas of content they can edit (through TinyMCE or the like).  That allows them to generate HTML content.  The problem is that content is fairly static.  What if a customer/user wanted to drop a thumbnail calender in line in their editable area?  Generally, they couldn't.  With these tags, there is no reason the USER can't place a tag in their code for thumbnail calendar  [tag:thumbcalendar()] but since they are not calling a PHP function directly, it is safe.  All they do is provided by the developers.


