<?php


/*
Template Name: Events Calendar
*/


?>


<script type-"text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>

<!--<script type="text/javascript">  
	
	function prevMonthSwap(prevMonthVal,prevYearVal) {
	    jQuery("#calendar-container").html("<h1>LOADING</h1>").show();
	    var ajaxurl = "admin_url('admin-ajax.php');"
	    jQuery.post(ajaxurl, {action: 'my_special_action', month: prevMonthVal, year: prevYearVal}, function(data){
	        jQuery("#calendar-container").html(data).show();
	    });
	    return false;
	}

	function nextMonthSwap(nextMonthVal,nextYearVal) {
	    jQuery("#calendar-container").html("<h1>LOADING</h1>").show();
	    var ajaxurl = "admin_url('admin-ajax.php');"
	    jQuery.post(ajaxurl, {action: 'my_special_action', month: nextMonthVal, year: nextYearVal}, function(data){
	        jQuery("#calendar-container").html(data).show();
	    });
	    return false;
	}

	// Attach the link event handlers
	jQuery(document).ready(function($) {
	    $('#prev-link').click(function(){ return prevMonthSwap(<?php echo $prevMonthVal; ?>,<?php echo $prevYearVal; ?>)});
	    $('#next-link').click(function(){ return nextMonthSwap(<?php echo $nextMonthVal; ?>,<?php echo $nextYearVal; ?>)});
	});


</script>-->

<?php

get_header(); 

?>

<div id="content">
	<div class="padder" id="cal-page">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<div class="calendar-nav" id="top">
					<a id="prev-link" href="<?php echo $prev_href; ?>">&larr; Previous</a>
				&nbsp;&nbsp;&nbsp;&nbsp;
					<a id="next-link" href="<?php echo $next_href; ?>">Next &rarr;</a>
			</div>
			
			<h2 class="pagetitle">Upcoming Events: <?php echo $month_title;?></h2>

			<div id="calendar-container">
			
				<?php draw_calendar($month, $year); ?>

			</div>

			<div class="calendar-nav" id="bottom">
				<a id="prev-link" href="<?php echo $prev_href; ?>">&larr; Previous</a>
			&nbsp;&nbsp;&nbsp;&nbsp;
				<a id="next-link" href="<?php echo $next_href; ?>">Next &rarr;</a>
			</div>
								
		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

	</div><!-- .padder -->
		
</div><!-- #content -->

<?php get_footer() ?>