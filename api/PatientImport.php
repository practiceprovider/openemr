<?php
/**
 * Patient import class
 *
 * @auther Muhammad Umer <umermalik129@gmail.com>
 */

namespace Api;


class PatientImport
{
    /**
     * Database columns to be mapped with CSV columns
     *
     * @var array $cols
     */
    public $cols = [
        'title' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Title',
        ],

        'language' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Language',
        ],

        'fname' => [
            'required' => '',
            'type' => 'text',
            'label' => 'First name',
        ],

        'lname' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Last name',
        ],

        'mname' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Middle name',
        ],

        'DOB' => [
            'required' => '',
            'type' => 'date',
            'label' => 'Date of birth',
        ],

        'street' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Street',
        ],

        'postal_code' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Postal code',
        ],

        'city' => [
            'required' => '',
            'type' => 'text',
            'label' => 'City',
        ],

        'state' => [
            'required' => '',
            'type' => 'text',
            'label' => 'State',
        ],

        'country_code' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Country code',
        ],

        'drivers_license' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Driver license',
        ],

        'ss' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Social security',
        ],

        'occupation' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Occupation',
        ],

        'phone_home' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Home phone',
        ],

        'phone_biz' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Business phone',
        ],

//        'phone_contact' => [
//            'required' => '',
//            'type' => '',
//            'label' => '',
//        ],

        'phone_cell' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Cell phone',
        ],

        'sex' => [
            'required' => '',
            'type' => 'gender',
            'label' => 'Gender',
        ],

        'email' => [
            'required' => '',
            'type' => 'email',
            'label' => 'Email',
        ],

        'ethnoracial' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Ethnoracial',
        ],

        'race' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Race',
        ],

        'ethnicity' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Ethnicity',
        ],

        'religion' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Religion',
        ],

        'interpretter' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Interpreter',
        ],

        'migrantseasonal' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Migrantseasonal',
        ],

        'family_size' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Family size',
        ],

        'monthly_income' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Monthly income',
        ],

        'billing_note' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Billing note',
        ],

        'homeless' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Homeless',
        ],

        'mothersname' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Mother name',
        ],

        'guardiansname' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Guardian name',
        ],

//        'deceased_reason' => [
//            'required' => '',
//            'type' => 'text',
//            'label' => '',
//        ],

        'county' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Country',
        ],

        'industry' => [
            'required' => '',
            'type' => 'text',
            'label' => 'Industry',
        ],
    ];

    /**
     * Patient ID
     */
    private $pid;

    private $insertIDs = [];

    public function __construct()
    {

    }

    /**
     * Set patient pid
     */
    public function setPID()
    {
        $result = sqlQuery("SELECT MAX(pid)+1 AS pid FROM patient_data");

        $newpid = 1;

        if ($result['pid'] > 1) $newpid = $result['pid'];

        setpid($newpid);
        $this->pid = $newpid;

        if (empty($this->pid)) {
            die("Internal error: setpid($newpid) failed!");
        }
    }

    /**
     * Save patient into database
     *
     * @param array $data patient data
     * @return array insert ids
     */
    public function insertPatient($data)
    {
        foreach ($data as $item) {
            $this->setPID();
            $this->upsert($item['key'], $item['data']);
        }
        return $this->insertIDs;

    }

    /**
     * Builds data string for sql query
     *
     * @param array $array data set
     * @return string concatenated sql data string
     */
    public function buildSqlData($array)
    {
        $return = "";
        foreach ($array as $key => $value) {
            $return .= "$key = " . pdValueOrNull($key, $value) . ",";
        }
        $return = rtrim($return, ",");
        return $return;
    }

    public function upsert($key, $data)
    {
        $cols = $this->buildSqlData($data);
        $sql = "INSERT INTO patient_data 
                SET pid = '$this->pid', date = NOW(), UniqueID = '$key', $cols
                ON DUPLICATE KEY UPDATE $cols";
        $insert_id = sqlInsert($sql);
        array_push($this->insertIDs, $insert_id);
    }
}