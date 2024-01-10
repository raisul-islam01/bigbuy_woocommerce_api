<?php 

function bigbuy_fetch_all_products() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.bigbuy.eu/rest/catalog/products.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer MjJlODI5OGJjZWUyZDUwZjg3NTRlZTRhZjM3YmQ0NTEzM2ZhMWFhMTJiOGFjMGI0Y2RkYWI3NWFmMGRlMTQ4Ng',
            'Cookie: BBSESSID=9beda4401953ba54cb1a8a7e395e4e62'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

// insert products to database
add_shortcode('insert_products_db', 'insert_products_to_db_callback');
function insert_products_to_db_callback() {
    ob_start();
    $api_response = bigbuy_fetch_all_products();

    if ($api_response) {
        $products = json_decode($api_response, true);

        if (is_array($products)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sync_products';
            $wpdb->query("TRUNCATE TABLE $table_name");

            // Insert to database
            foreach ($products as $product) {
                $product_data = json_encode($product);
                $wpdb->insert(
                    $table_name,
                    [
                        'operation_type' => 'product_create',
                        'operation_value' => $product_data,
                        'status' => 'pending',
                    ]
                );
            }

            echo '<h4>Products inserted successfully</h4>';
        }
    } 

    return ob_get_clean();
}


