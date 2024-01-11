<?php 

function get_api_category() {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.sandbox.bigbuy.eu/rest/catalog/categories.json',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer MjJlODI5OGJjZWUyZDUwZjg3NTRlZTRhZjM3YmQ0NTEzM2ZhMWFhMTJiOGFjMGI0Y2RkYWI3NWFmMGRlMTQ4Ng',
            'Cookie: BBSESSID=d96caae84a7ba1d653ea90a26e9b275a'
        ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

function insert_categories_to_db_callback() {
    ob_start();
    $api_response = get_api_category(); // Assuming this function fetches category data

    if ($api_response) {
        $categories = json_decode($api_response, true);

        if (is_array($categories)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'sync_category';
            $wpdb->query("TRUNCATE TABLE $table_name");

            // Inserting categories into the database
            foreach ($categories as $category) {
                // Assuming $category contains the fields you want to insert
                $category_data = [
                    'category_name' => isset($category['name']) ? $category['name'] : '',
                    'category_url' => isset($category['url']) ? $category['url'] : '',
                    'parent_category' => isset($category['parentCategory']) ? $category['parentCategory'] : '',
                    'category_Images' => isset($category['urlImages']) ? $category['urlImages'] : '',
                ];

                $wpdb->insert(
                    $table_name,
                    $category_data
                );
            }

            echo '<h4>Categories inserted successfully</h4>';
        }
    }

    return ob_get_clean();
}

add_shortcode('insert_category_db', 'insert_categories_to_db_callback');
