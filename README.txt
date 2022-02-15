=== Mark Posts ===
Contributors: flymke, hofmannsven
Tags: mark posts, highlight, highlight posts, status, post status, overview, post overview, featured, custom posts, featured posts, post, posts
Requires at least: 3.7
Tested up to: 5.9
Requires PHP: 7.0
Stable tag: 2.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.txt

Mark and highlight posts, pages and posts of custom post types within the posts overview.

== Description ==

Mark Posts plugin provides an easy way to mark and highlight posts, pages and posts of custom post types within the WordPress admin posts overview.

= Features =

* Set custom marker categories and colors
* Assign marker categories to posts/pages or any other post type
* View the highlighted posts within the posts overview
* Quick edit, bulk edit and/or edit all markers at once
* Dashboard widget with marker status count
* Optional custom setup via filters (check our [wiki](https://github.com/hofmannsven/mark-posts/wiki) for instructions)

== Installation ==

= Requirements =
Using the latest version of WordPress and PHP is highly recommended.

* WordPress 3.7 or newer
* PHP 7.0 or newer (also tested with PHP 7.4)

= Using WP-CLI =
1. Install and activate: `wp plugin install mark-posts --activate`

= Using Composer =
1. Install: `composer require hofmannsven/mark-posts`
2. Activate the plugin on the plugin dashboard

= Using WordPress =
1. Navigate to the plugins dashboard and select _Add New_
2. Search for _Mark Posts_
3. Click _Install Now_
4. Activate the plugin on the plugin dashboard

= Using SFTP =
1. Unzip the download package
2. Upload the `mark-posts` folder to your plugins directory
3. Activate the plugin on the plugin dashboard

== Support ==

Always feel free to [raise an issue](https://github.com/hofmannsven/mark-posts/issues) on GitHub.

== Frequently Asked Questions ==

= How can I display the marker taxonomy terms on my website? =

Check the [Custom Marker Taxonomy Arguments](https://github.com/hofmannsven/mark-posts/wiki/Custom-Marker-Taxonomy-Arguments) wiki page for further information.

= Can I set specific user roles for specific markers? =

Check the [Custom Marker Limits](https://github.com/hofmannsven/mark-posts/wiki/Custom-Marker-Limits) wiki page for further information.

= Can I set custom parameters for the posts displayed on the dashboard? =

Check the [Custom Dashboard Queries](https://github.com/hofmannsven/mark-posts/wiki/Custom-Dashboard-Queries) wiki page for further information.

= Can I export/import markers? =

Check the [Export & Import](https://github.com/hofmannsven/mark-posts/wiki/Export-&-Import) wiki page for further information.

= Can I remove my custom post type from the plugin options? =

Check the [Reset Custom Post Types](https://github.com/hofmannsven/mark-posts/wiki/Reset-Custom-Post-Types) wiki page for further information.

= I'm having issues getting the plugin to work what should I do? =

Always feel free to [raise an issue](https://github.com/hofmannsven/mark-posts/issues) on GitHub.

= Where can I get more information and support for this plugin? =

Visit [Mark Posts on Github](https://github.com/hofmannsven/mark-posts).

== Screenshots ==

1. Shows a screenshot of marked posts in the posts overview.
2. Shows a screenshot of the options box while editing a post.
3. Shows a screenshot of the quick edit box in the posts overview.
4. Shows a screenshot of the Mark Posts settings screen.
5. Shows a screenshot of the Mark Posts dashboard widget.

== Changelog ==

= 2.0.1 =
* Fixes a possible XSS vulnerability.
  Thanks @fuzzyap1 for discovering and responsibly disclosing this vulnerability.

= 2.0.0 =
* Breaking change: Markers are no longer public by default.
* Adds [`mark_posts_taxonomy_args`](https://github.com/hofmannsven/mark-posts/wiki/Custom-Marker-Taxonomy-Arguments) filter

= 1.2.4 =
* Fixes a bug with WordPress 5.5.1

= 1.2.3 =
* Excludes specific internal plugin post types per default

= 1.2.2 =
* Sets the minimum required PHP version to PHP 7.0

= 1.2.1 =
* Excludes internal post types per default
* Adds [`mark_posts_excluded_post_types`](https://github.com/hofmannsven/mark-posts/wiki/Reset-Custom-Post-Types) filter

= 1.2.0 =
* Migrates GitHub repository to [hofmannsven/mark-posts](https://github.com/hofmannsven/mark-posts)
* Adds Composer support

Check our [changelog](https://github.com/hofmannsven/mark-posts/blob/master/CHANGELOG.md) for previous releases.
