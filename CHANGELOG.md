# Changelog

Notable changes and release notes of the Mark Posts WordPress plugin.

## 1.2.1
* Exclude internal post types per default
* Add [`mark_posts_excluded_post_types`](https://github.com/hofmannsven/mark-posts/wiki/Reset-Custom-Post-Types) filter

## 1.2.0
* Migrate GitHub repository to [hofmannsven/mark-posts](https://github.com/hofmannsven/mark-posts)
* Add [composer support](https://packagist.org/packages/hofmannsven/mark-posts)

## 1.1.0
* Add `mark_posts_dashboard_query` filter for custom dashboard stats
* Dashboard Widget is activated per default
* Code refactoring and minor fixes
* Added italian localization

## 1.0.9
* Bugfix for Dashboard Widget
* Added hebrew localization

## 1.0.8
* Introducing the new Dashboard Widget

## 1.0.7
* Bugs fixed:
  * Update marker count if posts get deleted
  * Update dashboard count to only count published posts/markers

## 1.0.6
* Updates:
  * Better cross browser CSS rendering
  * Better script enqueue
  * Change `load_textdomain` to `load_plugin_textdomain`

## 1.0.5
* Bugfix (Sync)

## 1.0.4
* Add `mark_posts_marker_limit` filter for custom marker user roles
* Provide custom color palettes for markers

## 1.0.3
* Code refactoring

## 1.0.2
* Security fixes:
  * Prevent direct access to files (thanks Sergej Müller for pointing it out and helping to fix)

## 1.0.1
* Bugs fixed:
  * Update marker count if markers get deleted
  * Remove duplicate quickedit dropdowns (in case of multiple custom admin columns)
  * Assign default color to marker

## 1.0.0
* First release
