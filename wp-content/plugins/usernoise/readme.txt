=== Usernoise modal feedback / contact form ===
Contributors: karevn
Tags: feedback, contact, form, contact form, email, ajax, custom, admin, button, russian, dashboard, lightbox, ipad, plugin, jquery
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 0.4.1

Usernoise is a modal contact form with flexible and smooth interface.

== Description ==

Usernoise is a "just works" modal contact / feedback form. You will not need to change even a line of code in your site.

= Features =
* Adds a customizable "Feedback" button and form to your site.
* Just works - no need to modify anything in your site's theme.
* Flexible typography - it matches your site's style by default.
* Button and form copy is fully customizable from admin area.
* You can disable extra fields such as feedback type and title.
* Admin notifications, feedback archive available at admin area.
* IE7+, Firefox, Chrome, Safari, Opera, Safari on iPad supported.
* Modal window can be controlled by Javascript API.
* Lots, lots of options.
* High performance - Usernoise almost does not affect page loading speed.

= Translations =
If you need Usernoise in your language which is not supported currently - you can help Usernoise by 
translating it. Don't worry, translating is extremely easy - just [download a localization template](http://plugins.svn.wordpress.org/usernoise/languages/usernoise.pot), edit it with your favorite text editor and send to email hello (at) karevn dot com. Your help will be really appreciated.

__Available localizations__

* Dutch - by Reggie Biemans
* French - by Brad Coudray
* Portuguese - by rasilva
* Russian - by Nikolay Karev
* Spanish - by Dario Doidos
* Turkish - by Emre Aydin


= Support =
Having problems or need support? Feel free to email me - hello (at) karevn dot com or [open a support topic](http://wordpress.org/tags/usernoise?forum_id=10#postform).

== Installation ==

1. Upload `usernoise` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings - Usernoise in admin area of your site, set it up to your needs.

== Frequently Asked Questions ==

1. **Feedback button disappearead after upgrading to 0.4**. Most probably, it is script caching issue, because 0.4 uses slightly different button embedding techinique. Try to clean your cache. If it still does not show - please email me or start a support topic.
1. **Is Usernoise spam-proof?** -- Yes, it almost is. Let me know if you are receiving a lot of spam with Usernoise - I'll try to help.
2. **I need Usernoise in my language** - [Download a localization template](http://plugins.svn.wordpress.org/usernoise/languages/usernoise.pot), edit it with your favorite text editor, and send me to `hello (at) karevn dot com`. I will include your translation and credits into the next Usernoise release. It takes 2-3 days usually.
3. **Usernoise does not work on my site** - Please send me a bugreport or [open a support topic](http://wordpress.org/tags/usernoise?forum_id=10#postform). Don't forget to include: WordPress version, Usernoise version, which other plugins/themes are you using. That will give me more info for figuring out the problem. Having a real site url usually helps too.
4. **Can I add some custom fields to Usernoise form?** - Yes, you can, but it requires some coding skills. Use `un_feedback_form_body` action and `un_validate_feedback` filter.
5. **Usernoise is missing /some feature/** - Feel free to contact me! I really appreciate new ideas.

== Screenshots ==

1. Usernoise form on Nikolay Karev`s site.
2. Admin bar with "New usernoise records" notification.
3. Admin interface

== Changelog ==

= 0.4.1 = 
* Slight refactoring... as usually
* Compatibility with non-standard installs where plugins are not at wp-content
* Minor CSS compatibility fixes
* Spanish localization added, thanks to Dario Doidos
* Turkish localization added, thanks to Emre Aydin
* Added an option allowing to hide Usernoise button on mobile devices
* Direct link to usernoise settings added to admin bar
* Admin bar items not shown when Usernoise features are not available to the current user

= 0.4 =
* Post type changed to `un_feedback` to avoid conflicts.
* Added an option not to show feedback button border.
* Added a shortcut settings button at the feedback form. 
* Security hardening - now only administrators and editors can manage feedback.
* Feedback button removed from HTML code to improve compatibility
* Simplified localization loading

= 0.3.1 = 
* Admin area fix - feedback types were not shown in Firefox
* Submit feedback button compatibility with themes improved.
* feedback_type_slug WP_Query option added
* Portuguese localization added, thanks to rasilva
* Feedback button z-index increased for sake of compatibility with themes

= 0.3 =
* "Email feedback author" feature added.
* IE8 with left-positioned button fixed.
* JS code refactored totally, became much more extensible.
* Feedback form is loaded with separate ajax request now.
* Usernoise frontend code is not loaded at the backend now.

= 0.2.3 = 
* Minor optimization
* Compatibility with older jQuery versions < 1.4.1
* CSS code refactored
* Added "un_feedback_form_body" action to allow adding new fields.

= 0.2.2 =
* Dashboard widget added
* IE9 compatibility fix
* "Feedback button position" option added
* French localization added, thanks to Brad Coudray

= 0.2.1 =
* Added admin notifications settings
* Duplicated usernoise settings section link in admin UI to make it cleaner
* Bugfix: usernoise did not load jquery, relying on loading it by WP theme
* Feedback form style updated a bit
* Dutch localization added, thanks to Reggie Biemans
* Localization bugs fixed

= 0.2 =
* Customizable feedback button color
* Customizable form font
* Localization-ready
* Russian localization
* Added an option not to show email field
* Feedback is ordered by date descending by default
* Hooks added to single feedback item page
* Some bugfixes
* Added some hooks to get prepared for Usernoise Pro

= 0.1.1 =
* Minor bugfixes

= 0.1 = 
* Initial release