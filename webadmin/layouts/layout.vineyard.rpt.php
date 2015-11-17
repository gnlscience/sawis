<?php

$dtDate = "testDate";
$strProducerNum = "testProducerNum";
$strAddress = "testAddress";
$strTelNo = "testTel";
$strCellNo = "testCell";
$strFaxNo = "testFax";
$strEmail = "testEmail";
$strCoop = "testCoop";
$strFarmNo = "testFarmNum";
$strFarmName = "testFarmName";
$strFarmArea = "testFarmArea";

//data repeater vars
$strBlock = "testBlock";
$intYear = "testYear";
$strCultivar = "testCultivar";
$strCultivarRootstock = "testCultivarRootstock";
$dblRowSpacingWide = "tstW";
$dblRowSpacingNarrow = "tstN";
$dblVineSpacing = "tstVS";
$intVinesOpening = "tstVO";
$dblHectare = "tstH";
$blnGPS = "tstGPS";
$strIrrigation = "testIrrigation";
$strTrellis = "testTrellis";
$intYearUprooted = "testYear";
$intVinesUprooted = "testVines";
$intYearGrafted = "testYear";
$strCultivarGrafted = "testCultivarGrafted";
$intVinesGrafted = "testVines";


$html = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
		<title>SAWIS 1 - Vineyard Status</title>
	</head>
	<body>
		<header>
			<div align='center' style='border-style: solid'>Wingerdstandverslag soos op $dtDate <b>SAWIS 1</b> Report of vineyeard status as $dtDate</div>
			<table width='100%'>
				<tr>
					<td width='50%'>Datum/Date: $dtDate</td>
					<td width='50%'>Produsent No/Producer No: $strProducerNum</td>
				</tr>
				<tr>
					<td width='50%' rowspan='6'>$strAddress</td>
					<td width='50%'>Tel No/Tel Nr: $strTelNo</td>
				</tr>
				<tr>
					<td width='50%'>Sell No/Cell Nr: $strCellNo</td>
				</tr>
				<tr>
					<td width='50%'>Faks No/Fax Nr: $strFaxNo</td>
				</tr>
				<tr>
					<td width='50%'>E-Pos/E-Mail: $strEmail</td>
				</tr>
				<tr>
					<td width='50%'>Koop/Coop: $strCoop</td>
				</tr>
				<tr>
					<td width='50%'>Plaas No/Farm Nr: $strFarmNo</td>
				</tr>
				<tr>
					<td width='50%'>Naam van Plaas/Name of Farm: $strFarmName</td>
					<td width='50%'>Plaas Oppv/Farm Area: $strFarmArea</td>
				</tr>
			</table>
			<br />
		</header>
		<content>
			<table style='border: 1px solid; border-collapse: collapse; padding 1px; text-align: center; width: 100%'>
				<thead>
					<tr>
						<th colspan='12' style='border: 1px solid;'>Huidige stand van wingerde/Current status of vineyeards<br />
							 Maak asseblief regstelling waar nodig/Please rectify where necessary</th>
						<th colspan='2' rowspan='2' style='border: 1px solid;'>Stokke Uitgekap<br />Vines Uprooted</th>
						<th colspan='3' rowspan='2' style='border: 1px solid;'>Stokke Afgeent<br />Vines Topgraafted</th>
					</tr>
					<tr>
						<th rowspan='3' style='border: 1px solid;'>Nommer van blok<br />Number of block</th>
						<th rowspan='3' style='border: 1px solid;'>Plantjaar<br />Planting Year</th>
						<th rowspan='3' style='border: 1px solid;'>Varieteit<br />Grape Variety</th>
						<th rowspan='3' style='border: 1px solid;'>Soort Onderstok<br />Rootstock Variety</th>
						<th colspan='3' style='border: 1px solid;'>Plantwydte-CM<br />Planting Width-CM</th>
						<th rowspan='3' style='border: 1px solid;'>Getal Stokke<br />Number of Vines</th>
						<th rowspan='3' style='border: 1px solid;'>Opperv HA<br />Area Ha</th>
						<th rowspan='3' style='border: 1px solid;'>GPS*</th>
						<th rowspan='3' style='border: 1px solid;'>Besproeiings Stelsel<br />Irrigation System</th>
						<th rowspan='3' style='border: 1px solid;'>Prieelstelsel<br />Trellising System</th>
					</tr>
					<tr>
						<th colspan='2' style='border: 1px solid;'>Tussen Rye<br />Between Rows</th>
						<th rowspan='2' style='border: 1px solid;'>In Ry<br />In Rown</th>
						<th rowspan='2' style='border: 1px solid;'>Jaar<br />Year</th>
						<th rowspan='2' style='border: 1px solid;'>Getal<br />Number</th>
						<th rowspan='2' style='border: 1px solid;'>Jaar<br />Year</th>
						<th rowspan='2' style='border: 1px solid;'>Varueteit<br />Variety</th>
						<th rowspan='2' style='border: 1px solid;'>Getal<br />Number</th>
					</tr>
					<tr>
						<th style='border: 1px solid;'>Wye<br />Wide</th>
						<th style='border: 1px solid;'>Nou<br />Narrow</th>
					</tr>
				</thead>
<!-- Data row repeater START //-->
				<tr>
					<td style='border: 1px solid;'>$strBlock</td>
					<td style='border: 1px solid;'>$intYear</td>
					<td style='border: 1px solid;'>$strCultivar</td>
					<td style='border: 1px solid;'>$strCultivarRootstock</td>
					<td style='border: 1px solid;'>$dblRowSpacingWide</td>
					<td style='border: 1px solid;'>$dblRowSpacingNarrow</td>
					<td style='border: 1px solid;'>$dblVineSpacing</td>
					<td style='border: 1px solid;'>$intVinesOpening</td>
					<td style='border: 1px solid;'>$dblHectare</td>
					<td style='border: 1px solid;'>$blnGPS</td>
					<td style='border: 1px solid;'>$strIrrigation</td>
					<td style='border: 1px solid;'>$strTrellis</td>
					<td style='border: 1px solid;'>$intYearUprooted</td>
					<td style='border: 1px solid;'>$intVinesUprooted</td>
					<td style='border: 1px solid;'>$intYearGrafted</td>
					<td style='border: 1px solid;'>$strCultivarGrafted</td>
					<td style='border: 1px solid;'>$intVinesGrafted</td>
				</tr>
<!-- Data row repeater END//-->
			</table>
			<br />
		</content>
		<footer>
			<div align='center'><b>S A W I S, POSBUS/BOX 238, PAARL, 7620 TEL: 0218075712 FAX: 08655901792 E-MAIL:VOSS@SAWIS.CO.ZA</b></div>
		</footer>
	</body>



";

echo $html;

?>