<?php 
function bigbuy_product_information() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.bigbuy.eu/rest/catalog/productsinformation.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer MjJlODI5OGJjZWUyZDUwZjg3NTRlZTRhZjM3YmQ0NTEzM2ZhMWFhMTJiOGFjMGI0Y2RkYWI3NWFmMGRlMTQ4Ng',
            'Cookie: BBSESSID=d1f1588bebac472d0a6ef91e428f36e5'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

function insert_products_info_db_callback() {
    ob_start();
    $api_response = bigbuy_product_information();

    if ($api_response) {
        $products = json_decode($api_response, true);

        if (is_array($products)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sync_products_info';
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

            echo '<h4>Products information inserted successfully</h4>';
        }
    } 

    return ob_get_clean();
}
add_shortcode('insert_product_info', 'insert_products_info_db_callback');


