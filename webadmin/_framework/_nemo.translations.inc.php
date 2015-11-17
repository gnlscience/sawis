<?php
//20110414 - used for quick set values from POST - pj
//20141126 - new mysqli class - http://php.net/manual/en/mysqlinfo.api.choosing.php - pj

global $_TRANSLATION, $_LANGUAGE;

$_LANGUAGE["AF"]->strLanguage = "Afrikaans"; //used to change user language
$_LANGUAGE["AF"]->Short = "Afr";
$_LANGUAGE["AF"]->Code = "AF";
$_LANGUAGE["AF"]->IconHTML->src = "images/afr-icon.png";
$_LANGUAGE["AF"]->IconHTML->height= 
$_LANGUAGE["AF"]->IconHTML->width = "31px";
$_LANGUAGE["AF"]->IconHTML->Title = "Afrikaans";

$_LANGUAGE["EN"]->strLanguage = "English";
$_LANGUAGE["EN"]->Short = "Eng";
$_LANGUAGE["EN"]->Code = "EN";
$_LANGUAGE["EN"]->IconHTML->src = "images/eng-icon.png";
$_LANGUAGE["EN"]->IconHTML->height= 
$_LANGUAGE["EN"]->IconHTML->width = "31px";
$_LANGUAGE["EN"]->IconHTML->Title = "English";

/*
//template
   $_TRANSLATION["EN"]["___"] = "";
   $_TRANSLATION["AF"]["___"] = "";

*/

//globals / system
   $_TRANSLATION["EN"]["Brand"] = "SA Wine Industry Information & Systems";
   $_TRANSLATION["AF"]["Brand"] = "SA Wynbedryf-Inligting & -Stelsels";
   
   //$_TRANSLATION["AF"]["Title"] = "SA Wyn-Industry Informasie Systeems";

   $_TRANSLATION["EN"]["btnSave"] = "Save";
   $_TRANSLATION["AF"]["btnSave"] = "Stoor";
   $_TRANSLATION["EN"]["btnClose"] = "Close";
   $_TRANSLATION["AF"]["btnClose"] = "Sluit";
   $_TRANSLATION["EN"]["btnReload"] = "Reload";
   $_TRANSLATION["AF"]["btnReload"] = "Herlaai";
   $_TRANSLATION["EN"]["btnNew"] = "New";
   $_TRANSLATION["AF"]["btnNew"] = "Nuut";
   $_TRANSLATION["EN"]["btnDelete"] = "Delete";
   $_TRANSLATION["AF"]["btnDelete"] = "Verwyder";
   $_TRANSLATION["EN"]["btnExport"] = "Export";
   $_TRANSLATION["AF"]["btnExport"] = "Aflaai";
   $_TRANSLATION["EN"]["btnFilter"] = "Filter";
   $_TRANSLATION["AF"]["btnFilter"] = "Filtreer";
   $_TRANSLATION["EN"]["btnClear"] = "Clear";
   $_TRANSLATION["AF"]["btnClear"] = "Skrap";

//labels 
   $_TRANSLATION["EN"]["lblLogout"] = "Logout";
   $_TRANSLATION["AF"]["lblLogout"] = "Log uit";
   $_TRANSLATION["EN"]["lblDetails"] = "Details";
   $_TRANSLATION["AF"]["lblDetails"] = "Besonderhede";
   // $_TRANSLATION["EN"]["lblSearch"] = "Search";
   // $_TRANSLATION["AF"]["lblSearch"] = "Soek"; //20150310 - translating filter labels
   $_TRANSLATION["EN"]["lblDevelopedBy"] = "Developed by";
   $_TRANSLATION["AF"]["lblDevelopedBy"] = "Ontwikkeld deur";


//filters //20150310 - translating filter labels
   $_TRANSLATION["EN"]["frSearch"] = "Search";
   $_TRANSLATION["AF"]["frSearch"] = "Soek";

//form fields:
   $_TRANSLATION["EN"]["intOrder"] = "Order";
   $_TRANSLATION["AF"]["intOrder"] = "Order";

   $_TRANSLATION["EN"]["blnActive"] = "Active";
   $_TRANSLATION["AF"]["blnActive"] = "Aktief";

   $_TRANSLATION["EN"]["strFirstUser"] = "First User";
   $_TRANSLATION["AF"]["strFirstUser"] = "Eerste Verbruiker";
   $_TRANSLATION["EN"]["dtFirstEdit"] = "First Edit";
   $_TRANSLATION["AF"]["dtFirstEdit"] = "Eerste Edisie";

   $_TRANSLATION["EN"]["strLastUser"] = "Last User";
   $_TRANSLATION["AF"]["strLastUser"] = "Laaste Verbruiker";
   $_TRANSLATION["EN"]["dtLastEdit"] = "Last Edit";
   $_TRANSLATION["AF"]["dtLastEdit"] = "Laaste Edisie";

   $_TRANSLATION["EN"]["Yes"] = "Yes";
   $_TRANSLATION["AF"]["Yes"] = "Ja";
   $_TRANSLATION["EN"]["No"] = "No";
   $_TRANSLATION["AF"]["No"] = "Nee";


//ACTION REWRITE
   switch($Action)
   {
      case "Filtreer":
         $Action = "Filter";
         break;
      case "Skrap": 
         $Action = "Clear";
         break;
      case "Nuut": 
         $Action = "New";
         break;
      case "Verwyder": 
         $Action = "Delete";
         break;
      case "Aflaai": 
         $Action = "Export";
         break;
      case "Stoor": 
         $Action = "Save";
         break;
      case "Sluit": 
         $Action = "Close";
         break;
      case "Herlaai": 
         $Action = "Reload";
         break;
   }

   $_POST[Action] = 
   $_GET[Action] = 
   $_REQUEST[Action] = $Action;



?>