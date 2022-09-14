<?php
/**
 * Renders the view for the dashboard widget.
 *
 * @author       Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license      GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    exit;
}

/**
 * Build marker stats for each post type.
 *
 * @since 1.0.8
 */
function get_marker_stats()
{
    // attempt to get the stats from the transient
    $transient_data = get_transient('marker_posts_stats');
    if ($transient_data) {
        return $transient_data;
    }

    $markers              = get_terms(['taxonomy' => 'marker']);
    $mark_posts_posttypes = get_option('mark_posts_settings')['mark_posts_posttypes'];
    $marker_stats         = '';

    foreach ($mark_posts_posttypes as $mark_posts_posttype) {
        $marked_posts = '';

        foreach ($markers as $marker) {
            $default_args = [
                'post_type'      => $mark_posts_posttype,
                'taxonomy'       => $marker->taxonomy,
                'term'           => $marker->slug,
                'post_status'    => ['publish', 'pending', 'draft', 'future'],
                'posts_per_page' => -1,
            ];
            $post_args    = apply_filters('mark_posts_dashboard_query', $default_args);
            if (!is_array($post_args)) {
                $post_args = $default_args;
            }
            $posts_count = (new WP_Query($post_args))->post_count;

            if (!empty($posts_count)) {
                $marked_posts .= '<li class="mark-posts-info mark-posts-' . $marker->slug . '">';
                $marked_posts .= '<a href="edit.php?post_type=' . $mark_posts_posttype . '&marker=' . $marker->slug . '">' . $posts_count . ' ' . $marker->name . '</a>';
                $marked_posts .= '</li>';
            }
        } // end of marker loop

        $marker_post_type_object = get_post_type_object($mark_posts_posttype);
        if ($marker_post_type_object === null) {
            continue;
        }

        if (!empty($marked_posts)) {
            $marker_stats .= '<h3 class="mark_posts_headline">' . $marker_post_type_object->labels->name . '</h3>';
            $marker_stats .= '<ul class="markers_right_now">';
            $marker_stats .= $marked_posts;
            $marker_stats .= '</ul>';
        }
    } // end of post type loop

    // set transient
    set_transient('marker_posts_stats', $marker_stats, 60 * 60 * 12);

    if (!empty($marker_stats)) {
        return $marker_stats;
    }

    return __('No marked posts yet.', 'mark-posts');
}

echo get_marker_stats();
