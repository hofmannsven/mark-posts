=== Mark Posts ===
Contributors: flymke, hofmannsven
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=QZLNTW4AA4JS2
Tags: mark, mark posts, highlight, highlight posts, status, post status, overview, post overview, featured, custom posts, featured posts, post, posts
Requires at least: 3.7
Tested up to: 5.3
Requires PHP: 7.2
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Mark and highlight posts, pages and posts of custom post types within the posts overview.

== Description ==

Mark Posts plugin provides an easy way to mark and highlight posts, pages and posts of custom post types within the posts overview.

= Features =

* Set up marker categories and colors
* Assign marker categories to posts/pages/cpt
* View them within the posts/pages/cpt overview lists
* Quick edit, bulk edit and edit all markers
* Dashboard widget with marker overview
* Optional custom setup via filters (See [FAQ](https://wordpress.org/plugins/mark-posts/faq/))

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Mark posts'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `mark-posts.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Unzip the download package
2. Upload `mark-posts` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Support ==

If you find an issue, please [raise an issue](https://github.com/hofmannsven/mark-posts/issues/new/choose) on GitHub.

== Frequently Asked Questions ==

= Can I set specific user roles for specific markers? =

Check the [Custom Marker Limits](https://github.com/hofmannsven/mark-posts/wiki/Custom-Marker-Limits) wiki page for further information.

= Can I set custom parameters for the posts displayed on the dashboard? =

Check the [Custom Dashboard Queries](https://github.com/hofmannsven/mark-posts/wiki/Custom-Dashboard-Queries) wiki page for further information.

= Can I export/import markers? =

Check the [Export & Import](https://github.com/hofmannsven/mark-posts/wiki/Export-&-Import) wiki page for further information.

= I'm having issues getting the plugin to work what should I do? =

See [Mark Posts on Github](https://github.com/hofmannsven/mark-posts) for detailed rundown of common issues.

= Where can I get more information and support for this plugin? =

Visit [Mark Posts on Github](https://github.com/flymke/mark-posts)


== Screenshots ==

1. Shows a screenshot of marked posts in the posts overview
2. Shows a screenshot of the options box while editing a post
3. Shows a screenshot of the quick edit box in the posts overview
4. Shows a screenshot of the Mark Posts settings screen
5. Shows a screenshot of the Mark Posts dashboard widget

== Changelog ==

= 1.2.0 =
* Migrate GitHub repository to [hofmannsven/mark-posts](https://github.com/hofmannsven/mark-posts)
* Add Composer support

= 1.1.0 =
* Add `mark_posts_dashboard_query` filter for custom dashboard stats
* Dashboard Widget is activated per default
* Code refactoring and minor fixes
* Added italian localization

= 1.0.9 =
* Bugfix for Dashboard Widget
* Added hebrew localization

= 1.0.8 =
* Introducing the new Dashboard Widget

= 1.0.7 =
* Bugs fixed:
	* Update marker count if posts get deleted
	* Update dashboard count to only count published posts/markers

= 1.0.6 =
* Updates:
	* Better cross browser CSS rendering
	* Better script enqueue
	* Change `load_textdomain` to `load_plugin_textdomain`

= 1.0.5 =
* Bugfix (Sync)

= 1.0.4 =
* Add `mark_posts_marker_limit` filter for custom marker user roles
* Provide custom color palettes for markers

= 1.0.3 =
* Code refactoring

= 1.0.2 =
* Security fixes:
	* Prevent direct access to files (thanks Sergej Müller for pointing it out and helping to fix)

= 1.0.1 =
* Bugs fixed:
	* Update marker count if markers get deleted
	* Remove duplicate quickedit dropdowns (in case of multiple custom admin columns)
	* Assign default color to marker

= 1.0.0 =
* First release

== Upgrade Notice ==

No upgrade notices in here yet.
