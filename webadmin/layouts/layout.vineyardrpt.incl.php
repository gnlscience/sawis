<?php

$dtDate = "";
$strProducerNum = "";
$strAddress = "";
$strTelNo = "";
$strCellNo = "";
$strFaxNo = "";
$strEmail = "";
$strCoop = "";
$strFarmNo = "";
$strFarmNo = "";
$strFarmArea = "";

$strBlock = "";
$intYeaR = "";
$strCultivar = "";
$strCultivarRootstock = "";
$dblRowSpacingWide = "";
$dblRowSpacingNarrow = "";
$dblVineSpacing = "";
$intVinesOpening = "";
$dblHectare = "";
$blnGPS = "";
$strIrrigation = "";
$strTrellis = "";
$intYearUprooted = "";
$intVinesUprooted = "";
$intYearGrafted = "";
$strCultivarGrafted = "";
$intVinesGrafted = "";


$html = "

<html>
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
			<table width='100%' border='1'>
				<thead>
					<tr>
						<th colspan='12'>Huidige stand van wingerde/Current status of vineyeards<br />
							 Maak asseblief regstelling waar nodig/Please rectify where necessary</th>
						<th colspan='2' rowspan='2'>Stokke Uitgekap<br />Vines Uprooted</th>
						<th colspan='3' rowspan='2'>Stokke Afgeent<br />Vines Topgraafted</th>
					</tr>
					<tr>
						<th rowspan='3'>Nommer van blok<br />Number of block</th>
						<th rowspan='3'>Plantjaar<br />Planting Year</th>
						<th rowspan='3'>Varieteit<br />Grape Variety</th>
						<th rowspan='3'>Soort Onderstok<br />Rootstock Variety</th>
						<th colspan='3'>Plantwydte-CM<br />Planting Width-CM</th>
						<th rowspan='3'>Getal Stokke<br />Number of Vines</th>
						<th rowspan='3'>Opperv HA<br />Area Ha</th>
						<th rowspan='3'>GPS*</th>
						<th rowspan='3'>Besproeiings Stelsel<br />Irrigation System</th>
						<th rowspan='3'>Prieelstelsel<br />Trellising System</th>
					</tr>
					<tr>
						<th colspan='2'>Tussen Rye<br />Between Rows</th>
						<th rowspan='2'>In Ry<br />In Rown</th>
						<th rowspan='2'>Jaar<br />Year</th>
						<th rowspan='2'>Getal<br />Number</th>
						<th rowspan='2'>Jaar<br />Year</th>
						<th rowspan='2'>Varueteit<br />Variety</th>
						<th rowspan='2'>Getal<br />Number</th>
					</tr>
					<tr>
						<th>Wye<br />Wide</th>
						<th>Nou<br />Narrow</th>
					</tr>
				</thead>
				<tr>
					<td>$strBlock</td>
					<td>$intYear</td>
					<td>$strCultivar</td>
					<td>$strCultivarRootstock</td>
					<td>$dblRowSpacingWide</td>
					<td>$dblRowSpacingNarrow</td>
					<td>$dblVineSpacing</td>
					<td>$intVinesOpening</td>
					<td>$dblHectare</td>
					<td>$blnGPS</td>
					<td>$strIrrigation</td>
					<td>$strTrellis</td>
					<td>$intYearUprooted</td>
					<td>$intVinesUprooted</td>
					<td>$intYearGrafted</td>
					<td>$strCultivarGrafted</td>
					<td>$intVinesGrafted</td>
				</tr>
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