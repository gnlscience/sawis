<?php

$strProducerNum = "";
$dtDate = "";
$strProducerName = "";
$strTelNum = "";
$strEmail = "";
$strAddress = "";
$strDistrict = "";
$strFarmArea = "";
$strFarmName = "";
$strFarmNum = "";

$strBlock = "";
$intYear = "";
$blockID = "";
$strCultivar = "";
$strCultivarCode = "";
$strCultivarRootstock = "";
$dblRowSpacingWide = "";
$dblRowSpacingNarrow = "";
$dblVineSpacing = "";
$intVinesOpening = "";
$dblHeactare = "";
$intYearUprooted = "";
$intVinesUprooted = "";
$intYearGrafted = "";
$intVinesGrafted = "";
$intVinesClosing = "";
$strIrrigation = "";
$strTrellis = "";

$html = "

<html>
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
			<table width='100%' class='myTable'>
				<tr>
					<th rowspan='2'>Block<br />number</th>
					<th rowspan='2'>Plnt<br />year</th>
					<th rowspan='2'>Cde</th>
					<th rowspan='2'>Cultivar<br />Description</th>
					<th rowspan='2'>Cde</th>
					<th rowspan='2'>Root stock<br />Description</th>
					<th colspan='3'>Plant Widths</th>
					<th rowspan='2'>Number<br />Vines</th>
					<th rowspan='2'>Hectrs</th>
					<th colspan='2'>Uprooted</th>
					<th colspan='2'>Grafted</th>
					<th rowspan='2'>Nett<br />Vines</th>
					<th rowspan='2'>Irrigatn</th>
					<th rowspan='2'>Trellis</th>
					<th rowspan='2'>?????</th>
				</tr>
				<tr>
					<th>Wide</th>
					<th>Narw</th>
					<th>Vnsp</th>
					<th>Year</th>
					<th>Vines</th>
					<th>Year</th>
					<th>Vines</th>
				</tr>
				<tr>
					<td>$strBlock</td>
					<td>$intYear</td>
					<td>$blockID</td>
					<td>$strCultivar</td>
					<td>$strCultivarCode</td>
					<td>$strCultivarRootstock</td>
					<td>$dblRowSpacingWide</td>
					<td>$dblRowSpacingNarrow</td>
					<td>$dblVineSpacing</td>
					<td>$intVinesOpening</td>
					<td>$dblHeactare</td>
					<td>$intYearUprooted</td>
					<td>$intVinesUprooted</td>
					<td>$intYearGrafted</td>
					<td>$intVinesGrafted</td>
					<td>$intVinesClosing</td>
					<td>$strIrrigation</td>
					<td>$strTrellis</td>
					<td></td>
				</tr>
				<tr>
					<td colspan='9'>TOTALS</td>
					<td>$strTotalVinesOpening</td>
					<td>$strTotalHectares</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>$strTotalVinesClosing</td>
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

<style>

.myTable th, .myTable td {

	border: 1px dashed;

}

</style>