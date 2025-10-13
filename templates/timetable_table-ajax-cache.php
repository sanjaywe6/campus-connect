<?php
	$rdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('safe_html', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'timetable_table';

		/* data for selected record, or defaults if none is selected */
		var data = {
			subject_details: <?php echo json_encode(['id' => $rdata['subject_details'], 'value' => $rdata['subject_details'], 'text' => $jdata['subject_details']]); ?>,
			faculty_details: <?php echo json_encode(['id' => $rdata['faculty_details'], 'value' => $rdata['faculty_details'], 'text' => $jdata['faculty_details']]); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for subject_details */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'subject_details' && d.id == data.subject_details.id)
				return { results: [ data.subject_details ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for faculty_details */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'faculty_details' && d.id == data.faculty_details.id)
				return { results: [ data.faculty_details ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

