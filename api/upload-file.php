<?php

require_once('../lib/parsecsv.lib.php');

$csvParser = new parseCSV();

if($_FILES['csv']['error'] == 0) {

    $csvParser->auto($_FILES['csv']['tmp_name']);
}

?>
<style type="text/css" media="screen">
    table {
        background-color: #BBB;
    }
    th {
        background-color: #EEE;
    }
    td {
        background-color: #FFF;
    }
</style>
<table border="0" cellspacing="1" cellpadding="3">
    <tr>
        <?php foreach ($csvParser->titles as $value): ?>
            <th><?php echo $value; ?></th>
        <?php endforeach; ?>
    </tr>
    <?php foreach ($csvParser->data as $key => $row): ?>
        <tr>
            <?php foreach ($row as $value): ?>
                <td><?php echo $value; ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>