# Changelog

Notable changes and release notes of the Mark Posts WordPress plugin.

## 2.2.6
* Fixes a bug where the bulk edit nonce is not set
  Thanks to René Eger for finding and reporting the issue

## 2.2.5
* Adds additional user capability checks (quick edit and bulk edit)
* Adds Laravel Pint code style fixer as a developer dependency

## 2.2.4
* Adds support for the [WordPress playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/hofmannsven/mark-posts/master/.wordpress-org/blueprint.json)
* Hides new internal post types by default
* Fixes broken access control vulnerability
  Thanks @truonghuuphuc for discovering and responsibly disclosing this vulnerability

## 2.2.3
* Fixes the assignment of default colors when creating multiple new markers

## 2.2.2
* Hides internal (plugin) post types by default
  (ACF, Block Visibility, Contact Form 7, iThemes Security)

## 2.2.1
* Refactors stray PHP short tags
* Prefixes generic function names

## 2.2.0
* Fixes a bug with PHP 8
* Fixes wicked file permissions
* Low-level refactoring by @alpipego
* Sets the minimum required WordPress version to WordPress 4.1
  Further reading: [Dropping security updates for WordPress versions 3.7 through 4.0](https://wordpress.org/news/2022/09/dropping-security-updates-for-wordpress-versions-3-7-through-4-0/)

## 2.1.0
* Adds support for PHP 8

## 2.0.1
* Fixes a possible XSS vulnerability
  Thanks @fuzzyap1 for discovering and responsibly disclosing this vulnerability

## 2.0.0
* Breaking change: Markers are no longer public by default
* Adds [`mark_posts_taxonomy_args`](https://github.com/hofmannsven/mark-posts/wiki/Custom-Marker-Taxonomy-Arguments) filter

## 1.2.4
* Fixes a bug with WordPress 5.5.1

## 1.2.3
* Excludes specific internal plugin post types per default

## 1.2.2
* Sets the minimum required PHP version to PHP 7.0

## 1.2.1
* Excludes internal post types per default
* Adds [`mark_posts_excluded_post_types`](https://github.com/hofmannsven/mark-posts/wiki/Reset-Custom-Post-Types) filter

## 1.2.0
* Migrates the GitHub repository to [hofmannsven/mark-posts](https://github.com/hofmannsven/mark-posts)
* Adds [Composer support](https://packagist.org/packages/hofmannsven/mark-posts)

## 1.1.0
* Adds `mark_posts_dashboard_query` filter for custom dashboard stats
* Dashboard widget is activated per default
* Code refactoring and minor fixes
* Adds italian localization

## 1.0.9
* Bugfix for dashboard widget
* Adds hebrew localization

## 1.0.8
* Introduces the new dashboard widget

## 1.0.7
* Bugs fixed:
  * Updates marker count if posts get deleted
  * Updates dashboard count to only count published posts/markers

## 1.0.6
* Updates:
  * Better cross browser CSS rendering
  * Better script enqueue
  * Changes `load_textdomain` to `load_plugin_textdomain`

## 1.0.5
* Bugfix (Sync)

## 1.0.4
* Adds `mark_posts_marker_limit` filter for custom marker user roles
* Provides custom color palettes for markers

## 1.0.3
* Code refactoring

## 1.0.2
* Security fixes:
  * Prevents direct access to files (thanks Sergej Müller for pointing it out and helping to fix)

## 1.0.1
* Bugs fixed:
  * Updates marker count if markers get deleted
  * Removes duplicate quickedit dropdowns (in case of multiple custom admin columns)
  * Assigns default color to marker

## 1.0.0
* First release
