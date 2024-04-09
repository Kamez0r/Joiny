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


function flushTables()
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
}
