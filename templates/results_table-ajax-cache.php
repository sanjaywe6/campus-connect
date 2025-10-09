<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'results_table';

		/* data for selected record, or defaults if none is selected */
		var data = {
			exam_details: <?php echo json_encode(['id' => $rdata['exam_details'], 'value' => $rdata['exam_details'], 'text' => $jdata['exam_details']]); ?>,
			student_details: <?php echo json_encode(['id' => $rdata['student_details'], 'value' => $rdata['student_details'], 'text' => $jdata['student_details']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for exam_details */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'exam_details' && d.id == data.exam_details.id)
				return { results: [ data.exam_details ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for student_details */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'student_details' && d.id == data.student_details.id)
				return { results: [ data.student_details ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

