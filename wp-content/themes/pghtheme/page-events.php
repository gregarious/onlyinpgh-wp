<?php 

/*
Template Name: Events new
*/

get_header() ?>

<div id="wrapper">

	<div id="content" class="page-events">
	
	<?php
	$m = $_GET[month];
	?>
	
	<h1><?php echo $m; ?></h1>

	</div> <!-- #content -->
</div> <!-- #wrapper -->

<?php get_footer() ?>