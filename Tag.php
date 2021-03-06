<?php
namespace Negativenull\Cbatags;


class Tag {

	private $tagstring;
	private $cond;
	private $content;
	private $tag;
	private $variables;
	function __construct($tagstring='tag') {
		$this->tagstring=$tagstring;
		$this->cond=true;
		$this->variables = array();
	}

	function processTags($content) {
		$newContent = $content;
		$this->content = $content;
		$tagstringoffset = strlen($this->tagstring)+2;
		$offset=0;
		
		$pattern = '|\['.$this->tagstring.':.*(.*)\]|';
		
		preg_match_all($pattern, $content, $matches);
		
		foreach($matches[0] as $tag) {
			$this->tag = $tag;
			preg_match("/.*:(.*)\((.*)\)/", $tag, $output_array);
			$func = $output_array[1];
			$parmsstr = $output_array[2];
			$parms = str_getcsv($parmsstr);

			if((method_exists($this, $func) && $this->cond) || $func=='endcond') {
				$newContent = $this->$func($parms);
				$alterContent=true;
			}
			if(!$this->cond) {
				$endcondpos=strpos ( $newContent , "[".$this->tagstring.":endcond()]",$offset);
				$newContent = substr_replace ( $newContent, "" , $start, $endcondpos-4);
			}
			$this->content = $newContent;
		}
		return $newContent;
		
		
	}

	
// *****************************************************************************************************************************************
// ** Special Helper Functions *************************************************************************************************************
// *****************************************************************************************************************************************
	private function replace($newstring) {
		return $this->str_replace_once($this->tag, $newstring, $this->content);
	}
	private function str_replace_once($needle, $replace, $haystack) {
	   $pos = strpos($haystack, $needle);
	   if ($pos === false) {
		   // Nothing found
		   return $haystack;
	   }
	   
	   return substr_replace($haystack, $replace, $pos, strlen($needle));
	}
	



//*****************************************************************************************************************************************
//** Example Tag Functions ****************************************************************************************************************
//*****************************************************************************************************************************************
// Notes:
// Very simple example function
// 
	private function helloWorld($args=array()) {
		return $this->replace("Hello World");
	}
// 
// This would replace the tag [tag:helloWorld()] with Hello World
//

//
// if you want parameters passed into the function, use the $args array parameter
// simple example with 1 arg
//
	private function displayLine($args=array()) {
		$line = "<div style='width:".$args[0].";height:0px;border-bottom:#000 1px solid;' ></div>";
		return $this->replace($line);
	}
	//
// The tag would look like: [tag:displayLine(25px)] or [tag:displayLine("25px")]
//
// simple example with 2 args
//
	private function displaySquare($args=array()) {
		$square = "<div style='width:".$args[0].";height:".$args[1].";border:#000 1px solid;' ></div>";
		return $this->replace($square);
	}
//
// The tag would look like: [tag:displaySquare(100px,100px)] or [tag:displaySquare("100px","100px")]
//

//*****************************************************************************************************************************************
//** Example Tag Functions ****************************************************************************************************************
//*****************************************************************************************************************************************
	

	
	private function todaysDate($args=array()) {
		return $this->replace(date('l F j, Y'));
	}
	private function shortDate($args=array()) {
		return $this->replace(date('n/j/y'));
	}

	private function comment($args=array()) {
		return $this->replace('');
	}
	

	//example usage:
	// [tag:youtube("http://youtu.be/lxNORk0vKd0",300,200)]
	// [tag:youtube("http://youtu.be/lxNORk0vKd0")]
	private function youtube($args=array()) {
		$videostring=$args[0];
		$width=$args[1];
		$height=$args[2];
		if(empty($width)) $width=560;
		if(empty($height)) $height=315;
		$url = parse_url($videostring);
		$path = $url['path'];
		
		list($var,$value)=explode("=",$querystring);
	
		$vidcontent = '<iframe width="'.$width.'" height="'.$height.'" src="//www.youtube.com/embed'.$path.'" frameborder="0" allowfullscreen></iframe>';

		return $this->replace($vidcontent);
		
	}
	
	
	
//***********************************  Condictionals and variables (not quite working yet)
	private function assignvar($args=array()) {
		$variable = $args[0];
		$value = $args[1];
		$this->variables[$variable] = $value;
		return $this->replace('');
	}
	private function assignvartovar($args=array()) {
		$variable1 = $args[0];
		$variable2 = $args[1];
		$this->variables[$variable1] = $this->variables[$variable2];
		return $this->replace("");
	}
	private function getvar($args=array()) {
		$variable = $args[0];
		return $this->replace($this->variables[$variable]);
	}
	private function variable($args=array()) {
		$variable = $args[0];
		$value = $args[1];
		if(empty($value)) {
			return $this->replace($this->variables[$variable]);
		} else {
			$this->variables[$variable] = $value;
			return $this->replace("");
		}
	}
	private function add($args=array()) {
		$variable = $args[0];
		$value = $args[1];
		$this->variables[$variable]+=$value;
		
		return $this->replace("");
	}
	private function subtract($args=array()) {
		$variable = $args[0];
		$value = $args[1];
		$this->variables[$variable]-=$value;

		return $this->replace("");
	}
	private function multiply($args=array()) {
		$variable = $args[0];
		$value = $args[1];
		$this->variables[$variable]*=$value;

		return $this->replace("");
	}
	private function divide($content, $args=array()) {
		$variable = $args[0];
		$value = $args[1];
		$this->variables[$variable]/=$value;

		return $this->replace("");
	}
	
	
	private function cond($args=array()) {
		$var1 = $args[0];
		$var2 = $args[2];
		$operator = $args[1];

		if(!is_numeric($var1)) {
			$var1 = $this->variables[$var1];
		}
		if(!is_numeric($var2)) {
			$var2 = $this->variables[$var2];
		}

		switch($operator) {
			case "=":
				if((float)$var1===(float)$var2) {
					$this->cond=true;
				} else {
					$this->cond=false;
				}
				break;
			case "&lt;":
			case "<":
				if($var1<$var2)  {
					$this->cond=true;
				} else {
					$this->cond=false;
				}
				break;
			case "&gt;":
			case ">":
				if($var1>$var2)  {
					$this->cond=true;
				} else {
					$this->cond=false;
				}
				break;
			case "&lt;=":
			case "<=":
				if($var1<=$var2)  {
					$this->cond=true;
				} else {
					$this->cond=false;
				}
				break;
			case "&gt;=":
			case ">=":
				if($var1>=$var2)  {
					$this->cond=true;
				} else {
					$this->cond=false;
				}
				break;
			case "!=":
				if($var1!==$var2)  {
					$this->cond=true;
				} else {
					$this->cond=false;
				}
				break;
		}
		return $this->replace("");

	}

	private function endcond($args=array()) {
		$this->cond=true;
		return $this->replace("");
	}

}
?>
