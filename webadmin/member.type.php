<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/member.type.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&MemberTypeID=$MemberTypeID");
         break;
      case "Save":
         $Message = MemberType::Save($MemberTypeID);
         break;
      case "Delete":
         $Message = MemberType::Delete($_POST[chkSelect]);
         break;
   }

//redirect
   if($_POST[RETURN_URL] != "")
   {
      $url = $_POST[RETURN_URL] ."&". $_POST[RETURN_VAR] ."=". $$_POST[RETURN_VAR]; //vd($url); die;
      windowLocation($url);
   }
   
//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblMemberType", $MemberTypeID, "strMemberType");

         $page->Fields["MemberTypeID"]->Control->type = "hidden";

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new MemberType("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new MemberType(array("MemberTypeID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
