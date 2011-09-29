<?php


/*
Template Name: Single Day Events
*/


get_header(); 

?>
<div id="wrapper" class="cal">

	<div class="padder" id="eventlist">

			<?php require_once('eventlist.php') ?>
					
	</div><!-- .padder#eventlist -->

</div><!-- #wrapper -->
			
<?php get_footer() ?>