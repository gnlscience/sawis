<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/farm.cls.php");
   include_once("includes/registration.farm.cls.php"); //for referance $_TRANSLATION only

   $_TRANSLATION["EN"]["validationAccept"] = "Are you sure you want to ACCEPT this Transfer?";
   $_TRANSLATION["AF"]["validationAccept"] = "AF: Are you sure you want to ACCEPT this Transfer?";
   $_TRANSLATION["EN"]["validationDecline"] = "Are you sure you want to DECLINE this Transfer?";
   $_TRANSLATION["AF"]["validationDecline"] = "AF: Are you sure you want to DECLINE this Transfer?";
   $_TRANSLATION["EN"]["btnAccept"] = "Accept";
   $_TRANSLATION["AF"]["btnAccept"] = "AF: Accept";
   $_TRANSLATION["EN"]["btnDecline"] = "Decline";
   $_TRANSLATION["AF"]["btnDecline"] = "AF: Decline";
   $_TRANSLATION["EN"]["lblNotes"] = "Notes";
   $_TRANSLATION["AF"]["lblNotes"] = "Notas";

   $page = new Nemo();

//events 
   switch($Action){
      case "Reload":
      case "Herlaai":
         windowLocation("?Action=Edit&FarmID=$FarmID");
         break;
      case "Accept":
      case $_TRANSLATION["EN"]["btnAccept"]:
      case $_TRANSLATION["AF"]["btnAccept"]:

         ## COPY BLOCKS TO TMP WITH UPDATED REF FARM ID
         $row = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE RegistrationArgs LIKE ('%\"". $FarmID ."\"%') AND strStatus NOT IN('Active')");
         $rowMember = $xdb->getRowSQL("SELECT * FROM tblMember WHERE MemberID = ".$_SESSION[USER]->MEMBERID);
         
         CopyBlocks($FarmID, $row->FarmID);

         ## CREATE TEMP MEMBER RELATIONSHIP
         $xdb->doQuery("INSERT INTO tmpMemberRelationship (strType, refMemberID, refEntityID, refMemberTypeID, strLastUser)
                        VALUES (Farm to Member, value2, $row->FarmID, $rowMember->refMemberTypeID, 'System') ");

         ## SEND NOTIFICATION TO ADMIN FOR APPROVAL
         $Message = Farm::sendTransferAcceptNotification($FarmID);
         break;

      case "Decline":
      case $_TRANSLATION["EN"]["btnDecline"]:
      case $_TRANSLATION["AF"]["btnDecline"]:
         $Message = Farm::sendTransferDeclineNotification($FarmID);
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
         
         
         $page->ContentLeft = Farm::getFarmDetailsForm($FarmID);

        // echo json_encode($_SESSION);exit;


         $page->ContentRight = "
            <h3 style='color:#51732c'><u>". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["lblNotes"] .": </u></h3>
            ".$SystemSettings["strFarmTransferNotes_". $_SESSION[USER]->LANGUAGE] ."
            $BR
            <div style='height:100%;vertical-align:bottom;'>
               <div style='vertical-align:bottom;'>
                  <input type='submit' name='Action' id='btnAccept' class='controlButton' value='". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["btnAccept"] ."' 
                     onClick='return confirm(\"". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["validationAccept"] ."\")'>$SP$SP
                  <input type='submit' name='Action' id='btnDecline' class='controlButton' value='". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["btnDecline"] ."' 
                     onClick='return confirm(\"". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["validationDecline"] ."\")'>
               </div>
            </div>
            
            <input type=hidden name=FarmID value='$FarmID' />";

         break;   
      default:
         $page = new Farm(array("FarmID"));
         unset($page->Filters);
         $page->isPageable = 0;
         $page->isSelectable = 0;
         $page->ToolBar->Buttons[btnExport]->blnShow = 0;
         
         $page->Content = $page->getFarmTransferList($_SESSION[USER]->MEMBERID);
         
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>