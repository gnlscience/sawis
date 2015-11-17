<?php

   ## INCLUDES
   include_once("_framework/_nemo.basic.cls.php");
   include_once("_framework/_nemo.wizzard.cls.php");
   include_once("includes/member.registration.cls.php");

   include_once("_framework/_nemo.email.cls.php");

   ## SESSION TESTING VARS
   // unset($_SESSION);
   // session_destroy(); 
    
   if($_POST[btnConinueLink] == 1)
   {
      $Action = "Continue Link";
   }
   
   ## NAV EVENTS SWITCH
   switch ($Action) {
      case "Reset":
            unset($_SESSION[Wizzard]->ActiveParent);
            unset($_SESSION[S3REGISTRATION]); 
            $_SESSION["WizzardStep"] = 0;
            windowlocation("registration.member.php");
            break;

      case "Continue Link":
         $blnNewUserExistingMemberContinue = MemberRegistration::SubmitLink($_SESSION[S3REGISTRATION]->UserID, qs($_POST[MemberID])); 
         windowLocation("registration.member.php?Step=7");

         //windowLocation("registration.member.php?Step=7&type=ConfirmationSend");
         //windowLocation("registration.member.php?Step=7&type=NewSuperUserAssigned");

         break;

      case "Click Here":
      case $_TRANSLATION["EN"]["btnClickHere"]:
      case $_TRANSLATION["AF"]["btnClickHere"]:
         MemberRegistration::Submit($_SESSION[S3REGISTRATION]->UserID, $_SESSION[S3REGISTRATION]->MemberID);
         break;
      
      case "Continue":
      case $_TRANSLATION["EN"]["btnContinue"]:
      case $_TRANSLATION["AF"]["btnContinue"]:
         if($_POST[radMemberNumber] == 1)
         {
            windowLocation("index.php");
         } 
         break;

      case "Submit Application":
      case $_TRANSLATION["EN"]["btnSubmit"]:
      case $_TRANSLATION["AF"]["btnSubmit"]:
         //send emails
         MemberRegistration::Submit($_SESSION[S3REGISTRATION]->UserID, $_SESSION[S3REGISTRATION]->MemberID);
         windowLocation("registration.member.php?Step=7");
         break;

      case "Next":     
      case $_TRANSLATION["EN"]["btnNext"]:
      case $_TRANSLATION["AF"]["btnNext"]:


            //if($_SESSION[S3REGISTRATION]->MemberID){
               if($_POST[saveData] == 1)
               {    
                 
                  $blnNewUserExistingMemberContinue = MemberRegistration::Save($_SESSION[S3REGISTRATION]->UserID, $_SESSION[S3REGISTRATION]->MemberID, $_SESSION[WizzardStep]); 
                  if($blnNewUserExistingMemberContinue == false)
                  {
                     $_SESSION[Wizzard]->ActiveParent = "SelectMember";
                     windowLocation("registration.member.php?Step=7"); 
                  }
               }

               if(isset($_POST[StoreData]))
               {
                  $_SESSION["StoredData"] == $_POST[StoreData];
               }

            //}
            break;

      case "Previous":  
      case $_TRANSLATION["EN"]["btnPrevious"]:
      case $_TRANSLATION["AF"]["btnPrevious"]:
         
         if($_POST[saveData] == 1)
         {    
            MemberRegistration::Save($_SESSION[S3REGISTRATION]->UserID, $_SESSION[S3REGISTRATION]->MemberID, $_SESSION[WizzardStep]); 
         }

         if(isset($_POST[StoreData]))
         {
            $_SESSION["StoredData"] == $_POST[StoreData];
         }

         break;

      case "Load Draft": 
      case $_TRANSLATION["EN"]["btnLoadDraft"]:
      case $_TRANSLATION["AF"]["btnLoadDraft"]:
         unset($_SESSION[S3REGISTRATION]);
            $_SESSION[Wizzard]->ActiveParent = "NewMember";
         if(strlen($MemberID) > 0 && $IsActive == 1){
            $_SESSION[S3REGISTRATION]->MemberID = $MemberID;  
            switch($RegistrationStatus){
               case 11110:
                     $_SESSION["WizzardStep"] = 5;
                     break;   
               case 11111:
                     $_SESSION["WizzardStep"] = 6;
                     break; 
               default:
                        $_SESSION["WizzardStep"] = 2;  
                        //break;        
            }

            
         }else{
            $_SESSION["WizzardStep"] = 2; 
         }
         
         $_SESSION[S3REGISTRATION]->UserID = $UserID;
         //$getUserDetails = $xdb->getRowSQL("SELECT * FROM sysUser WHERE refMemberID = $MemberID"); 
         //$_SESSION[S3REGISTRATION]->UserID = $getUserDetails->UserID; 
         break;
   }

   $imgRequired = $SystemSettings[imgRequired];
   
   ## CURRENT LANGUAGE
   if(!isset($_SESSION[LANGUAGE]))
   {
      $_SESSION[LANGUAGE] = "EN";
   } 

   ## START NEMO BASIC
   $pageRegister = new NemoBasic();
  
   ## START NEMO WIZZARD
   $page = new NemoWizzard();

   ## SET UP WIZZARD STEPS
   $page->WizzardStep[0]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["LP_Heading"]; ## LANDING PAGE
   $page->WizzardStep[0]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["LP_Intro"];
   $page->WizzardStep[0]->validation = "validation"; 
   $page->WizzardStep[0]->group = "default"; 

   $page->WizzardStep[1]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["UD_Heading"]; ## USER DETAILS
   $page->WizzardStep[1]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["UD_Intro"];
   
   $page->WizzardStep[1]->validation = "validation";
   $page->WizzardStep[1]->group = "default";

   $page->WizzardStep[2]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["UT_Heading"]; ## USER DETAILS
   $page->WizzardStep[2]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["UT_Intro"];
   
   $page->WizzardStep[2]->validation = "validation";
   $page->WizzardStep[2]->group = "default"; 

   if(isset($_SESSION[Wizzard]->ActiveParent))
   {
      if($_SESSION[Wizzard]->ActiveParent == "NewMember")
      {
         $page->WizzardStep[3]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["MT_Heading"]; ## MEMBERSHIP DETAILS
         $page->WizzardStep[3]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["MT_Intro"];
         $page->WizzardStep[3]->validation = "validation";
         $page->WizzardStep[3]->group = "NewMember";
 

      }
      else if($_SESSION[Wizzard]->ActiveParent == "SelectMember")
      {
         $page->WizzardStep[3]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["MS_Heading"]; ## MEMBERSHIP DETAILS
         $page->WizzardStep[3]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["MS_Intro"]; 
         $page->WizzardStep[3]->validation = "validation";
         $page->WizzardStep[3]->group = "SelectMember";
 
      }
   }   

   $page->WizzardStep[4]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["MD_Heading"]; ## MEMBER DETAILS
   $page->WizzardStep[4]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["MD_Intro"]; 
   $page->WizzardStep[4]->validation = "validation"; 
   $page->WizzardStep[4]->group = "NewMember";
   
   $page->WizzardStep[5]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["D_Heading"]; ## DOCUMENTS
   $page->WizzardStep[5]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["D_Intro"];
   
   $page->WizzardStep[5]->validation = "validation";
   $page->WizzardStep[5]->group = "NewMember";

   $page->WizzardStep[6]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Heading"]; ## REVIEW
   $page->WizzardStep[6]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["R_Intro"];
   
   $page->WizzardStep[6]->validation = "validation";
   $page->WizzardStep[6]->Finish = "1";
   $page->WizzardStep[6]->group = "NewMember";

   if(isset($_SESSION[Wizzard]->ActiveParent))
   {
      if($_SESSION[Wizzard]->ActiveParent == "NewMember")
      { 
         $page->WizzardStep[7]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Heading"]; ## COMPLETED
         $page->WizzardStep[7]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["C_Intro"];
         $page->WizzardStep[7]->validation = "validation";
         $page->WizzardStep[7]->HideMenu = "1";
         $page->WizzardStep[7]->group = "NewMember";

      }
      else if($_SESSION[Wizzard]->ActiveParent == "SelectMember")
      { 
         $page->WizzardStep[7]->heading = $_TRANSLATION[$_SESSION[LANGUAGE]]["CL_Heading"]; ## COMPLETED
         $page->WizzardStep[7]->intro = $_TRANSLATION[$_SESSION[LANGUAGE]]["CL_Intro"];
         $page->WizzardStep[7]->validation = "validation";
         $page->WizzardStep[7]->HideMenu = "1";
         $page->WizzardStep[7]->group = "SelectMember";
      }
   }   
   

   ## CHECK WITH CONTENT FUNCTION TO LOAD
   switch($_SESSION["WizzardStep"])
   {
      case 0:
         $page->WizzardStep[0]->content = MemberRegistration::getLandingPageContent(); 
         break;
      case 1:
         $page->WizzardStep[1]->content = MemberRegistration::getUserDetailsContent(); 
         break;
      case 2:
         $page->WizzardStep[2]->content = MemberRegistration::getUserTypeContent();
         break;
      case 3:

         if(isset($_SESSION[Wizzard]->ActiveParent))
         {
            if($_SESSION[Wizzard]->ActiveParent == "NewMember")
            {               
               $page->WizzardStep[3]->content = MemberRegistration::getMembershipDetailsContent();
            }
            else if($_SESSION[Wizzard]->ActiveParent == "SelectMember")
            {
               $page->WizzardStep[3]->content = MemberRegistration::getLinkedMemberContent();
            }
         }   
         break;
      case 4:
         $page->WizzardStep[4]->content = MemberRegistration::getMemberDetailsContent();
         break;
      case 5:
         $page->WizzardStep[5]->content = MemberRegistration::getDocumentsContent();
         break;
      case 6:
         $page->WizzardStep[6]->content = MemberRegistration::getReviewContent();
         //TODO: add Reg.Type parameter - delete later 
         break;
      case 7:
         $page->WizzardStep[7]->content = MemberRegistration::GetCompletedContent();
         if($_SESSION[Wizzard]->ActiveParent == "SelectMember")
         {
            $page->WizzardStep[7]->content = MemberRegistration::GetCompletedContentLinked($blnNewUserExistingMemberContinue, $_GET[type]);
         }
         
         break;
   }
   
   ## IF USER ID DOES NOT EXIST THE THE FOLLOWING STEPS CANT BE VIEWED
   if(($_GET[Step] == 2) || ($_GET[Step] == 3) || ($_GET[Step] == 4) || ($_GET[Step] == 5) )
   {
      if(($_SESSION[S3REGISTRATION]->UserID == "") || (!isset($_SESSION[S3REGISTRATION]->UserID)))
      {
         windowLocation("registration.member.php?Step=1");
      } 
   }

   ## IF MEMBER ID DOES NOT EXIST THE THE FOLLOWING STEPS CANT BE VIEWED
   if($_GET[Step] == 5)
   { 
      if(($_SESSION[S3REGISTRATION]->MemberID == "") || (!isset($_SESSION[S3REGISTRATION]->MemberID)))
      {
         windowLocation("registration.member.php?Step=4");
      }
   }

   ## RENDER WIZZARD
   $pageRegister->Content = $page->renderWizzard($_TRANSLATION[$_SESSION[LANGUAGE]]["WizzardName"]);
   
   ## DISPLAY PAGE
   $pageRegister->Display();
   die;

?>