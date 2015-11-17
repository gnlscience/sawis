<?php

   ## INCLUDES
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("_framework/_nemo.wizzard.cls.php");
   include_once("includes/registration.blocks.cls.php");

   ## PRINT SESSION AND POST ( TESTING )
   //print_rr($_POST);
   //print_rr($_SESSION[S2REGISTRATION]); 

     ## SET FARM ID ::

   if(($_SESSION[S1REGISTRATION]->FarmID == "") || (!isset($_SESSION[S1REGISTRATION]->FarmID)))
   {
      windowLocation("registration.farm.php?Step=2");
   }
   else
   {
      $FarmID = $_SESSION[S1REGISTRATION]->FarmID;  
   }
   print_rr($FarmID); 
   ## ACTION 
   switch ($Action) {

      // case "Continue": 
      // case $_TRANSLATION["EN"]["btnContinue"]:
      // case $_TRANSLATION["AF"]["btnContinue"]: 
      //    RegistrationFarmBlocks::SaveSubDivision($FarmID, $_SESSION[WizzardStep]); 
      //    break;
      
      case "Submit Application": 
      case $_TRANSLATION["EN"]["btnSubmit"]:
      case $_TRANSLATION["AF"]["btnSubmit"]:
         RegistrationFarmBlocks::SubmitSubDivisionBlocks($FarmID); 
         windowLocation("subdivision.blocks.php?Step=3");
         break;

      case "Next":    
      case $_TRANSLATION["EN"]["btnNext"]:
      case $_TRANSLATION["AF"]["btnNext"]:
         RegistrationFarmBlocks::SaveSubDivision($FarmID, $_SESSION[WizzardStep]); 
         break;

      case "Previous":  
      case $_TRANSLATION["EN"]["btnPrevious"]:
      case $_TRANSLATION["AF"]["btnPrevious"]:
         //unset($Action);  
         break; 
   }

   ## IMAGE REQUIRED
   $imgRequired = $SystemSettings[imgRequired];
   
   ## CURRENT LANGUAGE
   if(!isset($_SESSION[LANGUAGE]))
   {
      $_SESSION[LANGUAGE] = "EN";
   }  

   ## START NEMO BASIC
   $pageRegister = new Nemo();
  
   ## START NEMO WIZZARD
   $page = new NemoWizzard();

   ## ADDITIONAL TRANSLATIONS
   $_TRANSLATION["EN"]["WizzardName"] = "SAWIS Vineyard Block Subdivision";
   $_TRANSLATION["AF"]["WizzardName"] = "SAWIS Wingerdblok Onderverdeling";

   $_TRANSLATION["EN"]["G_Heading"] = "Block Subdivision";
   $_TRANSLATION["AF"]["G_Heading"] = "Hersien";
   $_TRANSLATION["EN"]["G_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["G_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["R_Heading"] = "Review Subdivision of Farm Blocks";
   $_TRANSLATION["AF"]["R_Heading"] = "AF: Review Subdivision of Farm Blocks";
   $_TRANSLATION["EN"]["R_Intro"] = "Thank you for $BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["R_Intro"] = "Dankie$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["C_Heading"] = "Subdivision of Farm Blocks Completed";
   $_TRANSLATION["AF"]["C_Heading"] = "AF: Subdivision of Farm Blocks Completed";
   $_TRANSLATION["EN"]["C_Intro"] = "Thank you for $BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["C_Intro"] = "Dankie$BR This is just test data$BR This is just test data"; 

   ## SET UP WIZZARD STEPS
   $page->WizzardStep[1]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["G_Heading"]; ## REVIEW
   $page->WizzardStep[1]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["G_Intro"];
   //$page->WizzardStep[0]->content = RegistrationFarmBlocks::getReviewContent($FarmID);
   //$page->WizzardStep[0]->validation = "validation";
   //$page->WizzardStep[0]->Finish = "1";
   $page->WizzardStep[1]->group = "default"; 

   $page->WizzardStep[2]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Heading"]; ## COMPLETION PAGE
   $page->WizzardStep[2]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Intro"];
   $page->WizzardStep[2]->Finish = "1";
   //$page->WizzardStep[1]->content = RegistrationFarmBlocks::GetCompletedContent($FarmID);
   //$page->WizzardStep[1]->validation = "validation";
   //$page->WizzardStep[1]->HideMenu = "1";
   $page->WizzardStep[2]->group = "default"; 
   
   $page->WizzardStep[3]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Heading"]; ## COMPLETION PAGE
   $page->WizzardStep[3]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Intro"]; 
   $page->WizzardStep[3]->HideMenu = "1";
   $page->WizzardStep[3]->group = "default"; 
   

   $arrBlockStatus = $_POST[arrKeepBlock];
   $arrBlockIDs = $_POST[arrID];
  
  //print_rr($arrKeepBlocks);//die();

    switch($_SESSION["WizzardStep"])
   {
      case 1:
         $page->WizzardStep[1]->content = RegistrationFarmBlocks::getSubdivisionContent($FarmID);
         break;
      case 2:
         $page->WizzardStep[2]->content = RegistrationFarmBlocks::reviewSubdivisionContent($FarmID,$arrBlockStatus,$arrBlockIDs);
         break;
      case 3:
         $page->WizzardStep[3]->content = RegistrationFarmBlocks::GetCompletedSubDivisionContent($FarmID);
         break;

   }

   ## RENDER WIZZARD
   $pageRegister->Content = $page->renderWizzard($_TRANSLATION[$_SESSION[LANGUAGE]]["WizzardName"]);

   ## DISPLAY PAGE
   $pageRegister->Display();
   die;
?>