<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/single.vineyard.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&SingleVineyardID=$SingleVineyardID");
         break;
      case "Save":
         $Message = SingleVineyard::Save($SingleVineyardID);
         break;
      case "Delete":
         $Message = SingleVineyard::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblSingleVineyard", $SingleVineyardID, "strSingleVineyard");

         $page->Fields["SingleVineyardID"]->Control->type = "hidden";

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new SingleVineyard("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new SingleVineyard(array("SingleVineyardID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
