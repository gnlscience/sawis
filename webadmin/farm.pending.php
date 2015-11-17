<?php

   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/farm.pending.cls.php");
   include_once("includes/block.cls.php");   
   include_once("includes/farm.cls.php");   
   include_once("includes/user.cls.php");   
   include_once("includes/business.relationship.cls.php"); 
   include_once("includes/document.cls.php"); 

   $page = new Nemo();
//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&FarmID=$FarmID");
         break;
      case "Save":
         $Message = FarmPending::Save($FarmID);
         break;
      case "Delete":
         $Message = FarmPending::Delete($_POST[chkSelect]);
         break;
      case FarmPending::$NEW_DOCUMENT:
         windowLocation("document.php?Action=New&FarmID=$FarmID&RETURN_URL=farm.pending.php?Action=Edit&RETURN_VAR=FarmID&EntityID=$FarmID&EntityType=FarmTMP");
         break;        
      case FarmPending::$APPROVED_IN_PRINCIPLE:
         $Message = FarmPending::Save($FarmID);
         $Message .= FarmPending::ApproveInPrinciple($FarmID);
         //redirect to Farm details (proper)
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

         $page->AssimulateTable("tmpFarm", $FarmID, "strMember");

         $page->Fields["FarmID"]->Control->type = "hidden";

         $page->Fields["refInspectorID"]->sql = Farm::sqlInspectorDDL();
                
         $page->Fields["dtRegistered"]->Label = "Date Registered";
         $page->Fields["RegistrationStatus"]->Control->type = "hidden";

         $page->Fields["RegistrationArgs"]->Control->type = "hidden";
         unset($page->Fields["RegistrationArgs"]);

         $page->Fields["RegistrationType"]->Control->type = "hidden";
         unset($page->Fields["RegistrationType"]);   

         if($FarmID != 0)
         {

            $myDoc = new Document(array("DocumentID"));
            $myDoc->isPageable = 0;
            $myDoc->isSortable = 0;
            $myDoc->isSelectable = 0;
            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectD",$myDoc->getFarmDocsTMP($FarmID, "farm.pending.php")) . $BR;


            $myFarm = new Block(array("ID"));
            $myFarm->isPageable = 0;
            $myFarm->isSortable = 0;
            $myFarm->isSelectable = 0;

            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectF",$myFarm->getFarmBlocks($FarmID)) . $BR;



            //Form Member Relationships
            $myFarmRelationship = new BusinessRelationship(array("ID"));
            $myFarmRelationship->isPageable = 0;
            $myFarmRelationship->isSortable = 0;
            $myFarmRelationship->isSelectable = 0;

            $page->ContentRight .= 
                 str_replace("chkSelect","chkSelectF",$myFarmRelationship->getList(null, $FarmID)) . $BR
                .str_replace("chkSelect","chkSelectF",$myFarmRelationship->getListTMP(null, $FarmID)) . $BR;               




            //btnD
            $page->ToolBar->Buttons[btnNewD] = nCopy($page->ToolBar->Buttons[btnNew]);
            $page->ToolBar->Buttons[btnNewD]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewD]->Control->value = FarmPending::$NEW_DOCUMENT;
            $page->ToolBar->Buttons[btnNewD]->Control->id = "btnNewD";
            $page->ToolBar->Buttons[btnNewD]->blnShow = 1;

            // //btnApproveinPrinciple
            $page->ToolBar->Buttons[btnNewA] = nCopy($page->ToolBar->Buttons[btnNew2]);
            $page->ToolBar->Buttons[btnNewA]->intOrder += 0.1;
            $page->ToolBar->Buttons[btnNewA]->Control->value = FarmPending::$APPROVED_IN_PRINCIPLE;
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
         $page = new FarmPending("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new FarmPending(array("FarmID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>