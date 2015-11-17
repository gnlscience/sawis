<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/geo.cls.php");




   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&GeoID=$GeoID");
         break;
      case "Save":
         $Message = Geo::Save($GeoID);
         break;
      case "Delete":
         $Message = Geo::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblGeo", $GeoID, "strGeo");

         $page->Fields["GeoID"]->Control->type = "hidden";

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      default:
         $page = new Geo(array("GeoID"));
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
