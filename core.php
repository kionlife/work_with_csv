<?php

class CSV {

    private $csv_file = null;

    /**
     * @param string $csv_file
     * @throws Exception
     */
    public function __construct($csv_file) {
        if (file_exists($csv_file)) {
            $this->csv_file = $csv_file;
        }
        else {
            throw new Exception('File ' . $csv_file . ' not found!');
        }
    }

    public function saveContent(string $file, array $csv) {
        $handle = fopen($file, 'w+');
        foreach ($csv as $value) {
            fputcsv($handle, $value, ',');
        }

        fclose($handle);
    }

    /**
     * Read data from csv
     * @return array;
     */
    public function getContent() {
        $handle = fopen($this->csv_file, 'r');

        $headers = fgetcsv($handle, 256, ',');
        $content = array();
        while (($line = fgetcsv($handle, 256, ',')) !== false) {
            $content[] = array_combine($headers, $line);
        }

        fclose($handle);

        foreach ($content as $key => $item) {
            $arr[$item['driver_id']][$key] = $item;
        }

        ksort($arr, SORT_NUMERIC);

        return $arr;
    }

}
