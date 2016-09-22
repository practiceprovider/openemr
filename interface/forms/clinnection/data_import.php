<?php
require_once("../../../api.php");

require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/options.inc.php");

$csv = new \Api\CSVParser();
$import = new \Api\DataImport($csv);

if (isset($_POST['step']) && $_POST['step'] == 'map') {
    $file = $_FILES['file'];

    $filename = uniqid() . $file['name'];
    $filetype = end(explode('.', $filename));

    $upload_dir = $GLOBALS['OE_SITE_DIR'] . "/import/";
    $filepath = $upload_dir . $filename;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir);
    }

    if ($filetype != "csv") {
        exit('Only CSV file can be uploaded.');
    }

    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        $show_table = true;
        $array = $import->fieldTreeArray(3);

        // CSV column headers
        $headers = $csv->headers($filepath);
    } else {
        echo "There is some problem in uploading import file.";
    }
}

if (isset($_POST['step']) && $_POST['step'] == 'import') {
    $data = $csv->parseCSV($_POST['file']);
    $map = $_POST['map'];
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

//    $i = 0;
//    foreach ($data as $item) {
//        $row[$i]['key'] = $item[$map['identifier']];
//        foreach ($import->cols as $col => $data) {
//            if (isset($map[$col]) && $map[$col] != "") {
//                $row[$i]['data'][$col] = $item[$map[$col]];
//            }
//        }
//        $i++;
//    }
//    $ids = $import->insertPatient($row);
}
?>
<html>
<head>
    <link rel="stylesheet" href="/interface/themes/style_bootstrap.css" type="text/css">
</head>
<body class="body_top">

<p class="title"><b>Data Import</b></p>
<div>
    <form method=post enctype="multipart/form-data" action="" onsubmit="return top.restoreSession()">
        <div class="text">
            <p>
                <span>Source CSV File:</span>
                <input type="file" name="file" accept=".csv" required/>
            </p> &nbsp;
            <p><input type="submit" value="Upload"/></p>
        </div>
        <input type="hidden" name="step" value="map"/>
    </form>
</div>
<br>
<?php if (isset($show_table) && $show_table == true) { ?>
    <p>Map CSV column with patient info.</p>
    <form method=post action="" onsubmit="return top.restoreSession()">
        <table class="table table-striped">
            <thead>
            <tr>
                <th><strong>Data Tree</strong></th>
                <th><strong>Type</strong></th>
                <th><strong>CSV Column</strong></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="200px"><strong>Parent ID</strong></td>
                <td width="200px"><strong>Identifier</strong></td>
                <td><?php echo $csv->buildCSVHeaderDropDown($headers, "map[patient_identifier]"); ?></td>
            </tr>
<!--            <tr>-->
<!--                <td width="200px"><strong>Plan ID</strong></td>-->
<!--                <td width="200px"><strong>Identifier</strong></td>-->
<!--                <td>--><?php //echo $csv->buildCSVHeaderDropDown($headers, "map[encounter_identifier]"); ?><!--</td>-->
<!--            </tr>-->
            <?php echo $import->fieldTreeHtml($array, $headers) ?>
            </tbody>
        </table>
        <input type="hidden" name="file" value="<?= $filepath ?>"/>
        <input type="hidden" name="step" value="import"/>
        <p><input type="submit" value="Import"/></p>
    </form>
<?php } ?>
</body>
</html>
