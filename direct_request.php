<html>
<head>
<title>PHP XPath Google Calendar Integration Sample</title>
</head>
<body>
<h2>
	This simple page displays the last three months and next three months of<br>
	confirmed public events from a dummy Google Calendar account
</h2>

<?php 
	$confirmed = 'http://schemas.google.com/g/2005#event.confirmed';

	$three_months_in_seconds = 60 * 60 * 24 * 28 * 3;
	$three_months_ago = date("Y-m-d\Th:i:sP", time() - $three_months_in_seconds);
	$three_months_from_today = date("Y-m-d\Th:i:sP", time() + $three_months_in_seconds);

	$feed = "http://www.google.com/calendar/feeds/kartanonkaari22%40gmail.com/" . 
		"public/full?orderby=starttime&singleevents=true&" . 
		"start-min=" . $three_months_ago . "&" .
		"start-max=" . $three_months_from_today;

	$s = simplexml_load_file($feed); 

	foreach ($s->entry as $item) {
		$gd = $item->children('http://schemas.google.com/g/2005');

		if ($gd->eventStatus->attributes()->value == $confirmed) {
 ?>
			<font size=+1><b>
				<?php print $item->title; ?>
			</b></font><br>

			<?php 
			$startTime = '';
			if ( $gd->when ) {
				$startTime = $gd->when->attributes()->startTime;
			} elseif ( $gd->recurrence ) {
				$startTime = $gd->recurrence->when->attributes()->startTime; 
			} 

			print date("l jS \o\f F Y - h:i A", strtotime( $startTime ) );
			// Google Calendar API's support of timezones is buggy
			print " AST<br>";
			?>
			<?php print $gd->where->attributes()->valueString; ?><br>
			<br>

<?php
		}
} ?>

</body>
</html>