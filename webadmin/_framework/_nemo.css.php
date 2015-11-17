<?

## USING PHP FILE FOR CSS TO MAKE USE OF VARS

$CSS = 

"

<style>

   @import 'http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext';

   body, html 
   {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Lato;
      font-weight: 100; 
      font-size: 13px;
      position: relative;
   }

   body
   { 
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
      background-attachment: fixed;
      margin: 0 auto 0 auto;
      position: relative;
      overflow: visible;
   }

   .controlButton
   {
      background:url('images/butback.png') repeat;
      font-weight: bold;
      background-color: #ffffff;
      border: 1px solid #cccccc;
      border-radius: 3px;
      color: #6d6e71;
      cursor: pointer;
      font-size: 12px;
      font-weight: bold;
      height: 24px;
      padding-left: 6px; 
   }

   .controlButton:hover
   {
      background:url('images/butbackHover.png') repeat;
   }

   .controlText
   { 
     background-color: #ffffff;
    border: 1px solid #cccccc;
    border-radius: 3px;
    color: #6d6e71;
    cursor: pointer;
    font-family: sans-serif,Arial,Verdana;
    font-size: 12px;
    font-weight: normal;
    height: 20px;
    margin-bottom: 1px;
    margin-top: 1px;
    padding-left: 4px;
    padding-right: 4px;
    width: 240px;
   }

   select.controlText
   {
      width: 250px;
   }

   textarea.controlText
   {
      height: 70px;
      padding: 4px;
   }

   .controlFile
   {
      padding: 10px;
      width: 230px; 
   } 

   .controlLabel
   {
      border: 0px solid #9B9B9B;
      background-color: rgba(255, 255, 255, 0) !important;
   }

   .controlTotal
   {
      border: 0px solid #9B9B9B;
      border-top: 1px #9B9B9B solid;
      border-bottom: 1px #9B9B9B solid;
   }

   .controlNumeric
   {
      width: 100px;
      text-align: right;
   }

   .controlNarrow
   {
      width: 40px;
   }

   .controlWide
   {
      width: 300px;
   }

   .controlWideMax
   {
      width: 94%;
   }

   .controlDateTime
   {
      width: 100px;
   }

   .controlError
   {
      color: red;
      background-color: #FFCFCF;
      border: 1px solid #FF3F3F;
   }

   label
   {
      font-weight: bold;
      color: #333333;
   }
    
   a
   {
      color: #51732C;
      cursor: default;
      text-decoration: underline;
   }

   a:hover
   {
      text-decoration: none;
   }

   a:hover, .menu li a:hover, menu li ul a:hover
   {
      cursor: pointer;
      color: #51732C;
      text-decoration: none;
   }

   .textColour
   {
      color: #51732C;
   }

   .blokkie p
   {
      border: 1px solid #51732C;
      padding: 10px;
      margin: 0px;
   }

   .menuL2divider
   {
      position:absolute;border-top:solid #51732C 4px;width:100%
   }

   .linkbutton
   {
      color: #51732C;
   }

   fieldset
   {
      padding: 1em;
      border: 1px solid #343434;
   }

   legend
   {
      font-weight: bold;
      padding: 5px 5px 5px 5px;
      border: 0px solid #343434;
   }

   table
   { 
      font-size: 12px;
      padding: 0px;
      margin-bottom: 12px;
   }

   caption
   {
      background-color: #343434;
      border: 1px solid #343434;
      border-bottom: 0px;
      color: white;
      font-weight: bold;
   }

   th
   {
      background-color: #E6E6E6;

   }

   td, col
   {
      padding: 2px 4px 2px 4px;
   }

   textarea
   {
      height: 35px;
      width: 100%;
   }

   #divMessage
   {
      background-color: #ffffdd; 
      padding: 1px;
   }

   #tblContent, #divContent
   {  
      margin: 0px;
      padding-bottom: 12px; 
      min-height: 480px;
      height: auto !important;
      height: 480px;
   }

   #tdContentLeft
   {
      background: transparent;
      border: 0px blue dashed;
      padding-right: 10px;
      padding-top: 0px;
      padding-left: 0px;
      padding-bottom: 0px;
      width: 45%;
   }

   #tdContentLeft #divContent
   {
      padding: 0px;
   }

   #tdContentRight
   {
      background: transparent;
      border: 0px purple dashed;
      padding: 0px;
      padding-left: 10px;
      margin-right: 10px;
      width: 55%;
   }

   #divLogout
   {
      position:absolute;
      left:90%;
      top:5px;
      font-size: 18px;
   }

   #divMenu
   {
      background-color: #f1f1f1;
      zposition: relative; 
   }

   .imgbutton
   {
      cursor: pointer;
   }

   .toolbar .linkbutton
   { 
   }

   .toolbar tr td
   {
      opacity: 0.7;
      background-color: #ccc;
   }

   .toolbar tr td:hover
   {
      opacity: 1;
   }

   .linkbutton
   {
      background: transparent;
      text-transform: capitalize;
      text-decoration: underline;
      border: 0px solid gray;
      cursor: pointer;
   }

   .linkbutton:hover
   {
      text-decoration: none;
   }

   .linkbutton2
   {
      text-transform: capitalize;
      text-decoration: underline;
      cursor: pointer;
   }

   .blokkie
   {
      border: 1px solid #343434;
      padding: 0px;
      width: 100%;
   }

   .textNowrap
   {
      white-space: nowrap;
   }

   .textHeading
   {
      font-size: 20px;
      text-transform: capitalize;
   }

   .textComment
   {
      font-style: italic;
      color: #343434;
      padding: 2px;
   }

   .divWarning
   {
      color: darkorange; 
      padding: 10px 10px 10px 6px;
   }

   .divError
   {
      color: red; 
      padding: 10px 10px 10px 6px;
   }

   .divGood
   {
      color: forestgreen; 
      padding: 10px 10px 10px 6px;
   }

   .divMessage
   {
      color: #343434; 
      padding: 10px 10px 10px 6px;
   }

   .tdDetailsLeft
   {
      width: 30%;
      white-space: nowrap;
   }

   .tdDetailsRight
   {
      width: 70%;
      white-space: nowrap;
   }

   .tblBlank
   {
      border: 0px none white;
      background: transparent;
   }

   .tblBlank td
   {
      background: transparent;
   }

   .tblMaster
   {
      background: transparent;
   }

   .tblMaster table
   {
      margin: 0px;
   }

   .tblMaster td
   {
      padding: 0px;
   }

   .tblMaster table td
   {
      background-color: white;
      padding: 2px 4px 2px 4px;
   }

   .hidden
   {
      display: none;
   }

   .tblNemoList
   {
      border-collapse: collapse;
      margin: auto ; 
      background-color: #fff; 
      border:none !important;
      -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .tblNemoList caption
   {
      border:2px solid #333; 
      padding: 6px;
      text-align: left;
      font-size: 15px;
      background-color: #333;
   }

   .tblNemoList td
   {
      cursor: pointer;
      border: solid 1px #EFEFEF;
      border-collapse: collapse;
   }

   .tblNemoList th
   {
      cursor: pointer;
      border: solid 1px #ccc;
      border-collapse: collapse;
      padding: 6px;
   }

   .tblNemoList tbody tr:hover td
   {
      background: #ffffdd;
   }

   .tdPK
   {
      background: red;
      display: none;
   }

   .tdCount
   {
      padding-left: 0px;
      color: grey;
      font-style: italic;
      font-weight: bold;
      font-size: smaller;
   }

   .aCurrent
   {
      color: black;
      text-decoration: none;
   }

   .textRed{color: red;}
   .textGreen{color: lime;}
   .textBlack{color: #303030;}
   .textGraphite{color: #606060;}
   .textWhite{color: white;}

   .divVideo
   {
      border: 2px solid white;
      padding: 10px;
   }

   .divPreview
   {
      border: 2px dashed grey;
      background-color: white;
      padding: 10px;
   }

   .dora-HeaderBar
   {
      width: 100%;
      height: 60px;
      background-color: #f1f1f1;
      position: relative;
      border-bottom:2px solid #f1f1f1;

      -webkit-box-shadow: 0px 0px 6px 0px #000;
      -moz-box-shadow:    0px 0px 6px 0px #000;
      box-shadow:         0px 0px 6px 0px #000;
   }

   .dora-HeaderBar2
   {
      width: 100%;
      height: 60px;
      background-color: #fff;
      position: relative; 
    
   }

   .dora-HeaderBar .dora-HeaderLogo
   {
      background-color: #ffffff;
      border-right: 1px groove;
      height: 50px;
      padding: 6px;
      position: absolute;
      width: 143px;
   }

   .dora-HeaderBar2 .dora-HeaderLogo
   {
      background-color: #ffffff;
      height: 50px;
      padding: 4px 10px;
      position: absolute;
      width: 135px; 
   }


   .dora-HeaderLogo img
   { 
   }

   .dora-HeaderName
   {
      position: relative;
      float: left;
      left: 155px;
      font-size: 17px;
      font-family: helvetica;
      color: #333; 
      padding: 13px 0 0 20px;
   }

   .dora-HeaderBar .dora-HeaderName h1
   {
      color:#856822;
      padding:0px;
      font-size: 20px;
      margin:0px;
      text-shadow: 2px 2px #ccc;
   }

   .dora-HeaderBar2 .dora-HeaderName h1
   {
     color:#856822;
     padding:0px;
     font-size: 20px;
     padding: 7px;
     margin:0px;
      text-shadow: 2px 2px #ccc;
   }

   .dora-HeaderUser
   {
      position: relative;
      float: right;
      color: #fff;
      font-size: 20px;
      padding: 13px 16px;
   }

   .dora-HeaderUser a
   { 
      opacity: 0.7;
      color: #fff; 
      font-size: 14px;
      text-decoration: none;
   }

   .dora-HeaderUser a:hover
   { 
      opacity: 1; 
      cursor: pointer;
   }

   .logoutWord
   {
      position: relative;
      color:#333;
      font-size: 17px;
      text-shadow: 1px 1px #ccc;
      top: -8px;
   }

   .dora-LoginBox
   {
      position: relative;
      margin: 100px auto 25px;
      width: 500px; 
      background-color: #fff; 

      -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .dora-LoginBox table
   {
      width: 100%; 
      background: none !important;
      border: none !important;
   }

   .dora-LoginBox table caption
   {
      xborder-radius: 10px 10px 0px 0px;
      border:2px solid #333; 
      padding: 10px;
      text-align: left;
      font-size: 15px;
      background-color: #333;
   }
    
   .dora-Menu-toolbar
   {
      width: 100%; 
      -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .dora-DetailsTable
   {
      border-collapse: collapse;
      margin: auto ; 
      margin-bottom: 20px;
      background-color: #fff; 
      border:none !important; 
      -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);

   }

   .dora-DetailsTable caption 
   {
      background-color: #333;
      border: 2px solid #333;
      font-size: 15px;
      padding: 10px;
      text-align: left;
   }

   .dora-DetailsTable tr:nth-child(even) {background-color: #f4f4f4}
   .dora-DetailsTable tr:nth-child(odd) {background-color: #fbfbfb }

   .OpacityLink
   {
      opacity: 0.7;
   }

   .OpacityLink:hover
   {
      cursor: pointer;
      opacity: 1;
   }

   .textColourRed
   {
      color: #e14045;
   }

   .textColourGrey
   {
      color:#aaa;
   }

   .textColourBlack
   {
      color:#000000;
   }

   .divToolbarHeading
   {
      position: relative;
      float: left;
   }

   .divToolbarFilterItem
   {
      position: relative;
      float: left; 
      padding: 10px 10px 0px 10px;
   }

   .userPP
   {
      position: absolute; 
      left: 708px;  
      padding: 10px; 
      background-color: #fbfbfb;
      margin: 13px;
      -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .dora-TabsContainer
   { 
      -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .dora-TabsMenu
   {
      position: relative; 
      z-index: 100;  
      background-color: #e6e6e6;
      border-top: 2px solid #e6e6e6;
      border-left: 2px solid #e6e6e6;
      border-right: 2px solid #e6e6e6;
      font-size: 15px; 
      text-align: left;
   }

   .dora-TabsMenuItem
   {
      position: relative;
      float: left;
      padding: 10px 30px 10px 30px;
      margin-right: 10px;
      cursor: pointer;
   }

   .ActiveTab
   {
      background-color: #333333;
      color: #fff;
   }

   .InactiveTab
   {
      background-color: #f6f6f6;
      color: #000000;
   }

   .InactiveTab:hover
   {
      background-color: #ccc;
      color: #000000;
   }

   .dora-Views
   {
      
   }
   .dora-TabsWindow
   {
      position: relative;
      z-index: 100;
      border-top: #333333 4px solid;
      background-color: #fff;
      -webkit-box-shadow: 0px -3px 6px 0px rgba(50, 50, 50, 0.5);
      -moz-box-shadow:    0px -3px 6px 0px rgba(50, 50, 50, 0.5);
      box-shadow:         0px -3px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .dora-detailsGroup
   {
      background-color: #666;
      border: 2px solid #666;
      font-size: 13px;
      font-weight: bold;
      padding: 5px 5px 5px 10px;
      text-align: left;  
      color:#ffffff;

   }

   .footer1 
   {
     width:100%;
     height:80px;
     position:absolute;
     bottom:0;
     left:0;
     box-shadow: 0 0 2px 0 #000;
     background: url(images/2blur.jpg) no-repeat center center fixed;
   }

   .footer2
   {
     width:100%;
     height:60px;
     position:absolute;
     bottom:0;
     left:0;
     box-shadow: 0 0 2px 0 #000;
     background: #5c6354;
   }

   footer aside
   {
     position: relative;
     float: left;
     padding: 10px 10px 0 30px;
   }

   .footer2 article
   {
     position: relative;
     float: right; 
     padding: 30px;
     padding: 0px 10px 0 10px;
   }

   .footer1 article
   {
     position: relative;
     float: right; 
     padding: 30px;
     padding: 10px 10px 0 10px;
   }

   footer article img
   {
     margin:10px 10px 0 10px;
   }

   footer aside img
   {
     margin:10px 10px 0 10px;
   }

   .sidebar-toggle 
   { 
     top: 0px;
     right: 0px; 
     cursor: pointer;
     position: absolute;
     width: 23px; 
     height: 100%;
     background: #e6e6e6;
     z-index: 150;
     -webkit-box-shadow: 0px 0px 6px 0px rgba(50, 50, 50, 0.5);
     -moz-box-shadow:    0px 0px 6px 0px rgba(50, 50, 50, 0.5);
     box-shadow:         0px 0px 6px 0px rgba(50, 50, 50, 0.5);
   }

   .sidebar 
   {
     position: fixed;
     top:0;
     background: #9E8851;
     width: 240px;
     color: black;
     margin-left: -217px;
     height: 100%;
     z-index: 200;
   }

   .sidebar-list 
   {
     list-style-type: none;
     padding: 0;
     margin: 0;
     width: 150px;
     border-right: 1px solid #333;
   }

   .sidebar-list li 
   {
     padding: 10px;
     border-bottom: 1px solid #333;
   }

   .sidebar ul 
   {
       margin: 0;
       padding: 0;
       list-style: none;
   }

   .sidebar ul li 
   {
       margin: 0;
   }

   .sidebar ul li a 
   {
       position: relative;
       padding: 15px 20px;
       font-size: 16px;
       font-weight: 100;
       color: #FFFFFF;
       text-decoration: none;
       display: block;
       border-bottom: 1px solid #7e6d41;
       -webkit-transition:  background 0.3s ease-in-out;
       -moz-transition:  background 0.3s ease-in-out;
       -ms-transition:  background 0.3s ease-in-out;
       -o-transition:  background 0.3s ease-in-out;
       transition:  background 0.3s ease-in-out;
   }

   .sidebar ul li:hover a 
   {
       background: #7e6d41;
   }

   .NotifyCounter
   {
     background: rgba(255,255,255,0.3);
     font-size: 12px;
     font-weight: bold;
     padding: 7px 10px;
     position: absolute;
     right: 30px;
     top: 11px;
     color:#FFFFFF;
      border-radius: 5px;
   }

   .profile_placeholder
   {
     width: 100px;
     height: 100px;
     padding: 20px 58px;
     opacity: 0.3;
   }

   #passwordStrength
{
  height:30px;
  display:block;
  float:left;
}

.strength0
{
  width:500px;
  background:#f1f1f1;
  color:#666;
}

.strength1
{
  width:100px;
  background:#ff0000;
  opacity:0.8;
  color:#fff;
}

.strength2
{
  width:200px;  
  background:#ff5f5f;
  opacity:0.8;
  color:#fff;
}

.strength3
{
  width:300px;
  background:#56e500;
  opacity:0.8;
  color:#fff;
}

.strength4
{
  background:#4dcd00;
  width:400px;
  opacity:0.8;
  color:#fff;
}

.strength5
{
  background:#399800;
  width:500px;
  opacity:0.8;
  color:#fff;
}

.BarWrapper
{
  background-color: #f1f1f1;
    border-top: 1px groove #999;
    height: 30px;
    width: 500px;
}

#passwordDescription
{
  
  margin:9px;
  font-size:10px;
}
</style>

";

echo $CSS;

?>
