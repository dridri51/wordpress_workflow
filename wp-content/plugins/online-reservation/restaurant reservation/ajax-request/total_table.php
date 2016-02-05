<?php
	/*
		TABLE OF CONTENTS
		========================
		1.	POST DATA
		2.	RETRIEVE DATABASE DATA
		3.	SHOW TABLE
	*/

	/*########################################
		1.	POST DATA
	########################################*/	
	$Options 	= $_POST['options'];
	$table_name	= $_POST['table_name'];

	/*########################################
		2.	RETRIEVE DATABASE DATA
	########################################*/	
	$options = $Options;
	

	/*########################################################################
		3.	SHOW TABLE
	########################################################################*/	
		/*========================================
			1.	TABLE NAME
		========================================*/
		$table_name = $table_name;
		$total_table = $options[$table_name . '_table'];
		$out = '';
		for( $x = 1; $x <= $total_table; $x++ ){
			$out .='<option value="'.$x.'" >'.$x.'</option>';
		}
		echo $out;