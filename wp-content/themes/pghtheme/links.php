<?php get_header() ?>

	<div id="wrapper">
<div id="main-content" style="padding: 0px;width:955px;">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="eventsearch_bar" style="height: 60px;margin-bottom: 20px;width:650px;">
<p class="searchbar_head" style="float: left;">Find photos<br>taken</p>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Search Photos') ) : ?>
<?php endif; ?>
</div> <div id="contestinfo_bar"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Contest Info') ) : ?>
<?php endif; ?></div>
<?php the_content(); ?>


<div id="photos_sidebar">
<dl>
<dt>
<a href="/">Photos Key</a><img src="<?php bloginfo('stylesheet_directory'); ?>/images/expandarrow.png"/>
</dt>
<dd>
<?php echo GeoMashup::category_legend() ?>
</dd>
<dt>
<a href="/">Submit a Photo</a><img src="<?php bloginfo('stylesheet_directory'); ?>/images/expandarrow.png"/>
</dt>
<dd>
<div id="submitwidget"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Submit Photos') ) : ?>
<?php endif; ?></div>
</dd>
<dt>
<a href="/">Recent Photos</a><img src="<?php bloginfo('stylesheet_directory'); ?>/images/expandarrow.png"/>
</dt>
<dd>
<div id="submitwidget"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Photo Sidebar 3') ) : ?>
<?php endif; ?></div>
</dd>

</dl>
</div>
<?php endwhile; endif; ?>
</div>
</div><!-- #content -->
<?php get_footer() ?>
