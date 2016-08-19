<?php
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Negativenull\Cbatags\Tag;




$content = 'this is a test [tag:youtube("http://youtu.be/lxNORk0vKd0",300,200)] this is a test';

$tag = new Tag($tagstring="tag");
$newcontent = $tag->processTags($content);

echo $newcontent;



?>
