=== Custom Menu Images ===
Contributors: anabelle
Donate link: http://8manos.com/
Tags: nav_menus, images, custom images, icons, thumbnails, background, media gallery, upload, custom menu, item image
Requires at least: 3.0
Tested up to: 3.1.2
Stable tag: 0.8.5

Adds an image field to all menu items and generates CSS, for user side background image manipulation.

== Description ==

Adds an optional image field to all menu items. Images can be uploaded or linked. The plugin then generates basic CSS so you can work with images.

It can be used to set thumbnails and make mega-dropdowns, or to add a custom icon to each menu item 

Once configured by the developer, the site administrator can easily change each image without touching a line of code.

It wont always look nice out of the box (actually it never does), it is intended for developers who want extra functionality in the menu, and ease of use for their site administrators.

== Installation ==

1. Upload custom-menu-images folder to the /wp-content/plugins/ directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to manage menus and each item will have a Custom image field.
4. You should now go add some images and save.
5. Configure your CSS to display background images as you want. Just the image url is set by the menu so you should set background position, repeating, and menu item size. Even sprites can be used, or image replacement techniques.

== Frequently Asked Questions ==

= Why does it show `Parse error: syntax error, unexpected T_STRING, expecting T_OLD_FUNCTION or T_FUNCTION or T_VAR or '}'` ? =

You are most likely still using PHP4, In order to use this plugin PHP5 is required. 
Ask your hosting company about this or update it yourself.

= Why does it look bad? =

Because you have to use it in conjuction with your custom CSS

= More questions? =

Please write in the forums or write us to plugins@8manos.com we will be speaking in no time.

== Screenshots ==

1. Menu items with images from media library.

== Changelog ==

= 0.8.5 =
* We now generate a single CSS file for all menus, that means less HTTP requests and avoiding a common error where the active menu IDs where not correctly identified. 

= 0.8.1 =
* Minor fixes

= 0.8 =
* Multiple menus now supported
* CSS Simplified and moved to external file

= 0.5 =
* Fixed some bugs to allow selection of image size from Media Library ("Thumbnai", "Medium", "Large", "Original Size").

= 0.2 =
* Initial release
* Just testing

== Upgrade Notice ==

= 0.8.5 =
If you are having a 404 error from the CSS file, this version will fix your problem, just update, go and save your menus. CSS will be correctly generated now.

= 0.8.1 =
We like quick iterating. Thank for testing with us.

= 0.8 =
It is getting better, Still testing.

= 0.5 =
If you want to choose the image size to use and not just thumbnail, this update is for you.

= 0.2 =
Initial release, come on and help us test it!
