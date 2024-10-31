=== Rating ===
Contributors: MDevon
Donate link: http://www.starrating.net16.net/
Tags: rating, vote, voting, rate, stars, star, votes, star rating, rate post, rate page, page, post, pages, posts, admin, ajax, comment, comments, widget, widgets, shortcode
Requires at least: 2.7
Tested up to: 3.4
Stable tag: 1.2

Allow your users to rate post(s) and pages using a classic five star method.

== Description ==

Allow your users to rate post(s) and pages using a classic five star method.
Insert the shortcode: [rating] in any post or page for the rating plugin to be displayed.

A few notes:

*   The plugin requires jQuery. If jQuery is not "detected" when it is loaded, the plugin will load jQuery.
*   The JavaScript has been minified and placed in the /assets/js/ directory. You can modify the JavaScript as you see necessary.
*   The cascading stylesheet has been minified and placed in the /assets/css/ directory. You can modify the CSS as you see necessary.
*   The plugin uses an IP based cookie to disable the form to discourage users from inflating the vote.
*	The plugin will need an initial configuration to set cookie expiration and other options available.
== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin folder `rating` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin using the `Rating Settings` link in the admin `Settings` menu
1. Place `[rating]` in your post to display the Rating system
1. Edit /rating/assets/css/rating.css if you wish to customize the rating plugin.

== Frequently Asked Questions ==

= This plugin uses jQuery. Do I need to load jQuery in my site? =
Although you can load jQuery in the head section of your site, the plug in will try to detect if jQuery is loaded. If it does not detect the jQuery, it will use the default jQuery settings that WordPress is using.

= How to display the rating plugin? `[rating]`? =
The plugin requires only that you place the below code in your post(s) or page(s). You can pass the attribute star_type to the shortcode. The two current options for star_type are `star`,`abuse`,`happy`, and `thumb`. If no attribute is passed, the handler defaults to star.
`[rating star_type="abuse"]`
That's it!

= I run a website with 2000+ articles. I really don't want to go through and add this to every page.  Can I just embed this into my template? =
The simple answer is yes. Just add the following code to your template where you want it to appear: `<?php echo five_star_rating_func('star'); ?>`

= Can I have more than one star block per page? =
No, not right now. That has been a feature request that we are investigating.

== Changelog ==

= 1.2 =
* Changed code to use add_submenu_page()

= 1.1.1 =
* Bug fix: Multiple ratings on page all updating incorrectly when only one rating submitted.
* Removed minified .css file from svn and will call rating.css instead

= 1.1 =
* Language modifications

= 1.0 =
* Updated plugin to use AJAX.
* Added a functionality to allow addition of the plugin into post/page through addition of `[rating]`