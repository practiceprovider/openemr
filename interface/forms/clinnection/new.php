<?php
include_once("../../globals.php");
?>

<script>
    var getVars = [
        'encounter=True',
        'patient_id=<?= $pid ?>',
        'encounter_id=<?= $encounter ?>',
    ];

    var queryString = getVars.join('&')

    top.window.parent.left_nav.loadFrame2('nen1', 'RBot', '../clinnection/index.php?' + queryString);
</script>
