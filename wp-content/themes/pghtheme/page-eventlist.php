<?php


/*
Template Name: Event List
*/


get_header(); 

require_once('eventlist.php');

?>
<div id="wrapper" class="cal">

	<div class="padder" id="eventlist">

		<span id="cal-buttons" class="alignright">
			<a href="?date=<?php echo $prev_day_anchor; ?>" class="day-nav-link" id="cal-nav-prev">&lt;&lt;</a>
			<a href="?date=<?php echo $next_day_anchor; ?>" class="day-nav-link" id="cal-nav-next">&gt;&gt;</a>
		</span>
		<h2 class="page-title"><?php echo $date_txt; ?></h2>
		<ul class="eventslist-day">

		<?php
		foreach($events as $event) {
			?>
			
			<a href="/event/?eid=<?php echo $event['id']; ?>" target="_blank">
				<li>
					<div id="img-container">
						<img src="<?php echo $event['image_url']; ?>" class="alignleft">
					</div>
					<h3 class="el-name">
						<?php echo $event['name']; ?>
					</h3>
				
					<?php	
					// Only show 40 words of description
					$desc =$event['description_short'];
					$array = explode(" ",$desc,31);
					unset($array[30]);
					$limited = implode(" ",$array); ?>

					<?php

					$start_date_str = $event['start_dt']->format('M j');
					$start_time_str = $event['start_dt']->format('g:ia');
					$end_date_str = $event['end_dt']->format('M j');
					$end_time_str = $event['end_dt']->format('g:ia');

					echo '<p class="el-time">' . $start_date_str . ' ' . $start_time_str . ' - ';
					if( !onSameDay($event['start_dt'],$event['end_dt']) ) {
						echo $end_date_str . ' ';
					}
					echo $end_time_str . '</p>' . "\n"; ?>

					<div id="el-host-address" class="alignleft">
						<p class="hostedby">Hosted by</h4>
						<h4 class="host"><?php echo $event['org_name']; ?></h4>
						<p class="event-address"><?php echo $event['address'];?></p> 
					</div> <!-- #el-host-address -->

					<p class="el-desc alignleft"><?php echo $limited; ?>...</p>

				</li>
			</a>
				
		<?php } ?>

		</ul>
					
	</div><!-- .padder#eventlist -->

</div><!-- #wrapper -->
			
<?php get_footer() ?>