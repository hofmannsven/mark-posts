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
 * @since     1.0.1
 */
function mark_posts_get_default_colors()
{
    $default_colors = ['#96D754', '#FFFA74', '#FF7150', '#9ABADC', '#FFA74C', '#158A61'];

    return $default_colors;
}

/**
 * Get marker terms.
 *
 * @since     1.0.1
 */
function mark_posts_get_marker_terms()
{
    $marker_terms = get_terms('marker', 'orderby=id&hide_empty=0');

    return $marker_terms;
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
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['mark-all-posts-term-id'])) {
        $term_id = $_GET['mark-all-posts-term-id']; /* TODO:: SECURITY */

        // set color only for selected post types
        $get_mark_posts_settings = get_option('mark_posts_settings');
        foreach ($get_mark_posts_settings['mark_posts_posttypes'] as $post_type) {
            $args = [
                'posts_per_page' => -1,
                'post_type'      => $post_type, ];

            // get all posts
            $all_posts = get_posts($args);

            foreach ($all_posts as $post) {
                // Sanitize the user input.
                $mydata = sanitize_text_field($term_id);
                $myterm = get_term($term_id, 'marker');

                // Update the meta field.
                update_post_meta($post->ID, 'mark_posts_term_id', $mydata);
                // Update taxonomy count
                wp_set_object_terms($post->ID, $myterm->name, 'marker');
            }
        }

        echo mark_posts_display_settings_updated();
    }
}

/**
 * Save form data.
 *
 * @since     1.0.0
 */
function mark_posts_validate_form()
{

    // get default colors
    $default_colors = mark_posts_get_default_colors();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

        // get marker posttypes
        if (isset($_POST['markertypes'])) {
            $markertypes = $_POST['markertypes'];
        } else {
            $markertypes = [];
        }

        // update post type settings
        $get_mark_posts_settings = get_option('mark_posts_settings');
        $set_mark_posts_settings = $markertypes;
        $get_mark_posts_settings['mark_posts_posttypes'] = $set_mark_posts_settings;

        update_option('mark_posts_settings', $get_mark_posts_settings);

        // get marker dashboard
        if (isset($_POST['markerdashboard'])) {
            $markerdashboard = $_POST['markerdashboard'];
        } else {
            $markerdashboard = [];
        }

        // update dashboard settings
        $get_mark_posts_settings = get_option('mark_posts_settings');
        $set_mark_posts_settings = $markerdashboard;
        $get_mark_posts_settings['mark_posts_dashboard'] = $set_mark_posts_settings;

        update_option('mark_posts_settings', $get_mark_posts_settings);

        // news markers
        $markers = explode(',', $_POST['markers']);
        $count_markers = count(mark_posts_get_marker_terms());
        if ($count_markers) {
            $i = $count_markers;
        } // define $i for default color
        else {
            $i = 0;
        }
        foreach ($markers as $marker) {
            $marker = trim(sanitize_text_field($marker));
            $color = $default_colors[$i]; // define default color
            wp_insert_term($marker, 'marker', [
                'name'        => $marker,
                'slug'        => sanitize_title($marker),
                'description' => $color,
            ]);
            if ($i > 5) {
                $i = 0;
            } // reset $i to color count so the next color is first color again etc.
            else {
                $i++;
            }
        }

        // update markers
        $i = 0;
        if (isset($_POST['markernames'])) {
            foreach ($_POST['markernames'] as $markername) {
                $marker = trim(sanitize_text_field($markername));
                wp_update_term($_POST['term_ids'][$i], 'marker', [
                    'name'        => $marker,
                    'slug'        => sanitize_title($marker),
                    'description' => $_POST['colors'][$i],
                ]);
                $i++;
            }
        }

        // delete markers
        if (isset($_POST['delete'])) {
            foreach ($_POST['delete'] as $term_id) {
                wp_delete_term($term_id, 'marker');
            }
        }

        echo mark_posts_display_settings_updated();

        // Clear transient dashboard stats
        delete_transient('marker_posts_stats');
    }
}

/**
 * Show update notice.
 *
 * @since     1.0.0
 */
function mark_posts_display_settings_updated()
{
    return '<div id="message" class="updated"><p>'.__('Settings saved', 'mark-posts').'</p></div>';
}

/**
 * List of excluded post types.
 *
 * @since     1.2.1
 * @docs      https://github.com/hofmannsven/mark-posts/wiki/Reset-Custom-Post-Types
 *
 * @return array
 */
function mark_posts_excluded_post_types()
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
 * @since 1.2.3
 *
 * @param string $key
 *
 * @return string
 */
function mark_posts_get_post_type_name(string $key)
{
    $cpt = get_post_type_object($key);

    return is_object($cpt) ? $cpt->labels->name : ucfirst($key);
}

/**
 * Get all available post types.
 *
 * @since     1.0.0
 */
function mark_posts_get_all_types()
{
    $all_post_types = get_post_types();
    $option = get_option('mark_posts_settings');

    foreach ($all_post_types as $one_post_type) {
        // Filter excluded post types.
        if (!in_array($one_post_type, mark_posts_excluded_post_types())) {
            echo '<p><input name="markertypes[]" type="checkbox" value="'.esc_attr($one_post_type).'"';
            if (isset($option['mark_posts_posttypes'])) {
                if (in_array($one_post_type, $option['mark_posts_posttypes'])) {
                    echo ' checked="checked"';
                }
            }
            echo ' /> '.esc_html(mark_posts_get_post_type_name($one_post_type)).'</p>';
        }
    }
}

/**
 * Get dashboard widget setup.
 *
 * @since     1.0.8
 */
function mark_posts_dashboard()
{
    $option = get_option('mark_posts_settings');
    echo '<p><input name="markerdashboard[]" type="checkbox" value="dashboard"';
    if (!empty($option['mark_posts_dashboard'])) {
        echo ' checked="checked"';
    }
    echo ' /> '.__('Dashboard Widget', 'mark-posts').'</p>';
}

/**
 * Display all settings.
 *
 * @since     1.0.0
 */
function mark_posts_show_settings()
{

    // get default colors
    $default_colors = mark_posts_get_default_colors(); ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<?php

        // Get Marker terms from DB
        $markers_terms = mark_posts_get_marker_terms();
    $markers_registered = '';
    foreach ($markers_terms as $marker_term) {
        $markers_registered .= $marker_term->name;
        $markers_registered .= ', ';
    }
    $markers_registered = rtrim($markers_registered, ', '); // cut trailing comma and space

    if (!empty($markers_terms)) {
        echo '<h3 class="title">'.__('Markers', 'mark-posts').'</h3>';

        echo '<table class="form-table"><tbody>';

        $i = 0;
        foreach ($markers_terms as $marker_term) {
            if ($marker_term->description != '') {
                $color = $marker_term->description;
            } else {
                if (isset($default_colors[$i])) {
                    $color = $default_colors[$i];
                } else {
                    $i = 0; // reset pointer to 0 start over
                    $color = $default_colors[$i];
                }
            }

            echo '<tr valign="top"><th scope="row"><input type="text" name="markernames[]" value="'.esc_html($marker_term->name).'"></th>';
            echo '<td width="130"><input type="text" name="colors[]" value="'.esc_attr($color).'" class="my-color-field" data-default-color="'.esc_attr($color).'"/></td>';
            echo '<td><input type="checkbox" name="delete[]" id="delete_'.(int)$marker_term->term_id.'" value="'.(int)$marker_term->term_id.'"> <label for="delete_'.(int)$marker_term->term_id.'">'.__('delete', 'mark-posts').'?</label>';
            echo '<a href="javascript:void(0);" class="mark-posts-initial" data-confirm-msg="'.__('Do you really want to mark all posts with this marker? Note: This will override all your previous set markers. This will only effect the enabled post types.', 'mark-posts').'" data-term-id="'.(int)$marker_term->term_id.'">'.__('Mark all posts with this marker', 'mark-posts').'</a></td>';
            echo '<input type="hidden" name="term_ids[]" value="'.(int)$marker_term->term_id.'"/>';
            $i++;
        }

        echo '</tbody></table>';

        submit_button();

        echo '<hr />';
    } ?>


		<h3 class="title"><?php _e('Add new Markers', 'mark-posts'); ?></h3>

		<p>
			<?php _e('Add new marker (please separate them by comma):', 'mark-posts'); ?>
		</p>

		<textarea class="js-add-markers" name="markers" style="width:60%;height:120px;"></textarea>

		<div class="new-markers">
			<span class="js-new-markers-intro"><?php _e('Markers to add:', 'mark-posts'); ?></span>
			<span class="js-new-markers"></span>
		</div>

		<?php submit_button(); ?>

		<hr />
		<h3 class="title"><?php _e('Enable/Disable Markers', 'mark-posts'); ?></h3>

		<p>
			<?php _e('Enable/Disable markers for specific post types:', 'mark-posts'); ?>
		</p>

		<?php
        mark_posts_get_all_types();
    submit_button(); ?>

		<hr />
		<h3 class="title"><?php _e('Enable/Disable Dashboard Widget', 'mark-posts'); ?></h3>

		<?php
        mark_posts_dashboard();
    submit_button(); ?>

	</form>

<?php
} ?>

<div class="wrap">

	<?php mark_posts_misc_functions() ?>

	<?php mark_posts_validate_form(); ?>

	<h2><?php _e('Mark Posts Options', 'mark-posts'); ?></h2>

	<?php mark_posts_show_settings(); ?>

	<div class="mark-posts-copy">
		<hr />
		Mark Posts | Version: <?php echo WP_MARK_POSTS_VERSION; ?> | &copy; <?php echo date('Y'); ?>
		<a href="http://www.aliquit.de" target="_blank">Michael Schoenrock</a>,
		<a href="https://hofmannsven.com" target="_blank">Sven Hofmann</a>
	</div>

</div>
