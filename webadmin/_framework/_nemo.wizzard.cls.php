<?php
include_once("_framework/_nemo.cls.php");
include_once("_framework/_nemo.database2.cls.php");

class NemoWizzard
{ 
   public $WizzardStep = array();

   private $arrWizzardDEFAULT;
 
   public function __construct($RecordLabel, $arrTabs)
   {  
      $_TRANSLATION["EN"]["optSelect"] = "- Select -";
      $_TRANSLATION["AF"]["optSelect"] = "- Kies -";
      $_TRANSLATION["EN"]["btnCheck"] = "Check";
      $_TRANSLATION["AF"]["btnCheck"] = "Kontroleer";
      $_TRANSLATION["EN"]["btnNext"] = "Next";
      $_TRANSLATION["AF"]["btnNext"] = "Volgende";
      $_TRANSLATION["EN"]["btnPrevious"] = "Previous";
      $_TRANSLATION["AF"]["btnPrevious"] = "Gaan terug";
      $_TRANSLATION["EN"]["btnContinue"] = "Continue";
      $_TRANSLATION["AF"]["btnContinue"] = "Gaan voort";
      $_TRANSLATION["EN"]["btnLoadDraft"]= "Load Draft";
      $_TRANSLATION["AF"]["btnLoadDraft"]= "Laai";
      $_TRANSLATION["EN"]["btnSubmit"] = "Submit Application";
      $_TRANSLATION["AF"]["btnSubmit"] = "Stuur Aansoek";
      $_TRANSLATION["EN"]["btnClickHere"] = "Click Here";
      $_TRANSLATION["AF"]["btnClickHere"] = "klik Hier";
      $_TRANSLATION["EN"]["linkView"] = "View";
      $_TRANSLATION["AF"]["linkView"] = "AF: View";
      $_TRANSLATION["EN"]["validationMessage"] = "Please check if all fields are filled in.";
      $_TRANSLATION["AF"]["validationMessage"] = "AF: Please check if all fields are filled in.";
      $_TRANSLATION["EN"]["newBlock"] = "New";
      $_TRANSLATION["AF"]["newBlock"] = "AF: New";

      $this->arrWizzardDEFAULT->ToolbarItems = array('btnReload','btnSave', 'btnClose');
      $this->arrWizzardDEFAULT->jsValidate = ""; //js OnSave validation for this tab
      $this->arrWizzardDEFAULT->jsValidateFucntion = "jsNemoValidateSave";  
      $this->arrWizzardDEFAULT->default = 0;

      foreach($arrTabs as $strTab)
      {
         $this->Tabs[$strTab] = nCopy($this->arrTabDEFAULT);
      }
      //print_rr($_POST);
      $this->ACTIVE_TAB = $_POST["ACTIVE_TAB"];
      
      switch ($_REQUEST[Action]) 
      {
          case "Reset":
             
            break;
         
         case "Continue":
         case $_TRANSLATION["EN"]["btnContinue"]:
         case $_TRANSLATION["AF"]["btnContinue"]: 
            $_SESSION["WizzardStep"] = $_SESSION["WizzardStep"]+1; 
            break;

         case "Next":
         case $_TRANSLATION["EN"]["btnNext"]:
         case $_TRANSLATION["AF"]["btnNext"]:

            $_SESSION["WizzardStep"] = $_SESSION["WizzardStep"]+1;

            break;

         case "Previous":
         case $_TRANSLATION["EN"]["btnPrevious"]:
         case $_TRANSLATION["AF"]["btnPrevious"]:

            $_SESSION["WizzardStep"] = $_SESSION["WizzardStep"]-1;
            break;

         case "Submit Application":
         case $_TRANSLATION["EN"]["btnSubmit"]:
         case $_TRANSLATION["AF"]["btnSubmit"]:
            windowlocation("registration.member.php?Step=6");
            break;
         
         default: 
            if(!isset($_SESSION["WizzardStep"]))
            {
               $_SESSION["WizzardStep"] = 0;
            }
            else if(isset($_GET[Step]))
            { 
               $_SESSION["WizzardStep"] = $_GET[Step];
            }

            break;
      }
   }

   private function __autoload()
   { 


   }

   public function renderWizzard($WizzardName)
   { 
      global $SystemSettings, $xdb, $_TRANSLATION; 
      
      ## HANDLE NAVIGATION :: SET CURRENT STEP 
      

      $currentStep = $_SESSION["WizzardStep"];
      //echo "$currentStep";
      //print_rr($this->WizzardStep[$currentStep]);
      ## GET LAST KEY IN ARRAY
      end($this->WizzardStep);
      $LastKey = key($this->WizzardStep);  

      $displayNone = ""; 
      ## LOOP THROUGHT STEPS TO BUILD MENU
      foreach($this->WizzardStep AS $ID => $StepDetails)
      {  
         
         if($StepDetails->group == $_SESSION[Wizzard]->ActiveParent)
         {
            $displayNone = "";
         } 
         else if($StepDetails->group != "default")
         {
            $displayNone = "Display:none;";
         }

         if($ID == $currentStep)
         {
            $activeStep = "activeStep";
         }
         else
         {
            $activeStep = "";
         }
         
         if($ID == 0)
         {  
            $_TRANSLATION["EN"]["btnHome"] = "Start";
            $_TRANSLATION["AF"]["btnHome"] = "Begin";
            $menu .= "<li style='$displayNone' class='$activeStep'><a href='".$SystemSettings[FULL_PATH]."?Step=$ID'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnHome"]."</a></li>";
         }
         else 
         {  
            if($StepDetails->HideMenu != 1)
            {
               $menu .= "<li style='$displayNone' class='$activeStep'><a href='".$SystemSettings[FULL_PATH]."?Step=$ID'>$StepDetails->heading</a></li>";
            }
            
         } 
      }  

      ## SET NAVIGATION BUTTONS

      if($currentStep == 0)
      {
         $_TRANSLATION["EN"]["btnContinue"] = "Continue";
         $_TRANSLATION["AF"]["btnContinue"] = "Gaan voort";
         $buttons = "<input class='controlButton' type='submit' id='btnContinue' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnContinue"]."' name='Action' onclick='jsStartResetProcedure();''>";
      }
      else if($currentStep == 1)
      {
         $_TRANSLATION["EN"]["btnNext"] = "Next";
         $_TRANSLATION["AF"]["btnNext"] = "Volgende";
         $buttons = "<input class='controlButton' type='submit' id='btnNext' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnNext"]."' name='Action' onclick='return jsValidation();'>";
      }
      else if($this->WizzardStep[$currentStep]->Finish == 1)
      {
         //print_rr($currentStep);
         $_TRANSLATION["EN"]["btnPrevious"] = "Previous";
         $_TRANSLATION["AF"]["btnPrevious"] = "Gaan terug";
         $_TRANSLATION["EN"]["btnSubmit"] = "Submit Application";
         $_TRANSLATION["AF"]["btnSubmit"] = "Stuur Aansoek";
         $buttons = "<input class='controlButton' type='submit' id='btnPrevious' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnPrevious"]."' name='Action' onclick='return jsValidation();'>
                     <input class='controlButton' type='submit' id='btnSubmitApplication' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnSubmit"]."' name='Action' onclick='return jsValidationSubmit();'>";
      }
      else
      {
         
         $_TRANSLATION["EN"]["btnPrevious"] = "Previous";
         $_TRANSLATION["AF"]["btnPrevious"] = "Gaan terug";
         $_TRANSLATION["EN"]["btnNext"] = "Next";
         $_TRANSLATION["AF"]["btnNext"] = "Volgende";
         $buttons = "<input class='controlButton' type='submit' id='btnPrevious' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnPrevious"]."' name='Action' onclick='return jsValidation();'>
                     <input class='controlButton' type='submit' id='btnNext' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnNext"]."' name='Action' onclick='return jsValidation();'>";
      }
      
      if($currentStep == $LastKey)
      {
         $buttons = "  ";
      } 

      ## WIZZARD HTML
      $HTML = "
               <div class='Wiz-Container'>
                  <div class='Wiz-Head'>$WizzardName</div>
                  <div class='Wiz-Menu'>
                     <ul>
                        $menu
                     </ul>
                  </div> 
                  <input type='hidden' value='$currentStep' name='pageID'/>
                  <div class='Wiz-StepHeading'>".$this->WizzardStep[$currentStep]->heading."</div>
                  <div class='Wiz-Intro'>".$this->WizzardStep[$currentStep]->intro."</div>
                  <div class='Wiz-Content'>".$this->WizzardStep[$currentStep]->content."</div>
                  <div class='Wiz-Buttons'>
                     $buttons
                     <input style='position:relative;float:right;' class='controlButton' type='submit' id='btnReset' value='Reset' name='Action' onclick='return jsWarning();'>
                     <div style='clear:both;'></div>
                  </div>

               </div>";

      ## SOME JS
      $JS = "  <script>

                  function jsWarning()
                  {
                     return confirm('Are you sure you want to reset the Wizzard?');
                  }


                  function jsHoverTooltip(faqID)
                  {       
                     $('.Tooltip_Text').slideUp('fast'); 
                     
                     if($('#FaqContent_'+faqID).hasClass('activeTooltip'))
                     {
                         $('.Tooltip_Text').removeClass('activeTooltip');
                     }
                     else
                     {
                        $('.Tooltip_Text').removeClass('activeTooltip');
                        $('#FaqContent_'+faqID).slideDown('fast');
                        $('#FaqContent_'+faqID).addClass('activeTooltip');
                     }
                     
                  } 

                  function jsTriggerFAQ(FAQID)
                  {
                     $('#FaqIDx_'+FAQID).click(); 
                  }
               </script>";


      ## RETURN HTML
      return $HTML . $JS;
   }






}

?>
