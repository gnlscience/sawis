<?php

   ## INCLUDES
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("_framework/_nemo.wizzard.cls.php");
   include_once("includes/registration.blocks.cls.php");

   ## PRINT SESSION AND POST ( TESTING )
    //print_rr($_POST);

   ## START NEMO BASIC
   $pageRegister = new Nemo();

   if($_SESSION[S1REGISTRATION]->FarmID != 0){
      $FarmID = $_SESSION[S1REGISTRATION]->FarmID;  
   }
  //$FarmID = "59117000";
   if($FarmID == 0)
   {
      $pageRegister->Content = "No farm selected";
      $pageRegister->Display();
      die;
   }else{
      $_SESSION[S1REGISTRATION]->FarmID = $FarmID;
   }
   //print_rr($_SESSION[S1REGISTRATION]);
 

   ## ACTION 
   switch ($Action) {

      case "Continue": 
      case $_TRANSLATION["EN"]["btnContinue"]:
      case $_TRANSLATION["AF"]["btnContinue"]: 
         RegistrationFarmBlocks::Save($FarmID, $_SESSION[WizzardStep]); 
         break;
      
      case "Submit Application": 
      case $_TRANSLATION["EN"]["btnSubmit"]:
      case $_TRANSLATION["AF"]["btnSubmit"]:
         RegistrationFarmBlocks::SubmitBlocks($FarmID); 
         windowLocation("registration.blocks.php?Step=3&status=Buy");
         break;

      case "Next":    
      case $_TRANSLATION["EN"]["btnNext"]:
      case $_TRANSLATION["AF"]["btnNext"]:
         if($_POST[saveData] == 1)
         {     

            RegistrationFarmBlocks::Save($FarmID, $_SESSION[WizzardStep]); 

            if($_SESSION[WizzardStep] != 1)
            {
               if(!isset($_SESSION[S3REGISTRATION]->MemberID))
               {
                  $_SESSION[S3REGISTRATION]->MemberID = $MemberID;
               }
            }
         }

         if(isset($_POST[StoreData]))
         {
            $_SESSION["StoredData"] == $_POST[StoreData];
         }

         unset($Action);

         break;

      case "Previous":  
      case $_TRANSLATION["EN"]["btnPrevious"]:
      case $_TRANSLATION["AF"]["btnPrevious"]:
         unset($Action);  
         break; 
   }

   ## IMAGE REQUIRED
   $imgRequired = $SystemSettings[imgRequired];
   
   ## CURRENT LANGUAGE
   if(!isset($_SESSION[LANGUAGE]))
   {
      $_SESSION[LANGUAGE] = "EN";
   }  

  
   ## START NEMO WIZZARD
   $page = new NemoWizzard();

   ## ADDITIONAL TRANSLATIONS
   $_TRANSLATION["EN"]["WizzardName"] = "SAWIS Vineyard Block Registration";
   $_TRANSLATION["AF"]["WizzardName"] = "SAWIS Wingerdblok Regestrasie";

   $_TRANSLATION["EN"]["S_Heading"] = "SAWIS Vineyard Block Registration";
   $_TRANSLATION["AF"]["S_Heading"] = "SAWIS Wingerdblok Regestrasie";
   $_TRANSLATION["EN"]["S_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["S_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["BS_Heading"] = "Vineyard Block Setup";
   $_TRANSLATION["AF"]["BS_Heading"] = "Wingerdblok Setup";
   $_TRANSLATION["EN"]["BS_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["BS_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data";  

   $_TRANSLATION["EN"]["R_Heading"] = "Review";
   $_TRANSLATION["AF"]["R_Heading"] = "Hersien";
   $_TRANSLATION["EN"]["R_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["R_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["C_Heading"] = "Vineyard Block Registration Completed";
   $_TRANSLATION["AF"]["C_Heading"] = "Wingerdblok registrasie is Voltooid";
   $_TRANSLATION["EN"]["C_Intro"] = "Thank you for $BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["C_Intro"] = "Dankie$BR This is just test data$BR This is just test data"; 

   ## SET UP WIZZARD STEPS
   $page->WizzardStep[0]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["S_Heading"]; ## LANDING PAGE
   $page->WizzardStep[0]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["S_Intro"];
   //$page->WizzardStep[0]->content = RegistrationFarmBlocks::getLandingPageContent();
   //$page->WizzardStep[0]->validation = "validation"; 
   $page->WizzardStep[0]->group = "default"; 

   $page->WizzardStep[1]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["BS_Heading"]; ## REGISTRATION TYPE
   $page->WizzardStep[1]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["BS_Intro"];
   //$page->WizzardStep[1]->content = RegistrationFarmBlocks::getBlockSetupTypeContent($FarmID);
   //$page->WizzardStep[1]->validation = "validation";
   $page->WizzardStep[1]->group = "default"; 

   $page->WizzardStep[2]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Heading"]; ## REVIEW
   $page->WizzardStep[2]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Intro"];
   //$page->WizzardStep[2]->content = RegistrationFarmBlocks::getReviewContent($FarmID);
   //$page->WizzardStep[2]->validation = "validation";
   $page->WizzardStep[2]->Finish = "1";
   $page->WizzardStep[2]->group = "default"; 

   $page->WizzardStep[3]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Heading"]; ## COMPLETION PAGE
   $page->WizzardStep[3]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Intro"];
   //$page->WizzardStep[3]->content = RegistrationFarmBlocks::GetCompletedContent($FarmID);
   //$page->WizzardStep[3]->validation = "validation";
   $page->WizzardStep[3]->HideMenu = "1";
   $page->WizzardStep[3]->group = "default"; 

   //print_r($FarmID);
    switch($_SESSION["WizzardStep"])
   {
      case 0:
         $page->WizzardStep[0]->content = RegistrationFarmBlocks::getLandingPageContent();
         break;
      case 1:
         $page->WizzardStep[1]->content = RegistrationFarmBlocks::getBlockSetupTypeContent($FarmID);
         break;
      case 2:
         $page->WizzardStep[2]->content = RegistrationFarmBlocks::getReviewContent($FarmID);
         break;
      case 3:
         $page->WizzardStep[3]->content = RegistrationFarmBlocks::GetCompletedContent($FarmID);
         break;

   }

   ## RENDER WIZZARD
   $pageRegister->Content = $page->renderWizzard($_TRANSLATION[$_SESSION[LANGUAGE]]["WizzardName"]);

   ## DISPLAY PAGE
   $pageRegister->Display();
   die;
?>
