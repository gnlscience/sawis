<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.translator.cls.php");
   include_once("includes/faq.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&FAQID=$FAQID");
         break;
      case "Save":
         $Message = FAQ::Save($FAQID, $page);
         break;
      case "Delete":
         $Message = FAQ::Delete($_POST[chkSelect]);
         break;
   }
//nav

   switch($Action){
      case "Search":
      case "Clear":
         $page = new NemoDetailsTranslator();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("sysFAQ", $FAQID, $_SESSION["USER"]->LANGUAGE ."_strTitle");

         $page->Fields["FAQID"]->Control->type = "hidden";
         //$page->Fields["blnActive"]->Label = $_TRANSLATION[$_SESSION["USER"]->LANGUAGE]["blnActive"];

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave(); 
         break; 
      // case "ReadMore":

      //    break;
      // case "NavTopic":
      //    $page = new FAQ(array("FAQID"));
      //    $page->isPageable = 1;
      //    $page->Content = $page->getContent($_GET["FAQID"]);
      //    break;
      default:
         $page = new FAQ(array("FAQID"));
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>