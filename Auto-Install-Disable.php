add_action('admin_init', function() {
    $plugin = 'litespeed-cache/litespeed-cache.php'; // Path to the LiteSpeed Cache plugin

    // Check if the plugin is active
    if (is_plugin_active($plugin)) {
        // Deactivate the plugin
        deactivate_plugins($plugin);
    }

    // Delete the plugin files
    if (file_exists(WP_PLUGIN_DIR . '/litespeed-cache')) {
        delete_plugins([$plugin]);
    }
});
