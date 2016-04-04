<?php

use PDO;

require_once 'globals.php';

/**
 * Created by PhpStorm.
 * User: Tony
 * Date: 4/3/2016
 * Time: 1:29 PM
 */
class Api
{

    protected $route;
    /** @var  PDO */
    protected $dbh;

    public function __construct($route)
    {
        $this->route = $route;

        try {
            $sqlconf = $GLOBALS['sqlconf'];
            $this->dbh = new PDO("mysql:host={$sqlconf['host']};dbname={$sqlconf['dbase']}",
                $sqlconf['login'], $sqlconf['pass']);
            $this->$route();
            $this->dbh = null;
        } catch (\PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
        }
    }

    protected function script()
    {
        $fields = array('patient_id', 'filled_by_id', 'pharmacy_id', 'date_added', 'date_modified', 'provider_id', 'encounter',
            'start_date', 'drug', 'drug_id', 'rxnorm_drugcode', 'form', 'dosage', 'quantity', 'size', 'unit', 'route', 'interval',
            'substitute', 'refills', 'per_refill', 'filled_date', 'medication', 'note', 'active', 'datetime', 'user', 'site',
            'prescriptionguid', 'drug_info_erx', 'external_id', 'end_date', 'indication', 'prn');

        $values = implode(',', array_map(function($field) { return ":$field"; }, $fields));
        $fields = implode(',', array_map(function($field) { return "`$field`"; }, $fields));
        $data = array();

        foreach($_POST as $key => $value) {
            $data[":$key"] = $value;
        }

        /*
        $stmt = $this->dbh->prepare("INSERT INTO prescriptions (openemr.patient_id) VALUES (':filled_by_id');");
        $success = $stmt->execute(array(':patient_id' => 1, ':filled_by_id' => 1));
        */

        $sql = "INSERT INTO `prescriptions` ($fields) VALUES ($values)";

        $stmt = $this->dbh->prepare($sql);

        if ($stmt === false) {
            die(print_r($this->dbh->errorInfo(), true));
        } else {
            $success = $stmt->execute($data);

            if (!$success) {
                die(print_r($stmt->errorInfo(), true));
            }
        }
    }

}

$api = new Api($_GET['route']);