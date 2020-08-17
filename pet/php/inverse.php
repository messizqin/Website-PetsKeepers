<?php

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
Manipulate Path as String
*/

// remove double or single quotes
function dequote($pos){
	$rm1[] = '"';
	$rm2[] = "'";
	$n1 = str_replace($rm1, "", $pos);
	$n2 = str_replace($rm2, "", $n1);
	return $n2; 
}

// remove n items from end of a path in string format
function rewind_url($dire, $ind){
	$pieces = explode('/', $dire);
	for($i=0; $i<$ind; $i++){
		array_pop($pieces);
	}
	$pieces = implode('/', $pieces);
	return $pieces;
}

// append each item of the array to the end of the path, return a path in string format
function append_url($dire, $arr){
	$pieces = explode('/', $dire);
	foreach($arr as $ar){
		array_push($pieces, $ar);
	}
	$pieces = implode('/', $pieces);
	return $pieces;
}

/*
Inverse Class: namespace for getting absolute file path as string in php files
*/

// inve: return inverse.php absolute path
// root: return absolute path of ../php directory
// phpd: return absolute path of $fname php file
class Inverse{
	public static function inve(){return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";}
	public static function root(){return rewind_url(Inverse::inve(), 2);}
	public static function phpd($fname){return append_url(Inverse::root(), array('php', $fname.'.php'));}
}

?>