<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/trellising.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&TrellisID=$TrellisID");
         break;
      case "Save":
         $Message = Trellising::Save($TrellisID);
         break;
      case "Delete":
         $Message = Trellising::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblTrellis", $TrellisID, "strTrellis");

         $page->Fields["TrellisID"]->Control->type = "hidden";

         $page->Fields["strFilename"]->Control->type = "file";    
         if($page->Fields["strFilename"]->VALUE != "")
         {
            $page->Fields["strFilename"]->Control->comment = $page->Fields["strFilename"]->VALUE; 
            $page->ContentRight = "<img class='userPP' src='". $SystemSettings[TrellisingImagesDir] . $page->Fields["strFilename"]->VALUE ."' alt='".$page->Fields["strFilename"]->VALUE ."'' title='".$page->Fields["strFilename"]->VALUE ."'' />";
         }else{
            $page->Fields["strFilename"]->Control->comment = "No image loaded. Click the bowse button to upload a picture.";
            $page->ContentRight = "<img class='userPP' src='' alt=\"No image loaded :'( \"/>";
         }

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new Trellising("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new Trellising(array("TrellisID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
