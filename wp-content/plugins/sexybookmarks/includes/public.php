<?php

// Functions related to mobile.
require_once 'mobile.php';
$shrsb_is_mobile = shrsb_is_mobile();
$shrsb_is_bot = shrsb_is_bot();

// Written in the footer if shareaholic-javascript is on
$shrsb_js_params = array();

$shrsb_bgimg_map = array(
  'shr' => array(
    'url' => SHRSB_PLUGPATH.'images/sharing-shr.png',
    'padding' => '28px 0 0 10px',
    'class' => 'shr-bookmarks-bg-shr',
  ),
  'caring' => array(
    'url' => SHRSB_PLUGPATH.'images/sharing-caring-hearts.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-caring',
  ),
  'care-old' => array(
    'url' => SHRSB_PLUGPATH.'images/sharing-caring.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-care-old',
  ),
  'love' => array(
    'url' => SHRSB_PLUGPATH.'images/share-love-hearts.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-love',
  ),
  'wealth' => array(
    'url' => SHRSB_PLUGPATH.'images/share-wealth.png',
    'padding' => '35px 0 0 20px',
    'class' => 'shr-bookmarks-bg-wealth',
  ),
  'enjoy' => array(
    'url' => SHRSB_PLUGPATH.'images/share-enjoy.png',
    'padding' => '26px 0 0 10px',
    'class' => 'shr-bookmarks-bg-enjoy',
  ),
  'german' => array(
    'url' => SHRSB_PLUGPATH.'images/share-german.png',
    'padding' => '35px 0 0 20px',
    'class' => 'shr-bookmarks-bg-german',
  ),
  'knowledge' => array(
    'url' => SHRSB_PLUGPATH.'images/share-knowledge.png',
    'padding' => '35px 0 0 10px',
    'class' => 'shr-bookmarks-bg-knowledge',
  ),
);

/**
 * Helper method that returns array for the current post of
 * array(
 *   'link' => ..,
 *   'title' => ..,
 *   'feed_permalink' => ..,
 *   'mail_subject' => ..
 * )
 */
function shrsb_post_info($post) {
	global $shrsb_plugopts, $shrsb_bgimg_map;

  //Just so you don't forget, $r means "results"
  $r = array();

  // if (position == manual or (home and index)) and no post_title
  // then we are "Outside the loop".
  $ismanual = $shrsb_plugopts['position'] == 'manual';
  $ishome = is_home() &&
      false!==strpos($shrsb_plugopts['pageorpost'],"index");
  $isemptytitle = empty($post->post_title);
  if($ismanual || ($ishome && $isemptytitle)) {

    $r['link'] = trim('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
    $r['title'] = get_bloginfo('name') . wp_title('-', false);
    $r['feed_permalink'] = strtolower('http://' . $_SERVER['SERVER_NAME'] .  $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
    $r['mail_subject'] = urlencode(get_bloginfo('name') . wp_title('-', false));

	}
  //We are "in the loop"
  else {
		$r['link'] = trim(get_permalink($post->ID));
		$r['title'] = $post->post_title;
		$r['feed_permalink'] = strtolower($r['link']);
		$r['mail_subject'] = urlencode($post->post_title);
	}

	// Determine how to handle post titles for Twitter
	if (strlen($r['title']) >= 80) {
		$r['short_title'] = urlencode(substr($r['title'], 0, 80)."[..]");
	}
	else {
		$r['short_title'] = urlencode($r['title']);
	}
  return $r;
}

/**
 * Returns array of values that should be used in shareaholic-publishers.js
 */
function shrsb_get_publisher_config($post_id) {
    global $default_spritegen;
    $spritegen = $default_spritegen ? 'spritegen_default' : 'spritegen';
    $spritegen_basepath = $default_spritegen ? SHRSB_PLUGPATH : SHRSB_UPLOADPATH;
    
  $r = shrsb_get_params($post_id);
  
  //set default values if not set
  if (!isset($r['spriteimg'])){$r['spriteimg'] = '';}
  if (!isset($r['bgimg-padding'])){$r['bgimg-padding'] = '';}
  
  $params = array(
    'link' => $r['link'],
    'short_link' => $r['short_link'],
    'shortener' => $r['shortener'],
    'shortener_key' => $r['shortener_key'],
    'title' => $r['title'],
    'notes' => $r['notes'],
    'service' => $r['service'],
    'apikey' => $r['apikey'] ? $r['apikey'] : '8afa39428933be41f8afdb8ea21a495c',
    // we need this because wordpress won't pass it at all if it's FALSE
    // and the default value for expand is true.  We convert it to boolean in javascript
    'expand' => $r['expand'] ? true : 'false',
    'src' => $spritegen_basepath.$spritegen,
    'localize' => true,
    'share_src' => $r['shrbase'],
    'rel' => $r['reloption'],
    'target' => $r['targetopt'] == '_blank' ? '_blank' : '_top',
    'bgimg' => $r['bgimg-url'],
    'bgimg_padding' => $r['bgimg-padding'],
    'center' => $r['autocenter'] == 1,
    'spaced' => $r['autocenter'] == 2,
    'twitter_template' => $r['tweetconfig'],
    'mode' => 'inject',
    'spriteimg' => $r['spriteimg'],
    'designer_toolTips' => $r['designer_toolTips'],
    'tip_bg_color' => $r['tip_bg_color'],  // tooltip background color
    'tip_text_color' => $r['tip_text_color'], // tooltip text color
	'dontShowShareCount' => $r['showShareCount'] == "0",
	'shrlink'	=> $r['shrlink'],
  );

  if ($r['include_comfeed']) {
    // Shareaholic doesn't support comment rss feeds, so we add it as a custom link.
    $params['custom_link'] = array(
      $r['comfeed_position'] => array(
        'li_class' => 'custom-comfeed',
        'link' => $r['feed_link'],
        'tooltip' => __('Subscribe to the comments for this post?', 'shrsb'),
        'style' => 'background-image:url('.SHRSB_PLUGPATH.'images/comfeed.png);',
      ),
    );
  }

  return array_filter($params);
}

/**
 * Returns array of all relevant information about the current post for sexy
 */
function shrsb_get_params($post_id) {
  global $shrsb_plugopts, $shrsb_bgimg_map;
  $post = get_post($post_id);

  // response parameters
  $post_info = shrsb_post_info($post);
  $r = array_merge($shrsb_plugopts, $post_info);

  // Grab the short URL
  $r['short_link'] = shrsb_get_fetch_url();
  $r['shortener'] = $r['shorty'];
  $r['shortener_key'] = '';

  if (isset($shortener)){
      switch($shortener) {
      case 'bitly':
        case 'jmp':
        case 'supr':
            $user = $post_info['shortyapi'][$r['shorty']]['user'];
            $api = $post_info['shortyapi'][$r['shorty']]['key'];
            $r['shortener_key'] =  $user ? ($user.'|'.$api) : '';
            break;
        default:
    }
  }

  $r['post_summary'] = urlencode(strip_tags(
  strip_shortcodes($post->post_excerpt)));
  
  if ($r['post_summary'] == "") {
    $r['post_summary'] = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300));
  }

  $r['shrsb_content'] = urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));
  if ($r['shrsb_content'] == "") {
    $r['shrsb_content'] = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300));
  }
  
  $r['shrsb_content']	= str_replace('+','%20',$r['shrsb_content']);
  $r['post_summary'] = stripslashes(str_replace('+','%20',$r['post_summary']));
  $r['site_name'] = get_bloginfo('name');
  $r['mail_subject'] = str_replace("&#8217;","'",str_replace('+','%20',$r['mail_subject']));

  // Grab post tags for Twittley tags. If there aren't any, use default tags
  // set in plugin options page
  $getkeywords = get_the_tags();
  $r['d_tags'] = "";
  $tags = array();
	if (!empty($getkeywords) && !empty($d_tags)) {
    foreach($getkeywords as $tag) { 
      $tags[] = $tag->name; 
    }
    $r['d_tags'] = implode(',', $tags);
	}

	// Check permalink setup for proper feed link
  $hasquery = false !== strpos($r['feed_permalink'],'?');
  $isphp = false !== strpos($r['feed_permalink'],'.php',
  max(0,strlen($r['feed_permalink']) - 4));
	if ($hasquery || $isphp) {
		$r['feed_structure'] = '&amp;feed=comments-rss2';
	} 
  else {
    $endsinslash = '/' ==
    $r['feed_permalink'][strlen($r['feed_permalink']) - 1];
		if ($endsinslash) {
			$r['feed_structure'] = 'feed';
		}
		else {
			$r['feed_structure'] = '/feed';
		}
	}
  $r['feed_link'] = $r['feed_permalink'].$r['feed_structure'];

	// Compatibility fix for NextGen Gallery Plugin...
	if( (strpos($r['post_summary'], '[') || strpos($r['post_summary'], ']')) ) {
		$r['post_summary'] = "";
	}
	if((strpos($r['shrsb_content'], '[') || strpos($r['shrsb_content'],']'))) {
		$r['shrsb_content'] = "";
	}

  $r['bgimg-url'] = '';
	if(isset($shrsb_plugopts['bgimg-yes'])) {
    $r['bgimg-url'] = $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['url'];
    $r['bgimg-padding'] = $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['padding'];
  }

	// Select the background image
  $bclasses = array('shr-bookmarks');
	if (isset($shrsb_plugopts['bgimg-yes'])) {
    $bclasses[] = $shrsb_bgimg_map[$shrsb_plugopts['bgimg']]['class'];
	}
  if ($shrsb_plugopts['expand'] == 1) {
    $r['expand'] = true;
    $bclasses[] = 'shr-bookmarks-expand';
  }
	if ($shrsb_plugopts['autocenter'] == 1) {
		$bclasses[] = 'shr-bookmarks-center';
	} 
  elseif ($shrsb_plugopts['autocenter'] == 2) {
		$bclasses[] = 'shr-bookmarks-spaced';
	}
  $r['bookmarks-class'] = implode(' ', $bclasses);
  $r['notes'] = $r['post_summary'];

  // see if we need comfeed
  $position = array_search('shr-comfeed', $shrsb_plugopts['bookmark']);
  if (is_numeric($position)) {
    $r['include_comfeed'] = TRUE;
    if ($position == 0) {
      $r['comfeed_position'] = 'before_0';
    }
    else {
      $r['comfeed_position'] = 'after_'.($position-1);
    }
  } 
  else {
    $r['include_comfeed'] = FALSE;
  }

	return $r;
}


function shrsb_get_fetch_url() {
	global $post, $shrsb_plugopts, $wp_query; //globals
	
	//get link but first check if inside or outside loop and what page it's on
	$spost = $wp_query->post;

	if($shrsb_plugopts['position'] == 'manual') {
		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
		}
		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
		}
	}
	//Check if index page...
	elseif(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) {
		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
		}
		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
		}
	}
	//Apparently isn't on index page...
	else {
		$perms = get_permalink($post->ID);
	}
	$perms = trim($perms);
	
	//if is post, and post is not published then return permalink and go back
	if($post && get_post_status($post->ID) != 'publish') {
		return $perms;
	}
	//if user chose not to use shortener, return nothing
	if($shrsb_plugopts['shorty'] == 'none') {
		// return no short_link
	}
	if ($shrsb_plugopts['shorty'] == 'tflp' && function_exists('permalink_to_twitter_link')) {
		$fetch_url = permalink_to_twitter_link($perms);
	} 
    elseif ($shrsb_plugopts['shorty'] == 'yourls' && function_exists('wp_ozh_yourls_raw_url')) {
		$fetch_url = wp_ozh_yourls_raw_url();
	}
	
	if (!empty($fetch_url)) {
	    return $fetch_url;
    }
}


// Create an auto-insertion function
function shrsb_position_menu($post_content) {
	global $post, $shrsb_plugopts, $shrsb_is_mobile, $shrsb_is_bot, $shrsb_js_params;
	// If user selected manual positioning, get out.
	if ($shrsb_plugopts['position']=='manual') {
        if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
              $config = shrsb_get_publisher_config($post->ID);
              $shrsb_js_params['shr-publisher-'.$post->ID] = $config;
        }
		return $post_content;
	}

	// If user selected hide from mobile and is mobile, get out.
	elseif ($shrsb_plugopts['mobile-hide']=='yes' && false!==$shrsb_is_mobile || $shrsb_plugopts['mobile-hide']=='yes' && false!==$shrsb_is_bot) {
		return $post_content;
	}

    $output = "";
    $likeButtonSetTop = "";
    $likeButtonSetBottom = "";
    
	// Decide whether or not to generate the bookmarks.
	if ((is_single() && false!==strpos($shrsb_plugopts['pageorpost'],"post")) || (is_page() && false!==strpos($shrsb_plugopts['pageorpost'],"page")) || (is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) || (is_feed() && !empty($shrsb_plugopts['feed']))) { 
    // socials should be generated and added
    if(!get_post_meta($post->ID, 'Hide SexyBookmarks')) {
      if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
        $output = '<div class="shr-publisher-'.$post->ID.'"></div>';
        $likeButtonSetTop = get_shr_like_buttonset('Top', 1);
        $likeButtonSetBottom = get_shr_like_buttonset('Bottom', 1);
        $config = shrsb_get_publisher_config($post->ID);

        $shrsb_js_params['shr-publisher-'.$post->ID] = $config;
      }
      else {
        $output=get_sexy();
      }
    }
  }

	// Place of bookmarks and return w/ post content.
  $r = $post_content;
	if (!empty($output)) {
    switch($shrsb_plugopts['position']) {
      case 'above':
        $r = $output.$post_content;
        break;
      case 'both':
        $r = $output.$post_content.$output;
        break;
      case 'below':
        $r = $post_content.$output;
        break;
      default:
        error_log(__('An unknown error occurred in SexyBookmarks','shrsb'));
    }

    $r = $likeButtonSetTop.$r.$likeButtonSetBottom;
  }

  return $r;
} // End shrsb_position_menu...


function get_shr_like_buttonset($pos = 'Bottom', $return_type = NULL) { // $pos = 'Bottom'/'Top' Case sensitive
        global $shrsb_plugopts, $post;

        $href = urlencode(get_permalink($post->ID));
        $output = "";
        $float = "none";

        if($shrsb_plugopts['likeButtonSetAlignment'.$pos] == '1') {
            $float = "right";
        }

        if($shrsb_plugopts['likeButtonSet'.$pos] &&
                ($shrsb_plugopts['fbLikeButton'.$pos] == '1' || $shrsb_plugopts['fbSendButton'.$pos] == '1' || $shrsb_plugopts['googlePlusOneButton'.$pos] == '1')) {

            $spacer = '<div style="clear: both; min-height: 1px; height: 3px; width: 100%;"></div>';
            $like_layout = $shrsb_plugopts['likeButtonSetSize'.$pos];
            $height = "";
            switch($like_layout) {
                case '2':
                    $height = "height:60px";
                    break;
                default:
                    $height = "height:30px";
                    break;
            }
            $output .= "<div class='shareaholic-like-buttonset' style='float:$float;$height;'>";
            $plusOneHTML = "";
            $fbLikeHTML = "";
            $fbSendHTML = "";

            if($shrsb_plugopts['googlePlusOneButton'.$pos] == '1') {
                $plusoneSize = $like_layout;
                switch($plusoneSize) {
                    case '1':
                        $plusoneSize = "medium";
                        break;
                    case '2':
                        $plusoneSize = "tall";
                        break;
                    default:
                        $plusoneSize = "standard";
                        break;
                }
                $plusoneCount = $shrsb_plugopts['likeButtonSetCount'.$pos];
                $plusOneHTML = "<a class='shareaholic-googleplusone' shr_size='$plusoneSize' shr_count='$plusoneCount' shr_href='$href'></a>";
            }
            if($shrsb_plugopts['fbLikeButton'.$pos] == '1') {
                //$like_layout = $shrsb_plugopts['likeButtonSetSize'.$pos];
                switch($like_layout) {
                    case '1':
                        $like_layout = "button_count";
                        break;
                    case '2':
                        $like_layout = "box_count";
                        break;
                    default:
                        $like_layout = "standard";
                        break;
                }
                $fbLikeHTML = "<a class='shareaholic-fblike' shr_layout='$like_layout' shr_showfaces='false' shr_href='$href'></a>";
            }

            if($shrsb_plugopts['fbSendButton'.$pos] == '1') {
                $fbSendHTML = "<a class='shareaholic-fbsend' shr_href='$href'></a>";
            }

            foreach($shrsb_plugopts['likeButtonOrder'.$pos] as $likeOption) {
                switch($likeOption) {
                    case "shr-fb-like":
                        $output .= $fbLikeHTML;
                        break;
                    case "shr-plus-one":
                        $output .= $plusOneHTML;
                        break;
                    case "shr-fb-send":
                        $output .= $fbSendHTML;
                        break;
                }
            }

            $output .= '</div>';
            $output = $spacer.$output.$spacer;
        }
        $output = "<!-- Start Shareaholic LikeButtonSet$pos -->".$output."<!-- End Shareaholic LikeButtonSet$pos -->";
        
        if ($return_type == 1){
            return $output;
        }else{
            echo $output;
        }
}


function get_sexy() {
	global $shrsb_plugopts, $wp_query, $post;
	$spost = $wp_query->post;

    $output = "";
    if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
            $output .= '<div class="shr-publisher-'.$post->ID.'"></div>';
            return $output;
    }

	if($shrsb_plugopts['position'] == 'manual') {

		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
			$title = get_bloginfo('name') . wp_title('-', false);
			$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
			$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
		}

		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
			$title = $post->post_title;
			$feedperms = strtolower($perms);
			$mail_subject = urlencode($post->post_title);
		}
	}

	//Check if index page...
	elseif(is_home() && false!==strpos($shrsb_plugopts['pageorpost'],"index")) {

		//Check if outside the loop
		if(empty($post->post_title)) {
			$perms= 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
			$title = get_bloginfo('name') . wp_title('-', false);
			$feedperms = strtolower('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);
			$mail_subject = urlencode(get_bloginfo('name') . wp_title('-', false));
		}

		//Otherwise, it must be inside the loop
		else {
			$perms = get_permalink($post->ID);
			$title = $post->post_title;
			$feedperms = strtolower($perms);
			$mail_subject = urlencode($post->post_title);
		}
	}
	//Apparently isn't on index page...
	else {
		$perms = get_permalink($post->ID);
		$title = $post->post_title;
		$feedperms = strtolower($perms);
		$mail_subject = urlencode($post->post_title);
	}

  // Grab the short URL
  $fetch_url = shrsb_get_fetch_url();


	//Determine how to handle post titles for Twitter
	if (strlen($title) >= 80) {
		$short_title = urlencode(substr($title, 0, 80)."[..]");
	}
	else {
		$short_title = urlencode($title);
	}

	$title=urlencode($title);

	$shrsb_content	= urlencode(strip_tags(strip_shortcodes($post->post_excerpt)));

	if ($shrsb_content == "") {	$shrsb_content = urlencode(substr(strip_tags(strip_shortcodes($post->post_content)),0,300)); }

	$shrsb_content	= str_replace('+','%20',$shrsb_content);
	$post_summary = stripslashes($shrsb_content);
	$site_name = get_bloginfo('name');
	$mail_subject = str_replace('+','%20',$mail_subject);
	$mail_subject = str_replace("&#8217;","'",$mail_subject);



	// Grab post tags for Twittley tags. If there aren't any, use default tags set in plugin options page
	$getkeywords = get_the_tags(); 
  if($getkeywords) { 
    foreach($getkeywords as $tag) { 
      $keywords[]=$tag->name; 
    }
    $keywords = implode(',', $keywords);
	  if(!empty($getkeywords) && !empty($d_tags)) {
		  $d_tags=substr($d_tags, 0, count($d_tags)-2);
    }
	}


	// Check permalink setup for proper feed link
	if (false !== strpos($feedperms,'?') || false !== strpos($feedperms,'.php', max(0,strlen($feedperms) - 4))) {
		$feedstructure = '&amp;feed=comments-rss2';
	} 
  else {
		if ('/' == $feedperms[strlen($feedperms) - 1]) {
			$feedstructure = 'feed';
		}
		else {
			$feedstructure = '/feed';
		}
	}


	// Compatibility fix for NextGen Gallery Plugin...
	if( (strpos($post_summary, '[') || strpos($post_summary, ']')) ) {
		$post_summary = "";
	}
	if( (strpos($shrsb_content, '[') || strpos($shrsb_content,']')) ) {
		$shrsb_content = "";
	}


	// Select the background image
	if(!isset($shrsb_plugopts['bgimg-yes'])) {
		$bgchosen = '';
	}
  else {
    $bgchosen = ' shr-bookmarks-bg-';

    switch($shrsb_plugopts['bgimg']) {
      case 'care-old':
        $bgchosen .= 'caring-old';
        break;
      default:
        $bgchosen .= $shrsb_plugopts['bgimg'];
    }
  }


	$expand=$shrsb_plugopts['expand']?' shr-bookmarks-expand':'';
  switch($shrsb_plugopts['autocenter']) {
    case 1:
      $autocenter = ' shr-bookmarks-center';
      break;
    case 2:
      $autocenter = ' shr-bookmarks-spaced';
      break;
    default:
      $autocenter = '';
  }


	//Write the sexybookmarks menu
	$socials = "\n\n";
	$socials .= '<div class="shr-bookmarks'.$expand.$autocenter.$bgchosen.'">'."\n".'<ul class="socials">'."\n";
	foreach ($shrsb_plugopts['bookmark'] as $name) {
		switch ($name) {
			case 'shr-twitter':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-identica':
				$socials.=bookmark_list_item($name, array(
					'short_title'=>$short_title,
					'fetch_url'=>$fetch_url,
				));
				break;
			case 'shr-mail':
				$socials.=bookmark_list_item($name, array(
					'title'=>$mail_subject,
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
				));
				break;
			case 'shr-tomuse':
				$socials.=bookmark_list_item($name, array(
					'title'=>$mail_subject,
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
				));
				break;
			case 'shr-diigo':
				$socials.=bookmark_list_item($name, array(
					'sexy_teaser'=>$shrsb_content,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-linkedin':
				$socials.=bookmark_list_item($name, array(
					'post_summary'=>$post_summary,
					'site_name'=>$site_name,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
			case 'shr-comfeed':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urldecode($feedperms).$feedstructure,
				));
				break;
			case 'shr-yahoobuzz':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>$perms,
					'title'=>$title,
					'yahooteaser'=>$shrsb_content,
				));
				break;
			case 'shr-twittley':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urlencode($perms),
					'title'=>$title,
					'post_summary'=>$post_summary,
					'default_tags'=>$d_tags,
				));
				break;
			case 'shr-tumblr':
				$socials.=bookmark_list_item($name, array(
					'permalink'=>urlencode($perms),
					'title'=>$title,
				));
				break;
			default:
				$socials.=bookmark_list_item($name, array(
					'post_summary'=>$post_summary,
					'permalink'=>$perms,
					'title'=>$title,
				));
				break;
		}
	}
	$socials.='</ul>';
	if ($shrsb_plugopts['shrlink'] == 1) {
		$socials.= '<div style="clear: both;"></div>';
		$socials.= '<div class="shr-getshr" style="visibility:hidden;font-size:10px !important"><a target="_blank" href="http://www.shareaholic.com/?src=pub">Get Shareaholic</a></div>';
	}
	$socials.= '<div style="clear: both;"></div></div>';
	$socials.="\n\n";
	return $socials;
}

// This function is what allows people to insert the menu wherever they please rather than above/below a post... (depreciated)
function selfserv_sexy() {
	global $post;
	if(!get_post_meta($post->ID, 'Hide SexyBookmarks'))
		echo get_sexy();
}

//Same as above function, just a diff name
function selfserv_shareaholic() {
	global $post;
	if(!get_post_meta($post->ID, 'Hide SexyBookmarks'))
		echo get_sexy();
}

// Write the <head> code only on pages that the menu is set to display
function shrsb_publicStyles() {
	global $shrsb_plugopts, $post, $shrsb_custom_sprite;

	// If custom field is set, do not display sexybookmarks
	if ($post && get_post_meta($post->ID, 'Hide SexyBookmarks')) {
		echo "\n\n".'<!-- '.__('SexyBookmarks has been disabled on this page', 'shrsb').' -->'."\n\n";
	} 
  elseif ($shrsb_plugopts['shareaholic-javascript'] != '1') {
		//custom mods rule over custom css
    if ($shrsb_plugopts['custom-mods'] != 'yes') {
      if($shrsb_custom_sprite != '') {
        $surl = $shrsb_custom_sprite;
      } 
      else {
        $surl = SHRSB_PLUGPATH.'css/style.css';
      }
    } 
    elseif ($shrsb_plugopts['custom-mods'] == 'yes') {
      $surl = WP_CONTENT_URL.'/sexy-mods/css/style.css';
    }
		wp_enqueue_style('sexy-bookmarks', $surl, false, SHRSB_vNum, 'all');
  } 
  else {
    $position = array_search('shr-comfeed', $shrsb_plugopts['bookmark']);
    if (is_numeric($position)) {
      wp_enqueue_style('comfeed', SHRSB_PLUGPATH.'css/comfeed.css', false, SHRSB_vNum, 'all');
    }
  }
}
function shrsb_publicScripts() {
	global $shrsb_plugopts, $post, $default_spritegen;

    $spritegen = $default_spritegen ? 'spritegen_default' : 'spritegen';
    $spritegen_basepath = $default_spritegen ? SHRSB_PLUGPATH : SHRSB_UPLOADPATH;
    
    //Beta script
    if ($shrsb_plugopts['shareaholic-javascript'] == '1' && !is_admin()){// && !get_post_meta($post->ID, 'Hide SexyBookmarks')) {
        $infooter = ($shrsb_plugopts['scriptInFooter'] == '1')?true:false;
        wp_enqueue_script('shareaholic-publishers-js', $spritegen_basepath.$spritegen.'/jquery.shareaholic-publishers-sb.min.js', null, SHRSB_vNum, $infooter);
        wp_localize_script('shareaholic-publishers-js', 'SHRSB_Globals', array('src' => $spritegen_basepath.$spritegen,'perfoption'=> $shrsb_plugopts['perfoption']));
    } else {
    // If any javascript dependent options are selected, load the scripts
    if (($shrsb_plugopts['expand'] || $shrsb_plugopts['autocenter'] || $shrsb_plugopts['targetopt']=='_blank') && $post && !get_post_meta($post->ID, 'Hide SexyBookmarks')) {
      // If custom mods is selected, pull files from new location
      if($shrsb_plugopts['custom-mods'] == 'yes') {
        $surl = WP_CONTENT_URL.'/sexy-mods/js/sexy-bookmarks-public.js';
     } else {
        $surl = SHRSB_PLUGPATH.'js/sexy-bookmarks-public.js';
     }
      // If jQuery compatibility fix is not selected, go ahead and load jQuery
      $jquery = ($shrsb_plugopts['doNotIncludeJQuery'] != '1') ? array('jquery') : array();
      $infooter = ($shrsb_plugopts['scriptInFooter'] == '1')?true:false;
      wp_enqueue_script('sexy-bookmarks-public-js', $surl, $jquery, SHRSB_vNum, $infooter);
    }
  }
  
  //Perf tracking
  if (($shrsb_plugopts['perfoption'] == '1' || $shrsb_plugopts['perfoption'] == '' && !is_admin())
          && $shrsb_plugopts['shareaholic-javascript'] !== '1'){
      //include code
      wp_enqueue_script('shareaholic-perf-js', SHRSB_PLUGPATH.'js/shareaholic-perf.js', null, SHRSB_vNum, false);
    }
}

function shrsb_write_js_params() {
  global $shrsb_plugopts, $shrsb_js_params;

  if ($shrsb_plugopts['shareaholic-javascript'] == '1') {
    echo '<script type="text/javascript">var SHRSB_Settings = ';
    echo json_encode($shrsb_js_params);
    echo ';</script>';
  }
}

add_action('wp_print_styles', 'shrsb_publicStyles');
add_action('wp_print_scripts', 'shrsb_publicScripts');
add_filter('the_content', 'shrsb_position_menu');
add_action('wp_footer', 'shrsb_write_js_params');