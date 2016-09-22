<?php
/**
 * CSV parser class
 *
 * @auther Muhammad Umer <umermalik129@gmail.com>
 */

namespace Api;


class CSVParser
{
    public function __construct()
    {

    }

    /**
     * Parse csv as array
     *
     * @param $file file path
     * @return array $csv csv data as an array
     */
    public function parseCSV($file)
    {
        $csv = array_map('str_getcsv', file($file));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);

        return $csv;
    }

    /**
     * Extract CSV headers
     *
     * @param $file source file
     * @return array CSV headers
     */
    public function headers($file)
    {
        $array = $this->parseCSV($file);
        return array_keys($array[0]);
    }

    /**
     * Builds drop dwon with CSV columns headers
     *
     * @param $data array of CSV headers
     * @return string drop dwon with headers
     */
    public function buildCSVHeaderDropDown($data, $field_name)
    {
        $return = "<select name='$field_name'>";
        $return .= "<option value=''></option>";
        foreach ($data as $col) {
            $return .= "<option value='$col'>$col</option>";
        }
        $return .= "</select>";
        return $return;
    }
}