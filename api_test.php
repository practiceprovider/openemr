<?php
require_once("api.php");

require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

$patient = new \Api\PatientImport();
$csv = new \Api\CSVParser();
$import = new \Api\DataImport($csv);

$data = array('title' => 'Mr.', 'fname' => 'Muhammad', 'mname' => '', 'lname' => 'Umer', 'pubpid' => '', 'DOB' => '1991-09-25', 'sex' => 'Male', 'ss' =>'', 'drivers_license' => '', 'status' => '', 'genericname1' => '', 'genericval1' => '', 'genericname2' => '', 'genericval2' => '', 'billing_note' => '', 'street' => '', 'city' => '', 'state' => '', 'postal_code' => '', 'country_code' => '', 'county' => '', 'mothersname' => '', 'guardiansname' => '', 'contact_relationship' => '', 'phone_contact' => '', 'phone_home' => '', 'phone_biz' => '', 'phone_cell' => '', 'email' => '', 'email_direct' => '', 'pharmacy_id' => 0, 'hipaa_notice' => '', 'hipaa_voice' => '', 'hipaa_message' => '', 'hipaa_mail' => '', 'hipaa_allowsms' => '', 'hipaa_allowemail' => '', 'allow_imm_reg_use' => '', 'allow_imm_info_share' => '', 'allow_health_info_ex' => '', 'allow_patient_portal' => '', 'cmsportal_login' => '', 'occupation' => '', 'industry' => '', 'language' => '', 'ethnicity' => '', 'race' => '', 'family_size' => '', 'monthly_income' => '', 'homeless' => '', 'interpretter' => '', 'migrantseasonal' => '', 'referral_source' => '', 'vfc' => '', 'religion' => '', 'deceased_reason' => '');

//print_r($data);

//$patient->insertPatient($data);
//$p = $patient->getPatients();
//echo '<pre>';
//$keys = array_keys($p['data'][0]);
//echo "[";
//foreach ($keys as $k) {
//    echo "'$k', ";
//}
//echo "]";
//echo '</pre>';
//exit;
//$array = $csv->parseCSV("api/patients.csv");
//$array = array_keys($array[0]);
$array = $import->patientEncounter(50);


echo "<pre>";
if ($array)
    print_r($array);
else
    print 'not fou8nd';
//print $import->fieldTreeHtml($array);
echo "</pre>";