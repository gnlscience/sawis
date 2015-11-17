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
      case "Delete":
         $Message = Member::Delete($_POST[chkSelect]);
         break;
      case Member::$NEW_FARM:
         windowLocation("farm.php?Action=New&MemberID=$MemberID&RETURN_URL=member.php?Action=Edit&RETURN_VAR=MemberID");
         break;
      case Member::$NEW_USER:
         windowLocation("user.php?Action=New&MemberID=$MemberID&RETURN_URL=member.php?Action=Edit&RETURN_VAR=MemberID");
         break;
      case Member::$NEW_BUSINESS_RELATIONSHIP:
         windowLocation("business.relationship.php?Action=New&MemberID=$MemberID&RETURN_URL=member.php?Action=Edit&RETURN_VAR=MemberID");
         break;
      case Member::$NEW_DOCUMENT:
         windowLocation("document.php?Action=New&MemberID=$MemberID&RETURN_URL=member.php?Action=Edit&RETURN_VAR=MemberID");
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
         $page->AssimulateTable("tblMember", $MemberID, "strMember");

         $page->Fields["MemberID"]->Control->type = "hidden";

         $page->Fields["strMember"]->Control->class .= " controlWideMax";

         $page->Fields["dtRegistration"]->Label = "Date Registered";

         $page->Fields["RegistrationStatus"]->Control->type = "hidden";

         $page->Fields["RegistrationArgs"]->Control->type = "hidden";
         unset($page->Fields["RegistrationArgs"]);
        
         $page->Fields["strLegalEntityType"]->html->innerHTML = Member::sqlMemberLegalEntityTypeDDL();
         
         $page->Fields["refInspectorID"]->sql = Farm::sqlInspectorDDL();

          /* $page->Fields["refInspectorID"]->Control->type = "";
         $page->Fields["refInspectorID"]->Control->tag = "Select";
         $page->Fields["refInspectorID"]->Control->class = "controlText";
         $page->Fields["refInspectorID"]->sql = "";
         $page->Fields["refInspectorID"]->html->innerHTML = Farm::sqlInspectorDDL();*/

         /*$page->Fields["refInspectorID"]->tag = "select";
         $page->Fields["refInspectorID"]->html->value = "0"; 
         $page->Fields["refInspectorID"]->html->class = "controlText";
         $page->Fields["refInspectorID"]->sql = "";
         $page->Fields["refInspectorID"]->html->innerHTML = Farm::sqlInspectorDDL();*/
         //print_rr($page->Fields["refInspectorID"]); //die;

         if($MemberID != 0)
         {
            $myFarm = new Farm(array("FarmID"));
            $myFarm->isPageable = 0;
            $myFarm->isSortable = 0;
            $myFarm->isSelectable = 0;

            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectF",$myFarm->getMemberFarms($MemberID)) . $BR;

            $myBR = new BusinessRelationship(array("ID"));
            $myBR->isPageable = 0;
            $myBR->isSortable = 0;
            $myBR->isSelectable = 0;
            $page->ContentRight .= 
                str_replace("chkSelect","chkSelectBR",$myBR->getList($MemberID)) . $BR 
               .str_replace("chkSelect","chkSelectBR",$myBR->getListTMP($MemberID)) . $BR;

            $myDoc = new Document(array("DocumentID"));
            $myDoc->isPageable = 0;
            $myDoc->isSortable = 0;
            $myDoc->isSelectable = 0;
            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectD",$myDoc->getMemberDocs($MemberID, "member.php")) . $BR;

            $myUser = new User(array("UserID"));
            $myUser->isPageable = 0;
            $myUser->isSortable = 0;
            $myBR->isSelectable = 0;

            $page->Content .= 
               str_replace("chkSelect","chkSelectU",$myUser->getMemberUserList($MemberID)) . $BR;

            $btnNewFarm = nCopy($page->ToolBar->Buttons[btnNew]);
            $btnNewFarm = nCopy($page->ToolBar->Buttons[btnNew]);
            $btnNewFarm = nCopy($page->ToolBar->Buttons[btnNew]);

            //btnNewFarm
            $page->ToolBar->Buttons[btnNewF] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewF]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewF]->Control->value = Member::$NEW_FARM;
            $page->ToolBar->Buttons[btnNewF]->Control->id = "btnNewF";
            $page->ToolBar->Buttons[btnNewF]->blnShow = 1;

            //btnBR
            $page->ToolBar->Buttons[btnNewBR] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewBR]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewBR]->Control->value = Member::$NEW_BUSINESS_RELATIONSHIP;
            $page->ToolBar->Buttons[btnNewBR]->Control->id = "btnNewBR";
            $page->ToolBar->Buttons[btnNewBR]->blnShow = 1;

            //btnU
            $page->ToolBar->Buttons[btnNewU] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewU]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewU]->Control->value = Member::$NEW_USER;
            $page->ToolBar->Buttons[btnNewU]->Control->id = "btnNewU";
            $page->ToolBar->Buttons[btnNewU]->blnShow = 1;

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
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new Member("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new Member(array("MemberID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
