<?php

$strProducerNum = "testProducerNum";
$dtDate = "testDate";
$strProducerName = "testProducerName";
$strTelNum = "testTelNum";
$strEmail = "testEmail";
$strAddress = "testAddress";
$strDistrict = "testDistrict";
$strFarmArea = "testFarmArea";
$strFarmName = "testFarmName";
$strFarmNum = "testFarmNum";

//data repeater vars
$strBlock = "0";
$intYear = "0000";
$blockID = "0000";
$strCultivar = "testCultivar";
$strCultivarCode = "0000";
$strCultivarRootstock = "testCultivarRootstock";
$dblRowSpacingWide = "tstW";
$dblRowSpacingNarrow = "tstN";
$dblVineSpacing = "tstVS";
$intVinesOpening = "tstO";
$dblHeactare = "tstH";
$intYearUprooted = "tstY";
$intVinesUprooted = "tstV";
$intYearGrafted = "tstY";
$intVinesGrafted = "tstV";
$intVinesClosing = "tstC";
$strIrrigation = "testIrrigation";
$strTrellis = "testTrellis";
$strUNKNOWN = "testUNKNOWN";

$strTotalVinesOpening = "testTot";
$strTotalHectares = "testTot";
$strTotalVinesClosing = "testTot";

$html = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<title>SAWIS 1 - Vineyeard Status</title>
	</head>
	<body>
		<header>
			<table width='100%'>
				<tr>
					<td align='center'>SAWIS<br />SA Wine Industry Information & Systems<br /><br />For your information herewith your updated Vine population report<br />
											Should you not agree with the details, please contact Debbie Wait</td>
				</tr>
				<tr>
					<td>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
				</tr>
				<tr>
					<td align='center'>PO Box 238, Paarl, 7620&nbsp;&nbsp; Tel: 021 807 5711&nbsp;&nbsp; Fax: 021 807 6021&nbsp;&nbsp; E-mail: floris@sawis.co.za</td>
				</tr>
				<tr>
					<td>--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
				</tr>
			</table>
			<table width='100%'>
				<tr>
					<td width='50%'>Producer number: $strProducerNum</td>
					<td width='50%'>Date: $dtDate</td>
				</tr>
				<tr>
					<td width='50%' rowspan='2'>Producer name: $strProducerName</td>
					<td width='50%'>Telephone: $strTelNum</td>
				</tr>
				<tr>
					<td width='50%'>E-mail: $strEmail</td>
				</tr>
				<tr>
					<td width='50%' rowspan='3'>Postal Address: $strAddress</td>
					<td width='50%'>District: $strDistrict</td>
				</tr>
				<tr>
					<td width='50%'>Farm Area: $strFarmArea</td>
				</tr>
				<tr>
					<td width='50%'> </td>
				</tr>
				<tr>
					<td width='50%'>Farm Name: $strFarmName</td>
					<td width='50%'>Farm Number: $strFarmNum</td>
				</tr>
			</table>
			<br />
			<table style='border: 1px dashed; border-collapse: collapse; padding: 1px; width: 100%'>
				<tr>
					<th rowspan='2' style='border: 1px dashed;'>Block<br />number</th>
					<th rowspan='2' style='border: 1px dashed;'>Plnt<br />year</th>
					<th rowspan='2' style='border: 1px dashed;'>Cde</th>
					<th rowspan='2' style='border: 1px dashed;'>Cultivar<br />Description</th>
					<th rowspan='2' style='border: 1px dashed;'>Cde</th>
					<th rowspan='2' style='border: 1px dashed;'>Root stock<br />Description</th>
					<th colspan='3' style='border: 1px dashed;'>Plant Widths</th>
					<th rowspan='2' style='border: 1px dashed;'>Number<br />Vines</th>
					<th rowspan='2' style='border: 1px dashed;'>Hectrs</th>
					<th colspan='2' style='border: 1px dashed;'>Uprooted</th>
					<th colspan='2' style='border: 1px dashed;'>Grafted</th>
					<th rowspan='2' style='border: 1px dashed;'>Nett<br />Vines</th>
					<th rowspan='2' style='border: 1px dashed;'>Irrigatn</th>
					<th rowspan='2' style='border: 1px dashed;'>Trellis</th>
					<th rowspan='2' style='border: 1px dashed;'>UNKNOWN</th>
				</tr>
				<tr>
					<th style='border: 1px dashed;'>Wide</th>
					<th style='border: 1px dashed;'>Narw</th>
					<th style='border: 1px dashed;'>Vnsp</th>
					<th style='border: 1px dashed;'>Year</th>
					<th style='border: 1px dashed;'>Vines</th>
					<th style='border: 1px dashed;'>Year</th>
					<th style='border: 1px dashed;'>Vines</th>
				</tr>
<!-- Data row repeater START//-->				
				<tr>
					<td style='border: 1px dashed;'>$strBlock</td>
					<td style='border: 1px dashed;'>$intYear</td>
					<td style='border: 1px dashed;'>$blockID</td>
					<td style='border: 1px dashed;'>$strCultivar</td>
					<td style='border: 1px dashed;'>$strCultivarCode</td>
					<td style='border: 1px dashed;'>$strCultivarRootstock</td>
					<td style='border: 1px dashed;'>$dblRowSpacingWide</td>
					<td style='border: 1px dashed;'>$dblRowSpacingNarrow</td>
					<td style='border: 1px dashed;'>$dblVineSpacing</td>
					<td style='border: 1px dashed;'>$intVinesOpening</td>
					<td style='border: 1px dashed;'>$dblHeactare</td>
					<td style='border: 1px dashed;'>$intYearUprooted</td>
					<td style='border: 1px dashed;'>$intVinesUprooted</td>
					<td style='border: 1px dashed;'>$intYearGrafted</td>
					<td style='border: 1px dashed;'>$intVinesGrafted</td>
					<td style='border: 1px dashed;'>$intVinesClosing</td>
					<td style='border: 1px dashed;'>$strIrrigation</td>
					<td style='border: 1px dashed;'>$strTrellis</td>
					<td style='border: 1px dashed;'>$strUNKNOWN</td>
				</tr>
<!-- Data row repeater END//-->
<!-- Data footer //-->
				<tr>
					<td colspan='9' style='border: 1px dashed;'>TOTALS</td>
					<td style='border: 1px dashed;'>$strTotalVinesOpening</td>
					<td style='border: 1px dashed;'>$strTotalHectares</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td style='border: 1px dashed;'>$strTotalVinesClosing</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</header>
	</body>
</html>
";

echo $html;

?>

