<?php

/**
 * Mark Posts.
 *
 * @author    Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-mark-posts-admin.php`
 */
class Mark_Posts
{
    /**
     * Instance of this class.
     *
     * @since 1.0.0
     *
     * @var self
     */
    protected static $instance;

    /**
     * Unique identifier for your plugin.
     * It is used as a prefix for scripts and styles.
     *
     * @since 1.0.0
     *
     * @var string
     */
    const PLUGIN_SLUG = 'mark-posts';

    /**
     * Initialize the plugin by setting localization and loading public scripts
     * and styles.
     *
     * @since  1.0.0
     */
    private function __construct()
    {
        // Create marker taxonomy
        add_action('init', [$this, 'mark_posts_create_taxonomies']);

        // Activate plugin when new blog is added
        add_action('wpmu_new_blog', [$this, 'mark_posts_activate_new_site']);

        // Register settings
        add_action('admin_init', [$this, 'mark_posts_register_settings']);
    }

    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     *
     * @since  1.0.0
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Fired when the plugin is activated.
     *
     * @param  bool  $network_wide  True if WPMU superadmin uses
     *                              "Network Activate" action, false if
     *                              WPMU is disabled or plugin is
     *                              activated on an individual blog.
     *
     * @since 1.0.0
     */
    public static function activate(bool $network_wide)
    {
        /** @noinspection NotOptimalIfConditionsInspection */
        if (is_multisite() && $network_wide) {
            // Get all blog ids
            foreach (self::get_blog_ids() as $blog_id) {
                switch_to_blog($blog_id);
                self::single_activate();
            }

            restore_current_blog();
        } else {
            self::single_activate();
        }
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted.
     *
     * @return array Of blog ids.
     *
     * @since 1.0.0
     */
    private static function get_blog_ids()
    {
        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

        return $wpdb->get_col($sql);
    }

    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since 1.0.0
     *
     * @updated 1.1.0
     */
    private static function single_activate()
    {
        add_option(
            'mark_posts_settings',
            [
                'mark_posts_posttypes' => ['post', 'page'],
                'mark_posts_dashboard' => ['dashboard'],
            ]
        );
    }

    /**
     * Fired when the plugin is deactivated.
     *
     * @param  bool  $network_wide  True if WPMU superadmin uses
     *                              "Network Deactivate" action, false if
     *                              WPMU is disabled or plugin is
     *                              deactivated on an individual blog.
     *
     * @since 1.0.0
     */
    public static function deactivate(bool $network_wide)
    {
        if (is_multisite() && $network_wide) {
            // Get all blog ids
            foreach (self::get_blog_ids() as $blog_id) {
                switch_to_blog($blog_id);
                self::single_deactivate();
            }

            restore_current_blog();
        } else {
            self::single_deactivate();
        }
    }

    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since 1.0.0
     */
    private static function single_deactivate()
    {
        // @TODO: Define deactivation functionality here
    }

    /**
     * Return the plugin slug.
     *
     * @since 1.0.0
     */
    public function get_plugin_slug(): string
    {
        return self::PLUGIN_SLUG;
    }

    /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @param  int  $blog_id  ID of the new blog.
     *
     * @since 1.0.0
     */
    public function mark_posts_activate_new_site(int $blog_id)
    {
        if (did_action('wpmu_new_blog') !== 1) {
            return;
        }

        switch_to_blog($blog_id);
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Register settings.
     *
     * @since 1.0.0
     */
    public function mark_posts_register_settings()
    {
        $option_name = 'plugin_mark_posts_settings';

        register_setting(
            'general',
            $option_name,
            [$this, 'mark_posts_settings_validate']
        );
        add_settings_section(
            $option_name,
            __('Mark Posts Options', 'plugin_mark_posts_settings'),
            '__return_false',
            self::PLUGIN_SLUG
        );
    }

    /**
     * Validate settings.
     *
     * @since 1.0.0
     */
    public function mark_posts_settings_validate($input)
    {
        // todo: sanitize user input
        return $input;
    }

    /**
     * Create marker taxonomy.
     *
     * @since 1.0.0
     */
    public function mark_posts_create_taxonomies()
    {
        // Add new marker taxonomy
        $labels = [
            'name' => __('Marker', 'mark-posts'),
            'singular_name' => __('Marker', 'mark-posts'),
            'search_items' => __('Search Marker', 'mark-posts'),
            'all_items' => __('All Markers', 'mark-posts'),
            'parent_item' => __('Parent Marker', 'mark-posts'),
            'parent_item_colon' => __('Parent Marker:', 'mark-posts'),
            'edit_item' => __('Edit Marker', 'mark-posts'),
            'update_item' => __('Update Marker', 'mark-posts'),
            'add_new_item' => __('Add New Marker', 'mark-posts'),
            'new_item_name' => __('New Marker Name', 'mark-posts'),
            'menu_name' => __('Marker', 'mark-posts'),
        ];

        $args = [
            'hierarchical' => true,
            'labels' => $labels,
            'public' => false,
            'show_ui' => false,
            'show_admin_column' => false,
            'query_var' => true,
            'rewrite' => ['slug' => 'marker'],
            'update_count_callback' => 'marker_update_count_callback',
        ];

        /**
         * Filter: 'mark_posts_taxonomy_args' - Allow custom parameters for the marker taxonomy.
         *
         * @param  array  $args  Array with taxonomy arguments.
         *
         * @since 2.0.0
         */
        $args = apply_filters('mark_posts_taxonomy_args', $args);

        /**
         * Function for updating the marker taxonomy count.
         *
         * See the _update_post_term_count() function in WordPress or http://justintadlock.com/archives/2011/10/20/custom-user-taxonomies-in-wordpress for more info.
         *
         * @param  array  $terms  List of Term taxonomy IDs
         * @param  object  $taxonomy  Current taxonomy object of terms
         *
         * @since 1.0.7
         */
        function marker_update_count_callback($terms, $taxonomy)
        {
            global $wpdb;

            foreach ((array) $terms as $term) {
                $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term));

                do_action('edit_term_taxonomy', $term, $taxonomy);
                $wpdb->update($wpdb->term_taxonomy, compact('count'), ['term_taxonomy_id' => $term]);
                do_action('edited_term_taxonomy', $term, $taxonomy);
            }
        }

        /*
         * null - Setting explicitly to null registers the taxonomy but doesn't
         * associate it with any objects, so it won't be directly available within
         * the Admin UI. You will need to manually register it using the 'taxonomy'
         * parameter (passed through $args) when registering a custom post_type
         * (see register_post_type()), or using register_taxonomy_for_object_type().
         *
         * see http://codex.wordpress.org/Function_Reference/register_taxonomy
         */

        register_taxonomy('marker', 'null', $args);
    }
}
