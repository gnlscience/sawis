<?php

   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/member.cls.php");
   include_once("includes/farm.cls.php");   
   include_once("includes/block.cls.php");   
   include_once("includes/user.cls.php");   
   include_once("includes/business.relationship.cls.php"); 
   include_once("includes/document.cls.php"); 

   $page = new Nemo();
 // echo  $Action;exit;
//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&FarmID=$FarmID");
         break;
      case "Save":
         $Message = Farm::Save($MemberID);
         break;   
   }

//redirect
   if($_POST[RETURN_URL] != "")
   {
      $url = $_POST[RETURN_URL] ."&". $_POST[RETURN_VAR] ."=". $$_POST[RETURN_VAR];
      windowLocation($url);
   }
   
//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblFarm", $FarmID, "strMember");

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
               str_replace("chkSelect","chkSelectD",$myDoc->getFarmDocs($FarmID, "my.farms.php?Action=Edit&FarmID=$FarmID", 'my.farms.php', 'New Document')) . $BR;


            $myFarm = new Block(array("ID"));
            $myFarm->isPageable = 0;
            $myFarm->isSortable = 0;
            $myFarm->isSelectable = 0;

            $page->ContentRight .= 
               str_replace("chkSelect","chkSelectF",$myFarm->getFarmBlocks($FarmID,1,"my.farms?")) . $BR;



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
            $page->ToolBar->Buttons[btnNewD]->Control->value = "New Document";
            $page->ToolBar->Buttons[btnNewD]->Control->id = "btnNewD";
            $page->ToolBar->Buttons[btnNewD]->blnShow = 1;


                
            //print_rr($page->ToolBar->Buttons);
         }

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label).$page->getJsNemoValidateSave()."<input type='hidden' name='EntityType' value='MemberTMP'>";
         break;
      case "New Document":
         $page = new NemoDetails();
     
         $myDocs = new Document(array("FarmID"));
     
         $page->renderControls();
         //security for users not see each toher's docs
            $page->ContentLeft = "<input type='hidden' name='RETURN_URL' value='$RETURN_URL'></form>".$myDocs::getUploadForm('FARM',$FarmID);
            break;                            
      default:
         $page = new Farm(array("FarmID"));
         $page->isPageable = 1;
         $page->Content = $page->getMemberFarms($_SESSION['USER']->MEMBERID,'my.farms.php','New Document');
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
