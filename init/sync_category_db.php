<?php

// Activation hook: Create database table
function rangement_db_category_table_create() {
    global $wpdb;

    $table_name      = $wpdb->prefix . 'sync_category';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT,
        category_name VARCHAR(255) NOT NULL,
        category_url VARCHAR(255) NOT NULL,
        parent_category VARCHAR(255) NOT NULL,
        category_Images TEXT NOT NULL,
        dateAdd TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        dateUpd TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}


// Deactivation hook: Remove database table
function rangement_db_category_table_remove() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'sync_category';
    $sql        = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query( $sql );
}
