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

        // echo is_array($products) && count($products);
        // echo "<pre>";
        // print_r($products);
        // echo "</pre>";

        if (is_array($products)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sync_products';
            $wpdb->query("TRUNCATE TABLE $table_name");


            //Insert to database
            foreach ($products as $product) {
                // Assuming $product contains the fields you want to insert
                $product_data = [
                    'product_sku' => isset($product['sku']) ? $product['sku'] : '',
                    'product_weight' => isset($product['weight']) ? $product['weight'] : '',
                    'product_height' => isset($product['height']) ? $product['height'] : '',
                    'product_width' => isset($product['width']) ? $product['width'] : '',
                    'product_category' => isset($product['category']) ? $product['category'] : '',
                ];

                $wpdb->insert(
                    $table_name,
                    $product_data
                );
            }

          

            echo '<h4>Products inserted successfully</h4>';
        } 
    } 

    return ob_get_clean();
}




