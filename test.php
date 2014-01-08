<?php
include 'tag.php';



$content = 'this is a test [tag:youtube("http://youtu.be/lxNORk0vKd0",300,200)] this is a test';

$tag = new Tag($tagstring="tag");
$newcontent = $tag->processTags($content);

echo $newcontent;



?>
