=== Discussion Board ===
Contributors: Catapult_Themes
Donate Link: https://discussionboard.pro/
Tags: forum, discussion board, message board, bulletin board, question and answer, restrict content, topics, threads, community, messaging, training, groups, friends, support, ecommerce, customer service, conversation, customer support, discussion group, multisite, discussion, message, forums
Requires at least: 4.3
Tested up to: 4.7.1
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Add a simple discussion board or more advanced forums to your site - users can post new topics and reply to existing ones.

== Description ==

Discussion Board is an easy way to add a discussion board, message board or forum to your WordPress site. The plugin creates a new post type, allows you to add a front-end log-in and registration form and decide who has access to your Discussion Board. It's ideal for people running:

* Community forums
* School or other education sites
* Training websites
* Support forums
* Niche sites
* Discussion groups
* Question and answer sites
* Technical forums
* Ecommerce sites
* Bulletin boards
* Job boards

Users can post their own topics via the front-end form and reply to topics posted by others. Each topic features on its own page and you can list all topics via the archive template or the included shortcode.

As the owner of the site, you can choose whether new topics should be published immediately or held as draft in order for you to moderate them. You can:

* Use standard WordPress settings to determine moderation levels for comments
* Specify roles for users who are permitted to post content
* Specify different roles for users permitted to view content
* Allow anyone to view the content but only permit specific user roles to post content

> <strong>Discussion Board Pro</strong><br><br>
> Discussion Board has a pro version which gives you more features, including:<br>
> * Multiple Discussion Boards for different subjects<br>
> * Categories and tags<br>
> * Image uploads<br>
> * User profiles<br>
> * Topic following (subscribe to topics)<br>
> * WYSIWYG editor<br><br>
> Check out the [Pro demo here](https://discussionboard.pro/).

= Discussion Board Demo =

There's [a Discussion Board demo here](http://discussionboard.catapultthemes.com/ "Discussion Board Demo"). Feel free to register and post some sample content.

= Discussion Board Features =

* Automatic setup: on activation, Discussion Board will create the pages and insert the shortcodes necessary for the plugin to work
* Front-end log-in and registration
* Restrict access by user role
* Set different access levels for viewing and creating topics
* Register new users as a specific role
* Require users to activate their registration by clicking link in email - drastically reduces spam registrations
* Anti-spam fields in registration form
* Hide wp-login.php page if required
* Prevent user re-posting within set time to reduce any spam posts
* Simple styles to match any theme
* Enqueue / dequeue plugin styles easily
* Include / exclude icons
* Optional single and archive templates with hookable opening and closing wrapper tags - style the templates to match your theme
* Shortcodes for topic archives, recent topics and log-in form
* Compatible with Simple Comment Editing to allow users to make edits to their comments

= Translations =

* Français
* Русский

== Translations ==

* Français
* Русский

== Installation ==

= From your dashboard =

1. Go to Plugins > Add New
1. Search for 'Discussion Board'
1. Activate Discussion Board form the Plugins page
1. Go to Discussion Board > Settings to update any settings

== Frequently Asked Questions ==

= I've installed Discussion Board. Now what do I do? =

Discussion Board will automatically install three pages for you:

* A page with a log-in and registration form
* A page with a form for users to post new topics
* A page listing existing topics

There are further, detailed instructions for the free and pro versions on [the website](https://discussionboard.pro/documentation/ "Discussion Board").

= Where can I see a demo? =

There's [a demo here](http://discussionboard.catapultthemes.com/ "Discussion Board Demo") and [a pro demo here](https://discussionboard.pro/  "Discussion Board Pro"). Feel free to register and post some content so that you can see how it works from the user's point of view.

= Some users are reporting that they don't receive an activation email =

In the first instance, ask them to check their spam/junk folders. If you've tested and you're certain emails aren't getting through, use the Postman SMTP plugin or similar to assist the delivery of email from your site. It's a more reliable way of ensuring emails are sent and received.

= Do I need to enable registration on my site? =

No.

= I see an error message about invalid post type. What's that? =

In your dashboard, just go to Settings > Permalinks. Then everything should be fine.

= Help, I can't seem to log out. Why? =

We've included a helpful log-in / log-out shortcode to display a log-in / log-out link. Use a plugin like Shortcode Widget and add the [discussion_board_log_in_out] shortcode to your sidebar or other widget area of your choice. The plugin also automatically displays a log-out link under the new topic form.

= My shortcodes display as shortcodes, not as the content they're supposed to render. Why? =

Be careful if you are copying and pasting the shortcodes - you might need to delete `<code>` tags. Click on the 'Text' tab in your content editor to see if these tags are there.

== Screenshots ==

1. Discussion Board table view
2. Multiple Boards (Pro Version)
3. Single View (Twenty Sixteen theme)
4. Discussion Board options

== Changelog ==

= 2.1.1 - January 24, 2016 =

* Added: Simple Comment Editing support
* Added: tracking

= 2.1.0 - January 16, 2016 =

* Added: Customizer support
* Added: color settings
* Added: pass $class to CT_DB_Front_End::display_all_topics
* Updated: code refactoring
* Updated: use DB_PLUGIN_DIR in template uploader

= 2.0.0 - January 6, 2016 =

* Added: automatic installation of shortcodes and pages
* Added: theme detection and automatic formatting for some popular themes
* Fixed: enable_notification_opt_out
* Fixed: correctly hides log-in form when specified
* Updated: changed directory structure
* Updated: moved settings to Topics admin page
* Updated: renamed Topics admin page to Discussion Board
* Updated: renamed Options tab to General

= 1.7.4 - January 3, 2016 =

* Fixed: version

= 1.7.3 - January 3, 2016 =

* Fixed: incorrect text domain for translations

= 1.7.2 - December 28, 2016 =

* Fixed: default settings not loading correctly

= 1.7.1 - December 23, 2016 =

* Added: new_topic_button shortcode linking to New Topic page
* Added: $atts parameter to ctdb_discussion_topics_shortcode_args
* Added: global $ctdb_user_can_view variable
* Added: number parameter for discussion_topics shortcode
* Added: ctdb_new_topic_form_validation filter
* Fixed: redirect page after log-in not working
* Updated: globalized $CT_DB_Public, $CT_DB_Front_End variables
* Updated: better topic form validation - doesn't wipe all content when a field is missing

= 1.7.0 - December 9, 2016 =

* Added: CT_DB_Skins class to handle layout styles
* Added: CT_DB_Admin_Upgrades class for upgrades
* Added: classic forum layout style
* Added: conditional tags in the_content filters
* Added: 'voices' count
* Added: selectable meta data fields to display on single topic pages
* Updated: topic information bar now described as topic meta data
* Updated: Info bar layout re-termed as 'Single topic layout'
* Updated: improved single topic meta data layout
* Updated: icons no longer appear against poster name and topic date

= 1.6.1 - December 5, 2016 =

* Added: ctdb_discussion_topics_shortcode_orderby filter
* Added: table output now filterable

= 1.6.0 - December 1, 2016 =

* Added: ctdb_discussion_topics_shortcode_args filter
* Added: query parameters to discussion_topics shortcode
* Updated: use strip_tags in notification email

= 1.5.1 =

* Added: ctdb_info_meta_wrap_after_replies filter on table layout
* Fixed: pagination on discussion_topics shortcode on static homepage
* Updated: admin notices

= 1.5.0 =

* Added: opt out feature - allows topic author to choose not to select notifications
* Added: optional layouts for single.php
* Added: avatar to shortcode table layout
* Updated: include comment content in notification email
* Updated: default layout is now table
* Updated: disabled email_notification to avoid duplicate notifications

= 1.4.0 =

* Added: table layout

= 1.3.4 =

* Fixed: parse errors on failed log-in

= 1.3.3 =

* Fixed: missing filter to hide comments

= 1.3.2 =

* Fixed: email notifications for topics requiring moderation
* Updated: registration field rendering method

= 1.3.1 =

* Added: action hook ctdb_start_new_topic_form
* Added: extra shortcodes for log-in and registration forms
* Added: shortcodes for logged in and non-logged in users
* Added: filter author name in information bar
* Added: admin notification class
* Updated: additional front end styles
* Updated: retain field values in unsuccessful registration form
* Updated: validation message

= 1.3.0 =

* Added: filter for admin tabs
* Added: 0 option for reposting delay
* Added: AJAX validation on registration form
* Fixed: pagination issue with all topics shortcode on Twenty Sixteen theme
* Updated: refactored code into smaller classes
* Updated: notify admin of moderated comment
* Updated: strip_shortcodes in posted content

= 1.2.0 =

* Added: flush rewrite rules on activation
* Fixed: missing comment form fields

= 1.1.1 =

* Updated: tested to tag

= 1.1.0 =

* Added: hide log-in form option
* Added: multiple filters
* Added: new action hooks
* Changed: email address must be specified for notifications to be sent
* Fixed: replace comments template with empty file for excluded users
* Fixed: correct path to plugin templates

= 1.0.9 =

* Fixed: missing assets in admin

= 1.0.8 =

* Fixed: missing assets in admin

= 1.0.7 =

* Fixed: closed `<a>` tag after Lost Password link

= 1.0.6 =

* Added: Define email address to send notifications to

= 1.0.5 =

* Fixed: Reset query in shortcode.

= 1.0.4 =

* Added: French translation (thanks to Oazar)
* Added: Russian translation

= 1.0.3 =

* Changed: updated plugin for better translation support

= 1.0.2 =

* Change: defined constant for plugin directory
* Fix: incorrect post type in shortcode queries

= 1.0.1 =

* Fix: incorrect post type in notification email to admin

= 1.0.0 =

* First commit

== Upgrade Notice ==

= 2.0.0 =
2.0.0 is a significant update, introducing the automatic set-up of pages and installation of shortcodes when activating the plugin for the first time. If upgrading from a previous version to 2.0.0, you will notice that the plugin Settings and Topics menu items are all now contained within a separate Discussion Board menu item.