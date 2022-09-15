<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @author        Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license       GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    exit;
}

/**
 * Declare default colors.
 *
 * @since 1.0.1
 */
function mark_posts_get_default_colors()
{
    return ['#96D754', '#FFFA74', '#FF7150', '#9ABADC', '#FFA74C', '#158A61'];
}

/**
 * Get marker terms.
 *
 * @since 1.0.1
 */
function mark_posts_get_marker_terms()
{
    return get_terms([
        'taxonomy'   => 'marker',
        'orderby'    => 'id',
        'hide_empty' => false,
    ]);
}

/**
 * Misc functions.
 *
 * @since     1.0.0
 * @updated   1.0.8
 */
function mark_posts_misc_functions()
{
    // mark all posts
    if (
        !isset($_GET['mark-all-posts-term-id'], $_GET['_wpnonce'])
        || !wp_verify_nonce($_GET['_wpnonce'], 'mark-posts-initial')
    ) {
        return;
    }
    $term_id = (int)$_GET['mark-all-posts-term-id'];

    // set color only for selected post types
    foreach (get_option('mark_posts_settings')['mark_posts_posttypes'] as $post_type) {
        // get all posts
        $all_posts = new WP_Query([
            'posts_per_page' => -1,
            'post_type'      => $post_type,
        ]);

        foreach ($all_posts->posts as $post) {
            // Get the term.
            $myterm = get_term($term_id, 'marker');
            // Update the meta field.
            update_post_meta($post->ID, 'mark_posts_term_id', $term_id);
            // Update taxonomy count.
            wp_set_object_terms($post->ID, $myterm->name, 'marker');
        }
    }

    // remove the URL parameters after setting the marker.
    ?>
    <script>
        (() => {
            window.history.replaceState({}, '', '<?php echo esc_url(admin_url('options-general.php?page=mark-posts')) ?>');
        })();
    </script>
    <?php
    echo mark_posts_display_settings_updated();
}

/**
 * Save form data.
 *
 * @since     1.0.0
 */
function mark_posts_validate_form()
{
    if (!isset($_POST['submit'], $_POST['_wpnonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['_wpnonce'], 'mark_posts_save_settings')) {
        return;
    }

    // get options from db
    $get_mark_posts_settings = get_option('mark_posts_settings');

    // update post type settings
    $get_mark_posts_settings['mark_posts_posttypes'] = array_map('sanitize_text_field', $_POST['markertypes'] ?? []);


    // update dashboard settings
    $get_mark_posts_settings['mark_posts_dashboard'] = array_map('sanitize_text_field', $_POST['markerdashboard'] ?? []);

    // save options
    update_option('mark_posts_settings', $get_mark_posts_settings);

    // news markers
    $markers = array_map(static function (string $marker) {
        return trim(sanitize_text_field($marker));
    }, explode(',', $_POST['markers'] ?? ''));
    $i       = count(mark_posts_get_marker_terms()) ?: 0;
    // get default colors
    $default_colors = mark_posts_get_default_colors();

    foreach ($markers as $marker) {
        $color = $default_colors[$i]; // define default color
        wp_insert_term($marker, 'marker', [
            'name'        => $marker,
            'slug'        => sanitize_title($marker),
            'description' => $color,
        ]);
        if (++$i > 5) {
            $i = 0;
        }
    }

    // update markers
    $term_ids = array_map('intval', $_POST['term_ids'] ?? []);
    $colors   = array_map('sanitize_text_field', $_POST['colors'] ?? []);
    $markers  = array_map(static function (string $marker) {
        return trim(sanitize_text_field($marker));
    }, $_POST['markernames'] ?? []);
    foreach ($markers as $index => $marker) {
        wp_update_term($term_ids[$index], 'marker', [
            'name'        => $marker,
            'slug'        => sanitize_title($marker),
            'description' => $colors[$index],
        ]);
    }

    // delete markers
    foreach (array_map('intval', $_POST['delete'] ?? []) as $term_id) {
        wp_delete_term($term_id, 'marker');
    }

    echo mark_posts_display_settings_updated();

    // Clear transient dashboard stats
    delete_transient('marker_posts_stats');
}

/**
 * Show update notice.
 *
 * @since 1.0.0
 */
function mark_posts_display_settings_updated(): string
{
    return '<div id="message" class="updated"><p>' . esc_html__('Settings saved', 'mark-posts') . '</p></div>';
}

/**
 * List of excluded post types.
 *
 * @return array
 * @since 1.2.1
 * @docs https://github.com/hofmannsven/mark-posts/wiki/Reset-Custom-Post-Types
 *
 */
function mark_posts_excluded_post_types(): array
{
    return apply_filters('mark_posts_excluded_post_types', [
        'attachment',
        'revision',
        'nav_menu_item',
        'custom_css',
        'customize_changeset',
        'oembed_cache',
        'user_request',
        'wp_block',
        'tcb_symbol',
        'td_nm_notification',
        'tve_lead_group',
        'tve_lead_shortcode',
        'tve_lead_2s_lightbox',
        'tve_lead_1c_signup',
        'tve_form_type',
        'tcb_content_template',
    ]);
}

/**
 * Get the readable name of the custom post type.
 *
 * @param string $key
 *
 * @return string
 * @since 1.2.3
 *
 */
function mark_posts_get_post_type_name(string $key): string
{
    $cpt = get_post_type_object($key);

    return $cpt instanceof \WP_Post_Type ? $cpt->labels->name : ucfirst($key);
}

/**
 * Get all available post types.
 *
 * @since 1.0.0
 */
function mark_posts_get_all_types()
{
    $active_post_types   = get_option('mark_posts_settings')['mark_posts_posttypes'] ?? [];
    $excluded_post_types = mark_posts_excluded_post_types();

    foreach (get_post_types() as $post_type) {
        // Filter excluded post types.
        if (in_array($post_type, $excluded_post_types, true)) {
            continue;
        }
        ?>
        <p>
            <label>
                <input name="markertypes[]" type="checkbox" value="<?php esc_attr_e($post_type) ?>" <?php checked(in_array($post_type, $active_post_types, true)) ?>>
                <?php esc_html_e(mark_posts_get_post_type_name($post_type)) ?>
            </label>
        </p>
        <?php
    }
}

/**
 * Get dashboard widget setup.
 *
 * @since 1.0.8
 */
function mark_posts_dashboard()
{
    $option = get_option('mark_posts_settings');
    echo '<p><input name="markerdashboard[]" type="checkbox" value="dashboard"';
    if (!empty($option['mark_posts_dashboard'])) {
        echo ' checked="checked"';
    }
    echo ' /> ' . esc_html__('Dashboard Widget', 'mark-posts') . '</p>';
}

/**
 * Display all settings.
 *
 * @since 1.0.0
 */
function mark_posts_show_settings()
{
// get default colors
    $default_colors = mark_posts_get_default_colors(); ?>
    <form method="post" action="">
        <?php

        wp_nonce_field('mark_posts_save_settings');

        // Get Marker terms from DB
        $markers_terms = mark_posts_get_marker_terms();

        if (!empty($markers_terms)) :
            ?>
            <h3 class="title"><?php esc_html_e('Markers', 'mark-posts') ?></h3>
            <table class="form-table">
                <tbody>
                <?php
                $i = 0;
                foreach ($markers_terms as $marker_term) :
                    $color = $marker_term->description;
                    if ($color === '') {
                        if (!isset($default_colors[$i++])) {
                            $i = 0; // reset pointer to 0 start over
                        }
                        $color = $default_colors[$i];
                    }
                    ?>
                    <tr valign="top">
                        <td scope="row">
                            <input type="text" name="markernames[]" value="<?php esc_html_e($marker_term->name) ?>">
                        </td>
                        <td width="130">
                            <input type="text" name="colors[]" value="<?php esc_attr_e($color) ?>" class="my-color-field" data-default-color="<?php esc_attr_e($color) ?>"/>
                        </td>
                        <td>
                            <input type="checkbox" name="delete[]" id="delete_<?php echo (int)$marker_term->term_id ?>" value="<?php echo (int)$marker_term->term_id ?>">
                            <label for="delete_<?php echo (int)$marker_term->term_id ?>">
                                <?php esc_html_e('delete', 'mark-posts') ?>
                            </label>
                            <a href="javascript:void(0);" class="mark-posts-initial" data-confirm-msg="<?php esc_attr_e('Do you really want to mark all posts with this marker? Note: This will override all your previous set markers. This will only effect the enabled post types.', 'mark-posts') ?>" data-term-id="<?php echo (int)$marker_term->term_id ?>">
                                <?php esc_html_e('Mark all posts with this marker', 'mark-posts') ?>
                            </a>
                        </td>
                        <input type="hidden" name="term_ids[]" value="<?php echo (int)$marker_term->term_id ?>"/>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php submit_button(); ?>

            <hr/>
        <?php endif; ?>

        <h3 class="title"><?php esc_html_e('Add new Markers', 'mark-posts') ?></h3>

        <p>
            <?php esc_html_e('Add new marker (please separate them by comma):', 'mark-posts') ?>
        </p>

        <textarea class="js-add-markers" name="markers" style="width:60%;height:120px;"></textarea>

        <div class="new-markers">
            <span class="js-new-markers-intro"><?php esc_html_e('Markers to add:', 'mark-posts'); ?></span>
            <span class="js-new-markers"></span>
        </div>

        <?php submit_button(); ?>

        <hr/>
        <h3 class="title"><?php _e('Enable/Disable Markers', 'mark-posts'); ?></h3>

        <p>
            <?php esc_html_e('Enable/Disable markers for specific post types:', 'mark-posts') ?>
        </p>

        <?php
        mark_posts_get_all_types();
        submit_button();
        ?>

        <hr/>
        <h3 class="title"><?php esc_html_e('Enable/Disable Dashboard Widget', 'mark-posts') ?></h3>

        <?php
        mark_posts_dashboard();
        submit_button();
        ?>

    </form>

    <?php
}

?>

<div class="wrap">

    <?php mark_posts_misc_functions() ?>

    <?php mark_posts_validate_form(); ?>

    <h2><?php esc_html_e('Mark Posts Options', 'mark-posts'); ?></h2>

    <?php mark_posts_show_settings(); ?>

    <div class="mark-posts-copy">
        <hr/>
        Mark Posts | Version: <?php echo WP_MARK_POSTS_VERSION; ?> | &copy; <?php echo date('Y'); ?>
        <a href="http://www.aliquit.de" target="_blank">Michael Schoenrock</a>,
        <a href="https://hofmannsven.com" target="_blank">Sven Hofmann</a>
    </div>

</div>
