<?php

$TITLE= "TITLE";
$HEADER= "HEADER";
$FILTER= "FILTER";
$TOPICS=  "TOPICS";
$CONTENT= "CONTENT";
$FOOTER= "FOOTER";


$html=
	"<html>
		<head>
			<title>".$TITLE."</title>
		</head>
		<body>
			<header style='background: crimson; text-align: left;'><h1>".$HEADER."</h1></header><br />
			<section>".$FILTER."</section><br />
			<nav style='float: right; width: 200; height: 600;  display: inline; background: crimson'>".$TOPICS."</nav>
			<section style='background: green'>".$CONTENT."</section><br />
			<footer style='background: crimson; position:absolute; bottom: 0; width:100%'>".$FOOTER."</footer>
		</body>
	</html>";

echo $html;
?>