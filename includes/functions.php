<?php

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

