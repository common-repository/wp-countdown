=== Plugin Name ===
Contributors: NATOR2010
Donate link: http://www.dominik-laubach.de/2008/10/31/wp-plugin-countdown/
Tags: countdown, counter, ajax
Tested up to: 2.6.5
Stable tag: 1.0

This plugin allows you to add an ajax-updated countdown to your blog.

== Description ==

With this pluging you can add a countdown to your blog. Just specify an end-date in the settings (backend) and put one single line of php-code somewhere you want this countdown to be displayed. This plugin uses the ajax-technology for updating the countdown every second. The moment you change the specified date in the settings, the plugin recognizes this change and updates itself according to the new date. 

== Installation ==

1. Create the directory `/wp-content/plugins/wp-countdown` on your webserver
2. Upload `wp-countdown.php` to the `/wp-content/plugins/wp-countdown/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `<?php wp_getCountdown(); ?>` in your templates
5. Specify the end-date of your countdown in the options

== Screenshots ==

1. Backend-View.
2. Frontend-View.