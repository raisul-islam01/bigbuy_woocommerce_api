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
                    'category_code' => isset($product['category']) ? $product['category'] : '',
                    'depth' => isset($product['depth']) ? $product['depth'] : '',
                    'wholesale_price' => isset($product['wholesalePrice']) ? $product['wholesalePrice'] : '',
                    'retail_price' => isset($product['retailPrice']) ? $product['retailPrice'] : '',
                    'taxonomy_code' => isset($product['taxonomy']) ? $product['taxonomy'] : '',
                    'tax_rate' => isset($product['taxRate']) ? $product['taxRate'] : '',
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

// Function to convert array to XML node
function array_to_xml($xml, $data) {
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $subNode = $xml->addChild($key);
            array_to_xml($subNode, $value);
        } else {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
}

// Shortcode to generate XML and provide download link
add_shortcode('insert_products_db_download', 'generate_products_data_xml_download');
function generate_products_data_xml_download() {
    ob_start();
    $api_response = bigbuy_fetch_all_products();

    if ($api_response) {
        $products = json_decode($api_response, true);

        if (is_array($products)) {
            // Generate XML for product information
            $xml = new SimpleXMLElement('<products_information></products_information>');
            foreach ($products as $product) {
                $productNode = $xml->addChild('product_info');
                array_to_xml($productNode, $product);
            }

            // Save XML to a file
            $xmlFilePath = 'products_data.xml';
            $xml->asXML($xmlFilePath);

            // Provide a download link for the XML file
            echo '<h4>Products data generated successfully</h4>';
            echo '<a href="' . site_url('/') . $xmlFilePath . '" download>Download Products XML</a>';
        }
    }

    return ob_get_clean();
}



