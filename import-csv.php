<?php
	define('PREPEND_PATH', '');
	include_once(__DIR__ . '/lib.php');

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'students_table' => function($data, $options = []) {
			if(isset($data['dob'])) $data['dob'] = guessMySQLDateTime($data['dob']);
			if(isset($data['admission_date'])) $data['admission_date'] = guessMySQLDateTime($data['admission_date']);
			if(isset($data['department'])) $data['department'] = pkGivenLookupText($data['department'], 'students_table', 'department');
			if(isset($data['course'])) $data['course'] = pkGivenLookupText($data['course'], 'students_table', 'course');

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
		'subject_table' => function($data, $options = []) {
			if(isset($data['course_details'])) $data['course_details'] = pkGivenLookupText($data['course_details'], 'subject_table', 'course_details');
			if(isset($data['faculty_details'])) $data['faculty_details'] = pkGivenLookupText($data['faculty_details'], 'subject_table', 'faculty_details');

			return $data;
		},
		'enrollment_table' => function($data, $options = []) {
			if(isset($data['student_details'])) $data['student_details'] = pkGivenLookupText($data['student_details'], 'enrollment_table', 'student_details');
			if(isset($data['course_details'])) $data['course_details'] = pkGivenLookupText($data['course_details'], 'enrollment_table', 'course_details');
			if(isset($data['subject_details'])) $data['subject_details'] = pkGivenLookupText($data['subject_details'], 'enrollment_table', 'subject_details');

			return $data;
		},
		'exams_table' => function($data, $options = []) {
			if(isset($data['subject_details'])) $data['subject_details'] = pkGivenLookupText($data['subject_details'], 'exams_table', 'subject_details');
			if(isset($data['exam_date'])) $data['exam_date'] = guessMySQLDateTime($data['exam_date']);

			return $data;
		},
		'results_table' => function($data, $options = []) {
			if(isset($data['exam_details'])) $data['exam_details'] = pkGivenLookupText($data['exam_details'], 'results_table', 'exam_details');
			if(isset($data['student_details'])) $data['student_details'] = pkGivenLookupText($data['student_details'], 'results_table', 'student_details');

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'students_table' => function($data, $options = []) { return true; },
		'faculty_table' => function($data, $options = []) { return true; },
		'departments_table' => function($data, $options = []) { return true; },
		'courses_table' => function($data, $options = []) { return true; },
		'subject_table' => function($data, $options = []) { return true; },
		'enrollment_table' => function($data, $options = []) { return true; },
		'exams_table' => function($data, $options = []) { return true; },
		'results_table' => function($data, $options = []) { return true; },
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
