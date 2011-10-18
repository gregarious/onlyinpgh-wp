<ul class="rss-list">

<?php 

// Get site title - so we can print the source for Google Reader articles
// http://stackoverflow.com/questions/4348912/get-title-of-website-via-link
function getTitle($url){
    $str = file_get_contents($url);
    if(strlen($str)>0){
        preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
        return $title[1];
    }
}

// http://digwp.com/2009/11/import-and-display-feeds-in-wordpress/
if ( function_exists('fetch_feed') ) {

	include_once(ABSPATH . WPINC . '/feed.php');               // include the required file

	// Show a different feed per scene
	global $bp;
	$group = $bp->groups->current_group->name;
	$art = 'Art Scene';
	$music = 'Music Scene';

	if ( $group == $music ) { 
		$feed = fetch_feed('http://www.google.com/reader/shared/LaraS126');
	} elseif ( $group == $art ){ 
		$feed = fetch_feed('http://notlaura.com/studioblog/feed/');
	}

	$limit = $feed->get_item_quantity(10); // specify number of items
	$items = $feed->get_items(0, $limit); // create an array of items

}

if ($limit == 0) echo '<div>The feed is either empty or unavailable.</div>';
else foreach ($items as $item) : 

	// Parse the site url so it is just the domain name so that getTitle() returns the website title, not the article title
	//http://stackoverflow.com/questions/276516/parsing-domain-from-url-in-php
	$url = $item->get_permalink();
	$parse = parse_url($url);
	$domain = 'http://' . $parse['host']; // prints 'google.com' 
	$desc = strip_tags(substr($item->get_description(), 0, 200)); 

	?>



	<a href="<?php echo $url; ?>" target="_blank">

	  	<li>
	  		<h3 class="rss-title">
				<?php echo $item->get_title(); ?>
			</h3>

			<p class="rss-postedon">Source:</p><?php
			
			if( getTitle($domain) != 'Google FeedBurner' ) { ?>
				<h4 class="rss-blog"> <?php echo getTitle($domain); ?></h4><?php
			} else { ?>
				<p>(Unavailable)</p><?php
			} ?>

			<p class="rss-date"><?php echo $item->get_date('F j, Y'); ?></p>

			<p class="rss-desc">
				<?php echo $desc; ?> 
				<span>...</span>
			</p>

		</li>
	</a>

<?php endforeach; ?>

</ul>
