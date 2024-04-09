<?php

require 'core.php';

/**
 * @param $csvFile - path to csv to ingest
 * @param $limit - maximum number of rows to parse
 * @param $return_assoc - use first row to set keys for rows (true/false)
 * @return array
 */

function parseCSV($csvFile, $delimiter = ",", $limit = null, $return_assoc = false) {
    $csvData = [];
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
        $i = 0; $keys = [];

        while (($data = fgetcsv($handle, 1000, $delimiter)) !== FALSE
            && (is_null($limit) || $i < $limit))
        {
            if($i == 0 && $return_assoc) $keys = $data;

            if($i > 0  && $return_assoc)
            {
                for($k = 0; $k < min(count($data), count($keys)); $k++)
                {
                    $data[$keys[$k]] = $data[$k];
                    unset($data[$k]);
                }
            }

            $csvData[] = $data;
            $i++;
        }
        fclose($handle);
    }
    return $csvData;
}


//Needs work - create mysql views
function flushTables($fill_default = true)
{
    global $mysqli;

    $mysqli->query("SET FOREIGN_KEY_CHECKS=0;");

    //TABLE `global_keys`
    $mysqli->query("DROP TABLE IF EXISTS `global_keys`");
    $mysqli->query("
    CREATE TABLE `global_keys` (
        id INT PRIMARY KEY AUTO_INCREMENT,
        global_key VARCHAR(64) NOT NULL,
        global_key_label VARCHAR(128),
        type INT,
        weight INT
    )");


    //TABLE `data_sets`
    $mysqli->query("DROP TABLE IF EXISTS `data_sets`");
    $mysqli->query("
    CREATE TABLE `data_sets` (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL
    )");

    //TABLE `templates`
    $mysqli->query("DROP TABLE IF EXISTS `templates`");
    $mysqli->query("
    CREATE TABLE `templates` (
        id INT PRIMARY KEY AUTO_INCREMENT,
        data_set_id INT, FOREIGN KEY(data_set_id) REFERENCES data_sets(id),
        local_key VARCHAR(64),
        local_key_label VARCHAR(128),
        global_key_id INT, FOREIGN KEY(global_key_id) REFERENCES global_keys(id)
    )");



    //TABLE `transactions`
    $mysqli->query("DROP TABLE IF EXISTS `transactions`");
    $mysqli->query("
    CREATE TABLE `transactions` (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL,
        template_id INT, FOREIGN KEY(template_id) REFERENCES templates(id),
        d_insert TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status INT
    )");

    //ingest queue
    $mysqli->query("DROP TABLE IF EXISTS `ingest_queue`");
    $mysqli->query("
        CREATE TABLE `ingest_queue`(
            id INT PRIMARY KEY AUTO_INCREMENT,
            transaction_id INT, FOREIGN KEY(transaction_id) REFERENCES transactions(id),
            entry_id INT,
            template_id INT, FOREIGN KEY(template_id) REFERENCES templates(id),
            entry_value TEXT,
            status INT            
        )
    ");

    $mysqli->query("SET FOREIGN_KEY_CHECKS=1;");

    if($fill_default) :

        $mysqli->query("INSERT INTO `data_sets` (id, name) VALUES
        (1, 'GOOGLE'),
        (2, 'FACEBOOK'),
        (3, 'WEBSITE')");

        //Global Template / Global Keys
        $mysqli->query("INSERT INTO `global_keys` 
        (id, global_key, global_key_label, type, weight) VALUES 
        (1,  'name',        'Company Name',         1, 1),
        (2,  'category',    'Company Category',     1, 1),
        (3,  'address',     'Address Line',         1, 1),
        (4,  'zip_code',    'ZIP Code',             1, 1),
        (5,  'city',        'City',                 1, 1),
        (6,  'country',     'Country',              1, 1),
        (7,  'region',      'Region',               1, 1),
        (8,  'phone',       'Phone Number',         1, 1),
        (9, 'email',       'Email Address',        1, 1),
        (10, 'domain',      'Website Domain',       1, 1)       
        ");


        //Google Template
        $mysqli->query("INSERT INTO `templates`
        (data_set_id, global_key_id, local_key, local_key_label) VALUES 
        (1, 1,      'name',          'Company Name'),
        (1, 2,      'category',      'Company Category'),
        (1, 3,      'address',       'Address Line'),
        (1, 4,      'zip_code',      'ZIP Code'),
        (1, 5,      'city',          'City'),
        (1, 6,      'country_name',  'Country'),
        (1, NULL,   'country_code',  'Country Code'),
        (1, 7,      'region_name',   'Region'),
        (1, NULL,   'region_code',   'Region Code'),
        (1, 8,      'phone',         'Phone Number'),
        (1, NULL,   'raw_phone',     'Phone Number (Raw)'),
        (1, NULL,   'phone_country_code','Phone Country Code'),
        (1, 10,     'domain',        'Website Domain'),
        (1, NULL,   'text',          'Text')
        ");

        //Facebook Template
        $mysqli->query("INSERT INTO `templates`
        (data_set_id, global_key_id, local_key, local_key_label) VALUES 
        (2, 1,      'name',          'Company Name'),
        (2, 2,      'categories',    'Company Category'),
        (2, NULL,   'description',   'Company Description'),
        (2, 3,      'address',       'Address Line'),
        (2, 4,      'zip_code',      'ZIP Code'),
        (2, 5,      'city',          'City'),
        (2, 6,      'country_name',  'Country'),
        (2, NULL,   'country_code',  'Country Code'),
        (2, 7,      'region_name',   'Region'),
        (2, NULL,   'region_code',   'Region Code'),
        (2, 8,      'phone',         'Phone Number'),
        (2, NULL,   'phone_country_code','Phone Country Code'),
        (2, 9,      'email',         'Email Address'),
        (2, 10,     'domain',        'Website Domain'),
        (2, NULL,   'link',          'Website Link'),
        (2, NULL,   'page_type',     'Page Type')
        ");

        //Website Template
        $mysqli->query("INSERT INTO `templates`
        (data_set_id, global_key_id, local_key, local_key_label) VALUES 
        (3, 1,      'site_name',     'Company Name'),
        (3, 2,      's_category',    'Company Category'),
        (3, NULL,   'legal_name',    'Company Description'),
        (3, NULL,   'language',      'Website Language'),
        (3, 5,      'main_city',     'City'),
        (3, 6,      'main_country',  'Country'),
        (3, 7,      'main_region',   'Region'),
        (3, 8,      'phone',         'Phone Number'),
        (3, 10,     'root_domain',   'Website Domain'),
        (3, NULL,   'tld',           'TLD - Top-Level Domain'),
        (3, NULL,   'domain_suffix', 'Web Address Suffix')
        ");

    endif;
}
