<?php
/**
 * Mark Posts Marker Class.
 *
 * @author    Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class Mark_Posts_Marker
{
    /**
     * Build select dropdown with all available markers for the current user.
     *
     * @since 1.0.4
     *
     * @param $post_id
     *
     * @return string select with available markers as option
     */
    public function mark_posts_select($post_id = null)
    {

        // Retrieve post meta value from the database
        if (isset($post_id)) {
            $value = get_post_meta($post_id, 'mark_posts_term_id', true);
        }

        // Get marker terms
        $markers_terms = get_terms('marker', 'hide_empty=0');

        /**
         * Filter: 'mark_posts_marker_limit' - Allow custom user capabilities for marker terms.
         *
         * @since    1.0.4
         *
         * @param array $limited Array with marker term names and appropriate user capability
         */
        $limited = [];
        $limited = apply_filters('mark_posts_marker_limit', $limited);

        // Build select
        $select = '<select id="mark_posts_term_id" name="mark_posts_term_id">';
        $select .= '<option value="">---</option>';

        foreach ($markers_terms as $marker_term) {

            // Always display current marker
            if (isset($value) && $marker_term->term_id == $value) {
                $select .= '<option value="'.$marker_term->term_id.'" data-color="'.$marker_term->description.'" selected="selected">'.$marker_term->name.'</option>';
            } else {
                // Check if there is a custom limit
                if (isset($limited[$marker_term->name])) {
                    // Display markers depending on user capability
                    if (current_user_can($limited[$marker_term->name])) {
                        $select .= '<option value="'.$marker_term->term_id.'" data-color="'.$marker_term->description.'">'.$marker_term->name.'</option>';
                    }
                    // Display markers if there is no custom limit defined
                } else {
                    $select .= '<option value="'.$marker_term->term_id.'" data-color="'.$marker_term->description.'">'.$marker_term->name.'</option>';
                }
            }
        }
        $select .= '</select>';

        return $select;
    }
}
