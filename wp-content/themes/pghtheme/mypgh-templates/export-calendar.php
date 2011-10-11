<?php 
get_header();
$img_dir = get_bloginfo('stylesheet_directory') . '/images';

 require_once(ABSPATH . '/icalfeedurl.php');
//$ical_url = get_ical_url(bp_loggedin_user_id());

?>

<div id="wrapper">
<div id="content" class="profile-map">
	<div class="padder">

		<div id="item-header">
			<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>
		</div>

		<div id="item-nav">
			<div class="item-list-tabs no-ajax" id="object-nav">
				<ul>
					<?php bp_get_displayed_user_nav() ?>
				</ul>
			</div>
		</div>

		<div id="item-body">

	      	<div id="export-cal-container">
	            <p>Copy the following URL to import your MyPgh calendar into the calendar application of your choosing. If you are unsure what to do next, follow the instructions below.</p>
	            <input type"text" class="cal-feed-field" value="<?php echo $ical_url;?>" style="width:540px" name="ical-link-text" id="ical-link-text" readonly="readonly">
	            <!--<input class="attend-button cal-url" type="button" value="Copy to Clipboard">-->
	            <h4>Google Calendar</h4>
	            <ol>
	                  <li>Click the down arrow nest to <strong>Other Calendars</strong></li>
	                  <li>Select <strong>Add by URL</strong> from the menu</li>
	                  <li>Enter your calendar feed URL in the field provided.</li>
	                  <li>Click the <strong>Add Calendar</strong> button.</li>
	                  <li><a href="http://www.google.com/support/calendar/bin/answer.py?answer=37100">Read more here.</a></li>
	            </ol>
	            <h4>Apple iCal</h4>
	            <ol>
	                  <li>From the <strong>Calendar</strong> menu select <strong>Subscribe</strong></li>
	                  <li>Tap <strong>Mail, Contacts, Calendars</strong></li>
	                  <li>Copy and paste your calendar feed into the <strong>Calendar URL</strong> field.</strong> </li>
	                  <li>Click <strong>Subscribe.</strong></li>
	                  <li><a href="http://mcb.berkeley.edu/academic-programs/seminars/ical-feed-instructions/">Read more here.</a></li>
	            </ol>
	            <h4>Outlook 2010</h4>
	            <ol>
	                  <li>Click <strong>Open Calendar</strong> and select <strong>from Internet</strong>.</li>
	                  <li>Paste your URL in the box and click <strong>OK<strong>.</li>
	                  <li>Tap <strong>Mail, Contacts, Calendars</strong></li>
	                  <li>Scroll down and tap <strong>Add Account...</strong> </li>
	                  <li>Tap <strong>Other</strong></li>
	                  <li>Tap <strong>Add Subscribed Calendar</strong></li>
	                  <li>In the server field, type in your calendar feed URL. Then tap <strong>Next</strong></li>
	                  <li>On the next screen, change the name of the calendar under "Description" if you choose. Then tap <strong>Save</strong>.</li>
	                  <li><a href="http://www.sadev.co.za/content/using-outlook-2010-google-calendar-0">Read more here.</a></li>
	            </ol>
	            <h4>Outlook 2007</h4>
	            <ol>
	                  <li>Select <strong>Tools/Account Settings</strong>.</li>
	                  <li>Click the <strong>Internet Calendars</strong> tab.</li>
	                  <li>Click <strong>New</strong>. </li>
	                  <li>Enter your calendar URL in the field.</li>
	                  <li>Click <strong>Add</strong>.</li>
	                  <li><a href="http://www.rackspace.com/apps/support/portal/6008">Read more here.</a></li>
	            </ol>
	    	 </div> <!-- #export-cal-container -->

		</div> <!-- #item-body -->
	</div><!-- .padder -->
</div><!-- #content -->
</div> <!-- #wrapper -->

<script type="text/javascript">
	jQuery('#ical-link-text').click(function(){this.focus();this.select()});
</script>

<?php get_footer() ?>