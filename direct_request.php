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

	if (date("w") == 0) { 
     $adjuster = 6; 
    } 
    else { 
     $adjuster = date("w") - 1; 
    } 
    $startDate = date("Y-m-d", strtotime("-" .$adjuster. " days")); 
    $endDate = strtotime ( '+7 days' , strtotime ( $startDate ) ) ;
    $endDate = date ( 'Y-m-j' , $endDate );

	$feed = "http://www.google.com/calendar/feeds/kartanonkaari22%40gmail.com/" . 
		"public/full?orderby=starttime&singleevents=true&" . 
		"start-min=" . $startDate . "&" .
		"start-max=" . $endDate;

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