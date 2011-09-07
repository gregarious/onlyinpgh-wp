=== Quick Post Widget ===
Contributors: inapan
Donate link: http://www.famvanakkeren.nl/downloads/quick-post-widget/
Tags: post, quick, sidebar, frontpage, frontend, front end, anonymous, captcha, widget, tinymce, WYSIWYG, visual editor, easy, fast, upload
Requires at least: 2.8
Tested up to: 3.2
Stable tag: 1.9

This plugin provides a widget to post directly from the frontpanel of your site without going into the backend.


== Description ==

The quick and easy way to let users and guests post from the frontend of your site.


= Overview =

**New:** now you can not only post from the *sidebar*, but also from a *page*, with immediate or popup visual editing!

Looking for a quick and easy way to post directly from the frontend of your site? The Quick Post Widget has it all!  

This plugin provides a widget to post directly from the frontend of your site without going into the backend.  
The widget optionally offers visual editing while posting from the frontend, with a bunch of features (media uploading, spellchecking, preview,....)!  
Guest posting can be enabled with optional captcha security.  
Apart from post title, content and categories, tags, custom fields, custom taxonomies, post date and excerpt can optionally be entered.  
You can easily add images or video-clips to your posts without using the backend of your site.  
Originally developed to enable my 10-year old son to post youtube clips simply by copying and pasting the title and code from youtube and choose a category without having to use the backend of his blog.  
Of course the widget can be used for just about anything you want to post quickly. You could use the Quick Post Widget to write short messages, like twitter, but of course you can also write longer posts.  
The widget is highly configurable with the options panel.


= A demo says more than a thousand words =
Try it at [qpw.famvanakkeren.nl](http://qpw.famvanakkeren.nl "Quick Post Widget").  
The tags, excerpt, post date, custom fields and custom taxonomies are configured hidden in the demo.  
But they can all be enabled and set required if you wish!


= Features =

* Post directly from the frontend of your site (sidebar or page).
* The widget optionally offers visual editing directly from the frontend of your site, without going into the backend, with a bunch of features (media uploading, spellchecking, preview,....)!
* Media uploading can be disabled. You can choose to use a shared directory or private directories per user. Directories are automatically created.
* Input: post-title, post-content, category and optionally tags, custom fields, custom taxonomies, post date and excerpt. All optional items can be set required.
* By default the post is immediately published but you can set the default publish status in the options panel.
* By default the widget only displays when logged-in and allowed to post but guest posting can be enabled with optional captcha security to protect your blog from spam.
* When allowed to create new categories and enabled in the admin panel, normal categories can be added as well as categories for custom taxonomies.  
  For categories and hierarchical taxonomies it is possible to select the parent category.
* Categories and custom taxonomies can be displayed in either a droplist or a checklist to enable selecting more than just one category.
* Specific categories can be included or excluded.
* To support custom post types optionally the post type can be set in the admin panel.
* Optionally tags can be typed in manually but you can also select from a list of existing tags.
* The widget offers visual error checking by changing the border to a selectable color when a required item remains empty.
* Optionally a mail can be sent on quest posts or all posts.
* Every widget and options panel element can be styled with the stylesheet.
* Almost every widget element can also be styled with the options panel so you won't lose any customizations by upgrading.
* You can configure the widget as full as possible but to keep the widget small you can disable all the extra features so only the title and content can be entered.


== Installation ==

1. Download the plugin.
2. Upload the entire `quick-post-widget` folder to the `/wp-content/plugins/` directory of your blog.
3. Go to the Plugins section of the WordPress admin and activate the plugin.
4. Go to the Widget tab of the Presentation section and configure the widget.


== Frequently Asked Questions ==

= Feature requests = 
If you have any feature requests feel free to mail (or use the forum). Suggestions are welcome, but I will try to keep the widget as general as possible.

= How do I configure guest access? =
To enable guest access without being logged-in, enable the option [Allow guests (not logged-in)]. You will also have to create and/or select a dedicated guest account. You could for instance create an account 'Anonymous' or 'Guest'. Every post a non-logged-in guest creates will be created under this special guest account.  
In the user tab you can enable visual editing for the guest account, unless it is globally disabled in the widget's admin panel, and select a role. The selected role determines the capabilities of the guest account:  

Subscriber: cannot post. There's no point in using this role for the guest account.  

Contributor: can post but posts will have to be reviewed (pending for review). This overrides the default publish status in the admin panel (only for the guest account).  

Author: can also post but posts will have the default publish status selected in the admin panel. The role also allows media uploading from the visual editor (unless it is globally disabled in the widget's admin panel).  

Editor: same as Author but also allows creating new categories (unless it is globally disabled in the widget's admin panel). 

Admin: same as Editor.

Remember the role only determines the capabilities of the guest account when not logged-in. It is better not to use the guest account to actually log in.  
Use these options with care and only in controlled environments. Your blog will be open to everyone!

= How do I put the widget on a page? =
In the Dashboard (Appearance->Widgets) you will see an extra widget area called 'Quick Post Page'. This area can hold an instance of the Quick Post Widget.  
Drag the widget here and configure it. Use the shortcode [quick-post-page] to call the widget area in a page. You can also drag some other widgets here, for instance a textwidget with a tutorial on how to use the Quick Post Widget.

= How do I disable the visual editor? =
The visual editor can be disabled from the admin panel. It is however possible to disable the editor on a user basis by selecting the 'Disable the visual editor when writing' in the user's profile.

= How do I configure showing and hiding the widget? =
To enable the show and hide option both [Showtext] and [Hidetext] fields have to be given a value in the widget's admin panel (there's no point in enabling this option if you don't know where to click to show or hide the widget). The initial visibility can be set with the [Visibility] droplist.

= What about custom fields? =
In the admin panel you can define up to 10 custom fields to show in the widget. For every custom field you can define whether entering a value is required. You could use custom fields for instance to let guest users enter their email address or nickname. Custom fields only show up when both field an label are defined.

= What about custom taxonomies? =
For every existing custom taxonomy you can define whether input is required, whether entering a new category is allowed and how to display the taxonomies categories (droplist or checklist).

= How to hide the category selection? =
In the admin panel it is possible to include or exclude certain categories, to choose the categories order and to set a default category. If only one category remains, the default category is the same as the only category and creating a new category is disabled, the complete category section hides itself.

= The widget temporarily hides Flash content (YouTube). Why? = 
By default flash content bleeds through the modal dialog because of its wmode behaviour. Of course you can manually change the wmode parameter of Flash content but just hiding it temporarily is easier.

= How does uploading work? = 
In the media and image popups in the visual editor there is a small icon at the end of the Image/File URL fields. Clicking it opens an additional file manager popup with uploading capabilities. You can choose to use a shared directory or private directories per user.

= How can I upload for instance pdf- and doc-files? = 
To upload non-media/image files use the [Insert/edit link] button in the popup editor. In the popup which opens there is a small icon at the end of the Link URL field. Clicking it opens the file manager popup from where you can upload. The result will be a link to your uploaded file.

= My upload ends unsuccessfully with error 500 =
If you are using Apache with mod_security you can avoid those errors by putting the following lines in the .htaccess file in your WordPress root:  
SecFilterEngine Off  
SecFilterScanPOST Off

= Translations =
Translations for the popup visual editor are complete. The translations for the rest of the widget and the backend are, apart from Dutch, still incomplete.  
Please help me translate using the po- and pot-files in the langs subdirectory of the widget.

= Use P- or BR-tag for newlines? =
Using the P-tag for newlines is strongly recommended. Using the P-tag results in paragraphs when using [Enter]. Although using the BR-tag saves space it is not recommended.  
If you use the P-tag and don't want a new paragraph when hitting [Enter], thus saving space, use [Shift][Enter] instead.

= Bugs = 
If you experience bugs please mail or use the forum (don't just say its broken).


== Screenshots ==

1. The actual widget functioning on a site, configured full
2. The Quick Post Widget visual editor
3. The actual widget functioning on a site, configured small
4. Part of the options panel in the widget


== Changelog ==

= 1.9 =
* The widget can be placed on a page without the use of an extra plugin, with direct or popup visual editing. Please read the FAQ to configure.
* The maximum number of custom fields is raised form 5 to 10.
* Optionally text and/or shortcodes can be defined to be placed automatically at the top or bottom the post content.

= 1.8.1 =
* Optionally a custom post type can be set in the admin panel.
* Added right-to-left support.
* Optionally mail to admin when a guest post or any post is submitted.
* Added Finnish translation (big thanks to Jaakko Kangosjärvi).
* Show unattached tags in the tag droplist.
* Added buttons to message popups.

= 1.8 =
* At last, custom fields. You can define up to 5 of them. Please read the FAQ to configure.
* Also, custom taxonomies (at least WP3.0 required). Please read the FAQ to configure.
* At least one theme uses the same function name as the plugin does. Fixed it.
* Optionally the post date/time and excerpt can be set.
* The titles of the Visual Editor and message windows can be set.
* Optionally a message can be shown when posting is unsuccessful.
* Tuned some styling.

= 1.7.3 =
* Fixed a nasty bug which occasionally caused 301 redirect errors when no redirect URL was configured.
* Added the media plugin since WordPress 3.1 dropped it.
* Disabled the unnecessary loading of language files.
* Prevented unnecessary loading of styles and script in admin area.
* The category droplists now don't lose their values after an unsuccessful post.
* Added Private as publish status.
* Fixed jQuery UI CSS to center dialog in Chrome.
* Rearranged the admin panel.
* It is now possible to include or exclude certain categories, to choose the categories order and to set a default category. If only one category remains, the default category is the same as the only category and creating a new category is disabled, the complete category section hides itself.
* Fixed some browser issues: re-post on refresh (Firefox), radio buttons after refresh (Firefox, Chrome).

= 1.7.2 =
* Captcha security can be enabled to protect your blog from spam.
* Added the style plugin to enable advanced styling in the visual editor (works best with the P-tag).
* Optionally the widgets visibility can now be toggled (please read the FAQ section).
* Optionally a URL can be defined to redirect to after a successful post.

= 1.7.1 =
* Two bug fixes (guest users could not post using the category checklist in WP3, empty iframes using the visual editor).
* Optionally a confirmation message can be displayed after a successful post.

= 1.7 =
* Provided an option to let guests create posts without being logged-in. Use with care! Please read the FAQ section to configure.

= 1.6.1 =
* Added Italian and Polish translations for popup visual editor and the file manager.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate using the po- and pot-files in the langs subdirectory of the widget.

= 1.6 =
* Internationalized the popup visual editor and the file manager for English, Dutch, French, German, Portuguese and Spanish. The language follows the WordPress language setting.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate using the po- and pot-files in the langs subdirectory of the widget.

= 1.5 =
* Enabled media uploading in the popup visual editor (please read the FAQ section).  
* Fixed one or two small bugs.

= 1.4 =
* Enabled resizing, auto focus, paste plugin and default WordPress options of the popup WYSIWYG editor.  
* Provided an option to choose between using the P-tag or the BR-tag for newlines.

= 1.3 =
* The widget now offers visual editing directly from the frontpanel of your site, without going into the backend, with a bunch of features (spellchecking, preview,....).   If you do not want visual editing you can disable this feature. If you encounter difficulties you can disable the editor plugins.

= 1.2 =
* Categories can also be displayed in a checklist to enable selecting more than just one category.
* A new category can be given a parent category.
* Tags can be typed or selected from a list. Tags can be disabled in the options panel.
* It is possible to set the default publish status in the options panel.
* The creation of new categories can be disabled in the options panel.
* Slightly changed stylesheet.
* Cleaned up some code.

= 1.1.1 =
* Fixed a bug which occurs when wordpress is installed in a subdirectory.

= 1.1 =
* When allowed to create a new category, radio buttons are displayed to choose between an existing category and a new category as well as an input box to type in the name of the new category.
* Built in visual form validation. When a required item remains empty the item-border displays default red, but you can choose other colors (in case you have a red site).
* Corrected and slightly changed stylesheet.

= 1.0 =
* First release


== Upgrade Notice ==

= 1.9 =
* The widget can be placed on a page without the use of an extra plugin, with direct or popup visual editing. Please read the FAQ to configure.
* Maximum number of custom fields is now 10.
* Optionally text and/or shortcodes can be defined to be placed automatically at the top or bottom the post content.

= 1.8.1 =
* Optionally a custom post type can be set in the admin panel.
* Added right-to-left support.
* Optionally mail to admin when a guest post or any post is submitted.
* Added Finnish translation (big thanks to Jaakko Kangosjärvi).
* Show unattached tags in the tag droplist.
* Added buttons to message popups.

= 1.8 =
* Custom fields, please read the FAQ to configure.
* Custom taxonomies (at least WP3.0 required), please read the FAQ to configure.
* Fixed function naming issue.
* Optionally post date/time and excerpt can be set.
* Visual Editor and message window titles can be set.
* A fail message can be shown.

= 1.7.3 =
* Fixed cause of occasional 301 redirect errors.
* Category droplists now don't lose values after unsuccessful post.
* Added Private publish status.
* Rearranged admin panel.
* Made possible to include or exclude categories, choose categories order and set default category.
* Some rewriting and fixing.

= 1.7.2 =
* Captcha security can be enabled.
* Added the style plugin to enable advanced styling in the visual editor (works best with the P-tag).
* Optionally the widgets visibility can now be toggled (please read the FAQ section).
* Optionally a URL can be defined to redirect to after a successful post.

= 1.7.1 =
* Two bug fixes (guest users could not post using the category checklist in WP3, empty iframes using the visual editor)
* Optionally a confirmation message can be displayed after a successful post.

= 1.7 =
* Provided an option to let guests create posts without being logged-in. Use with care! Please read the FAQ section to configure.

= 1.6.1 =
* Added Italian and Polish translations for popup visual editor and the file manager.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate.

= 1.6 =
* Internationalized the popup visual editor and the file manager for English, Dutch, French, German, Portuguese and Spanish. The language follows the WordPress language setting.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate.

= 1.5 =
* Enabled media uploading in the popup visual editor (please read the FAQ section).
* Fixed one or two small bugs.

= 1.4 =
* Enabled resizing, auto focus, the paste plugin and default WordPress options of the popup WYSIWYG editor. Provided an option to choose between using the P-tag or the BR-tag for newlines.

= 1.3 =
* The widget now offers visual editing directly from the frontpanel of your site, without going into the backend, with a bunch of features (spellchecking, preview,....).

= 1.2 =
Categories can also be displayed in a checklist to enable selecting more than just one category.
A new category can be given a parent category.
Optionally tags can be defined by typing or selecting from a list.
The default publish status can be defined.
Creation of new categories can be disabled.

= 1.1.1 =
Fixed a bug which occurs when wordpress is installed in a subdirectory.

= 1.1 =
Adds a new feature to create a new category while posting.
Built in visual form validation.
Corrected and slightly changed stylesheet.

= 1.0 =
This is the first release of this plugin, no upgrade necessary.