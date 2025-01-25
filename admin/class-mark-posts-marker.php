<?php

/**
 * Mark Posts Marker Class.
 *
 * @author    Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    exit;
}

class Mark_Posts_Marker
{
    /**
     * Build select dropdown with all available markers for the current user.
     *
     * @param  int  $post_id  The current post id, empty if not in single post context
     * @return string select with available markers as option
     *
     * @since 1.0.4
     */
    public function mark_posts_select(int $post_id = 0)
    {
        $value = 0;
        // Retrieve post meta value from the database
        if ($post_id !== 0) {
            $value = get_post_meta($post_id, 'mark_posts_term_id', true);
        }

        // Get marker terms
        $markers_terms = get_terms([
            'taxonomy' => 'marker',
            'hide_empty' => false,
        ]);

        /**
         * Filter: 'mark_posts_marker_limit' - Allow custom user capabilities for marker terms.
         *
         * @param  array  $limited  Array with marker term names and appropriate user capability
         *
         * @since 1.0.4
         */
        $limited = apply_filters('mark_posts_marker_limit', []);
        $limited = is_array($limited) ?: [];

        // Build select
        $select = '<select id="mark_posts_term_id" name="mark_posts_term_id">';
        $select .= '<option value="">---</option>';

        foreach ($markers_terms as $marker_term) {
            // Always display current marker
            // Otherwise, check if there is a custom limit and continue if current user is missing the capability
            if ($marker_term->term_id !== $value && isset($limited[$marker_term->name]) && ! current_user_can($limited[$marker_term->name])) {
                continue;
            }

            $select .= sprintf(
                '<option value="%d" data-color="%s"%s>%s</option>',
                $marker_term->term_id,
                $marker_term->description,
                selected($marker_term->term_id, $value, false),
                $marker_term->name
            );
        }
        $select .= '</select>';

        return $select;
    }
}
