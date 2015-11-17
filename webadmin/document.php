<?php
   include_once("_framework/_nemo.cls.php");

   include_once("_framework/_nemo.details.cls.php");

   include_once("includes/document.cls.php"); 

   $page = new Nemo();
//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=New&MemberID=$MemberID&FarmID=$FarmID&RETURN_URL=$RETURN_URL&RETURN_VAR=$RETURN_VAR&EntityID=$EntityID&EntityType=$EntityType");
         break;
      case "Save":      
         $Message = Document::Save($DocumentID);
         break;
      case "Delete":
         $Message = Document::Delete($_POST[chkSelect]);
         break;
      
   }

//redirect
   if($_POST[RETURN_URL] != "")
   {

      $url = $_POST[RETURN_URL]. $_POST[RETURN_VAR] ."=". $$_POST[RETURN_VAR]; //vd($url); die;
      windowLocation($url);
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":

//testing delte later
      // $EntityType = "MemberTMP";
      // $EntityID = $MemberID;


         $page = new NemoDetails();

         $content = Document::getUploadForm($EntityType, $EntityID);
         
         //$page->hideOtherLanguage = true;

/*       $page->AssimulateTable("tblDocument", $DocumentID, "strDocument");

         $page->Fields["DocumentID"]->Control->type = "hidden";

         $page->Fields["strFilename"]->Control->class .= " controlWideMax";

         //if adding a new farm from a member details page
         if($_REQUEST[RETURN_URL] != ""){
            $hMemberID = "<input type='hidden' name='MemberID' value='$MemberID' />";
            
            $page->Fields["refEntityID"]->Control->value = $MemberID; 
            $page->Fields["EntityType"]->Control->value = "Member"; 
         }  

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label).$hMemberID.$page->getJsNemoValidateSave();*/

         $page->ToolBar->Label = "Document Manager";
         $page->ContentLeft =  "
                        <input type='hidden' value='$MemberID' name='MemberID' />
                        <input type='hidden' value='$FarmID' name='FarmID' />
                        <input type='hidden' value='$EntityID' name='EntityID' />
                        <input type='hidden' value='$EntityType' name='EntityType' />
                        <input type='hidden' value='$RETURN_URL' name='RETURN_URL' />
                        <input type='hidden' value='$RETURN_VAR' name='RETURN_VAR' />
                        </form>".$content;


         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new Document("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new Document(array("DocumentID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
