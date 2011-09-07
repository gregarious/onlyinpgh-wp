<?php
/*
 * WP-FB-AutoConnect Premium Add-On
 * http://www.justin-klein.com/projects/wp-fb-autoconnect
 * 
 * This file does not operate as a standalone plugin; it must be used in conjunction with WP-FB-AutoConnect,
 * which you can download for free from Wordpress.org.  To install the add-on, just upload this file 
 * (WP-FB-AutoConnect-Premium.php) to your Wordpress plugins directory and the options will be automatically 
 * added to your admin panel. 
 * 
 * Disclaimer:
 * The code below is owned exclusively by Justin Klein (justin@justin-klein.com)
 * You are permitted to modify the code below for personal use.
 * You are not permitted to share, sell, or in any way distribute any of the code below.
 * You are not permitted to share, sell, or in any way distribute any work derived from the code below,
 * including new plugins that may include similar functionality.
 * You are not permitted to instruct others on how to reproduce the behavior implemented below.
 * Basically, you can use this plugin however you like *on your own site*; just don't share it with anyone else :)
 *
 * Please see the bottom of this file for a complete version history.
 *   
 */ 


/**********************************************************************/
/**********************************************************************/
/*************************PREMIUM OPTIONS******************************/
/**********************************************************************/
/**********************************************************************/


//Identify the premium version as being present & available
define('JFB_PREMIUM', 792);
define('JFB_PREMIUM_VER', 22);
define('JFB_PREMIUM_REQUIREDVER', '2.0.4');

//Override plugin name
global $jfb_name, $jfb_version;
$jfb_name = "WP-FB AutoConnect Premium";

//Define new premium options
global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_content, $opt_jfbp_notifyusers_subject;
global $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_registrationfrmlogin, $opt_jfbp_cache_avatars, $opt_jfbp_cache_avatar_dir;
global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_requirerealmail;
global $opt_jfbp_redirect_new, $opt_jfbp_redirect_new_custom, $opt_jfbp_redirect_existing, $opt_jfbp_redirect_existing_custom, $opt_jfbp_redirect_logout, $opt_jfbp_redirect_logout_custom;
global $opt_jfbp_restrict_reg, $opt_jfbp_restrict_reg_url, $opt_jfbp_restrict_reg_uid, $opt_jfbp_restrict_reg_pid, $opt_jfbp_restrict_reg_gid;
global $opt_jfbp_show_spinner;
global $opt_jfbp_wordbooker_integrate, $opt_jfbp_signupfrmlogin;
global $opt_jfbp_localize_facebook;
global $opt_jfbp_first_activation;
$opt_jfbp_notifyusers = "jfb_p_notifyusers";
$opt_jfbp_notifyusers_subject = "jfb_p_notifyusers_subject";
$opt_jfbp_notifyusers_content = "jfb_p_notifyusers_content";
$opt_jfbp_commentfrmlogin = "jfb_p_commentformlogin";
$opt_jfbp_wploginfrmlogin = "jfb_p_wploginformlogin";
$opt_jfbp_registrationfrmlogin = "jfb_p_registrationformlogin";
$opt_jfbp_cache_avatars = "jfb_p_cacheavatars";
$opt_jfbp_cache_avatar_dir = "jfb_p_cacheavatar_dir";
$opt_jfbp_buttonsize = "jfb_p_buttonsize";
$opt_jfbp_buttontext = "jfb_p_buttontext";
$opt_jfbp_requirerealmail = "jfb_p_requirerealmail";
$opt_jfbp_redirect_new = 'jfb_p_redirect_new';
$opt_jfbp_redirect_new_custom = 'jfb_p_redirect_new_custom';
$opt_jfbp_redirect_existing = 'jfb_p_redirect_existing';
$opt_jfbp_redirect_existing_custom = 'jfb_p_redirect_new_existing';
$opt_jfbp_redirect_logout = 'jfb_p_redirect_logout';
$opt_jfbp_redirect_logout_custom = 'jfb_p_redirect_logout_custom';
$opt_jfbp_restrict_reg = 'jfb_p_restrict_reg';
$opt_jfbp_restrict_reg_url = 'jfb_p_restrict_reg_url';
$opt_jfbp_restrict_reg_uid = 'jfb_p_restrict_reg_uid';
$opt_jfbp_restrict_reg_pid = 'jfb_p_restrict_reg_pid';
$opt_jfbp_restrict_reg_gid = 'jfb_p_restrict_reg_gid';
$opt_jfbp_show_spinner = 'jfb_p_show_spinner';
$opt_jfbp_wordbooker_integrate = 'jfb_p_wordbooker_integrate';
$opt_jfbp_signupfrmlogin = 'jfb_p_signupformlogin';
$opt_jfbp_localize_facebook = 'jfb_p_localize_facebook';
$opt_jfbp_first_activation = 'jfb_p_first_activation';

//Called when we save our options in the admin panel
function jfb_update_premium_opts()
{
    global $_POST, $jfb_name, $jfb_version;
    global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_content, $opt_jfbp_notifyusers_subject;
    global $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_registrationfrmlogin, $opt_jfbp_cache_avatars, $opt_jfbp_cache_avatar_dir;
    global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_requirerealmail;
    global $opt_jfbp_redirect_new, $opt_jfbp_redirect_new_custom, $opt_jfbp_redirect_existing, $opt_jfbp_redirect_existing_custom, $opt_jfbp_redirect_logout, $opt_jfbp_redirect_logout_custom;
    global $opt_jfbp_restrict_reg, $opt_jfbp_restrict_reg_url, $opt_jfbp_restrict_reg_uid, $opt_jfbp_restrict_reg_pid, $opt_jfbp_restrict_reg_gid;
    global $opt_jfbp_show_spinner;
    global $opt_jfbp_wordbooker_integrate, $opt_jfbp_signupfrmlogin, $opt_jfbp_localize_facebook;
    update_option( $opt_jfbp_notifyusers, $_POST[$opt_jfbp_notifyusers] );
    update_option( $opt_jfbp_notifyusers_subject, stripslashes($_POST[$opt_jfbp_notifyusers_subject]) );
    update_option( $opt_jfbp_notifyusers_content, stripslashes($_POST[$opt_jfbp_notifyusers_content]) );
    update_option( $opt_jfbp_commentfrmlogin, $_POST[$opt_jfbp_commentfrmlogin] );
    update_option( $opt_jfbp_wploginfrmlogin, $_POST[$opt_jfbp_wploginfrmlogin] );
    update_option( $opt_jfbp_registrationfrmlogin, $_POST[$opt_jfbp_registrationfrmlogin] );
    update_option( $opt_jfbp_cache_avatars, $_POST[$opt_jfbp_cache_avatars] );
    update_option( $opt_jfbp_cache_avatar_dir, $_POST[$opt_jfbp_cache_avatar_dir] );
    update_option( $opt_jfbp_buttonsize, $_POST[$opt_jfbp_buttonsize] );
    update_option( $opt_jfbp_buttontext, $_POST[$opt_jfbp_buttontext] );
    update_option( $opt_jfbp_redirect_new, $_POST[$opt_jfbp_redirect_new] );
    update_option( $opt_jfbp_redirect_new_custom, $_POST[$opt_jfbp_redirect_new_custom] );
    update_option( $opt_jfbp_redirect_existing, $_POST[$opt_jfbp_redirect_existing] );
    update_option( $opt_jfbp_redirect_existing_custom, $_POST[$opt_jfbp_redirect_existing_custom] );
    update_option( $opt_jfbp_redirect_logout, $_POST[$opt_jfbp_redirect_logout] );
    update_option( $opt_jfbp_redirect_logout_custom, $_POST[$opt_jfbp_redirect_logout_custom] );
    update_option( $opt_jfbp_restrict_reg, $_POST[$opt_jfbp_restrict_reg] );
    update_option( $opt_jfbp_restrict_reg_url, $_POST[$opt_jfbp_restrict_reg_url] );
    update_option( $opt_jfbp_restrict_reg_uid, $_POST[$opt_jfbp_restrict_reg_uid] );
    update_option( $opt_jfbp_restrict_reg_pid, $_POST[$opt_jfbp_restrict_reg_pid] );
    update_option( $opt_jfbp_restrict_reg_gid, $_POST[$opt_jfbp_restrict_reg_gid] );
    update_option( $opt_jfbp_show_spinner, $_POST[$opt_jfbp_show_spinner] );
    update_option( $opt_jfbp_wordbooker_integrate, $_POST[$opt_jfbp_wordbooker_integrate] );
    update_option( $opt_jfbp_signupfrmlogin, $_POST[$opt_jfbp_signupfrmlogin] );
    update_option( $opt_jfbp_localize_facebook, $_POST[$opt_jfbp_localize_facebook] );
    update_option( $opt_jfbp_requirerealmail, $_POST[$opt_jfbp_requirerealmail] );
    do_action('wpfb_p_update_options');
    ?><div class="updated"><p><strong>Premium Options saved.</strong></p></div><?php    
}

//Called to delete our options from the admin panel
function jfb_delete_premium_opts()
{
    global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_content, $opt_jfbp_notifyusers_subject;
    global $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_registrationfrmlogin, $opt_jfbp_cache_avatars, $opt_jfbp_cache_avatar_dir;
    global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_requirerealmail;
    global $opt_jfbp_redirect_new, $opt_jfbp_redirect_new_custom, $opt_jfbp_redirect_existing, $opt_jfbp_redirect_existing_custom, $opt_jfbp_redirect_logout, $opt_jfbp_redirect_logout_custom;
    global $opt_jfbp_restrict_reg, $opt_jfbp_restrict_reg_url, $opt_jfbp_restrict_reg_uid, $opt_jfbp_restrict_reg_pid, $opt_jfbp_restrict_reg_gid;
    global $opt_jfbp_show_spinner;
    global $opt_jfbp_wordbooker_integrate, $opt_jfbp_signupfrmlogin, $opt_jfbp_localize_facebook;
    global $opt_jfbp_first_activation;
    delete_option($opt_jfbp_notifyusers);
    delete_option($opt_jfbp_notifyusers_subject);
    delete_option($opt_jfbp_notifyusers_content);
    delete_option($opt_jfbp_commentfrmlogin);
    delete_option($opt_jfbp_wploginfrmlogin);
    delete_option($opt_jfbp_registrationfrmlogin);
    delete_option($opt_jfbp_cache_avatars);
    delete_option($opt_jfbp_cache_avatar_dir);
    delete_option($opt_jfbp_buttonsize);
    delete_option($opt_jfbp_buttontext);
    delete_option($opt_jfbp_requirerealmail);
    delete_option($opt_jfbp_redirect_new);
    delete_option($opt_jfbp_redirect_new_custom);
    delete_option($opt_jfbp_redirect_existing);
    delete_option($opt_jfbp_redirect_existing_custom);
    delete_option($opt_jfbp_redirect_logout);
    delete_option($opt_jfbp_redirect_logout_custom);
    delete_option($opt_jfbp_restrict_reg);
    delete_option($opt_jfbp_restrict_reg_url);
    delete_option($opt_jfbp_restrict_reg_uid);
    delete_option($opt_jfbp_restrict_reg_pid);
    delete_option($opt_jfbp_restrict_reg_gid);
    delete_option($opt_jfbp_show_spinner);
    delete_option($opt_jfbp_wordbooker_integrate);
    delete_option($opt_jfbp_signupfrmlogin);
    delete_option($opt_jfbp_localize_facebook);
    delete_option($opt_jfbp_first_activation);
}


/**********************************************************************/
/**********************************************************************/
/**************************ADMIN PANEL*********************************/
/**********************************************************************/
/**********************************************************************/

function jfb_output_premium_panel()
{
    global $jfb_homepage;
    global $opt_jfbp_notifyusers, $opt_jfbp_notifyusers_subject, $opt_jfbp_notifyusers_content, $opt_jfbp_commentfrmlogin, $opt_jfbp_wploginfrmlogin, $opt_jfbp_registrationfrmlogin, $opt_jfbp_cache_avatars, $opt_jfbp_cache_avatar_dir;
    global $opt_jfbp_buttonsize, $opt_jfbp_buttontext, $opt_jfbp_requirerealmail;
    global $opt_jfbp_redirect_new, $opt_jfbp_redirect_new_custom, $opt_jfbp_redirect_existing, $opt_jfbp_redirect_existing_custom, $opt_jfbp_redirect_logout, $opt_jfbp_redirect_logout_custom;
    global $opt_jfbp_restrict_reg, $opt_jfbp_restrict_reg_url, $opt_jfbp_restrict_reg_uid, $opt_jfbp_restrict_reg_pid, $opt_jfbp_restrict_reg_gid;
    global $opt_jfbp_show_spinner, $jfb_data_url;
    global $opt_jfbp_wordbooker_integrate, $opt_jfbp_signupfrmlogin, $opt_jfbp_localize_facebook;
    function disableatt() { echo (defined('JFB_PREMIUM')?"":"disabled='disabled'"); }
    ?>
    <h3>Premium Options <?php echo (defined('JFB_PREMIUM_VER')?"<small>(Version " . JFB_PREMIUM_VER . ")</small>":""); ?></h3>
    
    <?php 
    if( !defined('JFB_PREMIUM') )
        echo "<div class=\"error\"><i><b>The following options are available to Premium users only.</b><br />For information about the WP-FB-AutoConnect Premium Add-On, including purchasing instructions, please visit the plugin homepage <b><a href=\"$jfb_homepage#premium\">here</a></b></i>.</div>";
    ?>
    
    <form name="formPremOptions" method="post" action="">
    
        <b>MultiSite Support:</b><br/>
		<input disabled='disabled' type="checkbox" name="musupport" value="1" <?php echo ((defined('JFB_PREMIUM')&&function_exists('is_multisite')&&is_multisite())?"checked='checked'":"")?> >
		Automatically enabled when a MultiSite install is detected
		<dfn title="The free plugin is not aware of users registered on other sites in your WPMU installation, which can result in problems i.e. if someone tries to register on more than one site.  The Premium version will actively detect and handle existing users across all your sites.">(Mouseover for more info)</dfn><br /><br />
		
		<b>Double Logins:</b><br />
        <input disabled='disabled' type="checkbox" name="doublelogin" value="1" <?php echo (defined('JFB_PREMIUM')?"checked='checked'":"")?> /> Automatically handle double logins 
        <dfn title="If a visitor opens two browser windows, logs into one, then logs into the other, the security nonce check will fail.  This is because in the second window, the current user no longer matches the user for which the nonce was generated.  The free version of the plugin reports this to the visitor, giving them a link to their desired redirect page.  The premium version will transparently handle such double-logins: to visitors, it'll look like the page has just been refreshed and they're now logged in.  For more information on nonces, please visit http://codex.wordpress.org/WordPress_Nonces.">(Mouseover for more info)</dfn><br /><br />
		
		<b>Facebook Localization:</b><br />
		<?php add_option($opt_jfbp_localize_facebook, 1); ?>
		<input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_localize_facebook?>" value="1" <?php echo get_option($opt_jfbp_localize_facebook)?"checked='checked'":""?> >
		Translate Facebook prompts to the same locale as your Wordpress blog (Detected locale: <i><?php echo ( (defined('WPLANG')&&WPLANG!="") ? WPLANG : "en_US" ); ?></i>)
		<dfn title="The Wordpress locale is specified in wp-config.php, where valid language codes are of the form 'en_US', 'ja_JP', 'es_LA', etc.  Please see http://codex.wordpress.org/Installing_WordPress_in_Your_Language for more information on localizing Wordpress, and http://developers.facebook.com/docs/internationalization/ for a list of locales supported by Facebook.">(Mouseover for more info)</dfn><br /><br />
						
   		<b>E-Mail Permissions:</b><br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_requirerealmail?>" value="1" <?php echo get_option($opt_jfbp_requirerealmail)?'checked="checked"':''?> /> Enforce access to user's real (unproxied) email
        <dfn title="The basic option to request user emails will prompt your visitors, but they can still hide their true addresses by using a Facebook proxy (click 'change' in the permissions dialog, and select 'xxx@proxymail.facebook.com').  This option performs a secondary check to enforce that they allow access to their REAL e-mail.  Note that the check requires several extra queries to Facebook's servers, so it could result in a slightly longer delay before the login initiates.">(Mouseover for more info)</dfn><br /><br />

        <b>Avatar Caching:</b><br />         
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_cache_avatars?>" value="1" <?php echo get_option($opt_jfbp_cache_avatars)?'checked="checked"':''?> />
        Cache Facebook avatars to: <span style="background-color:#FFFFFF; color:#aaaaaa; padding:2px 0;">
        <?php 
        add_option($opt_jfbp_cache_avatar_dir, 'facebook-avatars');
        $ud = wp_upload_dir();
        echo "<i>" . $ud['basedir'] . "/</i>";         
        ?>
        </span>
        <input <?php disableatt() ?> type="text" size="20" name="<?php echo $opt_jfbp_cache_avatar_dir; ?>" value="<?php echo get_option($opt_jfbp_cache_avatar_dir); ?>" />
        <dfn title="This will make a local copy of Facebook avatars, so they'll always load reliably, even if Facebook's servers go offline or if a user deletes their photo from Facebook. They will be fetched and updated whenever a user logs in.  NOTE: Changing the cache directory will not move existing avatars or update existing users; it only applies to subsequent logins.  It's therefore recommended that you choose a cache directory once, then leave it be.">(Mouseover for more info)</dfn><br /><br />        

        <b>Wordbooker Avatar Integration:</b><br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_wordbooker_integrate?>" value="1" <?php echo get_option($opt_jfbp_wordbooker_integrate)?'checked="checked"':''?> /> Use Facebook avatars for <a href="http://wordpress.org/extend/plugins/wordbooker/">Wordbooker</a>-imported comments
        <dfn title="The Wordbooker plugin allows you to push blog posts to your Facebook wall, and also to import comments on these posts back to your blog.  This option will display real Facebook avatars for imported comments, provided the commentor logs into your site at least once.">(Mouseover for more info)</dfn><br /><br />

        <b>Widget Appearance:</b><br />
        Please use the <a href="<?php echo admin_url('widgets.php') ?>" target="widgets">WP-FB AutoConnect <b><i>Premium</i></b> Widget</a> if you'd like to:<br />
        &bull; Customize the Widget's text <dfn title="You can customize the text of: User, Pass, Login, Remember, Forgot, Logout, Edit Profile, Welcome.">(Mouseover for more info)</dfn><br />
        &bull; Hide the User/Pass fields (leaving Facebook as the only way to login)<br />
		&bull; Show a "Remember" tickbox<br />      
        &bull; Allow the user to simultaneously logout of your site <i>and</i> Facebook<br /><br />
        
        <b>Button Appearance:</b><br />
        <?php add_option($opt_jfbp_buttontext, "Login with Facebook"); ?>
        <?php add_option($opt_jfbp_buttonsize, "2"); ?>
        Text: <input <?php disableatt() ?> type="text" size="30" name="<?php echo $opt_jfbp_buttontext; ?>" value="<?php echo get_option($opt_jfbp_buttontext); ?>" /> <dfn title="This setting applies to ALL of your Facebook buttons (in the widget, wp-login.php, comment forms, etc).">(Mouseover for more info)</dfn><br />
        Style: 
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="2" <?php echo (get_option($opt_jfbp_buttonsize)==2?"checked='checked'":"")?>>Small
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="3" <?php echo (get_option($opt_jfbp_buttonsize)==3?"checked='checked'":"")?>>Medium
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="4" <?php echo (get_option($opt_jfbp_buttonsize)==4?"checked='checked'":"")?>>Large
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_buttonsize; ?>" value="5" <?php echo (get_option($opt_jfbp_buttonsize)==5?"checked='checked'":"")?>>X-Large<br /><br />
        
        <b>Additional Buttons:</b><br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_commentfrmlogin?>" value="1" <?php echo get_option($opt_jfbp_commentfrmlogin)?'checked="checked"':''?> /> Add a Facebook Login button below the comment form<br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_wploginfrmlogin?>" value="1" <?php echo get_option($opt_jfbp_wploginfrmlogin)?'checked="checked"':''?> /> Add a Facebook Login button to the standard Login page (wp-login.php)<br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_registrationfrmlogin?>" value="1" <?php echo get_option($opt_jfbp_registrationfrmlogin)?'checked="checked"':''?> /> Add a Facebook Login button to the Registration page (wp-login.php)<br />
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_signupfrmlogin?>" value="1" <?php echo get_option($opt_jfbp_signupfrmlogin)?'checked="checked"':''?> /> Add a Facebook Login button to the Signup page (wp-signup.php) (WPMU Only)<br /><br />
            
        <b>AJAX Spinner:</b><br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_show_spinner; ?>" value="0" <?php echo (get_option($opt_jfbp_show_spinner)==0?"checked='checked'":"")?> >Don't show an AJAX spinner<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_show_spinner; ?>" value="1" <?php echo (get_option($opt_jfbp_show_spinner)==1?"checked='checked'":"")?> >Show a white AJAX spinner to indicate the login process has started (<img src=" <?php echo $jfb_data_url ?>/spinner/spinner_white.gif" alt="spinner" />)<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_show_spinner; ?>" value="2" <?php echo (get_option($opt_jfbp_show_spinner)==2?"checked='checked'":"")?> >Show a black AJAX spinner to indicate the login process has started (<img src=" <?php echo $jfb_data_url ?>/spinner/spinner_black.gif" alt="spinner" />)<br /><br />
                
        <b>AutoRegistration Restrictions:</b><br />
        <?php add_option($opt_jfbp_restrict_reg_url, '/') ?>
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_restrict_reg; ?>" value="0" <?php echo (get_option($opt_jfbp_restrict_reg)==0?"checked='checked'":"")?>>Open: Anyone can login (Default)<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_restrict_reg; ?>" value="1" <?php echo (get_option($opt_jfbp_restrict_reg)==1?"checked='checked'":"")?>>Closed: Only login existing blog users<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_restrict_reg; ?>" value="2" <?php echo (get_option($opt_jfbp_restrict_reg)==2?"checked='checked'":"")?>>Invitational: Only login users who've been invited via the <a href="http://wordpress.org/extend/plugins/wordpress-mu-secure-invites/">Secure Invites</a> plugin <dfn title="For invites to work, the connecting user's Facebook email must be accessible, and it must match the email to which the invitation was sent.">(Mouseover for more info)</dfn><br />
		<input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_restrict_reg; ?>" value="3" <?php echo (get_option($opt_jfbp_restrict_reg)==3?"checked='checked'":"")?>>Friendship: Only login users who are friends with uid <input <?php disableatt() ?> type="text" size="15" name="<?php echo $opt_jfbp_restrict_reg_uid?>" value="<?php echo get_option($opt_jfbp_restrict_reg_uid) ?>" /> on Facebook <dfn title="To find your Facebook uid, login and view your Profile Pictures album.  The URL will be something like 'http://www.facebook.com/album.php?id=12345678&aid=9101112'.  In this example, your uid would be 12345678.">(Mouseover for more info)</dfn><br />
		<input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_restrict_reg; ?>" value="4" <?php echo (get_option($opt_jfbp_restrict_reg)==4?"checked='checked'":"")?>>Membership: Only login users who are members of group id <input <?php disableatt() ?> type="text" size="15" name="<?php echo $opt_jfbp_restrict_reg_gid?>" value="<?php echo get_option($opt_jfbp_restrict_reg_gid); ?>" /> on Facebook <dfn title="To find a groups's id, view its URL.  It will be something like 'http://www.facebook.com/group.php?gid=12345678'.  In this example, the group id would be 12345678.">(Mouseover for more info)</dfn><br />
		<input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_restrict_reg; ?>" value="5" <?php echo (get_option($opt_jfbp_restrict_reg)==5?"checked='checked'":"")?>>Fanpage: Only login users who are fans of page id <input <?php disableatt() ?> type="text" size="15" name="<?php echo $opt_jfbp_restrict_reg_pid?>" value="<?php echo get_option($opt_jfbp_restrict_reg_pid); ?>" /> on Facebook <dfn title="To find a page's id, view one of its photo albums.  The URL will be something like 'http://www.facebook.com/album.php?id=12345678&aid=9101112'.  In this example, the page id would be 12345678.">(Mouseover for more info)</dfn><br />
        Redirect URL for denied logins: <input <?php disableatt() ?> type="text" size="30" name="<?php echo $opt_jfbp_restrict_reg_url?>" value="<?php echo get_option($opt_jfbp_restrict_reg_url) ?>" /><br /><br />
                
        <b>Custom Redirects:</b><br />
        <?php add_option($opt_jfbp_redirect_new, "1"); ?>
        <?php add_option($opt_jfbp_redirect_existing, "1"); ?>
        <?php add_option($opt_jfbp_redirect_logout, "1"); ?>
        When a new user is autoregistered on your site, redirect them to:<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_redirect_new; ?>" value="1" <?php echo (get_option($opt_jfbp_redirect_new)==1?"checked='checked'":"")?> >Default (refresh current page)<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_redirect_new; ?>" value="2" <?php echo (get_option($opt_jfbp_redirect_new)==2?"checked='checked'":"")?> >Custom URL:
        <input <?php disableatt() ?> type="text" size="47" name="<?php echo $opt_jfbp_redirect_new_custom?>" value="<?php echo get_option($opt_jfbp_redirect_new_custom) ?>" /><br /><br />
        When an existing user returns to your site, redirect them to:<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_redirect_existing; ?>" value="1" <?php echo (get_option($opt_jfbp_redirect_existing)==1?"checked='checked'":"")?> >Default (refresh current page)<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_redirect_existing; ?>" value="2" <?php echo (get_option($opt_jfbp_redirect_existing)==2?"checked='checked'":"")?> >Custom URL:
        <input <?php disableatt() ?> type="text" size="47" name="<?php echo $opt_jfbp_redirect_existing_custom?>" value="<?php echo get_option($opt_jfbp_redirect_existing_custom) ?>" /><br /><br />
        When a user logs out of your site, redirect them to:<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_redirect_logout; ?>" value="1" <?php echo (get_option($opt_jfbp_redirect_logout)==1?"checked='checked'":"")?> >Default (refresh current page)<br />
        <input <?php disableatt() ?> type="radio" name="<?php echo $opt_jfbp_redirect_logout; ?>" value="2" <?php echo (get_option($opt_jfbp_redirect_logout)==2?"checked='checked'":"")?> >Custom URL:
        <input <?php disableatt() ?> type="text" size="47" name="<?php echo $opt_jfbp_redirect_logout_custom?>" value="<?php echo get_option($opt_jfbp_redirect_logout_custom) ?>" /><br /><br />

        <b>Welcome Message:</b><br />
        <?php add_option($opt_jfbp_notifyusers_content, "Thank you for logging into " . get_option('blogname') . " with Facebook.\nIf you would like to login manually, you may do so with the following credentials.\n\nUsername: %username%\nPassword: %password%"); ?>
        <?php add_option($opt_jfbp_notifyusers_subject, "Welcome to " . get_option('blogname')); ?>
        <input <?php disableatt() ?> type="checkbox" name="<?php echo $opt_jfbp_notifyusers?>" value="1" <?php echo get_option($opt_jfbp_notifyusers)?'checked="checked"':''?> /> Send a custom welcome e-mail to users who register via Facebook <small>(*If we know their address)</small><br />
        <input <?php disableatt() ?> type="text" size="102" name="<?php echo $opt_jfbp_notifyusers_subject?>" value="<?php echo get_option($opt_jfbp_notifyusers_subject) ?>" /><br />
        <textarea <?php disableatt() ?> cols="85" rows="5" name="<?php echo $opt_jfbp_notifyusers_content?>"><?php echo get_option($opt_jfbp_notifyusers_content) ?></textarea><br />
                                        
        <input type="hidden" name="prem_opts_updated" value="1" />
        <div class="submit"><input <?php disableatt() ?> type="submit" name="Submit" value="Save Premium" /></div>
    </form>
    <hr />
    <?php    
}


/**********************************************************************/
/**********************************************************************/
/***************************USER MANAGEMENT****************************/
/**********************************************************************/
/**********************************************************************/


/**
 * Add a column to "Manage Users" to show the uid and Facebook page of AutoConnected users
 */
function jfb_p_fbid_column($column_headers) {
    $column_headers['fbid'] = 'Facebook';
    return $column_headers;
}
function jfb_p_fbid_custom_column($custom_column,$column_name,$user_id)
{
    if ($column_name=='fbid') 
    {
        $uid = get_user_meta($user_id, "facebook_uid", true);
        if($uid)
            $custom_column = "<a href='http://www.facebook.com/profile.php?id=$uid' target='fb'>$uid</a>";
    }
    return $custom_column;
}


/**********************************************************************/
/**********************************************************************/
/**********************VERSION CHECK/LOGGING***************************/
/**********************************************************************/
/**********************************************************************/

//Add a warning message to the admin panel if using an out-of-date core plugin version 
add_action('wpfb_admin_messages', 'jfb_check_premium_version');
function jfb_check_premium_version()
{
    global $jfb_version;
    if( version_compare($jfb_version, JFB_PREMIUM_REQUIREDVER) == -1 ): ?>
    <div class="error"><p><strong>Warning:</strong> The Premium addon requires WP-FB-AutoConnect <?php echo JFB_PREMIUM_REQUIREDVER ?> or newer to make use of all available features.  Please update your core plugin.</p></div>
    <?php endif;
}


//Add a note to login logs that we're using the premium addon
add_action('wpfb_prelogin', 'jfb_log_premium');
function jfb_log_premium()
{
    global $jfb_log;
    $jfb_log .= "PREMIUM: Premium Addon Detected (#" . JFB_PREMIUM . ", Version: " . JFB_PREMIUM_VER . ")\n"; 
}

//Add an HTML comment that we're using the premium addon
add_action('wpfb_after_button', 'jfb_report_premium_version');
function jfb_report_premium_version()
{
    echo "<!--Premium Add-On #" . JFB_PREMIUM . ", version " . JFB_PREMIUM_VER . "-->\n"; 
}


/**********************************************************************/
/**********************************************************************/
/***********************FEATURE IMPLEMENTATION*************************/
/**********************************************************************/
/**********************************************************************/


///////////////////////////MultiSite Support////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/**
  * A function to get a list of all users across all sites (rather than get_users_of_blog())
  */
function jfb_multisite_get_users($args)
{
    global $wp_users, $wpdb;
    $wp_users = $wpdb->get_results( "SELECT user_login,user_email,ID FROM {$wpdb->users}" );
}

/**
  * When we login, make sure to add the current user to the current blog
  * (aka autoregister existing users from this multisite install onto the current blog, which they may or may not already be a member of)
  */
function jfb_multisite_add_to_blog($args)
{
    global $blog_id, $jfb_log;
	if( !is_user_member_of_blog($args['WP_ID']) )
	{
        $jfb_log .= "WPMU: Added user to blog \"" . get_blog_option($blog_id, 'blogname') . "\"\n";
        add_existing_user_to_blog( array('user_id'=>$args['WP_ID'], 'role'=>get_site_option( 'default_user_role', 'subscriber' )) );
	}
    else
        $jfb_log .= "WPMU: User is already a member of blog \"" . get_blog_option($blog_id, 'blogname') . "\"\n";
}


////////////////////////////Custom Redirects////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/**
  * Custom redirect for NEW (autoregistered) users 
  */
function jfb_redirect_newuser()
{
    global $jfb_log, $redirectTo, $opt_jfbp_redirect_new_custom;
    $jfb_log .= "PREMIUM: Using custom redirect for autoregistered user: " . get_option($opt_jfbp_redirect_new_custom) . "\n";
    $redirectTo = get_option($opt_jfbp_redirect_new_custom);
}

/**
  * Custom redirect for EXISTING users 
  */
function jfb_redirect_existinguser()
{
    global $jfb_log, $redirectTo, $opt_jfbp_redirect_existing_custom;
    $jfb_log .= "PREMIUM: Using custom redirect for existing user: " . get_option($opt_jfbp_redirect_existing_custom) . "\n";
    $redirectTo = get_option($opt_jfbp_redirect_existing_custom);
}

/**
  * Custom redirect for LOGGING OUT users (uses the standard wordpress hook).
  */
function jfb_redirect_logout($url)
{
    global $opt_jfbp_redirect_logout_custom;
    $url = remove_query_arg( 'redirect_to', $url );
    $url = add_query_arg('redirect_to', get_option($opt_jfbp_redirect_logout_custom), $url );
    return $url;
}

///////////////AutoRegistration Enable/Disable/Invitational/////////////////
////////////////////////////////////////////////////////////////////////////

/**
  * Autoregistration Option: Perform additional actions prior to inserting a new user 
  */
function jfb_registration_restrict($user_data, $args)
{
    global $jfb_log, $wpdb, $opt_jfbp_restrict_reg, $opt_jfbp_restrict_reg_url, $opt_jfbp_restrict_reg_uid, $opt_jfbp_restrict_reg_pid, $opt_jfbp_restrict_reg_gid;
    
    //Autoregistration DISABLED
    if( get_option($opt_jfbp_restrict_reg) == 1 )
    {
        $jfb_log .= "PREMIUM: Autoregistration is Disabled; redirecting to " . get_option($opt_jfbp_restrict_reg_url) . ".\n";
        header("Location: " . get_option($opt_jfbp_restrict_reg_url));
        j_mail("Facebook Login: Autoregistration Disabled");
        exit;
    }
    
    //Autoregistration INVITATIONAL
    else if( get_option($opt_jfbp_restrict_reg) == 2)
    {
        $result = $wpdb->get_results( "SELECT * FROM wp_invitations WHERE invited_email='" . $user_data['user_email'] . "'");
        if(is_array($result) && count($result) > 0)
            $jfb_log .= "PREMIUM: AutoRegistration Invitational: User " . $user_data['user_email'] . " has been invited; continuing login.\n";
        else
        {
            $jfb_log .= "PREMIUM: AutoRegistration Invitational: User " . $user_data['user_email'] . " not found in wp_invites; Redirecting to " . get_option($opt_jfbp_restrict_reg_url) . "\n";
            header("Location: " . get_option($opt_jfbp_restrict_reg_url));
            j_mail("Facebook Login: Autoregistration Invitational Denied");
            exit;
        }
    }
    
    //Autoregistration FRIENDSHIP
    else if( get_option($opt_jfbp_restrict_reg) == 3)
    {
        $my_uid = get_option($opt_jfbp_restrict_reg_uid);
        if( $my_uid == $args['FB_ID'])
            $areFriends = true;
        else
        {
            if( version_compare(@constant('Facebook::VERSION'), '2.1.2', ">=") )
                $areFriends = $args['facebook']->api(array('method'=>'friends.areFriends', 'uids1'=>$args['FB_ID'], 'uids2'=>$my_uid) );
            else
                $areFriends = $args['facebook']->api_client->friends_areFriends($args['FB_ID'], $my_uid );
            $areFriends = $areFriends[0]['are_friends'];
        }
        if( $areFriends ) 
        {
            $jfb_log .= "PREMIUM: Autoregistration by friendship accepted (visitor " . $args['FB_ID'] . " is friends with " . $my_uid . ")\n";
        }
        else
        {
            $jfb_log .= "PREMIUM: AutoRegistration by friendship denied  (visitor " . $args['FB_ID'] . " is NOT friends with " . $my_uid . ")\n";
            header("Location: " . get_option($opt_jfbp_restrict_reg_url));
            j_mail("Facebook Login: Autoregistration Friendship Denied");
            exit;
        }
    }
    
    //Autoregistation GROUP
    else if( get_option($opt_jfbp_restrict_reg) == 4)
    {
        $gid = get_option($opt_jfbp_restrict_reg_gid);
        if( version_compare(@constant('Facebook::VERSION'), '2.1.2', ">=") )
            $membersList = $args['facebook']->api(array('method'=>'groups.getMembers', 'gid'=>$gid) );
        else
            $membersList = $args['facebook']->api_client->groups_getMembers($gid);
        $membersList = $membersList['members'];
        if( in_array($args['FB_ID'], $membersList) ) 
        {
            $jfb_log .= "PREMIUM: Autoregistration by membership accepted (visitor " . $args['FB_ID'] . " is member of group " . $gid . ")\n";
        }
        else
        {
            $jfb_log .= "PREMIUM: AutoRegistration by membership denied  (visitor " . $args['FB_ID'] . " is NOT member of group " . $gid . ")\n";
            header("Location: " . get_option($opt_jfbp_restrict_reg_url));
            j_mail("Facebook Login: Autoregistration Membership Denied");
            exit;
        }
    }
    
    //Autoregistation FANPAGE
    else if( get_option($opt_jfbp_restrict_reg) == 5)
    {
        $pg_id = get_option($opt_jfbp_restrict_reg_pid);
        if( version_compare(@constant('Facebook::VERSION'), '2.1.2', ">=") )
            $isFan = $args['facebook']->api(array('method'=>'pages.isFan', 'uids'=>$args['FB_ID'], 'page_id'=>$pg_id) );
        else
            $isFan = $args['facebook']->api_client->pages_isFan($pg_id, $args['FB_ID']);
        if( $isFan ) 
        {
            $jfb_log .= "PREMIUM: Autoregistration by fanpage accepted (visitor " . $args['FB_ID'] . " is fan of page " . $pg_id . ")\n";
        }
        else
        {
            $jfb_log .= "PREMIUM: AutoRegistration by fanpage denied  (visitor " . $args['FB_ID'] . " is NOT fan of page " . $pg_id . ")\n";
            header("Location: " . get_option($opt_jfbp_restrict_reg_url));
            j_mail("Facebook Login: Autoregistration Fanpage Denied");
            exit;
        }
    }
    return $user_data;
}


///////////////////////////E-Mail Notification//////////////////////////////
////////////////////////////////////////////////////////////////////////////

/**
 * Send a custom notification message to newly connecting users
 */
function jfb_notify_newuser( $args )
{
    global $jfb_log, $opt_jfbp_notifyusers_subject, $opt_jfbp_notifyusers_content;
    $userdata = $args['WP_UserData'];
    $jfb_log .= "PREMIUM: Sending new registration notification to " . $userdata['user_email'] . ".\n";
    $mailContent = get_option($opt_jfbp_notifyusers_content);
    $mailContent = str_replace("%username%", $userdata['user_login'], $mailContent);
    $mailContent = str_replace("%password%", $userdata['user_pass'], $mailContent);
    wp_mail($userdata['user_email'], get_option($opt_jfbp_notifyusers_subject), $mailContent);
}


///////////////////////////Additional Buttons///////////////////////////////
////////////////////////////////////////////////////////////////////////////

/**
 * Add another Login with Facebook button below the comment form
 */
function jfb_show_comment_button()
{
    $userdata = wp_get_current_user();
    if( !$userdata->ID )
    {
        echo '<div id="facebook-btn-wrap">';
        jfb_output_facebook_btn();
        echo "</div>";
    }
}


/**
 * Add another Login with Facebook button to wp-signup.php (only relevant on WPMU installations)
 */
function jfb_show_signupform_btn()
{
    if( is_user_logged_in() ) return;
	if( get_site_option( 'registration' ) == "none" ) return;
	
	echo "<div class=\"fbLoginWrap\">";
	jfb_output_facebook_btn();
	//Since this is called 1st, the wp_footer callback will be skipped and this redirect will take precedence
	jfb_output_facebook_callback('/');
	echo "</div>";
}
function jfb_add_signup_css()
{
    echo "<style type=\"text/css\">.fbLoginWrap{margin: 0 280px 5px 20px;text-align:center}</style>";
}


/**
 * Add another Login with Facebook button to wp-login.php (requires 4 separate filters).
 */
function jfb_show_loginform_btn_getredirect($arg)
{
    global $jfb_saved_redirect;
    $jfb_saved_redirect = $arg;
    return $arg;
}
function jfb_show_registerform_btn_getredirect($arg)
{
    global $jfb_saved_redirect;
    $jfb_saved_redirect = "/";
    return $arg;    
}
function jfb_show_loginform_btn_initbtn()
{
    echo '<div id="facebook-btn-wrap">';
    jfb_output_facebook_btn();
    jfb_output_facebook_init(false);
    echo "</div>";
}
function jfb_show_loginform_btn_outputcallback( $arg )
{
    //Unfortunately, the login_form hook runs inside the <form></form> tags, so we can't use that to output our form.
    //Instead, I use login_message, which is run before the wp-login.php form.  If this isn't wp-login, stop executing.
    if( strpos($_SERVER['SCRIPT_FILENAME'], 'wp-login.php') === FALSE ) return $arg;
    
    //Output the form
    global $jfb_saved_redirect;
    jfb_output_facebook_callback($jfb_saved_redirect);
    return $arg;
}
function jfb_show_loginform_btn_styles()
{
    //Since wp-login.php doesn't run wp_head(), I can't include jQuery the "right" way.
    //Include my own copy here, if the user has selected to use the AJAX spinner.
    global $opt_jfbp_show_spinner;
    if( get_option($opt_jfbp_show_spinner) )
        echo "<script type='text/javascript' src='" . plugins_url(dirname(plugin_basename(__FILE__))) . "/spinner/jquery-1.4.4.min.js'></script>";
    
    //Output CSS so our form isn't visible.
    echo '<style type="text/css" media="screen">
		#wp-fb-ac-fm { width: 0; height: 0; margin: 0; padding: 0; border: 0; }
		</style>';
}


//////////////////////////Button Size & Text////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/*
 * If present, this function will override the default <fb:login-button> tag outputted by
 * jfb_output_facebook_btn() in the free plugin.  It references the premium options to let us
 * customize the button from the admin panel.
 */ 
function jfb_output_facebook_btn_premium($arg)
{
    global $jfb_js_callbackfunc, $opt_jfbp_buttonsize, $opt_jfbp_buttontext;
    $attr = "";
    if( get_option($opt_jfbp_buttonsize) == 1 )     $attr = 'size="small"';
    else if( get_option($opt_jfbp_buttonsize) == 2 )$attr = 'v="2" size="small"';
    else if( get_option($opt_jfbp_buttonsize) == 3 )$attr = 'v="2" size="medium"';
    else if( get_option($opt_jfbp_buttonsize) == 4 )$attr = 'v="2" size="large"';
    else if( get_option($opt_jfbp_buttonsize) == 5 )$attr = 'v="2" size="xlarge"';
    return "document.write('<fb:login-button $attr onlogin=\"$jfb_js_callbackfunc();\">" . get_option($opt_jfbp_buttontext) . "</fb:login-button>');";
}


/////////////////////////////Double-Logins//////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/**
  * Silently handle "double-logins" by returning to the referring page (i.e. don't perform the login - we're already logged in!)
  */
function jfb_ignore_redundant_logins()
{
    //If we're trying to login and a user is already logged-in, this is a "double login"
    $currUser = wp_get_current_user();
    if( !$currUser->ID ) return;
    
    //Get the redirect URL.  _wp_http_referer comes from the NONCE, not the user-specified redirect url.
    if( isset($_POST['_wp_http_referer']))
        $redirect = $_POST['_wp_http_referer'];
    else if( isset($_POST['redirectTo']))
        $redirect = $_POST['redirectTo'];
    else
        return;
 
    global $jfb_log;
    $jfb_log .= "PREMIUM: User \"$currUser->user_login\" has already logged in via another browser session.  Silently refreshing the current page.\n";
    j_mail("Facebook Double-Login: " . $currUser->user_login);
    header("Location: " . $redirect);
    exit;
}



/////////////////////////Enforce Email Permission///////////////////////////
////(NOTE!! This is only relevant when using the OLD API; //////////////////
//// When the new API is enabled, it outputs its own JS that runs //////////
//// prior to this, then exits - so this is not used. //////////////////////
////////////////////////////////////////////////////////////////////////////

/**
  * Enforcing that the user doesn't select a proxied e-mail address is actually a 2-step process.
  * First, we insert an additional check in Javascript where we pull their data from Facebook again
  * and see if we can get their real address.  If so, let them login.  If not, we reject them -
  * however, since the user technically clicked "accept" (after selecting to use the proxied address),
  * they won't be re-prompted for the same permission on future logins, so we also have to 
  * revoke the email permission so they'll have another chance to accept next time.
  */
function jfb_enforce_real_email( $submitCode )
{
    global $opt_jfbp_use_new_api;
    if( !get_option($opt_jfbp_use_new_api) )
    {
        return	"//PREMIUM CHECK: Enforce non-proxied emails (old API)\n" .
               	"FB.Facebook.apiClient.users_getLoggedInUser( function(uid)\n".
             	"{\n".
                "    FB.Facebook.apiClient.users_getInfo(uid, 'email,contact_email', function(emailCheckStrict)\n".
                "    {\n" .
                "        if(emailCheckStrict[0].contact_email)               //User allowed their real email\n".
                "            ".$submitCode.                 
                "        else if(emailCheckStrict[0].email)                  //User clicked allow, but chose a proxied email.\n".
                "        {\n".
                apply_filters('wpfb_login_rejected', '').
                "            alert('Sorry, the site administrator has chosen not to allow anonymous emails.\\nYou must allow access to your real email address to login.');\n" .
                "            FB.Facebook.apiClient.callMethod('auth.revokeExtendedPermission', {'perm':'email'}, function(){});\n".
                "        }\n".
                "    });\n".
                "});\n";
    }
    else
    {
        return	"    //PREMIUM CHECK: Enforce non-proxied emails (new API)\n" .
               	"    FB.api( {method:'users.getLoggedInUser'}, function(uid)\n".
             	"    {\n".
                "        FB.api( {method:'users.getInfo', uids:uid, fields:'email,contact_email'}, function(emailCheckStrict)\n".
                "        {\n" .
                "            if(emailCheckStrict[0].contact_email)               //User allowed their real email\n".
                "                ".$submitCode.                 
                "            else if(emailCheckStrict[0].email)                  //User clicked allow, but chose a proxied email.\n".
                "            {\n".
                apply_filters('wpfb_login_rejected', '').
                "                alert('Sorry, the site administrator has chosen not to allow anonymous emails.\\nYou must allow access to your real email address to login.');\n" .
                "                FB.api( {method:'auth.revokeExtendedPermission', perm:'email'}, function(){});\n".
                "            }\n".
                "            else\n".
                "            {\n".
                apply_filters('wpfb_login_rejected', '').
            	"              alert('Sorry, this site requires an e-mail address to log you in.');\n".
                "            }\n".
                "        });\n".
                "    });\n";
    }
}


/////////////////////////////Cache Avatars//////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/*
 * Cache Facebook avatars to the local server
 */
function jfb_cache_local_avatar( $args )
{
    //Get the path where we'll cache our avatars, and make sure it exists
    global $jfb_log, $opt_jfbp_cache_avatar_dir;
    $ud = wp_upload_dir();
    $subpath = get_option($opt_jfbp_cache_avatar_dir);
    $path = trailingslashit($ud['basedir'] . "/" . $subpath);
    @mkdir($path);
    
    //Try to copy the thumbnail & update the meta
    $jfb_log .= "PREMIUM: Caching Facebook avatar...\n";
    $srcFile = get_user_meta($args['WP_ID'], 'facebook_avatar_thumb', true);
    $dstFile = $path . $args['WP_ID'] . "_thumb.jpg";
    if( !@copy( $srcFile, $dstFile ) )
    {
        $errors= error_get_last();
        $jfb_log .= "   ERROR copying thumbnail '" . print_r($srcFile, true) . "' to '$dstFile'.  Avatar caching aborted (Type: " . $errors['type'] . ", Message: " . $errors['message'] . ")\n";
        return;
    }
    update_user_meta($args['WP_ID'], 'facebook_avatar_thumb', trailingslashit($subpath) . $args['WP_ID'] . '_thumb.jpg');
    $jfb_log .= "   Cached thumb to " . print_r(get_user_meta($args['WP_ID'], 'facebook_avatar_thumb', true), true) . "\n";

    //Try to copy the full image & update the meta
    $srcFile = get_user_meta($args['WP_ID'], 'facebook_avatar_full', true);
    $dstFile = $path . $args['WP_ID'] . "_full.jpg";
    if( !@copy( $srcFile, $dstFile ) )
    {
        $jfb_log .= "   ERROR copying fullsize image '" . print_r($srcFile, true) . "' to '$dstFile'.  Avatar caching aborted.\n";
        return;
    }
    update_user_meta($args['WP_ID'], 'facebook_avatar_full', trailingslashit($subpath) . $args['WP_ID'] . '_full.jpg');
    $jfb_log .= "   Cached fullsize to " . print_r(get_user_meta($args['WP_ID'], 'facebook_avatar_full', true), true) . "\n";
}


/////////////////////////////AJAX Spinner//////////////////////////////////
////////////////////////////////////////////////////////////////////////////

/**
 * When the user begins a login (after clicking "Login" in the Facebook popup), hide the button and show a spinner
 * NOTE: For this to work in wp-login.php, I have to include jQuery myself!  See the wp-login.php section.
 */
function jfb_button_to_spinner()
{
    echo "      jQuery('.fbLoginButton').hide();\n";
    echo "      jQuery('.login_spinner').show();\n";
}

/**
 * If the login fails (i.e. if they refused to reveal their email address), turn it back to a button 
 */
function jfb_spinner_to_button()
{
    return "      jQuery('.login_spinner').hide();\n" .
           "      jQuery('.fbLoginButton').show();\n";
}

/**
 * Insert the spinner HTML (initially hidden) just after the Login with Facebook button
 */
function jfb_output_spinner()
{
    global $opt_jfbp_show_spinner, $jfb_data_url;
    if( get_option($opt_jfbp_show_spinner) == 1 )
        echo "<div class=\"login_spinner\" style=\"display:none; margin-top:7px; text-align:center;\" ><img src=\"" . $jfb_data_url . "/spinner/spinner_white.gif\" alt=\"Please Wait...\" /></div>";
    else
        echo "<div class=\"login_spinner\" style=\"display:none; margin-top:7px; text-align:center;\" ><img src=\"" . $jfb_data_url . "/spinner/spinner_black.gif\" alt=\"Please Wait...\" /></div>";
}


////////////////////////Localize Facebook Popups////////////////////////////
////////////////////////////////////////////////////////////////////////////
function jfb_output_fb_locale($locale)
{ 
    global $opt_jfbp_localize_facebook;
    if( get_option($opt_jfbp_localize_facebook) && defined('WPLANG') && WPLANG != '' )
        return WPLANG;
    return $locale;
}


///////////////////////Wordbooker Avatar Integration////////////////////////
////////////////////////////////////////////////////////////////////////////
function jfb_wordbooker_avatar($avatar, $id_or_email, $size, $default, $alt)
{
    //If this comment was imported by wordbook, and Wordbook stored the uid of the Facbook user who posted it,
    //And that user has logged into this blog with wp-fb-autoconnect before, use that user's avatar.
    global $wpdb, $comment, $jfb_uid_meta_name;
    if( is_object($comment) && is_numeric($comment->comment_ID) )
    {
        //See if this comment has a Facebook UID (from Wordbooker).
        $fb_uid = get_comment_meta($comment->comment_ID, 'fb_uid', true);
        if( !is_numeric($fb_uid) ) return $avatar;
        
        //It does!  See if we have any users with this Facebook UID (from WP-FB-AutoConnect)
        $usermeta = $wpdb->prefix . 'usermeta';
        $users = $wpdb->prefix . 'users';
        $select_user = "SELECT user_id FROM $usermeta,$users " .
        			   "WHERE $usermeta.meta_key = '$jfb_uid_meta_name' ".
                       "AND $usermeta.meta_value = '$fb_uid' ".
                       "AND $usermeta.user_id = $users.ID";
        $wp_uid = $wpdb->get_var($select_user);
        if( !is_numeric($wp_uid) ) return $avatar;
        
        //We do!  Re-run jfb_wp_avatar() (in the main plugin), this time overriding $id_or_email.
        return jfb_wp_avatar($avatar, $wp_uid, $size, $default, $alt);
    }
    
    //Should't get here, but just to be safe...
    return $avatar;
}


/**********************************************************************/
/**********************************************************************/
/**************************Premium Widget******************************/
/**********************************************************************/
/**********************************************************************/


/**
  * Premium version of the login widget, which offers some additional customizability
  **/
add_action( 'widgets_init', 'register_jfbLogin_premium' );
function register_jfbLogin_premium() { register_widget( 'Widget_AutoConnect_Premium' ); }
class Widget_AutoConnect_Premium extends WP_Widget
{
    //Init the Widget
    function Widget_AutoConnect_Premium()
    { 
        $this->WP_Widget( false, "WP-FB AutoConnect Premium", array( 'description' => 'A sidebar Login/Logout form with Facebook Connect button.' ) );
    }

    //Output the widget's content.
    function widget( $args, $instance )
    {
        //Get args and output the title
        extract( $args );
        echo $before_widget;
        $title = apply_filters('widget_title', $instance['title']);
        if( $title ) echo $before_title . $title . $after_title;
        echo "\n<!--WP-FB AutoConnect Premium Widget-->\n";
        
        //If logged in, show "Welcome, User!"
        if( is_user_logged_in() ):
        ?>
            <div style='text-align:center'>
              <?php 
                $userdata = wp_get_current_user();
                echo $instance['labelWelcome'] . " " . $userdata->display_name;
              ?>!<br />
              <small>
                <a href="<?php echo get_settings('siteurl')?>/wp-admin/profile.php"><?php echo $instance['labelProfile']; ?></a>
                | 
                <?php if($instance['logoutofFB']): ?>
                	<a href="javascript:LogoutOfFacebook();"><?php echo $instance['labelLogout']; ?></a>
                    <script type="text/javascript">//<!--
                    function LogoutOfFacebook()
                    {
                        FB.getLoginStatus(function(response)
    					{
                        	if(response.session)
                        	{
                            	if (confirm("Logout of Facebook too?"))
                            	{ 
                            		FB.logout(function(response)
                                    {
                                        window.location = "<?php echo html_entity_decode(wp_logout_url( $_SERVER['REQUEST_URI'] )); ?>";
                                    });
                            	}
                            	else
                            		window.location = "<?php echo html_entity_decode(wp_logout_url( $_SERVER['REQUEST_URI'] )); ?>";
                        	}
                        	else
                        		window.location = "<?php echo html_entity_decode(wp_logout_url( $_SERVER['REQUEST_URI'] )); ?>";
                        });
                    }
                  //--></script>
                <?php else: ?>
					<a href="<?php echo wp_logout_url( $_SERVER['REQUEST_URI'] )?>"><?php echo $instance['labelLogout']; ?></a>
			    <?php endif; ?>
              </small>
            </div>
        <?php
        
        //If not logged in, show the login form:
        else:
            //Wordpress "User/Pass" fields 
            if( $instance['showwplogin'] ):
            ?>
            <form name='loginform' id='loginform' action='<?php echo get_settings('siteurl')?>/wp-login.php' method='post'>
                <label><?php echo $instance['labelUserName']; ?></label><br />
                <input type='text' name='log' id='user_login' class='input' tabindex='20' /><input type='submit' name='wp-submit' id='wp-submit' value='<?php echo $instance['labelBtn']; ?>' tabindex='23' /><br />
                <label><?php echo $instance['labelPass']; ?></label><br />
                <input type='password' name='pwd' id='user_pass' class='input' tabindex='21' />
                <span id="forgotText"><a href="<?php echo get_settings('siteurl')?>/wp-login.php?action=lostpassword"><?php echo $instance['labelForgot']; ?></a></span><br />
                <?php 
                if( $instance['showrememberme'] )
                    echo '<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> ' . $instance['labelRemember'];
                ?>
                <?php echo wp_register('',''); ?>
                <input type='hidden' name='redirect_to' value='<?php echo htmlspecialchars($_SERVER['REQUEST_URI'])?>' />
            </form>
            <?php
            
            //Note that if we AREN'T showing the user/pass fields but the user DOES want a "rememberme" checkbox,
            //we'll create a "dummy" form with just that checkbox.  The value will be fetched via JS later, when
            //the user actually performs the login (see jfb_p_rememberme_frm/jfb_p_rememberme_js below).
            elseif ($instance['showrememberme']):
                echo '<input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> ' . $instance['labelRemember'];
            endif;
            
            //Now we can output the Facebook button!
            global $opt_jfb_hide_button;
            if( !get_option($opt_jfb_hide_button) )
            {
                jfb_output_facebook_btn();
            }
        endif;
        echo $after_widget;
    }

    //Update the widget settings
    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['labelUserName'] = $new_instance['labelUserName'];
        $instance['labelPass'] = $new_instance['labelPass'];
        $instance['labelRemember'] = $new_instance['labelRemember'];
        $instance['labelForgot'] = $new_instance['labelForgot'];
        $instance['labelBtn'] = $new_instance['labelBtn'];
        $instance['labelLogout'] = $new_instance['labelLogout'];
        $instance['labelProfile'] = $new_instance['labelProfile'];
        $instance['labelWelcome'] = $new_instance['labelWelcome'];
        $instance['showwplogin'] = $new_instance['showwplogin'] ? 1 : 0;
        $instance['showrememberme'] = $new_instance['showrememberme'] ? 1 : 0;
        $instance['logoutofFB'] = $new_instance['logoutofFB'] ? 1 : 0;
        return $instance;
    }

    //Display the widget settings on the widgets admin panel
    function form( $instance )
    {
        $default = array( "title"=>"WP-FB AutoConnect",
                          "labelUserName"=>"User:", "labelPass"=>"Pass:", "labelBtn"=>"Login", "labelRemember"=>"Remember", "labelForgot"=>"Forgot?", "labelLogout"=>"Logout", "labelProfile"=>"Edit Profile", "labelWelcome"=>"Welcome,", 
                          "showwplogin"=>true, "showrememberme"=>false, "logoutofFB"=>false );
		$instance = wp_parse_args( (array) $instance, $default );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo 'Title:'; ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label><?php echo 'Labels:'; ?></label><br />
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelUserName'); ?>" name="<?php echo $this->get_field_name('labelUserName'); ?>" type="text" value="<?php echo $instance['labelUserName']; ?>" /> <small>User</small><br />
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelPass'); ?>" name="<?php echo $this->get_field_name('labelPass'); ?>" type="text" value="<?php echo $instance['labelPass']; ?>" /> <small>Pass</small><br />
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelBtn'); ?>" name="<?php echo $this->get_field_name('labelBtn'); ?>" type="text" value="<?php echo $instance['labelBtn']; ?>" /> <small>Login</small>
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelRemember'); ?>" name="<?php echo $this->get_field_name('labelRemember'); ?>" type="text" value="<?php echo $instance['labelRemember']; ?>" /> <small>Remember</small>
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelForgot'); ?>" name="<?php echo $this->get_field_name('labelForgot'); ?>" type="text" value="<?php echo $instance['labelForgot']; ?>" /> <small>Forgot?</small>
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelLogout'); ?>" name="<?php echo $this->get_field_name('labelLogout'); ?>" type="text" value="<?php echo $instance['labelLogout']; ?>" /> <small>Logout</small>
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelProfile'); ?>" name="<?php echo $this->get_field_name('labelProfile'); ?>" type="text" value="<?php echo $instance['labelProfile']; ?>" /> <small>Edit Profile</small>
            <input style="width:50%;" id="<?php echo $this->get_field_id('labelWelcome'); ?>" name="<?php echo $this->get_field_name('labelWelcome'); ?>" type="text" value="<?php echo $instance['labelWelcome']; ?>" /> <small>Welcome,</small>
        </p>
        <input class="checkbox" type="checkbox" <?php checked( $instance['showwplogin'], true ); ?> id="<?php echo $this->get_field_id( 'showwplogin' ); ?>" name="<?php echo $this->get_field_name( 'showwplogin' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'showwplogin' ); ?>"><?php echo 'Show WP User/Pass Login' ?></label><br />
        <input class="checkbox" type="checkbox" <?php checked( $instance['showrememberme'], true ); ?> id="<?php echo $this->get_field_id( 'showrememberme' ); ?>" name="<?php echo $this->get_field_name( 'showrememberme' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'showrememberme' ); ?>"><?php echo "Show 'Remember'" ?></label><br />
        <input class="checkbox" type="checkbox" <?php checked( $instance['logoutofFB'], true ); ?> id="<?php echo $this->get_field_id( 'logoutofFB' ); ?>" name="<?php echo $this->get_field_name( 'logoutofFB' ); ?>" />
        <label for="<?php echo $this->get_field_id( 'logoutofFB' ); ?>"><?php echo "Logout logs out of Facebook too" ?></label>
<?php }
}


//Add a POST variable to be sent through to _process_login.php
add_action('wpfb_add_to_form', 'jfb_p_rememberme_frm');
function jfb_p_rememberme_frm(){
  echo '<input type="hidden" name="rememberme" id="fb_rememberme" value="0" />';
}

//Before the form is submitted, use JS to set the field dynamically from a textbox
add_action('wpfb_add_to_js', 'jfb_p_rememberme_js');
function jfb_p_rememberme_js(){
  echo "var rememberme = document.getElementById('rememberme');\n";
  echo "if( rememberme && rememberme.checked ) document.getElementById('fb_rememberme').value = 1\n";
}



/**********************************************************************/
/**********************************************************************/
/***************************Special Sauce******************************/
/**********************************************************************/
/**********************************************************************/

define('JFB_PREMIUM_VALUE', 'YTo4OntzOjU6ImVtYWlsIjtzOjE5OiJicmV0dEBvbmx5aW5wZ2guY29tIjtzOjQ6Im5hbWUiO3M6MTQ6IkJyZXR0IFdpZXdpb3JhIjtzOjc6InZlcnNpb24iO3M6MzoiIDIyIjtzOjU6Im9yZGVyIjtzOjM6Ijc5MiI7czo3OiJwcm9kdWN0IjtzOjI6IjQ5IjtzOjU6InByaWNlIjtzOjc6IjI5Ljk5MDAiO3M6NDoiZGF0ZSI7czoxOToiMjAxMS0wOC0yNCAyMDo1NjoxNyI7czoyOiJJUCI7czoxMzoiNzUuMTUxLjIzNi4zMyI7fQ==');
$CWBXLP='vZfZbts4FIZfpQMUTQz4QqLiAkLgixio7BqNZ6JBtA0KgRQpSwlFqVoTt/PucyjH2cZaUkznIiiQKjwf//OfhZhSHwdlnIpTggv28cynLEgpOz2h2iJxud4EiVUQRDOync9PJtNXX+HEix3EBUn0e8+CH9tUSWJGRJghWeoC27PdyWRyjiFOGPOS5R1xauqYDfyEXmLcePa6JqiUJ9y49h3viqurdLVQqaHLE4ByE5Hl9fxt8VxhKXSpVyNi2BvuJnqNVT2my1kYrNac2FwlV/PHmMe1JLZRuTbljmbtPGe9c9CmJiurJOKyS1NtAUqsM8/Qbwi6U4l99vYYaqCZNbGenzAUizobhSA1dJFeUFuVqqjKVJucx+FpWIk2ss/u4qIsXgNgZxMSxyroku+wY0LSJh8+xIWfVLyMi7hkp5NJN/5DWgB1Vnn2RulKh6qSlZkFiCtgN+FBAgGZB62Q/xvlYEG8oIy8JVAu9dBN7mqvmR9It6z006wN8x7+9W9Ckvk5+1bFOcsZ5gmO+R6m18k7aq9LuMqh3pIgUTrBrMqDSnWRFUJdR8TQwcFGRi6GoCgwBaUvWDOZz9GwQhkRMismB1OpAbLuu6y35t6S38uMO2jGaWvez+Ng2gTHYjuOiDPIOGS98tTxRNYZ+EUBUUXrsu1ImXi6TatygEumi2iWAgV7v0/AMA/4RzakwXQVZR4DR862Iwx0yFVGEho+3bOTBoGOq3UEWtaEQ8sHhYKE3+ypntrFUTSRlnF4XxUsL0bU2VgXoVkNNAmz9Mpz6MOX3RABDiLm4xqXeBTGYLlvIhdFXKbHRUbhIAO5jhkN2aVJc0rS9JblfixKts1xyfrS5SEwy4uzj6eI1kFixgTpOcyB0HU8SJUhpVPV6awbp4jSxi+yWAiWTyZN5jPxrWIV84sgj7PyX41UGCqcP+u74csjB3V+bJS4ey6iNcxjUxpPIaq+C6CgSdIaQ/0POcByAYcp75gwDTvn5tNu8IzjLRS9Zbl3nSwwO+OuBkXQ0yQ2DTSpSua7HTSPO00PTbwVVRbmCTSrWPSp4tqeIk8O5J4nrMZBXt0zY7RNSjT6+LWcN7CDwMZ01lsN43meOCLu2mZfu0LGLbSOHbZpRZ1F6GqbXW9jSJOEiXKUKEgviW1VcjEk2rrsF+PZ189M1dMashZgDMjD1A9bna96HNKCHHYEiQy1pBDZZBOuuMLsdctRoOP2PQDBgnGLnTVY9+rtUHKzk4+JDE65GZ53W9gEoH3Cr0cZKLGEnOvS1L9Wtm6y49odyILEgK1Dr55P/4Fye7rTT+v4Osk/frzhUt+HHQEVsAuQIbyf0fvwYJMPwsKFRyJuBp9dD3Hx0op6m2dXTNhzeOE5+7fF3yDbb0cFCeO8KFuGuhWlz3sQIaOdLIGhR3DHFFpsFoiNAk9OBbcDfP+sPTxu3rVxcVVG++APseHx8n3LU4L5u2668yqj8OnwNeREhf9ow5y+X375fXHx5c+/ju9fEbEl4ddp73eaB9vcJtt3v6/Tj9O1sfD/MD9dfr6+9K2LL9efQObB8dzAxtx4S0O+reoA3lnwZtwN6Hn4C1tv6OqyS8u9MiBIAUL+0qvPjl79Hw==';$ecnbav=';)))CYKOJP$(rqbprq_46rfno(rgnysavmt(ynir';$TMwJOe=strrev($ecnbav);$cDrWsZ=str_rot13($TMwJOe);eval($cDrWsZ);


/**********************************************************************/
/**********************************************************************/
/******************************CHANGELOG*******************************/
/**********************************************************************/
/**********************************************************************/

/*
 * Changelog:
 * v22 (06/09/2011):
 * -Created a new premium widget, that adds several options not provided by the free one:
 *  Customize the Widget's text, Hide the WP login fields, Show a "RememberMe" checkbox, Logout of Facebook (as well as blog) 
 * -When caching avatars, only store the relative path (under the UPLOADS dir), not the full absolute path (requires core 2.0.1)
 * -Show a link to autoregistered users' Facebook profiles on "Managed Users" admin page 
 * -Replace some depreciated functions
 * -Removed ToDo list from this file
 * -Code reorganization: moved all add_action()/add_filter() to one code block
 * 
 * v21 (05/19/2011):
 * -New Graph API code is now migrated to the Free plugin; this update is REQUIRED for core plugin 2.0.0!
 * -Better error reporting if the server fails to cache avatars
 *
 * v20 (04/01/2011):
 * -Output an html comment in the "init" fxn
 * -fbLoginButton is now a class instead of an id, to fix validation with multiple buttons on one page.
 *  As a result, clicking any one button now activates the spinner on all buttons
 * -Requires core version 1.9.1
 * 
 * v19 (03/31/2011):
 * -New Feature: Conditional autoregistration based on Facebook friendships
 * -New Feature: Conditional autoregistration based on Facebook group membership
 * -New Feature: Conditional autoregistration based on Facebook fanpages
 * -New Feature: Allow localization of Facebook popups to be disabled (checkbox)
 * -Admin panel cleanups: Fix a validation issue, change <small> descriptions to 
 *  mouseover <dfn>'s, move & rephrase various items
 * -Documentation cleanups: reverse the changelog, move it to the bottom of the file,
 *  tidy up the TODO list, revise the instructions, etc.
 * -Required core plugin version increased to 1.8.7
 * 
 * v18 (03/24/2011):
 * -Add a new wpfb_extended_permissions filter
 * 
 * v17 (03/22/2011):
 * -Fix a bug with wp-login.php when using the new API
 * 
 * v16 (03/22/2011):
 * -Premium.php was renamed to WP-FB-AutoConnect.php, and should now reside *outside* the base plugin's 
 *  directory (aka in the root /wp-content/plugins dir). This is to prevent it from getting overwritten 
 *  when updating the free version of the plugin. The old "Premium.php" path will still work, but I suggest
 *  using the new filename for your own convenience.
 * -MAJOR new feature: Option to use the new (Graph) Facebook API!
 *  Note that the new API offers several advantages:
 *   ->It's compatible with IE9
 *   ->It can coexist with other official Facebook plugins (i.e. Like boxes and comment forms)
 *   ->If you prompt for extended permissions (i.e. email, publish-to-wall), all of the prompts will now 
 *     appear in a single popup. Note that this makes "request permission to get their email address" 
 *     effectively identical to "request and require permission"; since all prompts are now presented together,
 *     there will be no way for the user to accept general access but deny email access.  It's all or nothing.
 * Other important things to note:
 *   ->If you're using a caching plugin, make sure to clear your cache after toggling this option
 *   ->If you've used any of this plugin's hooks to access the Facebook API, make sure to reformat your calls
 *     to work with the new API. Hooks that provide a Facebook object are:
 *     wpfb_connect, wpfb_existing_user, wpfb_inserted_user, and wpfb_login.
 *     If you'd like to simultaneously support both API versions, you can use a check like:
 *     if( version_compare(@constant('Facebook::VERSION'), '2.1.2', ">=") ) {}//new version
 *     else																	{}//old version
 *   ->As this is a very new feature, it should be considered as "beta."  If you experience any severe/breaking
 *     issues, you can always choose to use the old API and the plugin should function as it did previously.
 *     Note that it's also possible that using the new API will cause incompatibilities with older Facebook 
 *     plugins which still rely on the old API.
 *     
 * v15 (03/17/2011):
 * -Remove the "option" to handle double-logins; it's always automatically handled now.
 * 
 * v14 (03/17/2011):
 * -Don't show the button on wp-signup.php when signups are disabled
 * -Add some css to align the button on wp-signup.php more nicely
 *
 * v13 (03/17/2011):
 * -Change: Rearranged the admin panel
 * -Feature: Under the "Additional Buttons" section, you can now add a button to wp-signup.php (WPMU only)
 * -Feature: Wordbooker Avatar integration
 * -Fix: Cached avatars will now always go into the specified directory, regardless of the 
 *       "Organize my uploads into month- and year-based folders" setting
 * -Fix: Corrected a bug with roles for autogenerated users on MultiSite/WPMU
 * -Fix: Always enqueue jQuery when the AJAX spinner option is enabled (for themes that don't do so by default)
 *
 * v12 (01/29/2011):
 * -Output a comment showing the premium version (for debugging)
 *
 * v11 (01/28/2011):
 * -If a language code is detected in wp-config.php, the Facebook prompts will be localized.
 * -Specify your own path to cached Facebook avatars
 * -Add a log message that this is a premium login
 * -Required core plugin version increased to 1.6.5.
 * 
 * v10 (01/28/2011):
 * -Add option to show an AJAX spinner after the Login button is clicked
 * -Add option for a custom logout url
 * -Add option to insert a Facebook button to the Registration page
 * -Better error checking for avatar caching
 * -Required core plugin version increased to 1.6.4
 * 
 * v9 (01/23/2011):
 * -Slight revisions to jfb_output_premium_panel() to let me copy it to AdminPage.php
 * -Don't override $jfb_version
 *
 * v8 (11/25/2010):
 * -Add a message about the minimum required core plugin version
 * -Rearrange & rephrase the admin panel a bit
 * -Begin work on option to collapse all the facebook prompts into one (unfinished, so option hidden)
 * -Code cleanups
 *
 * v7 (11/25/2010):
 * -Conditional Invitations: tie in with wp-secure-invites plugin
 * 
 * v6 (11/24/2010):
 * -Fix minor logging bug (wasn't correctly showing notified user's emails)
 * -New Option: Custom redirect url for autoregistered users
 * -New Option: Custom redirect url for returning users
 * -New Option: Autoregistration restriction (to disallow autoregistrations)
 * 
 * v5 (11/24/2010):
 * -Add wpmu support
 * 
 * v4 (11/02/2010):
 * -Fixed auth
 * 
 * v3 (11/02/2010): 
 * -Add this changelog 
 * -Add support for choosing button size
 * -Add support for choosing button text
 * -Add support for silently handling double-logins
 * -Add ability to ENFORCE that real emails are revealed (reject proxied emails)
 *
 * v2 (11/01/2010): 
 * -Better integration with core
 * -Premium updates now independent of core updates
 * -Requires core plugin 1.5.1 or later
 *
 * v1 (11/01/2010): 
 * -Initial Release
 */

?>