<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/company.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&CompanyID=$CompanyID");
         break;
      case "Save":
         $Message = Company::Save($CompanyID, $page);
         break;
      case "Delete":
         $Message = Company::Delete($_POST[chkSelect]);
         break;
   }
//nav

   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();

         $page->AssimulateTable("tblCompany", $HorseID, "strCompany");

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      default:
         $page = new Company(array("CompanyID"));
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
