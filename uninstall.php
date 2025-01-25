<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @author    Michael Schoenrock <hello@michaelschoenrock.com>, Sven Hofmann <info@hofmannsven.com>
 * @license   GPL-2.0+
 */

// If uninstall not called from WordPress, then exit
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// unregister plugin settings

function mark_posts_unregister_plugin()
{
    register_taxonomy('marker', []);
}

mark_posts_unregister_plugin();
