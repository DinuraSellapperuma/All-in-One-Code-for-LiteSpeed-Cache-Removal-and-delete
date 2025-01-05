add_action('admin_init', function() {
    // Define LiteSpeed Cache plugin path
    $plugin = 'litespeed-cache/litespeed-cache.php';

    // 1. Deactivate the LiteSpeed Cache plugin if active
    if (is_plugin_active($plugin)) {
        deactivate_plugins($plugin);
    }

    // 2. Delete LiteSpeed Cache plugin files if they exist
    if (file_exists(WP_PLUGIN_DIR . '/litespeed-cache')) {
        delete_plugins([$plugin]);
    }

    // 3. Remove LiteSpeed Cache options from the database
    global $wpdb;

    // Delete options from wp_options
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE 'litespeed%'");

    // Drop LiteSpeed Cache-specific tables (if they exist)
    $tables_to_drop = [
        "{$wpdb->prefix}litespeed_url",
        "{$wpdb->prefix}litespeed_img_optm"
    ];
    foreach ($tables_to_drop as $table) {
        $wpdb->query("DROP TABLE IF EXISTS $table");
    }

    // 4. Remove LiteSpeed Cache .htaccess rules
    $htaccess_file = ABSPATH . '.htaccess';
    if (file_exists($htaccess_file)) {
        $htaccess_content = file_get_contents($htaccess_file);
        $htaccess_content = preg_replace('/# BEGIN LITESPEED[\s\S]+?# END LITESPEED/', '', $htaccess_content);
        file_put_contents($htaccess_file, $htaccess_content);
    }

    // 5. Clear any cached data (optional)
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
});
