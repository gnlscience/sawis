<?php

error_reporting(E_ALL ^ E_NOTICE);

require_once "excel_reader2.php";

?>
<body>
<?php


//$data = new Spreadsheet_Excel_Reader("example.xls");
//echo "arr<BR><BR>";
$data = new Spreadsheet_Excel_Reader("RSVP_Import.xls");
//echo "arr<BR><BR>";
print_r($data->sheets[0][cells]);

$data->sheets[0] = $data->sheets[1];

echo $data->dump(true,true); ?>
</body>
</html>
