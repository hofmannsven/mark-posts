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
 * Get available markers.
 *
 * @since     1.0.0
 */
$marker_args = [
    'hide_empty' => true,
];
$markers = get_terms('marker', $marker_args);

/**
 * Build marker stats for each post type.
 *
 * @since     1.0.8
 */
function get_marker_stats()
{
    // attempt to get the stats from the transient
    if (false === ($latest_marker_stats = get_transient('marker_posts_stats'))) {
        $marker_args = [
            'hide_empty' => true,
        ];
        $markers = get_terms('marker', $marker_args);
        $get_mark_posts_setup = get_option('mark_posts_settings');
        $mark_posts_posttypes = $get_mark_posts_setup['mark_posts_posttypes'];
        $marker_stats = '';

        foreach ($mark_posts_posttypes as $mark_posts_posttype) {
            $marked_posts = '';

            foreach ($markers as $marker) {
                $post_args = [
                    'post_type'      => $mark_posts_posttype,
                    'taxonomy'       => $marker->taxonomy,
                    'term'           => $marker->slug,
                    'post_status'    => ['publish', 'pending', 'draft', 'future'],
                    'posts_per_page' => -1,
                ];
                $posts = get_posts(apply_filters('mark_posts_dashboard_query', $post_args));
                $posts_count = count($posts);

                if (!empty($posts_count)) {
                    $marked_posts .= '<li class="mark-posts-info mark-posts-'.$marker->slug.'">';
                    $marked_posts .= '<a a href="edit.php?post_type='.$mark_posts_posttype.'&marker='.$marker->slug.'">'.$posts_count.' '.$marker->name.'</a>';
                    $marked_posts .= '</li>';
                }
            } // end of marker loop

            $marker_post_type_object = get_post_type_object($mark_posts_posttype);

            if (!empty($marked_posts)) {
                $marker_stats .= '<h3 class="mark_posts_headline">'.$marker_post_type_object->labels->name.'</h3>';
                $marker_stats .= '<ul class="markers_right_now">';
                $marker_stats .= $marked_posts;
                $marker_stats .= '</ul>';
            }
        } // end of post type loop

        // set transient
        set_transient('marker_posts_stats', $marker_stats, 60 * 60 * 12);

        if (!empty($marker_stats)) {
            return $marker_stats;
        } else {
            return __('No marked posts yet.', 'mark-posts');
        }
    } else {
        return get_transient('marker_posts_stats');
    }
}

if (!empty($markers)) {
    echo get_marker_stats();
} else {
    _e('No marked posts yet.', 'mark-posts');
}
