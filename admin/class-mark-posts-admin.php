<?php
/**
 * Mark Posts Class.
 *
 * @author    Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    exit;
}

class Mark_Posts_Admin
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
     * Slug of the plugin screen.
     *
     * @since 1.0.0
     *
     * @var string
     */
    protected $plugin_screen_hook_suffix;

    /**
     * The plugin slug as set in the plugin instance.
     *
     * @var string
     */
    private $plugin_slug;

    /**
     * Initialize the plugin by loading admin scripts & styles and adding a settings page and menu.
     *
     * @since     1.0.0
     */
    private function __construct()
    {
        $this->plugin_slug = Mark_Posts::get_instance()->get_plugin_slug();

        // Load admin style sheet and JavaScript
        add_action('admin_enqueue_scripts', [$this, 'mark_posts_enqueue_admin_styles']);
        add_action('admin_enqueue_scripts', [$this, 'mark_posts_enqueue_admin_scripts']);

        // Add the options page and menu item
        add_action('admin_menu', [$this, 'mark_posts_add_plugin_admin_menu']);

        $get_mark_posts_setup = get_option('mark_posts_settings');

        /**
         * Add dashboard
         *
         * @since 1.0.8
         */
        if (!empty($get_mark_posts_setup['mark_posts_dashboard'])) {
            add_action('wp_dashboard_setup', [$this, 'mark_posts_dashboard_widget']);
        }

        // Add an action link pointing to the options page
        $plugin_basename = plugin_basename(plugin_dir_path(__DIR__) . $this->plugin_slug . '.php');
        add_filter('plugin_action_links_' . $plugin_basename, [$this, 'mark_posts_add_action_links']);

        // Add quick edit and bulk edit actions
        add_action('bulk_edit_custom_box', [$this, 'mark_posts_display_quickedit_box']);
        add_action('quick_edit_custom_box', [$this, 'mark_posts_display_quickedit_box']);
        // Add JavaScript for quick edit and bulk edit actions
        add_action('admin_print_scripts-edit.php', [$this, 'mark_posts_edit_scripts'], 10, 2);

        // Add metabox
        add_action('add_meta_boxes', [$this, 'mark_posts_add_meta_box']);
        // Save action for metabox
        add_action('save_post', [$this, 'mark_posts_save']);
        // Save action for quick edit
        add_action('save_post', [$this, 'mark_posts_save_quick_edit'], 10, 2);
        // Save action for bulk edit
        add_action('save_post', [$this, 'mark_posts_save_bulk_edit'], 10, 1);
        // Trash action
        add_action('trash_post', [$this, 'mark_posts_trash'], 1);
        // Delete action
        add_action('delete_post', [$this, 'mark_posts_delete'], 10);

        /**
         * Custom admin post columns (custom post types only).
         *
         * @since 1.0.0
         */
        foreach ($get_mark_posts_setup['mark_posts_posttypes'] as $post_type) {
            add_filter('manage_' . $post_type . '_posts_columns', [$this, 'mark_posts_column_head'], 10, 2);
            add_action('manage_' . $post_type . '_posts_custom_column', [$this, 'mark_posts_column_content'], 10, 2);
        }
    }

    /**
     * Return an instance of this class.
     *
     * @return self
     * @since 1.0.0
     *
     */
    public static function get_instance(): Mark_Posts_Admin
    {
        // If the single instance hasn't been set, set it now.
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function mark_posts_enqueue_admin_styles()
    {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), [], WP_MARK_POSTS_VERSION);
    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function mark_posts_enqueue_admin_scripts()
    {
        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        global $pagenow;
        if ($pagenow === 'options-general.php' || $pagenow === 'edit.php' || $pagenow === 'post.php') {
            wp_enqueue_style('wp-color-picker'); // see http://make.wordpress.org/core/2012/11/30/new-color-picker-in-wp-3-5/
            wp_enqueue_script($this->plugin_slug . '-post-list-marker', plugins_url('assets/js/markposts.js', __FILE__), ['wp-color-picker'], WP_MARK_POSTS_VERSION, true);
            wp_localize_script($this->plugin_slug . '-post-list-marker', 'mark_posts', [
                'nonce' => wp_create_nonce('mark-posts-initial'),
            ]);
        }
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since 1.0.0
     */
    public function mark_posts_add_plugin_admin_menu()
    {
        // add a settings page for this plugin to the Settings menu
        $this->plugin_screen_hook_suffix = add_options_page(
            __('Mark Posts', $this->plugin_slug),
            __('Mark Posts', $this->plugin_slug),
            'manage_options',
            $this->plugin_slug,
            [$this, 'mark_posts_display_plugin_admin_page']
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since 1.0.0
     */
    public function mark_posts_display_plugin_admin_page()
    {
        include_once 'views/admin.php';
    }

    /**
     * Register custom dashboard widget.
     *
     * @since 1.0.0
     */
    public function mark_posts_dashboard_widget()
    {
        wp_add_dashboard_widget(
            'mark_posts_info_widget',
            'Mark Posts',
            [$this, 'mark_posts_dashboard_info']
        );
        add_action('admin_enqueue_scripts', [$this, 'mark_posts_enqueue_dashboard_styles']);
        add_action('admin_head', [$this, 'mark_posts_custom_dashboard_styles']);
    }

    /**
     * Render the dashboard widget.
     *
     * @since 1.0.0
     */
    public function mark_posts_dashboard_info()
    {
        include_once 'views/dashboard.php';
    }

    /**
     * Load additional dashboard styles.
     *
     * @since 1.0.0
     */
    public function mark_posts_enqueue_dashboard_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-dashboard-styles', plugins_url('assets/css/dashboard.css', __FILE__), [], WP_MARK_POSTS_VERSION);
    }

    /**
     * Build custom dashboard styles.
     *
     * @since 1.0.0
     */
    public function mark_posts_custom_dashboard_styles()
    {
        printf('<style>%s</style>', implode(' ', array_map(static function (WP_Term $marker) {
            return sprintf(
                '.mark-posts-%s a:before{color:%s}',
                esc_attr($marker->slug),
                esc_attr($marker->description)
            );
        }, get_terms([
            'taxonomy' => 'marker',
        ]))));
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @param array $links Associative array of plugin action links
     *
     * @return array
     * @since 1.0.0
     *
     */
    public function mark_posts_add_action_links(array $links): array
    {
        // add a 'Settings' link to the front of the actions list for this plugin
        return array_merge(
            [
                'settings' => sprintf(
                    '<a href="%s">%s</a>',
                    esc_url(admin_url('options-general.php?page=' . $this->plugin_slug)),
                    esc_html__('Settings', 'mark-posts')
                ),
            ],
            $links
        );
    }

    /**
     * Adds a box to the main column on the edit screens.
     *
     * @since 1.0.0
     */
    public function mark_posts_add_meta_box()
    {
        $mark_posts_posttypes = (get_option('mark_posts_settings')['mark_posts_posttypes'] ?? false) ?: [];

        foreach ($mark_posts_posttypes as $mark_posts_posttype) {
            add_meta_box(
                'mark_posts_options',
                __('Mark Posts Options', 'mark-posts'),
                [$this, 'mark_posts_inner_meta_box'],
                $mark_posts_posttype,
                'side'
            );
        }
    }

    /**
     * Prints the box content.
     *
     * @param WP_Post $post Current WP_Post object
     *
     * @since 1.0.0
     *
     */
    public function mark_posts_inner_meta_box(WP_Post $post)
    {
        // Add a nonce field so we can check for it later.
        wp_nonce_field('mark_posts_inner_meta_box', 'mark_posts_inner_meta_box_nonce');
        ?>
        <p><?php esc_html_e('Mark this post as:', 'mark-posts') ?></p>
        <?php
        // Get available markers as select dropdown
        echo (new Mark_Posts_Marker())->mark_posts_select($post->ID);
        ?>

        <span class="mark-posts-color"></span>
        <p>
            <?php
            printf(
                /* translators: %s: plugin settings page */
                wp_kses(__('Click <a href="%s">here</a> to manage Marker categories.', 'mark-posts'), [
                    'a' => [
                        'href' => true,
                        'rel'  => true,
                        'name' => true,
                    ],
                ]),
                esc_url('options-general.php?page=mark-posts')
            )
            ?>
        </p>
        <?php
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id ID of the post e.g. '1'
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function mark_posts_save(int $post_id)
    {
        // Check if our nonce is set.
        if (!isset($_POST['mark_posts_inner_meta_box_nonce'])) {
            return;
        }

        // Verify that the nonce is valid.
        if (!wp_verify_nonce($_POST['mark_posts_inner_meta_box_nonce'], 'mark_posts_inner_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted,
        // so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        $type = sanitize_text_field($_POST['post_type']) === 'page' ? 'page' : 'post';
        if (!current_user_can('edit_' . $type, $post_id)) {
            return;
        }

        /* OK, it's safe for us to mark_posts_save the data now. */

        // Sanitize the user input.
        $mydata = (int)$_POST['mark_posts_term_id'];

        // Update the meta field.
        update_post_meta($post_id, 'mark_posts_term_id', $mydata);

        // Update taxonomy count
        wp_update_term_count_now([$mydata], 'marker');

        // Clear transient dashboard stats
        delete_transient('marker_posts_stats');
    }

    /**
     * Update taxonomy count if posts get permanently deleted.
     *
     * @param int $post_id ID of the post e.g. '1'
     *
     * @since 1.0.7
     *
     */
    public function mark_posts_delete(int $post_id)
    {
        // Retrieve post meta value from the database
        $term = get_post_meta($post_id, 'mark_posts_term_id', true);
        // if term is empty, all markers will be unset
        wp_set_object_terms($post_id, $term, 'marker');
        // Clear transient dashboard stats
        delete_transient('marker_posts_stats');
    }

    /**
     * Update dashboard stats if posts get trashed.
     *
     * @since 1.0.7
     */
    public function mark_posts_trash()
    {
        // Clear transient dashboard stats
        delete_transient('marker_posts_stats');
    }

    /**
     * Custom quick edit box.
     *
     * @param string $column_name Custom column name e.g. 'mark_posts_term_id'
     *
     * @since 1.0.0
     *
     */
    public function mark_posts_display_quickedit_box(string $column_name)
    {
        if ($column_name !== 'mark_posts_term_id') {
            return;
        }
        ?>
        <fieldset class="inline-edit-col-right mark-posts-quickedit">
            <div class="inline-edit-col">
                <div class="inline-edit-group">
                    <label class="inline-edit-status alignleft">
                        <span class="title"><?php esc_html_e('Marker', 'mark-posts'); ?></span>
                        <?php echo (new Mark_Posts_Marker())->mark_posts_select() ?>
                    </label>
                </div>
            </div>
        </fieldset>
        <?php
    }

    /**
     * Save quick edit.
     *
     * @param int $post_id ID of the post e.g. '1'
     * @param WP_Post $post Information about the post e.g. 'post_type'
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function mark_posts_save_quick_edit(int $post_id, WP_Post $post)
    {
        // Pointless if $_POST is empty (this happens on bulk edit).
        if (empty($_POST)) {
            return;
        }

        // Verify quick edit nonce.
        if (!isset($_POST['_inline_edit']) || !wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce')) {
            return;
        }

        // Check the user's capabilities.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Don't mark_posts_save for autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Don't mark_posts_save for revisions.
        if (isset($post->post_type) && $post->post_type === 'revision') {
            return;
        }

        $mark_field = 'mark_posts_term_id';

        if (!array_key_exists($mark_field, $_POST)) {
            return;
        }

        $marker = (int)$_POST[$mark_field];

        // Update the post meta field.
        update_post_meta($post_id, $mark_field, $marker);

        // Update object terms.
        $term = get_term($marker, 'marker');
        wp_set_object_terms($post_id, $term->name ?? null, 'marker');

        // Clear transient dashboard stats.
        delete_transient('marker_posts_stats');
    }

    /**
     * Save bulk edit.
     *
     * @param int $post_id ID of the post
     *
     * @return void
     * @since 2.2.4
     */
    public function mark_posts_save_bulk_edit(int $post_id)
    {
        // Verify bulk edit nonce.
        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bulk-posts')) {
            return;
        }

        // Check the user's capabilities.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Get selected marker ID.
        $marker_id = (int) !empty($_REQUEST['mark_posts_term_id']) ? $_REQUEST['mark_posts_term_id'] : 0;

        // Update the post meta field.
        update_post_meta($post_id, 'mark_posts_term_id', $marker_id);

        // Update object terms.
        $term = get_term($marker_id, 'marker');
        wp_set_object_terms($post_id, $term->name ?? null, 'marker');

        // Clear transient dashboard stats.
        delete_transient('marker_posts_stats');
    }

    /**
     * Enqueue quick edit and bulk edit script in admin footer.
     *
     * @since    1.0.0
     */
    public function mark_posts_edit_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-quick-bulk-edit', plugins_url('assets/js/admin-edit.js', __FILE__), ['jquery', 'inline-edit-post'], WP_MARK_POSTS_VERSION, true);
    }

    /**
     * Set admin column.
     *
     * @param array $columns Array with existing column names
     *
     * @return array
     * @since 1.0.0
     *
     */
    public function mark_posts_column_head(array $columns): array
    {
        $columns['mark_posts_term_id'] = esc_html__('Marker', 'mark-posts');

        return $columns;
    }

    /**
     * Show column content.
     *
     * @param string $column_name Custom column name e.g. 'mark_posts_term_id'
     * @param int $post_id ID of the post e.g. '1'
     *
     * @since 1.0.0
     *
     */
    public function mark_posts_column_content(string $column_name, int $post_id)
    {
        if ($column_name !== 'mark_posts_term_id') {
            return;
        }

        $value = get_post_meta($post_id, 'mark_posts_term_id', true);

        if (!$value) {
            return;
        }

        $term = get_term($value, 'marker');

        if (!$term) {
            return;
        }

        if (isset($term->description, $term->name)) {
            printf(
                '<div id="mark_posts_term_id-%1$d" class="mark-posts-marker" style="background:%2$s;" data-val="%3$d" data-background="%2$s">%4$s</div>',
                $post_id,
                $term->description,
                $term->term_id,
                $term->name
            );
        }
    }
}
