<?php 

/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

/*
ajax subhandler called from parser.php
one table only integrition for users/dates/pets_table.php
*/

function table_html($table_headers, $table_cells, $table_id, $table_titles){
	$appendable = '<table id="' . $table_id . '_table" class="scroll table table-hover">';

	function admin_table_head($cells){
		$str = '';
		$str .= "<thead class='thead-dark'><tr>";
		foreach($cells as $cell){
			$str .= "<th>" . $cell . "</th>";
		}
		$str .= "<th colspan='2'>Conduct</th></tr></thead>";
		return $str;
	}

	function admin_table_body($cells, $sep, $table_id, $table_titles){
		$str = '';
		$str .= "<tbody>";
		$counter = -1;
		$arr = array();
		$arr['table'] = $table_id; 
		foreach($cells as $cell){
			$counter++;
			if($counter == 0){
				$arr['id'] = $cell;
				$str .= "<tr><td>" . $cell . "</td>";
			}else if($counter == $sep-1){
				$counter = -1;
				$arr[$table_titles[$sep-2]] = $cell;
				$str .= "<td>" . $cell . "</td><td><button value='" . json_encode($arr) . "' class='conduct btn btn-outline-secondary'>Edit</button></td></td><td><button value='" . json_encode($arr) . "' class='conduct btn btn-outline-danger'>Delete</button></td></tr>";
				$arr = array();
				$arr['table'] = $table_id; 
			}else{
				$str .= "<td>" . $cell . "</td>";
				$arr[$table_titles[$counter-1]] = $cell;
			}
		}
		return $str;
	}

	$appendable .= admin_table_head($table_headers);
	$appendable .= admin_table_body($table_cells, count($table_headers), $table_id, $table_titles);
	$appendable .= "</table>";

	return $appendable;
}

?>