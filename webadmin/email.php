<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/email.cls.php");
   include_once("_framework/_nemo.email.cls.php");

   $page = new Nemo();
//events
//print_rr($_POST); die;

   switch($Action)
   {
      case "Reload":
         $arrReload = array(true => "Edit", false => "New");
         windowLocation("?Action=". $arrReload[($EmailID > 0)] ."&EmailID=$EmailID");
         break;
      case "Save":
         $Message = Email::Save($EmailID); //updates NOTES only!
         break;
      case "Resend":

         $nemoEmail = new NemoEmail($_POST[strTo] , "" , 0);

         $result = $nemoEmail->SendFromDatabase($EmailID, $_POST[strResendTo]);
         //print_rr($result);
         if($result->Error == 1)
         {//error on email save

         }else{
            $EmailID = $result->ID;
         }
         break;
      case "Send":
//print_rr($_POST); die;
         if($_POST[blnIndividual] == 1)
         {
            $arrTo = explode(";", $_POST[strTo]);
            foreach($arrTo as $strTo)
            {
               $nemoEmail = new NemoEmail($strTo , "" , 0);

               $nemoEmail->Subject = $_POST[strSubject];
               $nemoEmail->EmailTemplateID = $_POST[refEmailTemplateID];
               $nemoEmail->arrBody[0] = strip_tags($_POST[txtBody]);
               $nemoEmail->arrBody[1] = mynl2br($_POST[txtBody]);

               //if($_POST[refEmailTemplateID] != 0)
               //{//enable substitution!
                  $rowContact = $xdb->getRowSQL("SELECT * FROM vieEmailContacts WHERE ViewID = ". $xdb->qs($strTo),0);
                  if($rowContact)
                  {
                     foreach($rowContact as $key => $value)
                     {
                        $arrValues[$key] = $value;
                     }
                     unset($arrValues[ViewID], $arrValues[strView]);//print_rr($arrValues);

                     $nemoEmail->Substitute($arrValues);
                  }
               //}

               if($_POST[strFrom] == ""){
                  $nemoEmail->addHeader("FROM", $page->SystemSettings["SMTP Send As"]);
                  $nemoEmail->From = $page->SystemSettings["SMTP Send As"];
               }
               else{
                  $nemoEmail->addHeader("FROM", $_POST[strFrom]);
                  $nemoEmail->From = $_POST[strFrom];
               }

               if($_POST[strCC] != ""){
                  $nemoEmail->addHeader("CC", $_POST[strCC]);
                  $nemoEmail->cc = $_POST[strCC];
               }

               $nemoEmail->addHeader("BCC", $page->SystemSettings["SMTP BCC"]);
               $nemoEmail->Bcc = $page->SystemSettings["SMTP BCC"];


      //set New EmailID: $EmailID = $nemoEmail->Send();
               $nemoEmail->arrFields[txtNotes] = $_POST[txtNotes] ." [i]=1"; // i = blnIndu
               $nemoEmail->arrFields[strLastUser] = $_SESSION['USER']->USERNAME;

               //add attachments
               if($_POST[arrAttachments] != "")
               {//new 20120817 - added template default attachments
                  $nemoEmail->arrFields[arrAttachments] = $_POST[arrAttachments];
                  foreach(explode(",", $_POST[arrAttachments]) as $i => $filename)
                  {               
                     $nemoEmail->addAttachment($SystemSettings[InvoicePdfDirAdmin].$filename, 1);
                  }
               }

               $result = $nemoEmail->Send();
               
         //print_rr($result);
               if($result->Error == 1)
               {//error on email save
                  $Message = $result->Message;
               }else{
                  $EmailID = $result->ID;
               }

            }//eoFE
         }else{


            $nemoEmail = new NemoEmail($_POST[strTo] , "" , 0);

            //$nemoEmail->SentFromDatabase(10,"exiledbandit@gmail.com"); die;

            //$nemoEmail->LoadEmailTemplate("Register");

            //print_r($_POST);

            $nemoEmail->Subject = $_POST[strSubject];
            $nemoEmail->EmailTemplateID = $_POST[refEmailTemplateID];
            $nemoEmail->arrBody[0] = strip_tags($_POST[txtBody]);
            $nemoEmail->arrBody[1] = mynl2br($_POST[txtBody]);

            //if($_POST[refEmailTemplateID] != 0)
            //{//enable substitution!
               $arrTo = explode(";", $_POST[strTo]);
               $strTo = $arrTo[0];
            
               $rowContact = $xdb->getRowSQL("SELECT * FROM vieEmailContacts WHERE ViewID = ". $xdb->qs($strTo),0);
               if($rowContact)
               {
                  foreach($rowContact as $key => $value)
                  {
                     $arrValues[$key] = $value;
                  }
                  unset($arrValues[ViewID], $arrValues[strView]);//print_rr($arrValues);
            
                  $nemoEmail->Substitute($arrValues);
               }
            //}

            if($_POST[strFrom] == ""){
               $nemoEmail->addHeader("FROM", $page->SystemSettings["SMTP Send As"]);
               $nemoEmail->From = $page->SystemSettings["SMTP Send As"];
            }
            else{
               $nemoEmail->addHeader("FROM", $_POST[strFrom]);
               $nemoEmail->From = $_POST[strFrom];
            }

            if($_POST[strCC] != ""){
               $nemoEmail->addHeader("CC", $_POST[strCC]);
               $nemoEmail->cc = $_POST[strCC];
            }

            $nemoEmail->addHeader("BCC", $page->SystemSettings["SMTP BCC"]);
            $nemoEmail->Bcc = $page->SystemSettings["SMTP BCC"];


   //set New EmailID: $EmailID = $nemoEmail->Send();
            $nemoEmail->arrFields[txtNotes] = $_POST[txtNotes] ." [i]=0";
            $nemoEmail->arrFields[strLastUser] = $_SESSION['USER']->USERNAME;
            
            //add attachments
            if($_POST[arrAttachments] != "")
            {//new 20120817 - added template default attachments
               $nemoEmail->arrFields[arrAttachments] = $_POST[arrAttachments];
               foreach(explode(",", $_POST[arrAttachments]) as $i => $filename)
               {               
                  $nemoEmail->addAttachment($SystemSettings[InvoicePdfDirAdmin].$filename, 1);
               }
            }
            
            //print_rr($nemoEmail); die;            
            $result = $nemoEmail->Send();
            //print_rr($result);
            if($result->Error == 1)
            {//error on email save
               $Message = $result->Message;
            }else{
               $EmailID = $result->ID;
            }
         }
         
         //die;

         //vd($EmailID);
         break;
      case "Delete":
         $Message = Email::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action)
   {
      case "New":
         $page = new NemoDetails();
         $page->Message->Text = $Message;

         $nemoEmail = new NemoEmail("","");

         $page->ToolBar->Buttons[btnSave]->blnShow = 0;
         $page->ToolBar->Buttons[btnSend]->blnShow = 1;
         $page->ToolBar->Buttons[btnSend]->Control->value = "Send";

         if($frEmailTemplateID == null) $frEmailTemplateID = -1; //load AdHoc template for ca only!

         $page->ContentLeft = $nemoEmail->EmailForm($frEmailTemplateID);
         
         break;
      case "Resend":
      case "Send":
      case "Save":
      case "Edit":
         $page = new NemoDetails();
         $page->Message->Text = $Message;
         $page->AssimulateTable("tblEmail", $EmailID, "UniqueID");
         
         $page->Fields["EmailID"]->Control->type = "hidden";

         $page->Fields["UniqueID"]->Control->class =
         $page->Fields["strTo"]->Control->class =
         $page->Fields["strFrom"]->Control->class =
         $page->Fields["strSubject"]->Control->class =
         $page->Fields["strCC"]->Control->class =
         $page->Fields["dtEmail"]->Control->class = "controlText controlLabel controlWide";

         $page->Fields["arrAttachments"]->Control->style = "display: none;";
         $page->Fields["arrAttachments"]->Control->comment = Email::renderAttachments($page->Fields["arrAttachments"]->VALUE);

         $page->Fields["dtEmail"]->Label = "Timestamp";

         if($EmailID == 0)
         {

         }else{

            $preview = "<div class='divPreview'>". Email::Preview($EmailID) . ($page->Security->blnSpecial == 1 ? Email::getResendControls($page->Fields["strTo"]->VALUE):"") ."</div>";
            
            if($page->Pages["email.php"]->Security->blnNew == 1)
               $page->ToolBar->Buttons[btnNew]->blnShow = 1;
            //$page->ToolBar->Buttons[btnNew2]->Control->value = "New Email";
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
         $page = new Email("");
         echo $page->getList();
         die;
         break;
      default:
         $page = new Email(array("EmailID"));
         $page->Message->Text = $Message;

         $page->isPageable = 1;
         $page->Content = $page->getList();

      
               break;
         }
//print_rr($page);
   $page->Display();


?>