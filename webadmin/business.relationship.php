<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/member.cls.php");
   include_once("includes/farm.cls.php");

   $page = new Nemo();
//WIP
//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&ID=$ID&RETURN_URL=$RETURN_URL&RETURN_VAR=$RETURN_VAR");
         break;
      case "Save":
         $Message = Member::Save($ID);
         break;
      case "Delete":
         $Message = Member::Delete($_POST[chkSelect]);
         break;
   }
   if($_POST[RETURN_URL] != "")
   {
      $url = $_POST[RETURN_URL] ."&". $_POST[RETURN_VAR] ."=". $$_POST[RETURN_VAR]; //vd( $url); exit;
      windowLocation($url);
   }   

//nav
   switch($Action){
      // case "Save":
      // case "Edit":
      // case "New":
      default:
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblMemberRelationship", $ID, null);

         $page->Fields["ID"]->Control->type = "hidden";


         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>