<?php

// Activation hook: Create database table
function sync_products_informetion_db_active() {
    global $wpdb;

    $table_name      = $wpdb->prefix . 'sync_products_info';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT,
        operation_type VARCHAR(255) NOT NULL,
        operation_value TEXT NOT NULL,
        status VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}


// Deactivation hook: Remove database table
function sync_products_informetion_table_remove() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'sync_products_info';
    $sql        = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query( $sql );
}
