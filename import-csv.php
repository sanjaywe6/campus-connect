<?php
	define('PREPEND_PATH', '');
	include_once(__DIR__ . '/lib.php');

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'students_table' => function($data, $options = []) {
			if(isset($data['dob'])) $data['dob'] = guessMySQLDateTime($data['dob']);
			if(isset($data['admission_date'])) $data['admission_date'] = guessMySQLDateTime($data['admission_date']);
			if(isset($data['department'])) $data['department'] = pkGivenLookupText($data['department'], 'students_table', 'department');

			return $data;
		},
		'faculty_table' => function($data, $options = []) {
			if(isset($data['dob'])) $data['dob'] = guessMySQLDateTime($data['dob']);
			if(isset($data['hire_date'])) $data['hire_date'] = guessMySQLDateTime($data['hire_date']);
			if(isset($data['department'])) $data['department'] = pkGivenLookupText($data['department'], 'faculty_table', 'department');

			return $data;
		},
		'departments_table' => function($data, $options = []) {

			return $data;
		},
		'courses_table' => function($data, $options = []) {
			if(isset($data['department'])) $data['department'] = pkGivenLookupText($data['department'], 'courses_table', 'department');
			if(isset($data['starting_date'])) $data['starting_date'] = guessMySQLDateTime($data['starting_date']);
			if(isset($data['ending_date'])) $data['ending_date'] = guessMySQLDateTime($data['ending_date']);

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'students_table' => function($data, $options = []) { return true; },
		'faculty_table' => function($data, $options = []) { return true; },
		'departments_table' => function($data, $options = []) { return true; },
		'courses_table' => function($data, $options = []) { return true; },
	];

	/*
	Hook file for overwriting/amending $transformFunctions and $filterFunctions:
	hooks/import-csv.php
	If found, it's included below

	The way this works is by either completely overwriting any of the above 2 arrays,
	or, more commonly, overwriting a single function, for example:
		$transformFunctions['tablename'] = function($data, $options = []) {
			// new definition here
			// then you must return transformed data
			return $data;
		};

	Another scenario is transforming a specific field and leaving other fields to the default
	transformation. One possible way of doing this is to store the original transformation function
	in GLOBALS array, calling it inside the custom transformation function, then modifying the
	specific field:
		$GLOBALS['originalTransformationFunction'] = $transformFunctions['tablename'];
		$transformFunctions['tablename'] = function($data, $options = []) {
			$data = call_user_func_array($GLOBALS['originalTransformationFunction'], [$data, $options]);
			$data['fieldname'] = 'transformed value';
			return $data;
		};
	*/

	@include(__DIR__ . '/hooks/import-csv.php');

	$ui = new CSVImportUI($transformFunctions, $filterFunctions);
