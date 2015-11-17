<?php

   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/member.cls.php");
   include_once("includes/farm.cls.php");   
   include_once("includes/user.cls.php");   
   include_once("includes/business.relationship.cls.php"); 
   include_once("includes/document.cls.php"); 

   $page = new Nemo();
   
//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&MemberID=$MemberID");
         break;
      case "Save":
         $Message = Member::Save($MemberID);
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
      default:
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;
         $page->AssimulateTable("tblMember", $_SESSION['USER']->MEMBERID, "strMember");

         $page->Fields["MemberID"]->Control->type = "hidden";

         $page->Fields["strMember"]->Control->class .= " controlWideMax";

         $page->Fields["dtRegistration"]->Label = "Date Registered";

         $page->Fields["RegistrationStatus"]->Control->type = "hidden";

         $page->Fields["RegistrationArgs"]->Control->type = "hidden";
         unset($page->Fields["RegistrationArgs"]);
         unset($page->Fields["strStatus"]);

        
         $page->Fields["strLegalEntityType"]->html->innerHTML = Member::sqlMemberLegalEntityTypeDDL();
         
         $page->Fields["refInspectorID"]->sql = Farm::sqlInspectorDDL();

         if($_SESSION['USER']->MEMBERID != 0)
         {
            $myFarm = new Farm(array("FarmID"));
            $myFarm->isPageable = 0;
            $myFarm->isSortable = 0;
            $myFarm->isSelectable = 0;

            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectF",$myFarm->getMemberFarms($_SESSION['USER']->MEMBERID)) . $BR;

            $myBR = new BusinessRelationship(array("ID"));
            $myBR->isPageable = 0;
            $myBR->isSortable = 0;
            $myBR->isSelectable = 0;
            $page->ContentRight .= 
                str_replace("chkSelect","chkSelectBR",$myBR->getList($_SESSION['USER']->MEMBERID)) . $BR 
               .str_replace("chkSelect","chkSelectBR",$myBR->getListTMP($_SESSION['USER']->MEMBERID)) . $BR;

            $myDoc = new Document(array("DocumentID"));
            $myDoc->isPageable = 0;
            $myDoc->isSortable = 0;
            $myDoc->isSelectable = 0;
            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectD",$myDoc->getLoggedMemberDocs("member.profile.php")) . $BR;

            $myUser = new User(array("UserID"));
            $myUser->isPageable = 0;
            $myUser->isSortable = 0;
            $myBR->isSelectable = 0;

            $page->Content .= 
               str_replace("chkSelect","chkSelectU",$myUser->getMemberUserList($_SESSION['USER']->MEMBERID)) . $BR;

            $btnNewFarm = nCopy($page->ToolBar->Buttons[btnNew]);
            $btnNewFarm = nCopy($page->ToolBar->Buttons[btnNew]);
            $btnNewFarm = nCopy($page->ToolBar->Buttons[btnNew]);

            //btnNewFarm
            $page->ToolBar->Buttons[btnNewF] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewF]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewF]->Control->value = Member::$NEW_FARM;
            $page->ToolBar->Buttons[btnNewF]->Control->id = "btnNewF";
            $page->ToolBar->Buttons[btnNewF]->blnShow = 1;



            //btnD
            $page->ToolBar->Buttons[btnNewD] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewD]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewD]->Control->value = Member::$NEW_DOCUMENT;
            $page->ToolBar->Buttons[btnNewD]->Control->id = "btnNewD";
            $page->ToolBar->Buttons[btnNewD]->blnShow = 1;
            
            //print_rr($page->ToolBar->Buttons);
         }

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;   
      case Member::$NEW_DOCUMENT:
         $page = new NemoDetails();
     
         $myDocs = new Document(array("DocumentID"));
     
         $page->renderControls();
         //security for users not see each toher's docs
         if($_SESSION['USER']->MEMBERID == $MemberID){
            $page->ContentLeft = "<input type='hidden' name='RETURN_URL' value='$RETURN_URL'></form>".$myDocs::getUploadForm('MEMBER',$_SESSION['USER']->MEMBERID);
            
            break;
         }                  
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
