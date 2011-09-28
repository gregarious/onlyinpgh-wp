<?php


/*
Template Name: Events Calendar
*/


?>

<?php

get_header(); 

?>
<div id="wrapper" class="cal">

	<div class="padder" id="cal-page">

			<?php require_once('calendar.php') ?>

			<!--<br>	
			<a href="http://localhost:8888/onlyinpgh/http://localhost:8888/onlyinpgh/event-list/?date=<?php echo $day ?>">asd</a>-->

	</div><!-- .padder#cal-page -->

</div><!-- #wrapper -->
			
<?php get_footer() ?>