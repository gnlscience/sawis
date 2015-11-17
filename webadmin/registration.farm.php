<?php

   ## INCLUDES
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("_framework/_nemo.wizzard.cls.php");
   include_once("includes/registration.farm.cls.php");
   
//$_SESSION[S2REGISTRATION]->FarmID = 59232000;
   // print_rr($_POST); 
   // print_rr($_SESSION[S2REGISTRATION]);
   //$_SESSION[WizzardStep] --;
   //die;
   ## ACTION
         
   switch ($Action) {
      case "Reset":
            unset($_SESSION[Wizzard]->ActiveParent); 
            unset($_SESSION[S2REGISTRATION]);
            $_SESSION["WizzardStep"] = 0;
            //windowlocation("registration.farm.php?Step=0"); //faaaaaaaaaaaaaaaaaaaaaaaaaak why!?
            break;
 
      case "Continue":
         break;
      
      case "Submit Application": 
      case $_TRANSLATION["EN"]["btnSubmit"]:
      case $_TRANSLATION["AF"]["btnSubmit"]:


         $row = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE FarmID = ". $_SESSION[S2REGISTRATION]->FarmID);

         RegistrationFarm::Submit($_SESSION[USER]->MEMBERID, $_SESSION[S2REGISTRATION]->FarmID);
         
         //print_rr($row->RegistrationType);die();
         switch ($row->RegistrationType) 
         {  
            
            case  "Buy":
            case  "SubDiv":
               windowlocation("registration.farm.php?Step=6");
               break;

            case  "New": 
               $_SESSION[S1REGISTRATION]->FarmID = $_SESSION[S2REGISTRATION]->FarmID;   
               windowLocation("registration.blocks.php?Step=0");
               break;

            case "Consolidate":
               $_SESSION[S1REGISTRATION]->FarmID = $_SESSION[S2REGISTRATION]->FarmID;   
               windowLocation("registration.blocks.php?Step=1&status=consolidate"); //consolidate = NO DELETE
               break;
         } 

         break;

      case "Next":
         if($_POST[saveData] == 1)
         {     
            RegistrationFarm::Save($_SESSION[S2REGISTRATION]->FarmID, $_SESSION[WizzardStep]); 

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

         break;
      case "Previous":

         if($_POST[saveData] == 1)
         {     
            RegistrationFarm::Save($_SESSION[S2REGISTRATION]->FarmID, $_SESSION[WizzardStep]); 

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
         break; 
   }
//   if($Action == "Next")
//die("cnt");
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

   //vd($_SESSION[WizzardStep]); //die;
   ## PRINT SESSION AND POST ( TESTING )
   // print_rr($_POST);
   // print_rr($_SESSION[S2REGISTRATION]);

   ## ADDITIONAL TRANSLATIONS
   $_TRANSLATION["EN"]["WizzardName"] = "SAWIS Farm Registration";
   $_TRANSLATION["AF"]["WizzardName"] = "SAWIS Plaasregestrasie";

   $_TRANSLATION["EN"]["LP_Heading"] = "SAWIS Farm Registration";
   $_TRANSLATION["AF"]["LP_Heading"] = "SAWIS Plaas regestrasie";
   $_TRANSLATION["EN"]["LP_Intro"] = "Welcome to the SAWIS Farm Registration site";
   $_TRANSLATION["AF"]["LP_Intro"] = "Welkom by SAWIS Plaas regestrasie webtuiste"; 

   $_TRANSLATION["EN"]["RT_Heading"] = "Registration Type";
   $_TRANSLATION["AF"]["RT_Heading"] = "Registrasie Tiepe";
   $_TRANSLATION["EN"]["RT_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["RT_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["FD_Heading"] = "Farm Details";
   $_TRANSLATION["AF"]["FD_Heading"] = "Plaas Besonderhede";
   $_TRANSLATION["EN"]["FD_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["FD_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data";  

   $_TRANSLATION["EN"]["TD_Heading"] = "Property Information";
   $_TRANSLATION["AF"]["TD_Heading"] = "Eiendom Inligting";
   $_TRANSLATION["EN"]["TD_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["TD_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["D_Heading"] = "Documents";
   $_TRANSLATION["AF"]["D_Heading"] = "Dokumente";
   $_TRANSLATION["EN"]["D_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["D_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["R_Heading"] = "Review";
   $_TRANSLATION["AF"]["R_Heading"] = "Hersien";
   $_TRANSLATION["EN"]["R_Intro"] = "This is just test data$BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["R_Intro"] = "AF: This is just test data$BR This is just test data$BR This is just test data"; 

   $_TRANSLATION["EN"]["C_Heading"] = "Member Registration Completed";
   $_TRANSLATION["AF"]["C_Heading"] = "Lidregestrasie is Voltooi";
   $_TRANSLATION["EN"]["C_Intro"] = "Thank you for $BR This is just test data$BR This is just test data";
   $_TRANSLATION["AF"]["C_Intro"] = "Dankie$BR This is just test data$BR This is just test data"; 

   ## SET UP WIZZARD STEPS
   $page->WizzardStep[0]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["LP_Heading"]; ## LANDING PAGE
   $page->WizzardStep[0]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["LP_Intro"];
   $page->WizzardStep[0]->validation = "validation"; 
   $page->WizzardStep[0]->group = "default"; 

   $page->WizzardStep[1]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["RT_Heading"]; ## REGISTRATION TYPE
   $page->WizzardStep[1]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["RT_Intro"];
   $page->WizzardStep[1]->validation = "validation";
   $page->WizzardStep[1]->group = "default"; 

   $page->WizzardStep[2]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["FD_Heading"]; ## FARM DETAILS
   $page->WizzardStep[2]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["FD_Intro"];
   $page->WizzardStep[2]->validation = "validation";
   $page->WizzardStep[2]->group = "default"; 
   
   $page->WizzardStep[3]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["TD_Heading"]; ## TITLE DEADS
   $page->WizzardStep[3]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["TD_Intro"];
   $page->WizzardStep[3]->validation = "validation"; 
   $page->WizzardStep[3]->group = "default"; 
   
   $page->WizzardStep[4]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["D_Heading"]; ## DOCUMENTS
   $page->WizzardStep[4]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["D_Intro"];
   $page->WizzardStep[4]->validation = "validation";
   $page->WizzardStep[4]->group = "default"; 

   $page->WizzardStep[5]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Heading"]; ## REVIEW
   $page->WizzardStep[5]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Intro"];
   $page->WizzardStep[5]->validation = "validation";
   $page->WizzardStep[5]->Finish = "1";
   $page->WizzardStep[5]->group = "default"; 

   $page->WizzardStep[6]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Heading"]; ## COMPLETION PAGE
   $page->WizzardStep[6]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Intro"];
   $page->WizzardStep[6]->validation = "validation";
   $page->WizzardStep[6]->HideMenu = "1";
   $page->WizzardStep[6]->group = "default";  
   switch($_SESSION["WizzardStep"])
   {
      case 0:
         $page->WizzardStep[0]->content = RegistrationFarm::getLandingPageContent();
         unset($_SESSION[S2REGISTRATION]);
         break;
      case 1:
         $page->WizzardStep[1]->content = RegistrationFarm::getRegistrationTypeContent();
         break;
      case 2:
         $page->WizzardStep[2]->content = RegistrationFarm::getFarmDetailsContent($_SESSION[S2REGISTRATION]->FarmID);
         break;
      case 3:
         $page->WizzardStep[3]->content = RegistrationFarm::getTitleDeadContent();
         break;
      case 4:
         $page->WizzardStep[4]->content = RegistrationFarm::getDocumentsContent();
         break;
      case 5:
         $page->WizzardStep[5]->content = RegistrationFarm::getReviewContent();
         break;
      case 6:         
         $page->WizzardStep[6]->content = RegistrationFarm::GetCompletedContent($row->RegistrationType);
         break;

   } 

   ## IF FARM ID NOT SET TAKE BACK TO FARM DETAILS
   if(($_GET[Step] == 3) || ($_GET[Step] == 4) || ($_GET[Step] == 5) || ($_GET[Step] == 6))
   {
      if(($_SESSION[S2REGISTRATION]->FarmID == "") || (!isset($_SESSION[S2REGISTRATION]->FarmID)))
      {
         windowLocation("registration.farm.php?Step=2");
      }
   }


   ## RENDER WIZZARD
   $pageRegister->Content = $page->renderWizzard($_TRANSLATION[$_SESSION[LANGUAGE]]["WizzardName"]);
   
   ## DISPLAY PAGE
   $pageRegister->Display();
   
?>