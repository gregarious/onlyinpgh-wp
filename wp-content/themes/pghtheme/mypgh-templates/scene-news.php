<ul class="rss-list">

<?php 
// Show a different feed per scene
global $bp;
$group = $bp->groups->current_group->name;
$art = 'Arts Scene';
$music = 'Music Scene';

if ( $group == $music ) { 
	// Nina's Google Reader feed
	$feed_url = 'http://www.google.com/reader/public/atom/user%2F04855140688536519265%2Fstate%2Fcom.google%2Fbroadcast';

} elseif ( $group == $art ){
	// Carrie's Google Reader feed
	$feed_url = 'http://www.google.com/reader/public/atom/user%2F06185373270144193201%2Fstate%2Fcom.google%2Fbroadcast';
}

$ch = curl_init($feed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);

$xmlstr = curl_exec($ch);
curl_close($ch);

$xml = simplexml_load_string( $xmlstr );

if ( count($xml->entry) == 0 ):
	echo '<div>The feed is either empty or unavailable.</div>';	
else:
	foreach($xml->entry as $entry) : 
		$title = $entry->title;
		$source = $entry->source->title;
		$desc = substr(strip_tags($entry->content), 0, 200);
		$pub_dt = new DateTime($entry->published);
		$pub_date = $pub_dt->format('F j, Y');
		$link_att = $entry->link->attributes();
		$url = $link_att['href'];
		?>

		<a href="<?php echo $url; ?>" target="_blank">

		  	<li>
		  		<h3 class="rss-title">
					<?php echo $title; ?>
				</h3>

				<p class="rss-postedon">Source:</p><h4 class="rss-blog"> <?php echo $source; ?></h4>
				<p class="rss-date"><?php echo $pub_date; ?></p>
				<p class="rss-desc">
					<?php echo $desc; ?> 
					<span>...</span>
				</p>

			</li>
		</a>
<?php endforeach; ?>
<?php endif; ?>

</ul>
<? 