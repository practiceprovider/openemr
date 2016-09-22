<?php
/**
 * Data import class
 *
 * @auther Muhammad Umer <umermalik129@gmail.com>
 */

namespace Api;


class DataImport
{
    /**
     * Indentation times
     *
     * @var $_indent int
     */
    public $_indent = 3;

    protected $parser;

    public function __construct(CSVParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Generate array of the fields
     *
     * @param int $protocol_id
     * @param null $parent
     * @return mixed fields
     */
    public function fieldTreeArray($protocol_id, $parent = null)
    {
        if ($parent == null) {
            $query = "SELECT * FROM qlc_field WHERE protocol_id = $protocol_id and parentId IS NULL ORDER BY `index` ASC";
        } else {
            $query = "SELECT * FROM qlc_field WHERE protocol_id = $protocol_id and parentId = '$parent' ORDER BY `index` ASC";
        }

        $res = sqlStatement($query);
        while ($row = sqlFetchArray($res)) {
            $out[$row['id']] = $row;
            $out[$row['id']]['child'] = $this->fieldTreeArray($protocol_id, $row['id']);
        }
        return $out;
    }

    /**
     * Generate Html of the fields
     *
     * @param int $protocol_id
     * @param null $parent
     * @return mixed fields
     */
    public function fieldTreeHtml($array, $headers, $parent = null, $level = 0)
    {
        $out = "";
        $parent = !is_null($parent) ? "[$parent]" : "";

        foreach ($array as $key => $item) {
            $out .= "<tr id='$key'>";
            if ($item['leaf'] != 0) {
                $out .= "<td><strong>" . str_repeat("&nbsp;", $level * $this->_indent) . $item['text'] . "</strong></td>";
                $out .= "<td><strong>" . $this->parser->buildCSVHeaderDropDown(['Identifier', 'Import'], "map{$parent}[$key][type]") . "</strong></td>";
                $out .= "<td><strong>" . $this->parser->buildCSVHeaderDropDown($headers, "map{$parent}[$key][col]") . "</strong></td>";

            } else {
                $out .= "<td><strong>" . str_repeat("&nbsp;", $level * $this->_indent) . $item['text'] . "</strong></td>";
                $out .= "<td><strong>&nbsp;</strong></td>";
                $out .= "<td><strong>&nbsp;</strong></td>";
            }
            if (is_array($item['child'])) {
                $out .= $this->fieldTreeHtml($item['child'], $headers, $key, $level + 1);
            }
            $out .= "</tr>";
        }

        return $out;
    }

    /**
     * Check if patient exists in database
     *
     * @param $value
     * @param string $field
     * @return array|null patient
     */
    public function checkPatient($value, $field = 'UniqueID')
    {
        $patient = sqlQuery("SELECT * FROM patient_data WHERE $field = '$value'");
        return $patient;
    }

    public function patientEncounter($value, $field = 'pid')
    {
        $query = "SELECT * FROM form_encounter WHERE $field = '$value'";

        $res = sqlStatement($query);
        while ($row = sqlFetchArray($res)) {
            $out[] = $row;
        }
        return $out;
    }
}