<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @author    Michael Schoenrock <hello@michaelschoenrock.com>
 * @license   GPL-2.0+
 */

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// unregister plugin settings

function unregister_plugin()
{
    register_taxonomy('marker', []);
}

unregister_plugin();
