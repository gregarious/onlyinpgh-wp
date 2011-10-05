<?php

/* Define a constant that can be checked to see if the component is installed or not. */
define ( 'BP_EXAMPLE_IS_INSTALLED', 1 );

/* Define a constant that will hold the current version number of the component */
define ( 'BP_EXAMPLE_VERSION', '1.5' );

/* Define a constant that will hold the database version number that can be used for upgrading the DB
 *
 * NOTE: When table defintions change and you need to upgrade,
 * make sure that you increment this constant so that it runs the install function again.
 *
 * Also, if you have errors when testing the component for the first time, make sure that you check to
 * see if the table(s) got created. If not, you'll most likely need to increment this constant as
 * BP_EXAMPLE_DB_VERSION was written to the wp_usermeta table and the install function will not be
 * triggered again unless you increment the version to a number higher than stored in the meta data.
 */
define ( 'BP_EXAMPLE_DB_VERSION', '1' );

/* Define a slug constant that will be used to view this components pages (http://example.org/SLUG) */
if ( !defined( 'BP_EXAMPLE_SLUG' ) )
	define ( 'BP_EXAMPLE_SLUG', 'example' );

/*
 * If you want the users of your component to be able to change the values of your other custom constants,
 * you can use this code to allow them to add new definitions to the wp-config.php file and set the value there.
 *
 *
 *	if ( !defined( 'BP_EXAMPLE_CONSTANT' ) )
 *		define ( 'BP_EXAMPLE_CONSTANT', 'some value' // or some value without quotes if integer );
 */

/**
 * You should try hard to support translation in your component. It's actually very easy.
 * Make sure you wrap any rendered text in __() or _e() and it will then be translatable.
 *
 * You must also provide a text domain, so translation files know which bits of text to translate.
 * Throughout this example the text domain used is 'bp-example', you can use whatever you want.
 * Put the text domain as the second parameter:
 *
 * __( 'This text will be translatable', 'bp-example' ); // Returns the first parameter value
 * _e( 'This text will be translatable', 'bp-example' ); // Echos the first parameter value
 */

if ( file_exists( dirname( __FILE__ ) . '/languages/' . get_locale() . '.mo' ) )
	load_textdomain( 'bp-example', dirname( __FILE__ ) . '/bp-example/languages/' . get_locale() . '.mo' );

/**
 * The next step is to include all the files you need for your component.
 * You should remove or comment out any files that you don't need.
 */

/* The classes file should hold all database access classes and functions */
require ( dirname( __FILE__ ) . '/bp-example-classes.php' );

/* The ajax file should hold all functions used in AJAX queries */
require ( dirname( __FILE__ ) . '/bp-example-ajax.php' );

/* The cssjs file should set up and enqueue all CSS and JS files used by the component */
require ( dirname( __FILE__ ) . '/bp-example-cssjs.php' );

/* The templatetags file should contain classes and functions designed for use in template files */
require ( dirname( __FILE__ ) . '/bp-example-templatetags.php' );

/* The widgets file should contain code to create and register widgets for the component */
require ( dirname( __FILE__ ) . '/bp-example-widgets.php' );

/* The notifications file should contain functions to send email notifications on specific user actions */
require ( dirname( __FILE__ ) . '/bp-example-notifications.php' );

/* The filters file should create and apply filters to component output functions. */
require ( dirname( __FILE__ ) . '/bp-example-filters.php' );

/**
 * bp_example_setup_globals()
 *
 * Sets up global variables for your component.
 */
function bp_example_setup_globals() {
	global $bp, $wpdb;

	/* For internal identification */
	$bp->example->id = 'example';

	$bp->example->table_name = $wpdb->base_prefix . 'bp_example';
	$bp->example->format_notification_function = 'bp_example_format_notifications';
	$bp->example->slug = BP_EXAMPLE_SLUG;

	/* Register this in the active components array */
	$bp->active_components[$bp->example->slug] = $bp->example->id;
}
add_action( 'bp_setup_globals', 'bp_example_setup_globals' );

/**
 * bp_example_add_admin_menu()
 *
 * This function will add a WordPress wp-admin admin menu for your component under the
 * "BuddyPress" menu.
 */
function bp_example_add_admin_menu() {
	global $bp;

	if ( !is_super_admin() )
		return false;

	require ( dirname( __FILE__ ) . '/bp-example-admin.php' );

	add_submenu_page( 'bp-general-settings', __( 'Example Admin', 'bp-example' ), __( 'Example Admin', 'bp-example' ), 'manage_options', 'bp-example-settings', 'bp_example_admin' );
}
// The admin menu should be added to the Network Admin screen when Multisite is enabled
add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', 'bp_example_add_admin_menu' );

/**
 * bp_example_setup_nav()
 *
 * Sets up the user profile navigation items for the component. This adds the top level nav
 * item and all the sub level nav items to the navigation array. This is then
 * rendered in the template.
 */

//----------------------//
// Add 'Export Calendar to MyPgh nav //
//----------------------//

function pgh_cal_setup_nav() {
	global $bp;

	/* Add the subnav items to the profile nav item */
	bp_core_new_nav_item( array( 'name' => __( 'Export Calendar', 'buddypress' ), 
			'slug' => 'calendar', 
			'screen_function' => 'bp_example_screen_two', 
			'position' => 30 ) );

	$bp->bp_nav['messages']['position'] = 100;
}

add_action( 'bp_setup_nav', 'pgh_cal_setup_nav' );


function bp_example_setup_nav() {
	global $bp;
//----------------------//
// Add 'My Events Map' to the main user profile navigation //
//----------------------//
	bp_core_new_nav_item( array(
		'name' => __( 'My Events Map', 'bp-example' ),
		'slug' => $bp->example->slug,
		'position' => 20,
		'screen_function' => 'bp_example_screen_one',
		'default_subnav_slug' => 'screen-one'
	) );

	$example_link = $bp->loggedin_user->domain . $bp->example->slug . '/';

	/* Create two sub nav items for this component */
	bp_core_new_subnav_item( array(
		'name' => __( 'Screen One', 'bp-example' ),
		'slug' => 'screen-one',
		'parent_slug' => $bp->example->slug,
		'parent_url' => $example_link,
		'screen_function' => 'bp_example_screen_one',
		'position' => 10
	) );

	bp_core_new_subnav_item( array(
		'name' => __( 'Screen Two', 'bp-example' ),
		'slug' => 'screen-two',
		'parent_slug' => $bp->example->slug,
		'parent_url' => $example_link,
		'screen_function' => 'bp_example_screen_two',
		'position' => 20,
		'user_has_access' => bp_is_my_profile() // Only the logged in user can access this on his/her profile
	) );

	/* Add a nav item for this component under the settings nav item. See bp_example_screen_settings_menu() for more info */
}
add_action( 'bp_setup_nav', 'bp_example_setup_nav' );

function bp_second_nav_setup() {
		/* Add 'Example' to the main user profile navigation */
	bp_core_new_nav_item( array(
		'name' => __( 'My Events Map', 'bp-example' ),
		'slug' => $bp->example->slug,
		'position' => 80,
		'screen_function' => 'bp_example_screen_one',
		'default_subnav_slug' => 'screen-one'
	) );
}
add_action( 'bp_setup_nav', 'bp_example_setup_nav' );

/**
 * bp_example_load_template_filter()
 *
 * You can define a custom load template filter for your component. This will allow
 * you to store and load template files from your plugin directory.
 *
 * This will also allow users to override these templates in their active theme and
 * replace the ones that are stored in the plugin directory.
 *
 * If you're not interested in using template files, then you don't need this function.
 *
 * This will become clearer in the function bp_example_screen_one() when you want to load
 * a template file.
 */
function bp_example_load_template_filter( $found_template, $templates ) {
	global $bp;

	/**
	 * Only filter the template location when we're on the example component pages.
	 */
	if ( $bp->current_component != $bp->example->slug )
		return $found_template;

	foreach ( (array) $templates as $template ) {
		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates[] = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;
	}

	$found_template = $filtered_templates[0];

	return apply_filters( 'bp_example_load_template_filter', $found_template );
}
add_filter( 'bp_located_template', 'bp_example_load_template_filter', 10, 2 );


/********************************************************************************
 * Screen Functions
 *
 * Screen functions are the controllers of BuddyPress. They will execute when their
 * specific URL is caught. They will first save or manipulate data using business
 * functions, then pass on the user to a template file.
 */

/**
 * bp_example_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-one"
 */
function bp_example_screen_one() {
	global $bp;

	/**
	 * There are three global variables that you should know about and you will
	 * find yourself using often.
	 *
	 * $bp->current_component (string)
	 * This will tell you the current component the user is viewing.
	 *
	 * Example: If the user was on the page http://example.org/members/andy/groups/my-groups
	 *          $bp->current_component would equal 'groups'.
	 *
	 * $bp->current_action (string)
	 * This will tell you the current action the user is carrying out within a component.
	 *
	 * Example: If the user was on the page: http://example.org/members/andy/groups/leave/34
	 *          $bp->current_action would equal 'leave'.
	 *
	 * $bp->action_variables (array)
	 * This will tell you which action variables are set for a specific action
	 *
	 * Example: If the user was on the page: http://example.org/members/andy/groups/join/34
	 *          $bp->action_variables would equal array( '34' );
	 */

	/**
	 * On this screen, as a quick example, users can send you a "High Five", by clicking a link.
	 * When a user sends you a high five, you receive a new notification in your
	 * notifications menu, and you will also be notified via email.
	 */

	/**
	 * We need to run a check to see if the current user has clicked on the 'send high five' link.
	 * If they have, then let's send the five, and redirect back with a nice error/success message.
	 */
	if ( $bp->current_component == $bp->example->slug && 'screen-one' == $bp->current_action && 'send-h5' == $bp->action_variables[0] ) {
		/* The logged in user has clicked on the 'send high five' link */
		if ( bp_is_my_profile() ) {
			/* Don't let users high five themselves */
			bp_core_add_message( __( 'No self-fives! :)', 'bp-example' ), 'error' );
		} else {
			if ( bp_example_send_highfive( $bp->displayed_user->id, $bp->loggedin_user->id ) )
				bp_core_add_message( __( 'High-five sent!', 'bp-example' ) );
			else
				bp_core_add_message( __( 'High-five could not be sent.', 'bp-example' ), 'error' );
		}

		bp_core_redirect( $bp->displayed_user->domain . $bp->example->slug . '/screen-one' );
	}

	/* Add a do action here, so your component can be extended by others. */
	do_action( 'bp_example_screen_one' );

	/****
	 * Displaying Content
	 */

	/****
	 * OPTION 1:
	 * You've got a few options for displaying content. Your first option is to bundle template files
	 * with your plugin that will be used to output content.
	 *
	 * In an earlier function bp_example_load_template_filter() we set up a filter on the core BP template
	 * loading function that will make it first look in the plugin directory for template files.
	 * If it doesn't find any matching templates it will look in the active theme directory.
	 *
	 * This example component comes bundled with a template for screen one, so we can load that
	 * template to display what we need. If you copied this template from the plugin into your theme
	 * then it would load that one instead. This allows users to override templates in their theme.
	 */

	/* This is going to look in wp-content/plugins/[plugin-name]/includes/templates/ first */
	bp_core_load_template( apply_filters( 'bp_example_template_screen_one', 'example/screen-one' ) );

	/****
	 * OPTION 2 (NOT USED FOR THIS SCREEN):
	 * If your component is simple, and you just want to insert some HTML into the user's active theme
	 * then you can use the bundle plugin template.
	 *
	 * There are two actions you need to hook into. One for the title, and one for the content.
	 * The functions you hook these into should simply output the content you want to display on the
	 * page.
	 *
	 * The follow lines are commented out because we are not using this method for this screen.
	 * You'd want to remove the OPTION 1 parts above and uncomment these lines if you want to use
	 * this option instead.
	 *
	 * Generally, this method of adding content is preferred, as it makes your plugin
	 * work better with a wider variety of themes.
 	 */

//	add_action( 'bp_template_title', 'bp_example_screen_one_title' );
//	add_action( 'bp_template_content', 'bp_example_screen_one_content' );

//	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
	/***
	 * The second argument of each of the above add_action() calls is a function that will
	 * display the corresponding information. The functions are presented below:
	 */
	function bp_example_screen_one_title() {
		_e( 'Screen One', 'bp-example' );
	}

	function bp_example_screen_one_content() {
		global $bp;

		$high_fives = bp_example_get_highfives_for_user( $bp->displayed_user->id );

		/**
		 * For security reasons, we MUST use the wp_nonce_url() function on any actions.
		 * This will stop naughty people from tricking users into performing actions without their
		 * knowledge or intent.
		 */
		$send_link = wp_nonce_url( $bp->displayed_user->domain . $bp->current_component . '/screen-one/send-h5', 'bp_example_send_high_five' );
	?>
		<h4><?php _e( 'Welcome to Screen One', 'bp-example' ) ?></h4>
		<p><?php printf( __( 'Send %s a <a href="%s" title="Send high-five!">high-five!</a>', 'bp-example' ), $bp->displayed_user->fullname, $send_link ) ?></p>

		<?php if ( $high_fives ) : ?>
			<h4><?php _e( 'Received High Fives!', 'bp-example' ) ?></h4>

			<table id="high-fives">
				<?php foreach ( $high_fives as $user_id ) : ?>
				<tr>
					<td width="1%"><?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'width' => 25, 'height' => 25 ) ) ?></td>
					<td>&nbsp; <?php echo bp_core_get_userlink( $user_id ) ?></td>
	 			</tr>
				<?php endforeach; ?>
			</table>
		<?php endif; ?>
	<?php
	}

/**
 * bp_example_screen_two()
 *
 * Sets up and displays the screen output for the sub nav item "example/screen-two"
 */
function bp_example_screen_two() {
	global $bp;

	/**
	 * On the output for this second screen, as an example, there are terms and conditions with an
	 * "Accept" link (directs to http://example.org/members/andy/example/screen-two/accept)
	 * and a "Reject" link (directs to http://example.org/members/andy/example/screen-two/reject)
	 */

	if ( $bp->current_component == $bp->example->slug && 'screen-two' == $bp->current_action && 'accept' == $bp->action_variables[0] ) {
		if ( bp_example_accept_terms() ) {
			/* Add a success message, that will be displayed in the template on the next page load */
			bp_core_add_message( __( 'Terms were accepted!', 'bp-example' ) );
		} else {
			/* Add a failure message if there was a problem */
			bp_core_add_message( __( 'Terms could not be accepted.', 'bp-example' ), 'error' );
		}

		/**
		 * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
		 * just by refreshing the browser.
		 */
		bp_core_redirect( $bp->loggedin_user->domain . $bp->current_component );
	}

	if ( $bp->current_component == $bp->example->slug && 'screen-two' == $bp->current_action && 'reject' == $bp->action_variables[0] ) {
		if ( bp_example_reject_terms() ) {
			/* Add a success message, that will be displayed in the template on the next page load */
			bp_core_add_message( __( 'Terms were rejected!', 'bp-example' ) );
		} else {
			/* Add a failure message if there was a problem */
			bp_core_add_message( __( 'Terms could not be rejected.', 'bp-example' ), 'error' );
		}

		/**
		 * Now redirect back to the page without any actions set, so the user can't carry out actions multiple times
		 * just by refreshing the browser.
		 */
		bp_core_redirect( $bp->loggedin_user->domain . $bp->current_component );
	}

	/**
	 * If the user has not Accepted or Rejected anything, then the code above will not run,
	 * we can continue and load the template.
	 */
	do_action( 'bp_example_screen_two' );

	add_action( 'bp_template_title', 'bp_example_screen_two_title' );
	add_action( 'bp_template_content', 'bp_example_screen_two_content' );

	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

	function bp_example_screen_two_title() {
		_e( 'Export MyPgh Calendar', 'bp-example' );
	}



//----------------------////----------------------////----------------------////----------------------////----------------------////----------------------//
//----------------------////----------------------////----------------------////----------------------////----------------------////----------------------//
// Changing to MyPgh 'Export Calendar' content.
//----------------------////----------------------////----------------------////----------------------////----------------------////----------------------//
//----------------------////----------------------////----------------------////----------------------////----------------------////----------------------//

	function bp_example_screen_two_content() {
		global $bp; ?>

		<?php require_once(ABSPATH . '/icalfeedurl.php');?>

		<div id="export-cal-container">
			<p>Copy the following URL to import your MyPgh calendar into the calendar application of your choosing. If you are unsure what to do next, follow the instructions below.</p>
			<input type"text" class="cal-feed-field" value="<?php echo get_ical_url(bp_loggedin_user_id()); ?>" style="width:540px" id="ical-link-text">
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
		</div>

		<script type="text/javascript">
			jQuery('#ical-link-text').click(function(){this.focus();this.select()});
		</script>

	<?php
	}

function bp_example_screen_settings_menu() {
	global $bp, $current_user, $bp_settings_updated, $pass_error;

	if ( isset( $_POST['submit'] ) ) {
		/* Check the nonce */
		check_admin_referer('bp-example-admin');

		$bp_settings_updated = true;

		/**
		 * This is when the user has hit the save button on their settings.
		 * The best place to store these settings is in wp_usermeta.
		 */
		update_user_meta( $bp->loggedin_user->id, 'bp-example-option-one', attribute_escape( $_POST['bp-example-option-one'] ) );
	}

	add_action( 'bp_template_content_header', 'bp_example_screen_settings_menu_header' );
	add_action( 'bp_template_title', 'bp_example_screen_settings_menu_title' );
	add_action( 'bp_template_content', 'bp_example_screen_settings_menu_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

	function bp_example_screen_settings_menu_header() {
		_e( 'Example Settings Header', 'bp-example' );
	}

	function bp_example_screen_settings_menu_title() {
		_e( 'Example Settings', 'bp-example' );
	}

	function bp_example_screen_settings_menu_content() {
		global $bp, $bp_settings_updated; ?>

		<?php if ( $bp_settings_updated ) { ?>
			<div id="message" class="updated fade">
				<p><?php _e( 'Changes Saved.', 'bp-example' ) ?></p>
			</div>
		<?php } ?>

		<form action="<?php echo $bp->loggedin_user->domain . 'settings/example-admin'; ?>" name="bp-example-admin-form" id="account-delete-form" class="bp-example-admin-form" method="post">

			<input type="checkbox" name="bp-example-option-one" id="bp-example-option-one" value="1"<?php if ( '1' == get_user_meta( $bp->loggedin_user->id, 'bp-example-option-one', true ) ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Do you love clicking checkboxes?', 'bp-example' ); ?>
			<p class="submit">
				<input type="submit" value="<?php _e( 'Save Settings', 'bp-example' ) ?> &raquo;" id="submit" name="submit" />
			</p>

			<?php
			/* This is very important, don't leave it out. */
			wp_nonce_field( 'bp-example-admin' );
			?>

		</form>
	<?php
	}


/********************************************************************************
 * Activity & Notification Functions
 *
 * These functions handle the recording, deleting and formatting of activity and
 * notifications for the user and for this specific component.
 */


/**
 * bp_example_screen_notification_settings()
 *
 * Adds notification settings for the component, so that a user can turn off email
 * notifications set on specific component actions.
 */
function bp_example_screen_notification_settings() {
	global $current_user;

	/**
	 * Under Settings > Notifications within a users profile page they will see
	 * settings to turn off notifications for each component.
	 *
	 * You can plug your custom notification settings into this page, so that when your
	 * component is active, the user will see options to turn off notifications that are
	 * specific to your component.
	 */

	 /**
	  * Each option is stored in a posted array notifications[SETTING_NAME]
	  * When saved, the SETTING_NAME is stored as usermeta for that user.
	  *
	  * For example, notifications[notification_friends_friendship_accepted] could be
	  * used like this:
	  *
	  * if ( 'no' == get_user_meta( $bp->displayed_user->id, 'notification_friends_friendship_accepted', true ) )
	  *		// don't send the email notification
	  *	else
	  *		// send the email notification.
      */

	?>
	<table class="notification-settings" id="bp-example-notification-settings">
		
		<thead>
		<tr>
			<th class="icon"></th>
			<th class="title"><?php _e( 'Example', 'bp-example' ) ?></th>
			<th class="yes"><?php _e( 'Yes', 'bp-example' ) ?></th>
			<th class="no"><?php _e( 'No', 'bp-example' )?></th>
		</tr>
		</thead>
		
		<tbody>
		<tr>
			<td></td>
			<td><?php _e( 'Action One', 'bp-example' ) ?></td>
			<td class="yes"><input type="radio" name="notifications[notification_example_action_one]" value="yes" <?php if ( !get_user_meta( $current_user->id, 'notification_example_action_one', true ) || 'yes' == get_user_meta( $current_user->id, 'notification_example_action_one', true ) ) { ?>checked="checked" <?php } ?>/></td>
			<td class="no"><input type="radio" name="notifications[notification_example_action_one]" value="no" <?php if ( get_user_meta( $current_user->id, 'notification_example_action_one') == 'no' ) { ?>checked="checked" <?php } ?>/></td>
		</tr>
		<tr>
			<td></td>
			<td><?php _e( 'Action Two', 'bp-example' ) ?></td>
			<td class="yes"><input type="radio" name="notifications[notification_example_action_two]" value="yes" <?php if ( !get_user_meta( $current_user->id, 'notification_example_action_two', true ) || 'yes' == get_user_meta( $current_user->id, 'notification_example_action_two', true ) ) { ?>checked="checked" <?php } ?>/></td>
			<td class="no"><input type="radio" name="notifications[notification_example_action_two]" value="no" <?php if ( 'no' == get_user_meta( $current_user->id, 'notification_example_action_two', true ) ) { ?>checked="checked" <?php } ?>/></td>
		</tr>

		<?php do_action( 'bp_example_notification_settings' ); ?>
		
		</tbody>
	</table>
<?php
}
add_action( 'bp_notification_settings', 'bp_example_screen_notification_settings' );

/**
 * bp_example_record_activity()
 *
 * If the activity stream component is installed, this function will record activity items for your
 * component.
 *
 * You must pass the function an associated array of arguments:
 *
 *     $args = array(
 *	 	 REQUIRED PARAMS
 *		 'action' => For example: "Andy high-fived John", "Andy posted a new update".
 *       'type' => The type of action being carried out, for example 'new_friendship', 'joined_group'. This should be unique within your component.
 *
 *		 OPTIONAL PARAMS
 *		 'id' => The ID of an existing activity item that you want to update.
 * 		 'content' => The content of your activity, if it has any, for example a photo, update content or blog post excerpt.
 *       'component' => The slug of the component.
 *		 'primary_link' => The link for the title of the item when appearing in RSS feeds (defaults to the activity permalink)
 *       'item_id' => The ID of the main piece of data being recorded, for example a group_id, user_id, forum_post_id - useful for filtering and deleting later on.
 *		 'user_id' => The ID of the user that this activity is being recorded for. Pass false if it's not for a user.
 *		 'recorded_time' => (optional) The time you want to set as when the activity was carried out (defaults to now)
 *		 'hide_sitewide' => Should this activity item appear on the site wide stream?
 *		 'secondary_item_id' => (optional) If the activity is more complex you may need a second ID. For example a group forum post may need the group_id AND the forum_post_id.
 *     )
 *
 * Example usage would be:
 *
 *   bp_example_record_activity( array( 'type' => 'new_highfive', 'action' => 'Andy high-fived John', 'user_id' => $bp->loggedin_user->id, 'item_id' => $bp->displayed_user->id ) );
 *
 */
function bp_example_record_activity( $args = '' ) {
	global $bp;

	if ( !function_exists( 'bp_activity_add' ) )
		return false;

	$defaults = array(
		'id' => false,
		'user_id' => $bp->loggedin_user->id,
		'action' => '',
		'content' => '',
		'primary_link' => '',
		'component' => $bp->example->id,
		'type' => false,
		'item_id' => false,
		'secondary_item_id' => false,
		'recorded_time' => gmdate( "Y-m-d H:i:s" ),
		'hide_sitewide' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );

	return bp_activity_add( array( 'id' => $id, 'user_id' => $user_id, 'action' => $action, 'content' => $content, 'primary_link' => $primary_link, 'component' => $component, 'type' => $type, 'item_id' => $item_id, 'secondary_item_id' => $secondary_item_id, 'recorded_time' => $recorded_time, 'hide_sitewide' => $hide_sitewide ) );
}

/**
 * bp_example_format_notifications()
 *
 * The format notification function will take DB entries for notifications and format them
 * so that they can be displayed and read on the screen.
 *
 * Notifications are "screen" notifications, that is, they appear on the notifications menu
 * in the site wide navigation bar. They are not for email notifications.
 *
 *
 * The recording is done by using bp_core_add_notification() which you can search for in this file for
 * examples of usage.
 */
function bp_example_format_notifications( $action, $item_id, $secondary_item_id, $total_items ) {
	global $bp;

	switch ( $action ) {
		case 'new_high_five':
			/* In this case, $item_id is the user ID of the user who sent the high five. */

			/***
			 * We don't want a whole list of similar notifications in a users list, so we group them.
			 * If the user has more than one action from the same component, they are counted and the
			 * notification is rendered differently.
			 */
			if ( (int)$total_items > 1 ) {
				return apply_filters( 'bp_example_multiple_new_high_five_notification', '<a href="' . $bp->loggedin_user->domain . $bp->example->slug . '/screen-one/" title="' . __( 'Multiple high-fives', 'bp-example' ) . '">' . sprintf( __( '%d new high-fives, multi-five!', 'bp-example' ), (int)$total_items ) . '</a>', $total_items );
			} else {
				$user_fullname = bp_core_get_user_displayname( $item_id, false );
				$user_url = bp_core_get_userurl( $item_id );
				return apply_filters( 'bp_example_single_new_high_five_notification', '<a href="' . $user_url . '?new" title="' . $user_fullname .'\'s profile">' . sprintf( __( '%s sent you a high-five!', 'bp-example' ), $user_fullname ) . '</a>', $user_fullname );
			}
		break;
	}

	do_action( 'bp_example_format_notifications', $action, $item_id, $secondary_item_id, $total_items );

	return false;
}


/***
 * From now on you will want to add your own functions that are specific to the component you are developing.
 * For example, in this section in the friends component, there would be functions like:
 *    friends_add_friend()
 *    friends_remove_friend()
 *    friends_check_friendship()
 *
 * Some guidelines:
 *    - Don't set up error messages in these functions, just return false if you hit a problem and
 *		deal with error messages in screen or action functions.
 *
 *    - Don't directly query the database in any of these functions. Use database access classes
 * 		or functions in your bp-example-classes.php file to fetch what you need. Spraying database
 * 		access all over your plugin turns into a maintainence nightmare, trust me.
 *
 *	  - Try to include add_action() functions within all of these functions. That way others will find it
 *		easy to extend your component without hacking it to pieces.
 */

/**
 * bp_example_accept_terms()
 *
 * Accepts the terms and conditions screen for the logged in user.
 * Records an activity stream item for the user.
 */
function bp_example_accept_terms() {
	global $bp;

	/**
	 * First check the nonce to make sure that the user has initiated this
	 * action. Remember the wp_nonce_url() call? The second parameter is what
	 * you need to check for.
	 */
	check_admin_referer( 'bp_example_accept_terms' );

	/***
	 * Here is a good example of where we can post something to a users activity stream.
	 * The user has excepted the terms on screen two, and now we want to post
	 * "Andy accepted the really exciting terms and conditions!" to the stream.
	 */
	$user_link = bp_core_get_userlink( $bp->loggedin_user->id );

	bp_example_record_activity( array(
		'type' => 'accepted_terms',
		'action' => apply_filters( 'bp_example_accepted_terms_activity_action', sprintf( __( '%s accepted the really exciting terms and conditions!', 'bp-example' ), $user_link ), $user_link ),
	) );

	/* See bp_example_reject_terms() for an explanation of deleting activity items */
	if ( function_exists( 'bp_activity_delete') )
		bp_activity_delete( array( 'type' => 'rejected_terms', 'user_id' => $bp->loggedin_user->id ) );

	/* Add a do_action here so other plugins can hook in */
	do_action( 'bp_example_accept_terms', $bp->loggedin_user->id );

	/***
	 * You'd want to do something here, like set a flag in the database, or set usermeta.
	 * just for the sake of the demo we're going to return true.
	 */

	return true;
}

/**
 * bp_example_reject_terms()
 *
 * Rejects the terms and conditions screen for the logged in user.
 * Records an activity stream item for the user.
 */
function bp_example_reject_terms() {
	global $bp;

	check_admin_referer( 'bp_example_reject_terms' );

	/***
	 * In this example component, the user can reject the terms even after they have
	 * previously accepted them.
	 *
	 * If a user has accepted the terms previously, then this will be in their activity
	 * stream. We don't want both 'accepted' and 'rejected' in the activity stream, so
	 * we should remove references to the user accepting from all activity streams.
	 * A real world example of this would be a user deleting a published blog post.
	 */

	$user_link = bp_core_get_userlink( $bp->loggedin_user->id );

	/* Now record the new 'rejected' activity item */
	bp_example_record_activity( array(
		'type' => 'rejected_terms',
		'action' => apply_filters( 'bp_example_rejected_terms_activity_action', sprintf( __( '%s rejected the really exciting terms and conditions.', 'bp-example' ), $user_link ), $user_link ),
	) );

	/* Delete any accepted_terms activity items for the user */
	if ( function_exists( 'bp_activity_delete') )
		bp_activity_delete( array( 'type' => 'accepted_terms', 'user_id' => $bp->loggedin_user->id ) );

	do_action( 'bp_example_reject_terms', $bp->loggedin_user->id );

	return true;
}

/**
 * bp_example_send_high_five()
 *
 * Sends a high five message to a user. Registers an notification to the user
 * via their notifications menu, as well as sends an email to the user.
 *
 * Also records an activity stream item saying "User 1 high-fived User 2".
 */
function bp_example_send_highfive( $to_user_id, $from_user_id ) {
	global $bp;

	check_admin_referer( 'bp_example_send_high_five' );

	/**
	 * We'll store high-fives as usermeta, so we don't actually need
	 * to do any database querying. If we did, and we were storing them
	 * in a custom DB table, we'd want to reference a function in
	 * bp-example-classes.php that would run the SQL query.
	 */

	/* Get existing fives */
	$existing_fives = maybe_unserialize( get_user_meta( $to_user_id, 'high-fives', true ) );

	/* Check to see if the user has already high-fived. That's okay, but lets not
	 * store duplicate high-fives in the database. What's the point, right?
	 */
	if ( !in_array( $from_user_id, (array)$existing_fives ) ) {
		$existing_fives[] = (int)$from_user_id;

		/* Now wrap it up and fire it back to the database overlords. */
		update_user_meta( $to_user_id, 'high-fives', serialize( $existing_fives ) );
	}

	/***
	 * Now we've registered the new high-five, lets work on some notification and activity
	 * stream magic.
	 */

	/***
	 * Post a screen notification to the user's notifications menu.
	 * Remember, like activity streams we need to tell the activity stream component how to format
	 * this notification in bp_example_format_notifications() using the 'new_high_five' action.
	 */
	bp_core_add_notification( $from_user_id, $to_user_id, $bp->example->slug, 'new_high_five' );

	/* Now record the new 'new_high_five' activity item */
	$to_user_link = bp_core_get_userlink( $to_user_id );
	$from_user_link = bp_core_get_userlink( $from_user_id );

	bp_example_record_activity( array(
		'type' => 'rejected_terms',
		'action' => apply_filters( 'bp_example_new_high_five_activity_action', sprintf( __( '%s high-fived %s!', 'bp-example' ), $from_user_link, $to_user_link ), $from_user_link, $to_user_link ),
		'item_id' => $to_user_id,
	) );

	/* We'll use this do_action call to send the email notification. See bp-example-notifications.php */
	do_action( 'bp_example_send_high_five', $to_user_id, $from_user_id );

	return true;
}

/**
 * bp_example_get_highfives_for_user()
 *
 * Returns an array of user ID's for users who have high fived the user passed to the function.
 */
function bp_example_get_highfives_for_user( $user_id ) {
	global $bp;

	if ( !$user_id )
		return false;

	return maybe_unserialize( get_user_meta( $user_id, 'high-fives', true ) );
}

/**
 * bp_example_remove_screen_notifications()
 *
 * Remove a screen notification for a user.
 */
function bp_example_remove_screen_notifications() {
	global $bp;

	/**
	 * When clicking on a screen notification, we need to remove it from the menu.
	 * The following command will do so.
 	 */
	bp_core_delete_notifications_for_user_by_type( $bp->loggedin_user->id, $bp->example->slug, 'new_high_five' );
}
add_action( 'bp_example_screen_one', 'bp_example_remove_screen_notifications' );
add_action( 'xprofile_screen_display_profile', 'bp_example_remove_screen_notifications' );

/**
 * bp_example_remove_data()
 *
 * It's always wise to clean up after a user is deleted. This stops the database from filling up with
 * redundant information.
 */
function bp_example_remove_data( $user_id ) {
	/* You'll want to run a function here that will delete all information from any component tables
	   for this $user_id */

	/* Remember to remove usermeta for this component for the user being deleted */
	delete_user_meta( $user_id, 'bp_example_some_setting' );

	do_action( 'bp_example_remove_data', $user_id );
}
add_action( 'wpmu_delete_user', 'bp_example_remove_data', 1 );
add_action( 'delete_user', 'bp_example_remove_data', 1 );

/***
 * Object Caching Support ----
 *
 * It's a good idea to implement object caching support in your component if it is fairly database
 * intensive. This is not a requirement, but it will help ensure your component works better under
 * high load environments.
 *
 * In parts of this example component you will see calls to wp_cache_get() often in template tags
 * or custom loops where database access is common. This is where cached data is being fetched instead
 * of querying the database.
 *
 * However, you will need to make sure the cache is cleared and updated when something changes. For example,
 * the groups component caches groups details (such as description, name, news, number of members etc).
 * But when those details are updated by a group admin, we need to clear the group's cache so the new
 * details are shown when users view the group or find it in search results.
 *
 * We know that there is a do_action() call when the group details are updated called 'groups_settings_updated'
 * and the group_id is passed in that action. We need to create a function that will clear the cache for the
 * group, and then add an action that calls that function when the 'groups_settings_updated' is fired.
 *
 * Example:
 *
 *   function groups_clear_group_object_cache( $group_id ) {
 *	     wp_cache_delete( 'groups_group_' . $group_id );
 *	 }
 *	 add_action( 'groups_settings_updated', 'groups_clear_group_object_cache' );
 *
 * The "'groups_group_' . $group_id" part refers to the unique identifier you gave the cached object in the
 * wp_cache_set() call in your code.
 *
 * If this has completely confused you, check the function documentation here:
 * http://codex.wordpress.org/Function_Reference/WP_Cache
 *
 * If you're still confused, check how it works in other BuddyPress components, or just don't use it,
 * but you should try to if you can (it makes a big difference). :)
 */

?>