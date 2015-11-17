<?php
   ////2013-11-20 Added preview of related email template to template detail
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/email.template.cls.php");
   include_once("includes/email.cls.php");


   $page = new Nemo();
//events
   switch($Action)
   {
      case "Reload":
         windowLocation("?Action=Edit&EmailTemplateID=$EmailTemplateID");
         break;
      case "RemoveAttachment":
         $Message = EmailTemplate::RemoveAttachment($EmailTemplateID, $strAttachment);
         break;
      case "Save":
         $Message = EmailTemplate::Save($EmailTemplateID);
         break;
      case "Delete":
         $Message = EmailTemplate::Delete($_POST[chkSelect]);
         break;
      case "New Email":
         windowLocation("email.php?Action=New&frEmailTemplateID=$EmailTemplateID");
         break;
   }
//nav
   switch($Action)
   {
      case "RemoveAttachment":
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         $page->Message->Text = $Message;
         $page->AssimulateTable("tblEmailTemplate", $EmailTemplateID, "strEmailTemplate");
         $page->Fields["EmailTemplateID"]->Control->type = "hidden";

         $page->Fields["arrSubstitutions"]->Control->class = "controlText controlLabel controlWideMax";
         if($EmailTemplateID == 0){
            $page->Fields["strEmailTemplate"]->Control->class = "controlText controlWide";
            $page->Fields["strEmailTemplate"]->Control->comment = "";
         }else{
            $page->Fields["strEmailTemplate"]->Control->class = "controlText controlLabel controlWide";
            $page->Fields["strEmailTemplate"]->Control->readonly = "readonly";
         }

         //KM ONLY!
         //20130731 - Added CourseType - pj
         $page->Fields["refCourseTypeID"]->ORDINAL_POSITION ++;

         $page->Fields["strSubject"]->Control->class .= " controlWideMax";
         $page->Fields["txtBody"]->Control->style = "height: 350px;";

         $page->Fields["arrSubstitutions"]->ORDINAL_POSITION -= 1;
         $page->Fields["arrSubstitutions"]->Control->style = "display: none;";
         $page->Fields["arrSubstitutions"]->Control->comment = EmailTemplate::renderSubstitutions($page->Fields["arrSubstitutions"]->VALUE);

         $page->Fields["arrAttachments"]->Control->style = "display: none;";
         $page->Fields["arrAttachments"]->Control->comment = EmailTemplate::renderAttachments($EmailTemplateID, $page->Fields["arrAttachments"]->VALUE);

         if($EmailTemplateID == 0)
         {

         }else{

            //$preview = "<div class='divPreview'>". EmailTemplate::Preview($EmailTemplateID) ."</div>";
            //print_rr($page->Security);
            //2013-11-20 Added preview of related email template to template detail
            $preview = "";

            $rowEmail = $xdb->getRowSQL("SELECT Max(EmailID) as EmailID FROM tblEmail WHERE refEmailTemplateID = $EmailTemplateID ",0);
            if($rowEmail)
            {
                 $preview = "                    
                    <table width='100%' cellspacing='1' cellpadding='2' border='0'>
                           <caption>Last Email of this type</caption>
                     </table>
                     <div class='divPreview'>". Email::Preview($rowEmail->EmailID)  ."</div>";            
            }        

            if($page->Security->blnSpecial == 1)
               $page->ToolBar->Buttons[btnNew2]->blnShow = 1;
            $page->ToolBar->Buttons[btnNew2]->Control->value = "New Email";
         }

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label)
            . $page->getJsNemoValidateSave()
            . js("

               ");

         $page->ContentRight = $preview;
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new EmailTemplate("");
         echo $page->getList();
         die;
         break;
      default:
         $page = new EmailTemplate(array("EmailTemplateID"));
         $page->Message->Text = $Message;

         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }
//print_rr($page);
   $page->Display();


?>
