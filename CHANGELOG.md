# Changelog

Notable changes and release notes of the Mark Posts WordPress plugin.

## 2.0.0
* Breaking change: Markers are no longer public by default.
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
* Migrates GitHub repository to [hofmannsven/mark-posts](https://github.com/hofmannsven/mark-posts)
* Adds [composer support](https://packagist.org/packages/hofmannsven/mark-posts)

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
