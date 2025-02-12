=== Mark Posts ===
Contributors: hofmannsven, flymke
Tags: highlight, color, status, tag, featured
Requires at least: 4.1
Tested up to: 6.7
Requires PHP: 7.0
Stable tag: 2.2.6
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

= Live Demo =

Try out the features of Mark Posts on the [WordPress playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/hofmannsven/mark-posts/master/.wordpress-org/blueprint.json).

== Installation ==

= Requirements =
Using the latest version of WordPress and PHP is highly recommended.

* WordPress 4.1 or newer
* PHP 7.4 or newer (tested up to PHP 8.2)

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

Active development of this plugin is handled on GitHub. Always feel free to [raise an issue](https://github.com/hofmannsven/mark-posts/issues).

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

Visit [Mark Posts on GitHub](https://github.com/hofmannsven/mark-posts).

== Screenshots ==

1. Shows a screenshot of marked posts in the posts overview.
2. Shows a screenshot of the options box while editing a post.
3. Shows a screenshot of the quick edit box in the posts overview.
4. Shows a screenshot of the Mark Posts settings screen.
5. Shows a screenshot of the Mark Posts dashboard widget.

== Changelog ==

= 2.2.6 =
* Fixes a bug where the bulk edit nonce is not set
  Thanks to René Eger for finding and reporting the issue

= 2.2.5 =
* Adds additional user capability checks (quick edit and bulk edit)
* Adds Laravel Pint code style fixer as a developer dependency

= 2.2.4 =
* Adds support for the [WordPress playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/hofmannsven/mark-posts/master/.wordpress-org/blueprint.json)
* Hides new internal post types by default
* Fixes broken access control vulnerability
  Thanks @truonghuuphuc for discovering and responsibly disclosing this vulnerability

= 2.2.3 =
* Fixes the assignment of default colors when creating multiple new markers

= 2.2.2 =
* Hides internal (plugin) post types by default

= 2.2.1 =
* Refactors stray PHP short tags
* Prefixes generic function names

= 2.2.0 =
* Fixes a bug with PHP 8
* Fixes wicked file permissions
* Low-level refactoring by @alpipego
* Sets the minimum required WordPress version to WordPress 4.1
  Further reading: [Dropping security updates for WordPress versions 3.7 through 4.0](https://wordpress.org/news/2022/09/dropping-security-updates-for-wordpress-versions-3-7-through-4-0/)

= 2.1.0 =
* Adds support for PHP 8

= 2.0.1 =
* Fixes a possible XSS vulnerability
  Thanks @fuzzyap1 for discovering and responsibly disclosing this vulnerability

= 2.0.0 =
* Breaking change: Markers are no longer public by default
* Adds [`mark_posts_taxonomy_args`](https://github.com/hofmannsven/mark-posts/wiki/Custom-Marker-Taxonomy-Arguments) filter

= Earlier versions =
Check out our [full changelog](https://github.com/hofmannsven/mark-posts/blob/master/CHANGELOG.md) for previous releases
