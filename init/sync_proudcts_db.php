<?php

// Activation hook: Create database table
function rangement_db_products_table_create() {
    global $wpdb;

    $table_name      = $wpdb->prefix . 'sync_products';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT AUTO_INCREMENT,
        product_sku VARCHAR(255) NOT NULL,
        product_weight TEXT NOT NULL,
        product_height TEXT NOT NULL,
        product_width TEXT NOT NULL,
        depth VARCHAR(255) NOT NULL,
        wholesale_price VARCHAR(255) NOT NULL,
        retail_price VARCHAR(255) NOT NULL,
        taxonomy_code VARCHAR(255),
        category_code VARCHAR(255),
        tax_rate VARCHAR(255),
        dateAdd TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        dateUpd TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
}


// Deactivation hook: Remove database table
function rangement_db_products_table_remove() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'sync_products';
    $sql        = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query( $sql );
}
