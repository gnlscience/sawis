<?php

   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/member.pending.cls.php");
   //include_once("includes/farm.cls.php");   
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
         $Message = MemberPending::Save($MemberID);
         break;
      case "Delete":
         $Message = MemberPending::Delete($_POST[chkSelect]);
         break;
      case MemberPending::$APPROVED_IN_PRINCIPLE:
         $Message = MemberPending::Save($MemberID);
         $Message .= MemberPending::ApproveInPrinciple($MemberID);
         windowLocation("member.php?Action=Edit&MemberID=$MemberID&Message=$Message");
         break;
      // case MemberPending::$NEW_BUSINESS_RELATIONSHIP:
      //    windowLocation("business.relationship.php?Action=New&MemberID=$MemberID&RETURN_URL=member.pending.php?Action=Edit&RETURN_VAR=MemberID");
      //    break;
      case MemberPending::$NEW_DOCUMENT:
         windowLocation("document.php?Action=New&MemberID=$MemberID&RETURN_URL=member.pending.php?Action=Edit&RETURN_VAR=MemberID&EntityID=$MemberID&EntityType=MemberTMP");
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

         $page->AssimulateTable("tmpMember", $MemberID, "strMember");

         $page->Fields["MemberID"]->Control->type = "hidden";

         unset($page->Fields["RegistrationArgs"]);

         $page->Fields["strMember"]->Control->class .= " controlWideMax";

         $page->Fields["dtRegistration"]->Label = "Date Registered";

         $page->Fields["RegistrationStatus"]->Control->type = "hidden";

         $page->Fields["refInspectorID"]->sql = Farm::sqlInspectorDDL();

         $page->Fields["strLegalEntityType"]->html->innerHTML = MemberPending::sqlMemberLegalEntityTypeDDL();
         
         if($MemberID != 0)
         {

            //20151110 - n/a TMP - pj
            // $myFarm = new Farm(array("FarmID"));
            // $myFarm->isPageable = 0;
            // $myFarm->isSortable = 0;

            // $page->ContentRight .= 
            //    str_replace("chkSelect","chkSelectF",$myFarm->getMemberFarms($MemberID)) . $BR;

            //20151110 - n/a TMP - pj
            $myBR = new BusinessRelationship(array("ID"));
            $myBR->isPageable = 0;
            $myBR->isSortable = 0;
            $myBR->isSelectable = 0;
            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectBR", $myBR->getListTMP($MemberID)) . $BR;

            //20151110 - ???
            //change to use TMP
            $myDoc = new Document(array("DocumentID"));
            $myDoc->isPageable = 0;
            $myDoc->isSortable = 0;
            $myDoc->isSelectable = 0;
            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectD",$myDoc->getMemberDocsTMP($MemberID, "member.pending.php")) . $BR;

            // $myUser = new User(array("UserID"));
            // $myUser->isPageable = 0;
            // $myUser->isSortable = 0;
            // $myUser->isSelectable = 0;
            // $page->Content .= 
            //    str_replace("chkSelect","chkSelectU",$myUser->getMemberUserList($MemberID)) . $BR;

            //btnNewFarm
            // $page->ToolBar->Buttons[btnNewF] = nCopy($page->ToolBar->Buttons[btnNew]);
            // $page->ToolBar->Buttons[btnNewF]->intOrder += 0.1;
            // $page->ToolBar->Buttons[btnNewF]->Control->value = MemberPending::$NEW_FARM;
            // $page->ToolBar->Buttons[btnNewF]->Control->id = "btnNewF";
            // $page->ToolBar->Buttons[btnNewF]->blnShow = 1;

            //btnBR
            // $page->ToolBar->Buttons[btnNewBR] = nCopy($page->ToolBar->Buttons[btnNew]);
            // $page->ToolBar->Buttons[btnNewBR]->intOrder += 0.1;
            // $page->ToolBar->Buttons[btnNewBR]->Control->value = MemberPending::$NEW_BUSINESS_RELATIONSHIP;
            // $page->ToolBar->Buttons[btnNewBR]->Control->id = "btnNewBR";
            // $page->ToolBar->Buttons[btnNewBR]->blnShow = 1;

            //btnU
            // $page->ToolBar->Buttons[btnNewU] = nCopy($page->ToolBar->Buttons[btnNew]);
            // $page->ToolBar->Buttons[btnNewU]->intOrder += 0.1;
            // $page->ToolBar->Buttons[btnNewU]->Control->value = MemberPending::$NEW_USER;
            // $page->ToolBar->Buttons[btnNewU]->Control->id = "btnNewU";
            // $page->ToolBar->Buttons[btnNewU]->blnShow = 1;

            //btnD
            $page->ToolBar->Buttons[btnNewD] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewD]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewD]->Control->value = MemberPending::$NEW_DOCUMENT;
            $page->ToolBar->Buttons[btnNewD]->Control->id = "btnNewD";
            $page->ToolBar->Buttons[btnNewD]->blnShow = 1;

            //btnApproveinPrinciple
            $page->ToolBar->Buttons[btnNewA] = nCopy($page->ToolBar->Buttons[btnNew2]);
            $page->ToolBar->Buttons[btnNewA]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewA]->Control->value = MemberPending::$APPROVED_IN_PRINCIPLE;
            $page->ToolBar->Buttons[btnNewA]->Control->id = "btnNewA";
            $page->ToolBar->Buttons[btnNewA]->blnShow = 1;
            $page->ToolBar->Buttons[btnNewA]->Control->onclick = $page->ToolBar->Buttons[btnSave]->Control->onclick;
                
            //print_rr($page->ToolBar->Buttons);
         }

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label).$page->getJsNemoValidateSave()."<input type='hidden' name='EntityType' value='MemberTMP'>";
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new MemberPending("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new MemberPending(array("MemberID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>