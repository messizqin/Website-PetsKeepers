<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
javascript parser for one-to-many relationship table illustrating
access restricted
an admin page, that illustrates tables of database within one-to-many relationship. because there are quite a few tables, I wrote an jsParser in php that allow administer to resort table sequence by clicking on the table header, also, if they click on the row, it brings them to a child table that associated with it, it's looped until there are no more child tables. due to this, 90% of my code in the admin page is javascript, by using element.innerHTML, I inject html into one div <div class='table-container'></div>

to archive delete and edit: delete is pretty easy, I only need to write a confirm form. but edit, man, I need to revalidate every stuff that the administer has entered before pass them to the database, which is suppported by admin.js, integrated in admin.php, with an executed sequence of admin.js -> parser.php, which all predefined in admin.js is reach-able in parser.php, for example, last_called_parser, is the last row-clock triggered function that sort out the table.

there is no tags in html page that I can access from button line javascript, since most of my contents are temporarily generated from parser. therefore, I need to write eventlistener scripts in string format and add them to the parser immediately after the code is injected.
*/

require_once('../php/sess.php');

// url direct access deny
$session_handler = new Sess();
session_start();

if(isset($_SESSION) && $_SESSION['admin']['retry'] == 1){}else{
	require_once('../php/inverse.php');
	echo "<h2>403 Forbidden</h2>";
	echo "<p>Sorry, this page cannot be accessed directly from url</p>";
	echo '<hr />';
	echo '<a href="' . Inverse::root() . '/php/index.php' . '">Click here to the home page</a>';
    die();
}

// keys: table names; values: wanted to be editable fields; ID FIELD IS REQUIRED;
$tables = array('users' => array('id', 'first_name', 'last_name', 'email', 'phone'), 'dates' => array('id', 'booked', 'sendin', 'pickup', 'duration'), 'pets' => array('id', 'category', 'petname', 'petage', 'petweight'));

// parser.php handles backend data, following files handle frontend table illustation.
$files = array('users' => '../php/users_table.php', 'dates' => '../php/dates_table.php', 'pets' => '../php/pets_table.php');

// admin.php always and only exist tag
$output = '#admin-container';

// store parsed funcs into array, implode at last
$func = array();
// edit button and delete button click, catched in admin.js
$btn_func = "function button_click(){document.querySelectorAll('button.conduct.btn-outline-secondary').forEach(bt=>{bt.addEventListener('click', ()=>{btn_clicked = true; start_edit(JSON.parse(bt.value));});});document.querySelectorAll('button.conduct.btn-outline-danger').forEach(bt=>{bt.addEventListener('click', ()=>{btn_clicked = true; confirm_delete(JSON.parse(bt.value));});});};";

// users, dates, pets
$array_keys = array_keys($tables);

// the goback green button only shows up if there is previous table
// goback_depth controls goback btn display in goback_func, which is triggered each time gobackbtn is clicked
$vars = 'var goback_depth = 0; var btn_clicked = false; var ';
$tbv = 'var ';
$goback_func = 'function goback_control(){switch(goback_depth){';
$goback_event = 'goback.addEventListener("click", ()=>{switch(goback_depth){';
foreach($array_keys as $k => $v){
	if($k != count($array_keys) - 1){
		$vars .= $v . '_table_indicator, ';
		$tbv .= 'tb_' . $v . '_id, '; 
	}
	if($k == 0){
		$goback_func .= 'case ' . $k . ': $(goback).hide(); break;';
	}else{
		$goback_func .= 'case ' . $k . ': ' . $array_keys[$k-1] . '_table_indicator(); $(goback).show(); break;';
		$goback_event .= 'case ' . $k . ':' . $array_keys[$k-1] . '_table_indicator();break;';
	}
}
$goback_event .= '}goback_depth--;goback_control();});';
// remove comma, add semicolon
$vars = substr($vars, 0, -2);
$vars .= ';';
$tbv = substr($tbv, 0, -2);
$tbv .= ';';
$vars .= $tbv;
$vars .= 'var goback = document.getElementById("goback");';
$goback_func .= '}}';

// funcs allow user to click on table headers to resort the table 
// in first click, sort in ascending sequence
// in second click, sort in descending sequence
$counter = -1;
foreach ($tables as $tbname => $tbarray){
	$counter++;
	$notlast = $counter != count($array_keys)-1;
	$isfirst = $counter == 0;
	$col = array();
	$type = $isfirst ? 'general' : $array_keys[$counter-1];
	foreach ($tbarray as $item){
		// str: asc funcs; stri: desc funcs; one should follow another
		$str = '';
		$stri = '';
		$str .= 'function ' . $tbname . '_' . $item . '_order_asc(){';
		$stri .= 'function ' . $tbname . '_' . $item . '_order_desc(){';
		if($notlast){
			// goback click brings to parent table last sorted view
			$str .=  $tbname . '_table_indicator = ' . $tbname . '_' . $item . '_order_asc;';
			$stri .=  $tbname . '_table_indicator = ' . $tbname . '_' . $item . '_order_desc;';
			// admin.js variable last_called_parser, used to refresh page and anchor
			$str .= 'last_called_parser = ' . $tbname . '_' . $item . '_order_asc;';
			$stri .= 'last_called_parser = ' . $tbname . '_' . $item . '_order_desc;';
		}
		if($isfirst){
			$tbid = '';
		}else{
			$tbid = '"' . $type . '_id": tb_' . $type . '_id, ';
		}
		// instead of sorting on our own machine, ask server to sort it and pass all information back
		$str .= '$.ajax({url: "' .  $files[$tbname] . '", method: "POST", data: {"type": "' . $type . '", ' . $tbid . '"order": "' . $item . '", "seq": "ASC"},}).done(function(result){var data = JSON.parse(result);$("' . $output . '")' . '.html(data.htm);' . 'var cols = document.querySelectorAll("th");';
		$stri .= '$.ajax({url: "' .  $files[$tbname] . '", method: "POST", data: {"type": "' . $type . '", ' . $tbid . '"order": "' . $item . '", "seq": "DESC"},}).done(function(result){var data = JSON.parse(result);$("' . $output . '")' . '.html(data.htm);' . 'var cols = document.querySelectorAll("th");';
		foreach($tbarray as $key => $value){
			$str .= 'cols[' . $key . '].addEventListener("click", ()=>{' . $tbname . '_' . $value . '_order_';
			$stri .= 'cols[' . $key . '].addEventListener("click", ()=>{' . $tbname . '_' . $value . '_order_';
			if($value == $item){
				$str .= 'desc();});';
				$stri .= 'asc();});';
			}else{
				$str .= 'asc();});';
				$stri .= 'asc();});';
			}
		}
		if($notlast){
			$str .= $tbname . '_row_click();'; 
			$stri .= $tbname . '_row_click();'; 
		}
		$str .= 'button_click();});}';
		$stri .= 'button_click();});}';
		array_push($func, $str, $stri);
	}
	if($notlast){
		$strin = 'function ' . $array_keys[$counter+1] . '_table_by_' . $array_keys[$counter] . '_id(' . $array_keys[$counter] . '_id){tb_' . $array_keys[$counter] . '_id = ' . $array_keys[$counter] . '_id;' . $array_keys[$counter+1] . '_id_order_asc();}';
		// readd row click after sorted
		$string = 'function ' . $array_keys[$counter] . '_row_click(){' . 'document.querySelectorAll("tbody tr").forEach(tr=>{tr.addEventListener("click", ()=>{if(btn_clicked){btn_clicked = false;}else{goback_depth++;goback_control();' . $array_keys[$counter+1] . '_table_by_' . $array_keys[$counter] . '_id(tr.firstElementChild.innerHTML)}});});}';
		array_push($func, $strin, $string);
	}
}

$win = 'window.addEventListener("load", ()=>{' . $array_keys[0] . '_id_order_asc();});';

$funcs = implode('', $func);
// iife
$javascript = '(function(){' . $vars . $btn_func . $goback_func . $goback_event . $funcs . $win . '})();';
echo $javascript;

?>
