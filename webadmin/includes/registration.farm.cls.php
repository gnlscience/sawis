<?php

##DB CHANGES###############################################################################################################################################################################
//Add columns for title, name and surname.
#ALTER TABLE `tblFarm`  ADD `strTitle` VARCHAR(100) NOT NULL AFTER `strContact`,  ADD `strName` VARCHAR(100) NOT NULL AFTER `strTitle`,  ADD `strSurname` VARCHAR(100) NOT NULL AFTER `strName`
###########################################################################################################################################################################################
include_once("_framework/_nemo.list.cls.php");
include_once("includes/registration.blocks.cls.php");
include_once("includes/farm.cls.php");

## STEP 1 :: Landing Page
$_TRANSLATION["EN"]["Content"] = "In order to complete your registration as quickly as possible you will require the following:";
$_TRANSLATION["AF"]["Content"] = "Om die registrasie so gou as moontlik te doen het u die volgende nodig:";
$_TRANSLATION["EN"]["listItem1"] = "Business Registration & Legal Docs";
$_TRANSLATION["AF"]["listItem1"] = "Besigheid regestrasie en Dokumente";
$_TRANSLATION["EN"]["listItem2"] = "Owner information";
$_TRANSLATION["AF"]["listItem2"] = "Eienaar Informasie";
$_TRANSLATION["EN"]["listItem3"] = "etc";
$_TRANSLATION["AF"]["listItem3"] = "En so voorts";

### STEP 2 :: Resgistration Type 
$_TRANSLATION["EN"]["TypeOfMembership"] = "Type of Regstration";
$_TRANSLATION["AF"]["TypeOfMembership"] = "Riigestrasie Tiepe";
$_TRANSLATION["EN"]["radOptionD1"] = "New Farm";
$_TRANSLATION["AF"]["radOptionD1"] = "Nuwe Plaas";
$_TRANSLATION["EN"]["radOptionD2"] = "New Producer on a previously SAWIS registered farm";
$_TRANSLATION["AF"]["radOptionD2"] = "AF: New Producer on a previously SAWIS registered farm";
$_TRANSLATION["EN"]["radOptionD3"] = "Subdivision of an existing SAWIS farm";
$_TRANSLATION["AF"]["radOptionD3"] = "AF: Subdivision of an existing SAWIS farm";
$_TRANSLATION["EN"]["radOptionD4"] = "Consolidation of existing SAWIS farms";
$_TRANSLATION["AF"]["radOptionD4"] = "AF: Consolidation of existing SAWIS farms";
$_TRANSLATION["EN"]["strExistingFarmNumber"] = "Enter an existing SAWIS farm number.";
$_TRANSLATION["AF"]["strExistingFarmNumber"] = "AF: Enter an existing SAWIS farm number.";

## STEP 3 :: Farm Details
$_TRANSLATION["EN"]["strFarm"] = "Farm Name";
$_TRANSLATION["AF"]["strFarm"] = "Handelsnaam van plaas";
$_TRANSLATION["EN"]["strNearestTown"] = "Nearest Town";
$_TRANSLATION["AF"]["strNearestTown"] = "Naaste dorp";
$_TRANSLATION["EN"]["txtPhysicalAddress"] = "Physical Address";
$_TRANSLATION["AF"]["txtPhysicalAddress"] = "Fisiese Adres";

$_TRANSLATION["EN"]["optSelect"] = "- Select -";
$_TRANSLATION["AF"]["optSelect"] = "- Kies -";
$_TRANSLATION["EN"]["btnCheck"] = "Check";
$_TRANSLATION["AF"]["btnCheck"] = "Kontroleer";
$_TRANSLATION["EN"]["btnNext"] = "Next";
$_TRANSLATION["AF"]["btnNext"] = "Volgende";
$_TRANSLATION["EN"]["btnPrevious"] = "Previous";
$_TRANSLATION["AF"]["btnPrevious"] = "Vorige";
$_TRANSLATION["EN"]["btnContinue"] = "Continue";
$_TRANSLATION["AF"]["btnContinue"] = "Gaan voort";
$_TRANSLATION["EN"]["btnLoadDraft"]= "Load Draft";
$_TRANSLATION["AF"]["btnLoadDraft"]= "Laai";
$_TRANSLATION["EN"]["btnSubmit"] = "Submit Application";
$_TRANSLATION["AF"]["btnSubmit"] = "Dien in";
$_TRANSLATION["EN"]["linkView"] = "View";
$_TRANSLATION["AF"]["linkView"] = "AF: View";
$_TRANSLATION["EN"]["validationMessage"] = "Please check if all fields are filled in.";
$_TRANSLATION["AF"]["validationMessage"] = "AF: Please check if all fields are filled in.";
$_TRANSLATION["EN"]["newBlock"] = "New";
$_TRANSLATION["AF"]["newBlock"] = "AF: New";

## STEP 4 :: Property Information
$_TRANSLATION["EN"]["btnContinue"] = "Continue";
$_TRANSLATION["AF"]["btnContinue"] = "Gaan voort";
$_TRANSLATION["EN"]["btnAddTitleDeed"] = "Add Title Deed";
$_TRANSLATION["AF"]["btnAddTitleDeed"] = "AF: Add Title Deed";
$_TRANSLATION["EN"]["btnAddActDescription"] = "Add Act Description";
$_TRANSLATION["AF"]["btnAddActDescription"] = "AF: Add Act Description";
$_TRANSLATION["EN"]["TitleDeed"] = "Title Deeds";
$_TRANSLATION["AF"]["TitleDeed"] = "Titelaktes";
$_TRANSLATION["EN"]["arrActNumbers"] = "Title Act Numbers";
$_TRANSLATION["AF"]["arrActNumbers"] = "Titelakte nommers van plaas";
$_TRANSLATION["EN"]["strOwner"] = "Owner";
$_TRANSLATION["AF"]["strOwner"] = "Eienaar";
$_TRANSLATION["EN"]["arrActNumbers_Number"] = "Number";
$_TRANSLATION["AF"]["arrActNumbers_Number"] = "Nommer";
$_TRANSLATION["EN"]["arrActDescription"] = "Act description of farm";
$_TRANSLATION["AF"]["arrActDescription"] = "Akte omskrywing van plaas";
$_TRANSLATION["EN"]["arrActDescription_A"] = "Deeds Office";
$_TRANSLATION["AF"]["arrActDescription_A"] = "AF: Deeds Office";
$_TRANSLATION["EN"]["arrActDescription_B"] = "Registration Division";
$_TRANSLATION["AF"]["arrActDescription_B"] = "AF: Registration Division";
$_TRANSLATION["EN"]["arrActDescription_C"] = "Farm/Plot #";
$_TRANSLATION["AF"]["arrActDescription_C"] = "AF: Farm/Plot #";
$_TRANSLATION["EN"]["arrActDescription_D"] = "Portion";
$_TRANSLATION["AF"]["arrActDescription_D"] = "AF: Portion";
$_TRANSLATION["EN"]["arrActDescription_E"] = "Extent (Ha)";
$_TRANSLATION["AF"]["arrActDescription_E"] = "AF: Extent (Ha)";
$_TRANSLATION["EN"]["dblArea"] = "Total area of farm";
$_TRANSLATION["AF"]["dblArea"] = "Totale grootte van plaas";
$_TRANSLATION["EN"]["Converter"] = "Morgen to Hectare calculater";
$_TRANSLATION["AF"]["Converter"] = "Morgen na Gektaar calculater";

## STEP 5 :: Documents
$_TRANSLATION["EN"]["arrDocs_Type"] = "Type";
$_TRANSLATION["AF"]["arrDocs_Type"] = "Tiepe";
$_TRANSLATION["EN"]["arrDocs_Filename"] = "Filename";
$_TRANSLATION["AF"]["arrDocs_Filename"] = "lernaam";
$_TRANSLATION["EN"]["arrDocs_UploadDate"] = "Upload Date";
$_TRANSLATION["AF"]["arrDocs_UploadDate"] = "Oplaai Datum";
$_TRANSLATION["EN"]["btnAdd"] = "Add";
$_TRANSLATION["AF"]["btnAdd"] = "Voeg";

## STEP 6 :: Review
$_TRANSLATION["EN"]["RT_Heading"] = "Registration Type";
$_TRANSLATION["AF"]["RT_Heading"] = "Registrasie Tiepe";
$_TRANSLATION["EN"]["FD_Heading"] = "Farm Details";
$_TRANSLATION["AF"]["FD_Heading"] = "Plaas Besonderhede";
$_TRANSLATION["EN"]["TD_Heading"] = "Title Dead Descriptions";
$_TRANSLATION["AF"]["TD_Heading"] = "Tietelakte Beskywing";
$_TRANSLATION["EN"]["D_Heading"] = "Documents";
$_TRANSLATION["AF"]["D_Heading"] = "Dokumente";
$_TRANSLATION["EN"]["R_Heading"] = "Review";
$_TRANSLATION["AF"]["R_Heading"] = "Hersien";
$_TRANSLATION["EN"]["N_Heading"] = "Notes";
$_TRANSLATION["AF"]["N_Heading"] = "Notes";
$_TRANSLATION["EN"]["VB_Heading"] = "Vineyard Blocks";
$_TRANSLATION["AF"]["VB_Heading"] = "Vineyard Blocks";
$_TRANSLATION["EN"]["TypeOfRegstration_Title"] = "Farm Regestration Type";
$_TRANSLATION["AF"]["TypeOfRegstration_Title"] = "Plaas Regestrasie Tipe";
$_TRANSLATION["EN"]["strVATRegistrationNumber"] = "VAT Registration Number?";
$_TRANSLATION["AF"]["strVATRegistrationNumber"] = "BTW Registrasie Nommer";
$_TRANSLATION["EN"]["TitleDocuments"] = "Document List";
$_TRANSLATION["AF"]["TitleDocuments"] = "Dokument Lys";
$_TRANSLATION["EN"]["arrFileName"] = "Filename";
$_TRANSLATION["AF"]["arrFileName"] = "Dokument Naam";

## VALIDATION TRANSLATION
$_TRANSLATION["EN"]["valid_MembershipType"] = "Please Choose a registration type.";
$_TRANSLATION["AF"]["valid_MembershipType"] = "AF: Please Choose a registration type.";
$_TRANSLATION["EN"]["valid_D2_1"] = "Please indicate which farm facility will be used.";
$_TRANSLATION["AF"]["valid_D2_1"] = "AF: Please indicate which farm facility will be used.";
$_TRANSLATION["EN"]["valid_D2_2"] = "Please insert active farm number";
$_TRANSLATION["AF"]["valid_D2_2"] = "AF: Please insert active farm number";

$_TRANSLATION["EN"]["valid_strOwner"] = "Please provide Farm owner name.";
$_TRANSLATION["AF"]["valid_strOwner"] = "Verskaf asseblief die plaas eienaar se naam.";
$_TRANSLATION["EN"]["valid_dblArea"] = "Please provide the total area of farm.";
$_TRANSLATION["AF"]["valid_dblArea"] = "Verskaf asseblief die totale gebied van die plaas.";
$_TRANSLATION["EN"]["valid_RowRequired"] = "Row Required";
$_TRANSLATION["AF"]["valid_RowRequired"] = "Kort ten minste 1 ry.";
$_TRANSLATION["EN"]["valid_Deedblock"] = "There are some missing act numbers, please remove the Title Deed if not going to be used.";
$_TRANSLATION["AF"]["valid_Deedblock"] = "AF: There are some missing act numbers, please remove the Title Deed if not going to be used.";
$_TRANSLATION["EN"]["valid_RowItem"] = "Some Rows do not have values, please add, or remove unwanted rows.";
$_TRANSLATION["AF"]["valid_RowItem"] = "AF: Some Rows do not have values, please add, or remove unwanted rows.";
$_TRANSLATION["EN"]["valid_strFarm"] = "Please provide the farm name.";
$_TRANSLATION["AF"]["valid_strFarm"] = "Verskaf asseblief die  plaas eienaar se naam.";
$_TRANSLATION["EN"]["valid_strFarm_exist"] = "Farm Name already in use.";
$_TRANSLATION["AF"]["valid_strFarm_exist"] = "Plas naam in gebruik.";
$_TRANSLATION["EN"]["valid_strRegisteredBusinessName"] = "Please provide your registered business name.";
$_TRANSLATION["AF"]["valid_strRegisteredBusinessName"] = "Verskaf asseblief u registreede Besigheidsnaam.";
$_TRANSLATION["EN"]["valid_strRegisteredBusinessName_exist"] = "Business name already in use.";
$_TRANSLATION["AF"]["valid_strRegisteredBusinessName_exist"] = "Besigheid naam in gebruik.";
$_TRANSLATION["EN"]["valid_strRegistrationNumber"] = "Please provide business registration number.";
$_TRANSLATION["AF"]["valid_strRegistrationNumber"] = "Verskaf asseblief u besigheid se registrasie nommer.";
$_TRANSLATION["EN"]["valid_strRegistrationNumber_exist"] = "Registration number is already in use.";
$_TRANSLATION["AF"]["valid_strRegistrationNumber_exist"] = "Registrasienommer in gebruik.";
$_TRANSLATION["EN"]["valid_strVATRegistrationNumber"] = "Please provide VAT registration number.";
$_TRANSLATION["AF"]["valid_strVATRegistrationNumber"] = "Verskaf asseblief u BTW registrasienommer.";
$_TRANSLATION["EN"]["valid_strVATRegistrationNumber_exist"] = "VAT registration number already is use.";
$_TRANSLATION["AF"]["valid_strVATRegistrationNumber_exist"] = "BTW registrasienommer in gebruik.";
$_TRANSLATION["EN"]["valid_strNearestTown"] = "Please provide your nearest town.";
$_TRANSLATION["AF"]["valid_strNearestTown"] = "Verskaf asseblief u naaste dorp.";
$_TRANSLATION["EN"]["valid_txtPostalAddress"] = "Please provide postal address.";
$_TRANSLATION["AF"]["valid_txtPostalAddress"] = "Verskaf asseblief u pos adres.";
$_TRANSLATION["EN"]["valid_txtPhysicalAddress"] = "Please provide physical address.";
$_TRANSLATION["AF"]["valid_txtPhysicalAddress"] = "Verskaf asseblief u fisiese adres.";
$_TRANSLATION["EN"]["valid_strTitle"] = "Please provide your Title.";
$_TRANSLATION["AF"]["valid_strTitle"] = "Verskaf asseblief u titel.";
$_TRANSLATION["EN"]["valid_strName"] = "Please provide your Name.";
$_TRANSLATION["AF"]["valid_strName"] = "Verskaf asseblief u naam.";
$_TRANSLATION["EN"]["valid_strSurname"] = "Please provide your Surname.";
$_TRANSLATION["AF"]["valid_strSurname"] = "Verskaf asseblief u van.";
$_TRANSLATION["EN"]["valid_strArea"] = "Invalid area code.";
$_TRANSLATION["AF"]["valid_strArea"] = "ongeldige area kode.";
$_TRANSLATION["EN"]["valid_strTel"] = "Please provide telephone Number.";
$_TRANSLATION["AF"]["valid_strTel"] = "Verskaf asseblief u telefoon nommer.";
$_TRANSLATION["EN"]["valid_strTel2"] = "Telephone number invalid.";
$_TRANSLATION["AF"]["valid_strTel2"] = "Telefoon nommer is nie geldig nie.";
$_TRANSLATION["EN"]["valid_strCell"] = "Invalid Cellphone number.";
$_TRANSLATION["AF"]["valid_strCell"] = "ongeldige Selfoon nommer.";
$_TRANSLATION["EN"]["valid_strFax"] = "Invalid Fax number.";
$_TRANSLATION["AF"]["valid_strFax"] = "ongeldige Faks number.";
$_TRANSLATION["EN"]["valid_strEmail"] = "Please provide email address.";
$_TRANSLATION["AF"]["valid_strEmail"] = "Verskaf asseblief epos adres.";
$_TRANSLATION["EN"]["valid_strEmail2"] = "Email address invalid.";
$_TRANSLATION["AF"]["valid_strEmail2"] = "Epos adres is nie geldig nie.";
$_TRANSLATION["EN"]["valid_strFileSize_Invalid"] = "Please choose another file,this file is bigger than 5MB.";
$_TRANSLATION["AF"]["valid_strFileSize_Invalid"] = "AF: Please choose another file,this file is bigger than 5MB.";
$_TRANSLATION["EN"]["valid_strFileType_Invalid"] = "Please choose another file,this file type is not accepted.";
$_TRANSLATION["AF"]["valid_strFileType_Invalid"] = "AF: Please choose another file,this file type is not accepted.";
$_TRANSLATION["EN"]["valid_Document"] = "Please upload at least 1 file.";
$_TRANSLATION["AF"]["valid_Document"] = "AF: Please upload at least 1 file.";
$_TRANSLATION["EN"]["NoFarms"] = "No Available Farms";
$_TRANSLATION["AF"]["NoFarms"] = "Geen plaase";

class RegistrationFarm extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      parent::__construct($DataKey);
   }

   ## GET CONTENT FOR LANDING PAGE ########################################################################################################################################################
   ########################################################################################################################################################################################
   ########################################################################################################################################################################################

   function getLandingPageContent()
   {
      ## GLOBAL VARS
      global $xdb, $arrSys, $TR, $SP, $BR, $HR, $DATABASE_SETTINGS, $SystemSettings, $imgRequired,$_TRANSLATION;

      ## HTML CONTENT
      $Content =  $_TRANSLATION[$_SESSION[LANGUAGE]]["Content"].
                  "<ul>
                     <li>".$_TRANSLATION[$_SESSION[LANGUAGE]]["listItem1"]."</li>
                     <li>".$_TRANSLATION[$_SESSION[LANGUAGE]]["listItem2"]."</li>
                     <li>".$_TRANSLATION[$_SESSION[LANGUAGE]]["listItem3"]."</li>
                  </ul>";

      ## JAVASCRIPT
      $JS = "  <script>
               </script>";

      ## RETURN
      return $Content . $JS;
   }

   ## GET THE REGISTRATION TYPE CONTENT ###################################################################################################################################################
   ########################################################################################################################################################################################
   ########################################################################################################################################################################################

   function getRegistrationTypeContent()
   {
      ## GLOBAL VARS
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings,$_TRANSLATION;

      ## INI ## :)
      $RegistrationType = "";
      $MemberID = $_SESSION[USER]->MEMBERID; 

      ## IF MEMBER EXIST GET DETAILS AND FILL FORM
      if((isset($_SESSION[S2REGISTRATION]->FarmID)) && ($_SESSION[S2REGISTRATION]->FarmID != ""))
      {
         ## GET FARM DETAILS
         $FarmID = $_SESSION[S2REGISTRATION]->FarmID;
         $row = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE FarmID = ".$_SESSION[S2REGISTRATION]->FarmID);

 
         ## UPDATE VARS
         $RegistrationType = $row->RegistrationType;
      }

      ## GET ARUMANTS ARRAY
      $Args = unserialize($row->RegistrationArgs);
       
      $D1Checked = "";
      if($RegistrationType == "New")
      {
         $D1Checked = "checked='checked'";
      }

      ## CHECK CORRECT MEMBERSHIP RADIO BUTTON
      $D2Checked = "";
      $D2Disable = "disabled='disabled'";
      $D2Value = "";
      $D3Checked = "";
      $D3Disable = "disabled='disabled'";
      $D3Value = "";
      $D4Checked = "";
      $D4Disable = "style='display:none'";

      switch ($RegistrationType)
      {
         case "Buy":
            $D2Checked = "checked='checked'";
            $D2Disable = "";
            $D2Value = $Args[FarmFacility];
            break;

         case "SubDiv":
            $D3Checked = "checked='checked'";
            $D3Disable = "";
            $D3Value = $Args[FarmFacility];
            break;

         case "Consolidate":
            $D4Checked = "checked='checked'";
            $D4Disable = "";
            if($Args[bMemberID] != "")
            {
               $BValue = $Args[bMemberID];
            }
            break;
      }

      ## COLUMN WIDTH
      $ColumWidthLabel = "width= '20%'";
      $ColumWidthInput = "width= '35%'";
      $ColumWidthIcon = "width= '5%'";
      $ColumWidthComment = "width= '40%'";

      ## LIST OF MEMBER FAMRS
      print_rr($MemberID);
      // $MemberID = 1101;//1101
      $rst = $xdb->doQuery("SELECT tblFarm.FarmID,tblFarm.strFarm,vieLocation.EN_strLocation AS 'Location' 
                           FROM tblMemberRelationship 
                           LEFT JOIN tblFarm ON tblMemberRelationship.refEntityID = tblFarm.FarmID
                           LEFT JOIN vieLocation ON vieLocation.LocationID = tblFarm.refLocationID
                           WHERE strType = 'Farm to Member' AND refMemberID = $MemberID AND tblFarm.strStatus = 'Active'
                           GROUP BY tblFarm.FarmID, tblFarm.strFarm, vieLocation.EN_strLocation
                           ORDER BY vieLocation.EN_strLocation ASC,tblFarm.strFarm ASC");//  
      
      while($row = $xdb->fetch_object($rst))
      {
         print_rr($row);
         $checked = "";
         $farmIDLabel = "<label>Farm ID</label><br>";
         $farmNameLabel = "<label>Farm Name</label><br>";
         $captionOpen="<caption style ='background-color:#51732c'>";
         $captionClose="</caption>";
            
            if($Args[FarmFacility][$row->FarmID] == 1)
            {
               $checked = "checked='checked'";
            }

            if($LastLocationHeading == $row->Location)
            {
               $row->Location = "";
               $farmIDLabel = "";
               $farmNameLabel = "";
               $captionOpen="";
               $captionClose="";
            }

            $divMyFarms_D4.="
            <table width=100% border=0 align=center>
               $captionOpen
               $row->Location
               $captionClose
               <tr>
                  <th width=126px></th>
                  <th width=315px>$farmIDLabel</th>
                  <th width=315px>$farmNameLabel</th>
               </tr>
               <tr>
                  <td width=126px border-bottom=0 ><input id='$row->FarmID' type='checkbox' name='arrConsolidateFarms[$row->FarmID]' $checked value=1 /></td>
                  <td width=315px>$row->FarmID</td>
                  <td width=315px>$row->strFarm</td>
               </tr>
            </table>";

            /*$divMyFarms_D4 .= "
            <div class='FarmOptionDiv'>
               <u><h3 style =' color:#51732c'>$row->Location</h3></u>
               <div class='FarmOption'>
               <label></label><br>
                  <input id='$row->FarmID' type='checkbox' name='arrConsolidateFarms[$row->FarmID]' $checked value=1 />
               </div>
               <div class='FarmOption'>
                  $farmIDLabel
                  $row->FarmID
               </div>
               <div class='FarmOption'>
                  $farmNameLabel
                  $row->strFarm
               </div>
            </div><br><br>";*/

            if($row->Location != "")
            {
               $LastLocationHeading = $row->Location;
               $farmIDLabel = "<label>Farm ID</label><br>";
               $farmNameLabel = "<label>Farm Name</label><br>";
               $captionOpen="<caption style ='background-color:#51732c'>";
               $captionClose="</caption>";
            }
      
             

      }

      if($divMyFarms_D4 == "")
      {
         $MemberFarmList = $_TRANSLATION[$_SESSION[LANGUAGE]]["NoFarms"];
      }
      else
      {
         $MemberFarmList = "$divMyFarms_D4 <div style='clear:both;'></div>";
      }

      ## HTML CONTENT
      $strContent = "
      <div>
         <div id='ErrorContent_Main' class='Error_Text'></div>
         <br>
         ".$_TRANSLATION[$_SESSION[LANGUAGE]]["TypeOfMembership"]."
         <br><br>
         <table width='100%'>
            <tr>
               <td>
                  <input id='OptionD1' $D1Checked type='radio' name='refMembershipType' value='New' onclick='jsMembershipEngine(this.id);' /> <label for='OptionD1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["radOptionD1"]."</label>
                  <label id='lbl_OptionD1' class='RequiredFieldIndicatorChanger'></label>
               </td>
               <td align='right'></td>
               <td align='right' width='50px'>
                  <div class='Tooltip'><div id='FaqIDx_3' class='Tooltip_Icon' onClick='jsHoverTooltip(3);'><img width='25px' height='25px' src='images/question-icon.png' /></div></div>
               </td>
            </tr>
         </table>
         <div id='FaqContent_3' class='Tooltip_Text'>".GetTooltip(15)."</div>
         <div id='ErrorContent_D1' class='Error_Text'></div>

         <div style='border-bottom:1px groove #333;'></div><BR>

         <table width='100%'>
            <tr>
               <td >
                  <input id='OptionD2' $D2Checked type='radio' name='refMembershipType' value='Buy' onclick='jsMembershipEngine(this.id);' /> <label for='OptionD2'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["radOptionD2"]."</label>
                  <label id='lbl_OptionD2' class='RequiredFieldIndicatorChanger'></label>
               </td>
               <td align='right'>
                  <input type='text' onchange='jsGetFarmDetails(2);' name='D2FarmID' class='controlText clsMembershipType' value='$D2Value' id='Input_OptionD2' $D2Disable/>
                  <div id='D2FarmName'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strExistingFarmNumber"]."</div>
               </td>
               <td align='right' width='50px'>
                  <div class='Tooltip'><div id='FaqIDx_4' class='Tooltip_Icon' onClick='jsHoverTooltip(4);'><img width='25px' height='25px' src='images/question-icon.png' /></div></div>
               </td>
            </tr>
         </table>
         <div id='FaqContent_4' class='Tooltip_Text'>".GetTooltip(16)."</div>
         <div id='ErrorContent_D2' class='Error_Text'></div>

         <div style='border-bottom:1px groove #333;'></div><BR>

         <table width='100%'>
            <tr>
               <td >
                  <input id='OptionD3' $D3Checked type='radio' name='refMembershipType' value='SubDiv' onclick='jsMembershipEngine(this.id);' /> <label for='OptionD3'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["radOptionD3"]."</label>
                  <label id='lbl_OptionD3' class='RequiredFieldIndicatorChanger'></label>
               </td>
               <td align='right'>
                  <input type='text' onchange='jsGetFarmDetails(3);' name='D3FarmID' class='controlText clsMembershipType' value='$D3Value' id='Input_OptionD3' $D3Disable/>
                  <div id='D3FarmName'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strExistingFarmNumber"]."</div>
               </td>
               <td align='right' width='50px'>
                  <div class='Tooltip'><div id='FaqIDx_5' class='Tooltip_Icon' onClick='jsHoverTooltip(5);'><img width='25px' height='25px' src='images/question-icon.png' /></div></div>
               </td>
            </tr>
         </table>
         <div id='FaqContent_5' class='Tooltip_Text'>".GetTooltip(17)."</div>
         <div id='ErrorContent_D3' class='Error_Text'></div>

         <div style='border-bottom:1px groove #333;'></div><BR>

          <table width='100%'>
            <tr>
               <td >
                  <input id='OptionD4' $D4Checked type='radio' name='refMembershipType' value='Consolidate' onclick='jsMembershipEngine(this.id);' /> <label for='OptionD4'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["radOptionD4"]."</label>
                  <label id='lbl_OptionD4' class='RequiredFieldIndicatorChanger'></label>
               </td>
               <td align='right'></td>
               <td align='right' width='50px'>
                  <div class='Tooltip'><div id='FaqIDx_6' class='Tooltip_Icon' onClick='jsHoverTooltip(6);'><img width='25px' height='25px' src='images/question-icon.png' /></div></div>
               </td>
            </tr>
         </table>
         <div id='FarmList' $D4Disable>
            $MemberFarmList
         </div>
         <div id='FaqContent_6' class='Tooltip_Text'>".GetTooltip(6)."</div>
         <div id='ErrorContent_D4' class='Error_Text'></div>

      </div>
      <input type='hidden' value='1' name='saveData' />
      <input type='hidden' value='1' name='storeData' />
      <input type='hidden' id='UserID' name='UserID' value='$UserID' />
      <input type='hidden' id='MemberID' name='MemberID' value='$MemberID' />";

      ## JAVASCRIPT
      $JS = "  <script>

                  $( document ).ready(function() {
                     jsGetFarmDetails();
                  });


                  // THIS FUNCTION CHECKS IF FARM ID EXIST ////////////////////////////////////////////
                  function jsGetFarmDetails(idx)
                  {
                     tmpFarmID = $('#Input_OptionD'+idx).val();
                     $('#D'+idx+'FarmName').hide('fast')
                     $.ajax(
                     {
                        type: 'POST',
                        url: 'ajaxfunctions.php',
                        data: 'header=text&type=getFarmDetails&tmpFarmID=' + tmpFarmID,
                        success: function(data)
                        {

                           if(data != 0)
                           {
                              $('#D'+idx+'FarmName').html(data)
                           }
                           else
                           {
                              $('#D'+idx+'FarmName').html('Farm number does not exist.')
                           }
                        }
                     });

                     $('#D'+idx+'FarmName').show('fast');
                  }

                  // THIS FUNCTION HANDLES MEMBERSHIP SWITCHES ////////////////////////////////////////////

                  function jsMembershipEngine(idx)
                  {
                     $('.clsMembershipType').attr('disabled','disabled');
 
                     $('#Input_'+idx).removeAttr('disabled');

                     $('.RequiredFieldIndicatorChanger').html('');
                     $('#lbl_'+idx).html('*');

                     if(idx == 'OptionD4')
                     {
                        $('#FarmList').slideDown('Fast');
                     }
                     else
                     {  
                        $('#FarmList').slideUp('Fast');
                     }
                  }

                  // PAGE VALIDATION ///////////////////////////////////////////////////////////////////////

                  function jsValidation()
                  {

                     $('.Error_Text').slideUp('fast');
                     msg = '';


                     if(!$('#Input_OptionD2').is(':disabled'))
                     {
                        if($('#Input_OptionD2').val() == '')
                        {
                           $('#ErrorContent_D2').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_D2_1"]."')
                           $('#ErrorContent_D2').slideDown('fast');
                           msg += 'Please select a Cellar type. \\n'
                        }

                        if($('#D2FarmName').html() == 'Farm number does not exist.')
                        {
                           $('#ErrorContent_D2').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_D2_2"]."')
                           $('#ErrorContent_D2').slideDown('fast');
                           msg += 'Please select a Cellar type. \\n'
                        }
                     }

                     if(!$('#Input_OptionD3').is(':disabled'))
                     {
                        if($('#Input_OptionD3').val() == '')
                        {
                           $('#ErrorContent_D3').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_D2_1"]."')
                           $('#ErrorContent_D3').slideDown('fast');
                           msg += 'Please select a Wholesaler type. \\n'
                        }
                     }

                     if (!$('input[name=\"refMembershipType\"]:checked').length)
                     {
                        $('#ErrorContent_Main').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_MembershipType"]."')
                        $('#ErrorContent_Main').slideDown('fast');
                        msg += 'Please Choose a type of registration. \\n'
                     }

                     if(msg != '')
                     {
                        return false;
                     }
                     return true;
                  }

               </script>";

      ## RETURN
      return $strContent . $JS;
   }

   ## GET THE FARM DETAILS CONTENT ########################################################################################################################################################
   ########################################################################################################################################################################################
   ########################################################################################################################################################################################

   function getFarmDetailsContent($FarmID)
   {
      ## GLOBAL VARS
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $imgRequired, $_LANGUAGE,$_TRANSLATION ;

      ## INI ## :)
      $strFarm = "";
      $strRegisteredBusinessName = "";
      $strRegistrationNumber = "";
      $strVATRegistrationNumber = "";
      $strNearestTown = "";
      $txtPostalAddress = "";
      $txtPhysicalAddress = "";
      $strTitle = "";
      $strName = "";
      $strSurname = "";
      $strTel = "";
      $strCell = "";
      $strFax = "";
      $strEmail = "";
      $strWebsiteURL = "";

      ## UPDATE VARS         
      $row = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE FarmID = '$FarmID'");
      if($row){
         $strFarm = $row->strFarm;
         $strRegisteredBusinessName = $row->strRegisteredBusinessName;
         $strRegistrationNumber = $row->strRegistrationNumber;
         $strVATRegistrationNumber = $row->strVATRegistrationNumber;
         $strNearestTown = $row->strNearestTown;
         $txtPostalAddress = $row->txtPostalAddress;
         $txtPhysicalAddress = $row->txtPhysicalAddress;
         $strTitle = $row->strTitle;
         $strName = $row->strName;
         $strSurname = $row->strSurname;
         $strSurname = $row->strSurname;
         $strAreaCode = $row->strAreaCode;
         $strTel = $row->strTel;
         $strCell = $row->cell;
         $strFax = $row->strFax;
         $strEmail = $row->strEmail;
         $strWebsiteURL = $row->strWebsiteURL;
      }


      if($strVATRegistrationNumber == "")
      {
         $showVATReg = "display:none;";
         $radVatRegNo = "checked";
         //$radVatRegNo = "checked"; //do not check No automatically else it will default on first run
      }else{
         $showVATReg = "";
         $radVatRegYes = "checked";
      }

      ## COLUMN WIDTH
      $ColumWidthLabel = "width= '20%'";
      $ColumWidthInput = "width= '35%'";
      $ColumWidthIcon = "width= '5%'";
      $ColumWidthComment = "width= '40%'";

      ## STRIPPING THE TELEPHONE NUMBER OF THE AREA CODE
      $telNo =  explode("-", $strTel);
      $strAreaCode = $telNo[0];
      $strTel = $telNo[1];

      ##Creating Datalist for Nearest Town Input 
      $rstNearestTown = $xdb->doQuery("
         SELECT strTown AS Town FROM tblTown WHERE strTown IS NOT NULL
         UNION
         SELECT strNearestTown AS Town FROM tmpFarm WHERE strNearestTown IS NOT NULL
         GROUP BY Town 
         ORDER BY Town ASC",0);

      ##BUILDING DATA LIST FOR NEAREST TOWN
      $dlNearestTown = "";
      while($row = $xdb->fetch_object($rstNearestTown))
      {
         $dlNearestTown .= "<option value=\"$row->Town\">";      
      }

      ## HTML CONTENT
      $Content = "<div>
                     <div id='ErrorContent_Main' class='Error_Text'></div>
                     <br>
                     <table width='100%'>
                        <tr>
                           <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strFarm"].":</label>$imgRequired</td>
                           <td align='right'><input type='text' id='strFarm' name='strFarm' value=\"$strFarm\" class='controlText' onchange='jsTradingNameExists()' />  </td>
                           <td align='right' width='50px'></td>
                        </tr>
                     </table>
                     <div id='ErrorContent_strFarm' class='Error_Text'></div> 
                     <table width='100%'>
                        <tr>
                           <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strNearestTown"].":</label>$imgRequired</td>
                           <td align='right' style='position:relative;'>
                              <input id='strNearestTown' name='strNearestTown' class='controlText' list='dlNearestTown' value=\"$strNearestTown\">
                              <datalist id='dlNearestTown'>
                                 $dlNearestTown 
                              </datalist>
                              <div class='predictiveDiv' style='display:none;' id='predictiveDiv'></div>
                              </td>
                           <td align='right' width='50px'></td>
                        </tr>
                     </table>
                     <div id='ErrorContent_strNearestTown' class='Error_Text'></div> 
                     <table width='100%'>
                        <tr>
                           <td><label for='txtPhysicalAddress'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["txtPhysicalAddress"].":</label>$imgRequired</td>
                           <td align='right'><textarea class='controlText' id='txtPhysicalAddress' name='txtPhysicalAddress' class='controlText'>$txtPhysicalAddress</textarea></td>
                           <td align='right' width='50px'></td>
                        </tr>
                     </table>
                     <div id='ErrorContent_txtPhysicalAddress' class='Error_Text'></div> 

                      
                     <div id='ErrorContent_strWebsiteURL' class='Error_Text'></div>
                     <div align='center' id='msgPendingDraft' > </div>
                     <input type='hidden' id='UserID' name='UserID' value='$UserID' />
                     <input type='hidden' id='MemberID' name='MemberID' value='$MemberID' />


                  </div>
                  <input type='hidden' value='1' name='saveData' />  ";

      ## JAVASCRIPT
      $JS = "  <script> 
 
               function jsPopulateTown(town)
               {   
                  $('#strNearestTown').val(town);
               }

               function jsShowPredictive()
               {  
                  jsGetTownList();
                  $('#predictiveDiv').slideDown('fast');
               }

               function jsHidePredictive()
               { 
                  $('#predictiveDiv').slideUp('fast').delay(200);
               }

               function jsGetTownList()
               {
                  TownName = $('#strNearestTown').val();

                  $.ajax(
                  {
                     type: 'POST',
                     url: 'ajaxfunctions.php',
                     data: 'header=text&type=GetTownList&strTownName='+ TownName,
                     success: function(data)
                     {
                        $('.predictiveDiv').html(data)
                        
                     }
                  });
               }

               // PAGE VALIDATION ///////////////////////////////////////////////////////////////////////
               function jsValidation()
               {
                  $('.Error_Text').slideUp('fast');
                  msg = '';

                   
                  if(($('#strFarm').val() == '') || ($('#strFarm').val() == ' '))
                  {
                     $('#ErrorContent_strFarm').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strFarm"]."')
                     $('#ErrorContent_strFarm').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strFarm"]." \\n' ;
                  }

                   

                  if(($('#strNearestTown').val() == '') || ($('#strNearestTown').val() == ' '))
                  {
                     $('#ErrorContent_strNearestTown').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strNearestTown"]."')
                     $('#ErrorContent_strNearestTown').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strNearestTown"]." \\n' ;
                  }

                  

                  if(($('#txtPhysicalAddress').val() == '') || ($('#txtPhysicalAddress').val() == ' '))
                  {
                     $('#ErrorContent_txtPhysicalAddress').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_txtPhysicalAddress"]."')
                     $('#ErrorContent_txtPhysicalAddress').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_txtPhysicalAddress"]." \\n' ;
                  }
 
                  if(msg != '')
                  {
                     return false;
                  }
                  return true;
               } 

               </script>";

      ## RETURN
      return $Content . $JS;
   }

   ## GET THE TITLE DEAD CONTENT ##########################################################################################################################################################
   ########################################################################################################################################################################################
   ########################################################################################################################################################################################

   function getTitleDeadContent()
   {
      ## GLOBAL VARS
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $imgRequired, $_LANGUAGE,$_TRANSLATION ;


      ## COLUMN WIDTH
      $ColumWidthLabel = "width= '20%'";
      $ColumWidthInput = "width= '35%'";
      $ColumWidthIcon = "width= '5%'";
      $ColumWidthComment = "width= '40%'";

      ## INI ## :)
      $strOwner = "";
      $dblArea = "";
      $Args = "";

      $AN_Row = "";
      $AN_Counter = 0;

      $AD_Row = "";
      $AD_Counter = 0;

      ## IF MEMBER EXIST GET DETAILS AND FILL FORM
      if((isset($_SESSION[S2REGISTRATION]->FarmID)) && ($_SESSION[S2REGISTRATION]->FarmID != ""))
      {
         ## GET MEMBER AND USER DETAILS
         $FarmID = $_SESSION[S2REGISTRATION]->FarmID;
         $row = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE FarmID = " . $FarmID);

         ## UPDATE VARS
         $strOwner = $row->strOwner;
         $dblArea = $row->dblArea;

         ## GET ARUMANTS ARRAY
         $Args = unserialize($row->RegistrationArgs);
         //print_rr($Args);

         $AddedContent = "";

         ## LOOP THROUGHT TITLE ACT NUMBERS
         foreach($Args[arrAN_ID] AS $AN_ID => $AN_Val)
         {
            ## LOOP THROUGHT ACT DESCRIPTIONS
            $AN_Counter++;
            $AD_Row = "";
            foreach($Args[arrAD_ID][$AN_ID] AS $AD_ID => $AD_Val)
            {
               $AD_Counter++;
               $AD_Row .= "
               <tr>
                  <td align='center'>$AD_Counter<input type='hidden' id='AD_ID_".$AN_Counter."_$AD_Counter' class='AD_ID' name='arrAD_ID[$AN_Counter][$AD_Counter]' value='".$AN_Counter."_$AD_Counter' /></td>
                  <td align='left'><input type='text' class='controlText' style='width:180px;' name='arrAD_Field1[$AN_Counter][$AD_Counter]' value='".$Args[arrAD_Field1][$AN_ID][$AD_ID]."' id='AD_Field1_".$AN_Counter."_$AD_Counter' /></td>
                  <td align='left'><input type='text' class='controlText' style='width:180px;' name='arrAD_Field2[$AN_Counter][$AD_Counter]' value='".$Args[arrAD_Field2][$AN_ID][$AD_ID]."' id='AD_Field2_".$AN_Counter."_$AD_Counter' /></td>
                  <td align='left'><input type='text' class='controlText' style='width:90px;' name='arrAD_Field3[$AN_Counter][$AD_Counter]' value='".$Args[arrAD_Field3][$AN_ID][$AD_ID]."' id='AD_Field3_".$AN_Counter."_$AD_Counter' /></td>
                  <td align='left'><input type='text' class='controlText' style='width:90px;' name='arrAD_Field4[$AN_Counter][$AD_Counter]' value='".$Args[arrAD_Field4][$AN_ID][$AD_ID]."' id='AD_Field4_".$AN_Counter."_$AD_Counter' /></td>
                  <td align='left'><input type='text' class='controlText AD_Field5' style='width:90px; text-align:right;' name='arrAD_Field5[$AN_Counter][$AD_Counter]' value='".$Args[arrAD_Field5][$AN_ID][$AD_ID]."' id='AD_Field5_".$AN_Counter."_$AD_Counter' onChange='jsCalcHectares(); removeAlpha(this);' /></td>
                  <td align='center'> 
                     <img onclick='jsDeleteAct(this);'  src='images/delete.png' width='20px' style='cursor:pointer'/>
                  </td>
               </tr>";
            }


            $AddedContent .= "
               <div class='DeedBox' id='DeedBox_$AN_Counter' style='background-color:#f3f3f3;'>

                  <table width='100%' style='margin-top:12px;'>
                     <tr>
                        <td><label for=''>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActNumbers"].":</label>$imgRequired</td>
                        <td><label for=''>
                           <input type='hidden' name='arrAN_ID[$AN_Counter]' id='AN_ID_$AN_Counter' class='AN_ID' value='$AN_Counter' />
                           <input type='text' class='controlText' style='width:75%;' name='arrAN_Number[$AN_Counter]' value='".$Args[arrAN_Number][$AN_ID]."' id='AN_Number_$AN_Counter' />
                           <img onclick='jsDeleteDeedBlock($AN_Counter);'  src='images/delete.png' width='20px' style='padding:3px; position:absolute; cursor:pointer'/>
                        </td>
                        <td align='right' colspan='2'>
                           <input id='$AN_Counter' class='controlButton btnAddItem' type='button' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnAddActDescription"]."' onClick='jsAddItem(this.id);' name='btnAdd' />
                         </td>
                     </tr>
                     <tr>
                        <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strOwner"].":</label>$imgRequired</td>
                        <td colspan='2' align='right'><input style='width:98.4%;' id='strOwner_$AN_Counter' class='controlText' type='text' value='".$Args[arr_Owner][$AN_ID]."' name='arr_Owner[$AN_Counter]' ></td> 
                     </tr>
                  </table>

                  <table width='100%' style='margin-top:12px;' cellspacing='0' cellpadding='0' border='0'>
                     <tr>
                        <th width='20px' style='padding:4px 4px;' align='center'>#</th>
                        <th width='180px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_A"]."</th>
                        <th width='180px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_B"]."</th>
                        <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_C"]."</th>
                        <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_D"]."</th>
                        <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_E"]."</th>
                        <th width='20px' style='padding:4px 4px;' align='left'></th>
                     </tr>

                     <tr><td colspan='7'></td></tr>
                     $AD_Row
                     <tr class='itemRow' id='extraRow' style='display:none'>
                        <td align='center'>+<input type='hidden' id='AD_ID' /></td>
                        <td align='left'><input type='text' class='controlText' style='width:180px;' name='' id='AD_Field1' /></td>
                        <td align='left'><input type='text' class='controlText' style='width:180px;' name='' id='AD_Field2' /></td>
                        <td align='left'><input type='text' class='controlText' style='width:90px;' name='' id='AD_Field3' /></td>
                        <td align='left'><input type='text' class='controlText' style='width:90px;' name='' id='AD_Field4' /></td>
                        <td align='left'><input type='text' class='controlText AD_Field5' style='width:90px; text-align:right;' name='' id='AD_Field5' onChange='jsCalcHectares(); removeAlpha(this);'  /></td>
                        <td align='center'><img onclick='jsDeleteAct(this);' src='images/delete.png' width='20px' style='cursor:pointer'/></td>
                     </tr>
                  </table>
                  <div style='border-bottom:2px groove #333;'></div>

               </div>";

         }

      }

      ## HTML CONTENT
      $Content = "
                  <div class='deeds'>

                     <table width='100%' style='margin-top:12px;'>
                        <tr>
                           <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["TitleDeed"].":</label>$imgRequired</td>
                           <td align='right' colspan='2'>
                              <input id='' class='controlButton' type='button' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnAddTitleDeed"]."' onClick='jsAddDeedBox();' name='btnAdd' />
                           </td>
                        </tr>
                     </table>

                     <div style='border-bottom:1px groove #333;'></div>

                     $AddedContent

                     <div class='DeedBox' id='DeedBox_Default' style='background-color:#f3f3f3; display:none;'>


                        <table width='100%' style='margin-top:12px;'>
                           <tr>
                              <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActNumbers"].":</label>$imgRequired</td>
                              <td><label for='OptionA1'>
                                 <input type='hidden' id='AN_ID' />
                                 <input type='text' class='controlText' style='width:75%;' name='' id='AN_Number' />
                                 <img id='AN_Remove' src='images/delete.png' width='20px' style='padding:3px; position:absolute; cursor:pointer'/>
                              </td>
                              <td align='right' colspan='2'>
                                 <input id='' class='controlButton btnAddItem' type='button' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnAddActDescription"]."' onClick='jsAddItem(this.id);' name='btnAdd' /> 
                              </td>
                           </tr>
                           <tr>
                              <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strOwner"].":</label>$imgRequired</td>
                              <td colspan='3' align='left'><input style='width:98.4%;' id='strOwner' class='controlText' type='text' value=\"$strOwner\" name='strOwner' ></td> 
                           </tr>
                        </table>

                        <table width='100%' style='margin-top:12px;' cellspacing='0' cellpadding='0' border='0'>
                           <tr>
                              <th width='20px' style='padding:4px 4px;' align='center'>#</th>
                              <th width='180px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_A"]."</th>
                              <th width='180px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_B"]."</th>
                              <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_C"]."</th>
                              <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_D"]."</th>
                              <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_E"]."</th>
                              <th width='20px' style='padding:4px 4px;' align='left'></th>
                           </tr>

                           <tr><td colspan='7'></td></tr>

                           <tr class='itemRow' id='extraRow' style='display:none'>
                              <td align='center'>+<input type='hidden' id='AD_ID' /></td>
                              <td align='left'><input type='text' class='controlText' style='width:180px;' name='' id='AD_Field1' /></td>
                              <td align='left'><input type='text' class='controlText' style='width:180px;' name='' id='AD_Field2' /></td>
                              <td align='left'><input type='text' class='controlText' style='width:90px;' name='' id='AD_Field3' /></td>
                              <td align='left'><input type='text' class='controlText' style='width:90px;' name='' id='AD_Field4' /></td>
                              <td align='left'><input type='text' class='controlText AD_Field5' style='width:90px; text-align:right;' name='' id='AD_Field5' onChange='jsCalcHectares(); removeAlpha(this);' /></td>
                              <td align='center'><img onclick='jsDeleteAct(this);'  src='images/delete.png' width='20px' style='cursor:pointer'/></td>
                           </tr>
                        </table>
                        <div style='border-bottom:2px groove #333;'></div>


                     </div>


                  </div>";

      $Content .= "<div>

                     <div style='margin-top:15px;' id='ErrorContent' class='Error_Text'></div>
                     <table width='100%'>
                     <tr><td colspan='3'></td></tr>
                        <tr>
                           <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["dblArea"].":</label>$imgRequired</td>
                           <td align='right'><input id='dblArea' class='controlText controlNumeric controlLabel' type='text' value=\"$dblArea\" name='dblArea' readonly ></td>
                           <td align='right' width='50px'><div class='Tooltip'><div id='FaqIDx_19' class='Tooltip_Icon' onClick='jsHoverTooltip(19);'><img width='25px' height='25px' src='images/info-icon.png' /></div></div></td>
                        </tr>
                     </table>
                     <div id='FaqContent_19' class='Tooltip_Text'>
                        <span>".$_TRANSLATION[$_SESSION[LANGUAGE]]["Converter"]." :</span>
                        <input type='text' onChange='jsCalcArea();' class='controlText' id='AreaM' style='margin-right:20px;' />
                        <label id='AreaConverted'></label>

                     </div>
                     <div id='ErrorContent_dblArea' class='Error_Text'></div>

                     <div style='border-bottom:1px groove #333;'></div>



                  </div>
                  <input type='hidden' value='1' name='saveData' /> ";

      ## JAVASCRIPT
      $JS = "  <script>

               function jsCalcHectares()
               {
                  ccSum = 0;

                  $('.deeds').find('.AD_Field5').each(function()
                  {
                     HectareVal = $(this).val(); 
                     ccSum += parseFloat(HectareVal) || 0;
                  });

                  $('#dblArea').val(ccSum.toFixed(2));
               }



               function jsDeletRow(UN_ID)
               {

                  $('.AD_Remove_'+UN_ID).each(function()
                  {
                     var name = $(this).attr('name');
                     if ($('input[name=\"'+name+'\"]:checked').length)
                     {
                        $(this).parent().parent().remove();
                     }
                  });
                  
                  jsCalcHectares()
               }

               function jsDeleteAct(Act)
               {
                  $(Act).parent().parent().remove();
               }

               function jsDeleteDeedBlock(idx)
               {
                  $('#DeedBox_'+idx).remove();
               }


               var DeedBox_UniqueID = -1;
               function jsAddDeedBox()
               {
                  // COPY DEFAULT DEED BOX
                  var copy = $('#DeedBox_Default').clone(true).insertAfter('#DeedBox_Default');
                  var trID = 'DeedBox_'+ DeedBox_UniqueID;
                  copy.attr('id', trID);

                  // SET ACCESSABLE UNIQUE ID FOR DEED BOX
                  $('#DeedBox_'+DeedBox_UniqueID).find('.btnAddItem').each(function(){
                     $(this).attr('id', DeedBox_UniqueID);
                  });

                  $('#DeedBox_'+DeedBox_UniqueID).find('#AN_ID').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arrAN_ID['+DeedBox_UniqueID+']');
                     $(this).attr('id', Id+'_'+DeedBox_UniqueID);
                     $('#'+Id+'_'+DeedBox_UniqueID).val(DeedBox_UniqueID)
                     $(this).addClass('AN_ID');
                  });

                  $('#DeedBox_'+DeedBox_UniqueID).find('#AN_Number').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arrAN_Number['+DeedBox_UniqueID+']');
                     $(this).attr('id', Id+'_'+DeedBox_UniqueID);
                  });

                  $('#DeedBox_'+DeedBox_UniqueID).find('#strOwner').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_Owner['+DeedBox_UniqueID+']');
                     $(this).attr('id', Id+'_'+DeedBox_UniqueID);
                  });

                  $('#DeedBox_'+DeedBox_UniqueID).find('#AN_Remove').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('id', Id+'_'+DeedBox_UniqueID);
                     $(this).attr('class', 'AN_Remove');
                     $(this).attr('onClick', 'jsDeleteDeedBlock('+DeedBox_UniqueID+');');
                  });

                  $('#DeedBox_'+DeedBox_UniqueID).find('.btnRemoveItem').each(function(){
                     $(this).attr('onClick', 'jsDeletRow('+DeedBox_UniqueID+');');
                  });


                  // SHOW DEED BOX
                  $('#DeedBox_'+DeedBox_UniqueID).css('display','');

                  // CALL ADD ITEM TO CREATE ONE DEFAULT RECORD
                  jsAddItem(DeedBox_UniqueID);

                  // UPDATE UNIQUE ID VAR
                  DeedBox_UniqueID--
               }

               rowItemID = -1
               function jsAddItem(idx)
               {
                  $('#DeedBox_'+idx).find('#extraRow').each(function(){
                     var copy = $(this).clone(true).insertAfter(this);
                     var trID = 'extraRow_'+idx+'_'+rowItemID;
                     copy.attr('id', trID);

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_ID').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_ID['+ idx + '][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                        $('#'+Id+'_'+idx + '_' + rowItemID).val(idx + '_' + rowItemID)
                        $(this).addClass('AD_ID');
                     });

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_Field1').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_Field1['+ idx + '][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                     });

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_Field2').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_Field2['+ idx + '][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                     });

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_Field3').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_Field3['+ idx + '][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                     });

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_Field4').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_Field4['+ idx + '][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                     });

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_Field5').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_Field5['+ idx + '][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                     });

                     $('#extraRow_'+ idx + '_' + rowItemID).find('#AD_Remove').each(function(){
                        var Id = $(this).attr('id');
                        $(this).attr('name', 'arrAD_Remove['+idx+'][' + rowItemID + ']');
                        $(this).attr('id', Id+'_'+ idx + '_' + rowItemID);
                        $(this).attr('class', 'AD_Remove_'+idx);
                     });

                     // SHOW DEED BOX
                     $('#extraRow_' + idx + '_' + rowItemID).css('display','');
                  });



                  // UPDATE UNIQUE ID VAR
                  rowItemID--
               }

               function jsCalcArea()
               {
                  Value = $('#AreaM').val()
                  ConvertedVal = (Value/0.8567).toFixed(2);

                  if(ConvertedVal == 'NaN')
                  {
                     $('#AreaConverted').html('N/A');
                  }
                  else
                  {
                     $('#AreaConverted').html(ConvertedVal + ' Hectare');
                  }
               }

               function jsValidation()
               {

                  $('.Error_Text').slideUp('fast');
                  msg = '';

               

                  if(($('#dblArea').val() == '') || ($('#dblArea').val() == ' '))
                  {
                     $('#ErrorContent_dblArea').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_dblArea"]."')
                     $('#ErrorContent_dblArea').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_dblArea"]." \\n' ;
                  }


                  DeedRequired = 0
                  RowRequired = 0
                  DeedblockValid = 0;
                  RowItemValid = 0;
                  isDocument = false;


                  $('.DeedBox').find('.AN_ID').each(function()
                  {
                     DeedID = $(this).val();

                     ActNumber = $('#AN_Number_'+DeedID).val();

                     Owner = $('#strOwner_'+DeedID).val();

                     if(Owner == '' || Owner == ' ')
                     {
                        DeedblockValid = 1;
                     }
                     else
                     {
                        DeedRequired = 1
                     }

                     if(ActNumber == '' || ActNumber == ' ')
                     {
                        DeedblockValid = 1;
                     }
                     else
                     {
                        DeedRequired = 1
                     }

                     $('#DeedBox_'+DeedID).find('.AD_ID').each(function()
                     {
                        RowItemID = $(this).val();

                        Field1 = $('#AD_Field1_'+RowItemID).val();
                        Field2 = $('#AD_Field2_'+RowItemID).val();
                        Field3 = $('#AD_Field3_'+RowItemID).val();
                        Field4 = $('#AD_Field4_'+RowItemID).val();
                        Field5 = $('#AD_Field5_'+RowItemID).val();

                        if(Field1 == '' || Field2 == '' || Field3 == '' || Field4 == '' || Field5 == '' || Field1 == ' ' || Field2 == ' ' || Field3 == ' ' || Field4 == ' ' || Field5 == ' ')
                        {
                           RowItemValid = 1;
                        }
                        else
                        {
                           RowRequired = 1
                        }
                     });
                  });

                  if(DeedRequired == 0 || RowRequired == 0)
                  {
                     $('#ErrorContent').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_RowRequired"]."')
                     $('#ErrorContent').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_RowRequired"]." \\n' ;
                  }
                  if(DeedblockValid == 1)
                  {
                     $('#ErrorContent').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_Deedblock"]."')
                     $('#ErrorContent').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_Deedblock"]." \\n' ;
                  }
                  else if(RowItemValid == 1)
                  {
                     $('#ErrorContent').html('".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_RowItem"]."')
                     $('#ErrorContent').slideDown('fast');
                     msg += '".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_RowItem"]." \\n' ;
                  }
                  
                  if(msg != '')
                  {
                     return false;
                  }
                  return true;
               }

               </script>";

      ## RETURN
      return $Content . $JS;
   }

   ## GET THE DOCUMENTS CONTENT ###########################################################################################################################################################
   ########################################################################################################################################################################################
   ########################################################################################################################################################################################

   function getDocumentsContent()
   {
      ## GLOBAL VARS
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $imgRequired, $_LANGUAGE,$_TRANSLATION ;
      
      
      $_TRANSLATION["EN"]["ddlDocType"] = "
         <option value='Title Dead' ". ($strLegalEntityType == "Title Dead" ? "selected" : "").">Title Dead</option>
         <option value='Change of Legal Entity Name?' ". ($strLegalEntityType == "Change of Legal Entity Name?" ? "selected" : "").">Change of Legal Entity Name?</option>";

      $_TRANSLATION["AF"]["ddlDocType"] = "
         <option value='Title Dead' ". ($strLegalEntityType == "Title Dead" ? "selected" : "").">Title Dead</option>
         <option value='Change of Legal Entity Name?' ". ($strLegalEntityType == "Change of Legal Entity Name?" ? "selected" : "").">Change of Legal Entity Name?</option>";


      $Documents_Table = "";
      $Documents_Counter = 0;

      $rstDocs = $xdb->doQuery("SELECT * FROM vieDocumentFarm WHERE refFarmID = '". $_SESSION[S2REGISTRATION]->FarmID."'");
      while($rowDocs = $xdb->fetch_object($rstDocs))
      {
         $Documents_Counter++;
         $Documents_Table .= "<tr>
                                 <td align='center'>$Documents_Counter</td> 
                                 <td align='left'>".$rowDocs->strFilename."</td> 
                                 <td align='left'></td>
                                 <td align='center'><img src='images/delete.png' width='20px' onclick='jsRemoveItem(this, ".$rowDocs->DocumentID.");' /></td>
                              </tr>";
      }

      ## HTML CONTENT
      $Content = "<div>

                     <table width='100%' style='margin-top:12px;'>
                        <tr>
                           <td><label for='OptionA1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["TitleDocuments"].":</label> <br>
                           <br> <b style='font-weight:bold'>Allowed File Types :</b> PDF,PNG ,JPG & JPEG
                           <br> <b style='font-weight:bold'>Max File Size :</b> 5MB</td>
                           <td align='right' colspan='2'>
                              <input id='' class='controlButton' type='button' value='".$_TRANSLATION[$_SESSION[LANGUAGE]]["btnAdd"]."' onClick='jsAddItem();' name='btnAdd' />
                           </td>
                        </tr>
                     </table>

                     <table class='docTable' width='100%' style='margin-top:12px;' cellspacing='0' cellpadding='0' border='0'>
                        <tr>
                           <th width='20px' style='padding:4px 4px;' align='center'>#</th>
                           <th width='250px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrFileName"]."</th> 
                           <th width='20px' style='padding:4px 4px;' align='left'></th>
                           <th width='20px' style='padding:4px 4px;' align='left'></th>
                        </tr>
                        <tr><td colspan='7'></td></tr>

                        $Documents_Table

                        <tr class='itemRow' id='extraRow' style='display:none;'>
                           <td align='center'>+<input type='hidden' id='strID' /></td> 
                           <td align='left'><input type='file'  onChange='' class='controlText' style='width:99%;' name='' id='strFile' /></td>
                           <td align='left'></td>
                           <td align='center'><img src='images/delete.png' width='20px' onclick='jsRemoveItem(this, 0);' /></td>
                        </tr>

                     </table>
                     <div id='ErrorContent_Document' class='Error_Text'></div>
                     <div id='ErrorContent_AN' class='Error_Text'></div>
                     <div id='ErrorFileSize_EN' class='Error_Text'></div>
                     <div id='ErrorFileType_EN' class='Error_Text'></div>
                  </div>
                  <input type='hidden' value='1' name='saveData' /> ";


      ## JAVASCRIPT
      $JS = js("

               var uniqueID = -1;

               function jsCheckFile(inputId)
               {

                  msg = '';
                  var uploadedFile = document.getElementById('strFile_' + inputId);
                  var fileType = uploadedFile.files[0].type;
                  var fileSize = uploadedFile.files[0].size;

                 $('.Error_Text').slideUp('fast');

                  if((fileType != 'application/pdf') && (fileType != 'image/png') && (fileType != 'image/jpeg') && (fileType != 'image/jpg'))
                  {
                     
                     $('#ErrorFileType_EN').html(\"".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strFileType_Invalid"]."\")
                     $('#ErrorFileType_EN').slideDown('fast');
                     msg = 'random'
                  }
                  else
                  {
                     $('#ErrorFileType_EN').slideUp('fast');
                  }

                  if(fileSize > 5500000)
                  {
                     $('#ErrorFileSize_EN').html(\"".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strFileSize_Invalid"]."\")
                     $('#ErrorFileSize_EN').slideDown('fast');
                     msg = 'random'
                  }
                  else
                  {
                     $('#ErrorFileSize_EN').slideUp('fast');
                  }

                    return msg;
               }


               function jsAddItem()
               {
                  var copy = $('#extraRow').clone(true).insertAfter('#extraRow');
                  var trID = 'extraRow_'+ uniqueID;
                  copy.attr('id', trID);

                  $('#extraRow_'+uniqueID).find('#strID').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arrID['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                     $('#'+Id+'_'+uniqueID).val(uniqueID)
                     $(this).addClass('strID');
                  });

                  $('#extraRow_'+uniqueID).find('#ddlType').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_ddlType['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                  });

                  $('#extraRow_'+uniqueID).find('#strFile').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_strFile['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                     $(this).attr('onChange', 'jsCheckFile('+uniqueID+')');
                  });

                  $('#extraRow_'+uniqueID).find('#strUploadDate').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_strUploadDate['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                  });

                  $('#extraRow_'+uniqueID).find('#chkRemove').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_chkRemove['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                     $(this).attr('class', 'chkRemove');
                  });


                  $('#extraRow_'+uniqueID).css('display','');
                  uniqueID--
               }

               function jsRemoveItem(DocRow, IDx)
               {
                  if(IDx == 0)
                  {
                     $(DocRow).parent().parent().remove();
                  }
                  else
                  {
                     $.ajax(
                     {
                        type: 'POST',
                        url: 'ajaxfunctions.php',
                        data: 'header=text&type=DeleteDocument&DocumentID=' + IDx,
                        success: function(data)
                        {
                           try {}
                           catch(e) {}
                           finally {}
                        }
                     });

                     $(DocRow).parent().parent().remove();
                  }
               }

               function jsValidation()
               {
                  
                  $('.Error_Text').slideUp('fast');
                  msg = '';

                  $('.docTable').find('.strID').each(function(){
                     count++;
                     inputId = $(this).val()
                     return jsCheckFile(inputId);
                     
                  });
                     
                  if(msg != '')
                  {
                     return false;
                  }
                  return true;
               }");
      
      ## SERIALISED ARRAY WITH ENTITY ID AND ENTITY TYPE :: JACQUES :: 30 OKT  
      if(isset($_SESSION[S2REGISTRATION]->FarmID))
      {
         $DocTitle = $_SESSION[S2REGISTRATION]->FarmID;
         $DocDecs = "FarmTMP";
      }
      else if($_SESSION[S3REGISTRATION]->MemberID != "")
      {
         $DocTitle = $_SESSION[S3REGISTRATION]->MemberID;
         $DocDecs = "MemberTMP";
      } 

      ## FILE UPLOAD WIDGET HTML
      $NewContent  = "  <link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/CUSTOM/bootstrap.css'>  
                        <link rel='stylesheet' href='http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css'> 
                        <link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload.css'>
                        <link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload-ui.css'> 
                        <noscript><link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload-noscript.css'></noscript>
                        <noscript><link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload-ui-noscript.css'></noscript> 

                        </form>
                        <form id='fileupload' action='https://jquery-file-upload.appspot.com/' method='POST' enctype='multipart/form-data'>
                           
                           <noscript><input type='hidden' name='redirect' value='https://blueimp.github.io/jQuery-File-Upload/''></noscript>
                            
                           <div class='row fileupload-buttonbar'>
                              <div class='col-lg-9' style='width:500'> 
                                 <span class='btn btn-success fileinput-button'>
                                    <i class='glyphicon glyphicon-plus'></i>
                                    <span>Add files...</span>
                                    <input type='file' name='files[]' multiple>
                                 </span>
                                 <button type='submit' class='btn btn-primary start'>
                                    <i class='glyphicon glyphicon-upload'></i>
                                    <span>Start upload</span>
                                 </button>
                                 <button type='reset' class='btn btn-warning cancel'>
                                    <i class='glyphicon glyphicon-ban-circle'></i>
                                    <span>Cancel upload</span>
                                 </button>
                                  

                              <div class='col-lg-12 fileupload-progress fade'>
                                 <div class='progress progress-striped active' role='progressbar' aria-valuemin='0' aria-valuemax='100'>
                                    <div class='progress-bar progress-bar-success' style='width:0%;''></div>
                                 </div>
                                 <div class='progress-extended'>&nbsp;</div>
                              </div>
                           </div> 
                           <table role='presentation' class='table table-striped'><tbody class='files'></tbody></table>
                        </form>
                        <form name='frmNemo' enctype='multipart/form-data' action='". $page->SystemSettings[FULL_PATH] ."' method='post'>
 
                        <script id='template-upload' type='text/x-tmpl'>
                        
                           {% for (var i=0, file; file=o.files[i]; i++) { %}

                              <tr class='template-upload fade'>
                                 <td width='15%'>
                                    <span class='preview'></span>
                                    <input type='hidden' name='title[]' value='$DocTitle' class='form-control'> 
                                    <input type='hidden' name='description[]' value='$DocDecs' class='form-control'> 
                                 </td>
                                 <td width='35%'>
                                    <p class='name'>{%=file.name%}</p>
                                    <strong class='error text-danger'></strong>
                                 </td>
                                 <td width='15%'>
                                    <p class='size'>Processing...</p>
                                    <div class='progress progress-striped active' role='progressbar' aria-valuemin='0' aria-valuemax='100' aria-valuenow='0'><div class='progress-bar progress-bar-success' style='width:0%;'></div></div>
                                 </td>
                                 <td width='35%' align='right'>
                                    {% if (!i && !o.options.autoUpload) { %}
                                        <button class='btn btn-primary start' disabled>
                                            <i class='glyphicon glyphicon-upload'></i>
                                            <span>Start</span>
                                        </button>
                                    {% } %}
                                    {% if (!i) { %}
                                        <button class='btn btn-warning cancel'>
                                            <i class='glyphicon glyphicon-ban-circle'></i>
                                            <span>Cancel</span>
                                        </button>
                                    {% } %}
                                 </td>
                              </tr>
                           {% } %}
                        
                        </script>

   
                        <script id='template-download' type='text/x-tmpl'>
                        alert(file)
                           {% for (var i=0, file; file=o.files[i]; i++) { %}
                              <tr class='template-download fade'>
                                 <td width='15%'>
                                    <span class='preview'>
                                       {% if (file.thumbnailUrl) { %}
                                          <a href='{%=file.url%}' title='{%=file.name%}' download='{%=file.name%}' data-gallery><img src='{%=file.thumbnailUrl%}'></a>
                                       {% } %}
                                    </span>
                                 </td>
                                 <td width='35%'>
                                    <p class='name'>
                                       {% if (file.url) { %}
                                          <a href='{%=file.url%}' title='{%=file.name%}' download='{%=file.name%}' {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                       {% } else { %}
                                          <span>{%=file.name%}</span>
                                       {% } %}
                                    </p>
                                    {% if (file.error) { %}
                                       <div><span class='label label-danger'>Error</span> {%=file.error%}</div>
                                    {% } %}
                                 </td>
                                 <td width='15%'>
                                    <span class='size'>{%=o.formatFileSize(file.size)%}</span>
                                 </td>
                                 <td width='35%' align='right'>
                                    {% if (file.deleteUrl) { %}
                                       <button class='btn btn-danger delete' data-type='{%=file.deleteType%}' data-url='{%=file.deleteUrl%}'{% if (file.deleteWithCredentials) { %} data-xhr-fields='{\"withCredentials\":true}'{% } %}>
                                          <i class='glyphicon glyphicon-trash'></i>
                                          <span>Delete</span>
                                       </button> 
                                    {% } else { %}
                                       <button class='btn btn-warning cancel'>
                                          <i class='glyphicon glyphicon-ban-circle'></i>
                                          <span>Cancel</span>
                                       </button>
                                    {% } %}
                                 </td>
                              </tr>
                           {% } %}
                        </script>
                        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/vendor/jquery.ui.widget.js'></script> 
                        <script src='http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js'></script> 
                        <script src='http://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js'></script> 
                        <script src='http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js'></script> 
                        <script src='http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script> 
                        <script src='http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js'></script>
 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.iframe-transport.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-process.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-image.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-audio.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-video.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-validate.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-ui.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/main.js'></script>"; 

      $rstDocs = $xdb->doQuery("SELECT * FROM tblDocument WHERE refEntityID = ".$_SESSION[S2REGISTRATION]->FarmID." AND EntityType = 'FarmTMP'");
      while($rowDocs = $xdb->fetch_object($rstDocs))
      {  

         if($rowDocs->type == "application/pdf")
         {
            $thumbnail = "<img height='60px' src='images/pdf_icon.png'>";
         }
         else if($rowDocs->type == "image/tiff")
         {
            $thumbnail = "<img height='60px' src='images/tiff_icon.png'>";
         }
         else
         {  
            $thumbnail = "<img src='js/jQuery-File-Upload-9.11.2/server/php/files/2thumbnail/$rowDocs->strFilename'>";
         }

         $rowDocs->size = round($rowDocs->size/1024, 2);
         $CurrentContent .= " <tr class='template-download'>
                                 <td width='15%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'><span class='preview'> <a href='#' title='' download=''>  $thumbnail </a></span></td>
                                 <td width='35%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'><p class='name'> <span>$rowDocs->strFilename</span> </p></td>
                                 <td width='15%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'><span class='size'>$rowDocs->intSize KB</span> </td>
                                 <td align='right' width='35%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'> 
                                    <div class='btn btn-danger delete' onClick='jsRemoveFile(this,$rowDocs->DocumentID);' > <i class='glyphicon glyphicon-trash'></i> <span>Delete</span> </div> 
                                 </td>
                              </tr> ";
      }
      
      $JS = "  <script>
               function jsRemoveFile(ctrl, idx)
               {      
                  $(ctrl).parent().parent().hide('slow');
                  
                  $.ajax(
                  {
                     type: 'POST',
                     url: 'ajaxfunctions.php',
                     data: 'header=text&type=removeFile&DocID=' + idx,
                     success: function(data)
                     { 
                       $(ctrl).parent().parent().remove();
                     }
                  });
               }
               </script>";
      return $NewContent . "<table width='100%'>" . $CurrentContent . "</table><input type='hidden' value='1' name='saveData' />" . $JS;
      ## RETURN
      //return $Content . $JS;
   }

   ## GET THE REVIEW CONTENT ##############################################################################################################################################################
   ########################################################################################################################################################################################
   ########################################################################################################################################################################################

//TODO: add parameters for D2/D3
   function getReviewContent()
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings,$_TRANSLATION;

      $_TRANSLATION["EN"]["TypeOfRegstration_New"] = "New";
      $_TRANSLATION["AF"]["TypeOfRegstration_New"] = "New";
      $_TRANSLATION["EN"]["TypeOfRegstration_Buy"] = "Buy";
      $_TRANSLATION["AF"]["TypeOfRegstration_Buy"] = "Buy";
      $_TRANSLATION["EN"]["TypeOfRegstration_SubDiv"] = "SubDiv";
      $_TRANSLATION["AF"]["TypeOfRegstration_SubDiv"] = "SubDiv";
      $_TRANSLATION["EN"]["TypeOfRegstration_Consolidate"] = "consolidate";
      $_TRANSLATION["AF"]["TypeOfRegstration_Consolidate"] = "consolidate";
      $_TRANSLATION["EN"]["blnVB_Notice"] = "NOTE! Vineyard Blocks will be transferred during the next process. ";
      $_TRANSLATION["AF"]["blnVB_Notice"] = "NEEM KENNIS! Vineyard Blocks will be transferred during the next process.";
 
      ## INI
      $iconWrong = "<img src='images/wrong_icon.png' />";
      $iconCorrect = "<img src='images/icon-correct.png' />";

      ## LOAD
      $DoneRT = $iconWrong;
      $DoneFD = $iconWrong;
      $DoneTD = $iconWrong;
      $DoneDD = $iconWrong;

      ## GET TEMP FARM DETAILS

      $rowFarmDetails = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE FarmID = '". $_SESSION[S2REGISTRATION]->FarmID ."'",0);
      

      ## CHECK IF EACH SECTION IS COMPLETE
      $SplitStatus = str_split($rowFarmDetails->RegistrationStatus);

      if($SplitStatus[0] == 1)
      {
         $DoneRT = $iconCorrect;
      }

      if($SplitStatus[1] == 1)
      {
         $DoneFD = $iconCorrect;
      }

      if($SplitStatus[2] == 1)
      {
         $DoneTD = $iconCorrect;
      }

      if($SplitStatus[3] == 1)
      {
         $DoneDD = $iconCorrect;
      }

      ## GET CORRECT REGISTRATION TYPE
      switch ($rowFarmDetails->RegistrationType)
      {
         case "New":
            $RegType = "TypeOfRegstration_New";
            break;

         case "Buy":
            $RegType = "TypeOfRegstration_Buy";
            break;

         case "SubDiv":
            $RegType = "TypeOfRegstration_SubDiv";
            break;

         case "Consolidate":
            $RegType = "TypeOfRegstration_Consolidate";
            break;
      }//vd($_TRANSLATION[$_SESSION[LANGUAGE]][$RegType]);

      if($RegType == "TypeOfRegstration_New" || $RegType == "TypeOfRegstration_Consolidate")
      {
         $VineyardBlocks = "
            <div class='reviewSection'>
               <h6 class='SectionHeader'>
                  ".$_TRANSLATION[$_SESSION[LANGUAGE]]["VB_Heading"]."
                  <img src='images/wrong_icon.png' />
               </h6>
               <table class='SectionList'>
                  <col width='500px'>
                  <col width=''>
                  <tr>
                     <td colspan='2' style='padding-left:0px;'><i>". $_TRANSLATION[$_SESSION[LANGUAGE]]["blnVB_Notice"] ."</i></td>
                  </tr>
               </table>
            </div>";
      }
       


      $Args = "";
      if($rowFarmDetails->RegistrationArgs != "")
      {
         $Args = unserialize($rowFarmDetails->RegistrationArgs);
      }

      ## BUILD ACT NUMBER TABLE
      $AN_Table = "";
      $AN_Counter = 0;
      
      foreach($Args[arrAN_ID] AS $AN_ID => $AN_Val)
      {
         ## LOOP THROUGHT ACT DESCRIPTIONS
         $AN_Counter++;
         $AD_Row = "";
         foreach($Args[arrAD_ID][$AN_ID] AS $AD_ID => $AD_Val)
         {
            $AD_Counter++;
            $AD_Row .= "<tr>
                           <td align='center'>$AD_Counter</td>
                           <td align='left'>".$Args[arrAD_Field1][$AN_ID][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_Field2][$AN_ID][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_Field3][$AN_ID][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_Field4][$AN_ID][$AD_ID]."</td>
                           <td align='right'>". number_format($Args[arrAD_Field5][$AN_ID][$AD_ID], 2, ".",",") ."</td>
                        </tr>";

            
         }

         $AddedContent .= "
            <div class='DeedBox' style='background-color:#f3f3f3;'>

               <table width='100%' style='margin-top:12px;'>
                  <tr>
                     <td width='50%'><label for=''>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActNumbers"].":</label>$SP $SP ".$Args[arrAN_Number][$AN_ID]."</td> 
                     <td width='50%'><label for=''>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strOwner"].":</label>$SP $SP ".$Args[arr_Owner][$AN_ID]."</td> 
                  </tr>
               </table>

               <table width='100%' style='margin-top:12px;' cellspacing='1' cellpadding='2' border='0'>
                  <tr>
                     <th width='20px' style='padding:4px 4px;' align='center'>#</th>
                     <th width='180px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_A"]."</th>
                     <th width='180px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_B"]."</th>
                     <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_C"]."</th>
                     <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_D"]."</th>
                     <th width='90px' style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrActDescription_E"]."</th> 
                  </tr>

                  <tr><td colspan='100%'></td></tr>
                  $AD_Row 
               </table>

               <div style='border-bottom:2px groove #333;'></div>

            </div>";
         }

      foreach($Args[arrAN_ID] AS $AN_ID => $AN_Val)
      {
         $AN_Counter++;
         $AN_Table .= " <tr>
                           <td align='center'>$AN_Counter</td>
                           <td align='left'>".$Args[arrAN_Number][$AN_ID]."</td>
                        </tr>";
      }

      ## BUILD ACT DESCRIPTION TABLE
      $AD_Table = "";
      $AD_Counter = 0;

      foreach($Args[arrAD_ID] AS $AD_ID => $AD_Val)
      {
         $AD_Counter++;
         $AD_Table .= " <tr>
                           <td align='center'>$AD_Counter</td>
                           <td align='left'>".$Args[arrAD_Area][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_Farm][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_Yard][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_AdministrativeOffice][$AD_ID]."</td>
                           <td align='left'>".$Args[arrAD_Hectares][$AD_ID]."</td>
                        </tr>";
      }

      $Documents_Table = "";
      $Documents_Counter = 0;

      $rstDocs = $xdb->doQuery("SELECT * FROM tblDocument
         WHERE refEntityID = '".$_SESSION[S2REGISTRATION]->FarmID ."' AND EntityType = 'FarmTMP'
         ORDER BY strFilename",0);
      while($rowDocs = $xdb->fetch_object($rstDocs))
      {
         $Documents_Counter++;
         $Documents_Table .= "<tr>
                                 <td align='center'>$Documents_Counter</td> 
                                 <td align='left'>".$rowDocs->strFilename."</td> 
                              </tr>";
      }

      if($Documents_Counter == 0)
      {
         $DoneDD = $iconWrong;
      }

      ## CREATE HTML
      $strContent = "<div class='reviewWrapper'>

                        <div class='reviewSection'>
                           <h6 class='SectionHeader'>
                              <a href='registration.member.php?Step=1'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["RT_Heading"]."</a>
                              $DoneRT
                           </h6>
                           <table class='SectionList'>
                              <col width='250px'>
                              <col width=''>
                              <tr>
                                 <td style='padding-left:0px;'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["TypeOfRegstration_Title"]." :</td>
                                 <td><b style='font-weight:bold'>".$_TRANSLATION[$_SESSION[LANGUAGE]][$RegType]."</b></td>
                              </tr>
                           </table>
                        </div>

                        <div class='reviewSection'>
                           <h6 class='SectionHeader'>
                              <a href='registration.member.php?Step=2'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["FD_Heading"]."</a>
                              $DoneFD
                           </h6>
                           <table class='SectionList'>
                              <col width='250px'>
                              <col width=''>
                              <tr>
                                 <td style='padding-left:0px;'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strFarm"]." :</td>
                                 <td>$rowFarmDetails->strFarm</td>
                              </tr> 
                              <tr>
                                 <td style='padding-left:0px;'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strNearestTown"]." :</td>
                                 <td>$rowFarmDetails->strNearestTown</td>
                              </tr>
                              <tr>
                                 <td style='padding-left:0px;'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["txtPhysicalAddress"]." :</td>
                                 <td>$rowFarmDetails->txtPhysicalAddress</td>
                              </tr>
                           </table>
                        </div>

                        <div class='reviewSection'>
                           <h6 class='SectionHeader'>
                              <a href='registration.member.php?Step=3'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["TD_Heading"]."</a>
                              $DoneTD
                           </h6>
                           <table class='SectionList' width='100%'>
                              <col width='250px'>
                              <col width=''>
                              <tr>
                                 <td style='padding-left:0px;'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["strOwner"]." :</td>
                                 <td>$rowFarmDetails->strOwner</td>
                              </tr>
                              <tr>
                                 <td style='padding-left:0px;'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["dblArea"]." :</td>
                                 <td>". number_format($rowFarmDetails->dblArea, 2, ".",",") ." (Ha)</td>
                              </tr>
                              <tr>
                                 <td style='padding-left:0px;' colspan='2'>$AddedContent</td>
                              </tr> 
                           </table>
                        </div>
                        <div class='reviewSection'>
                           <h6 class='SectionHeader'>
                              <a href='registration.member.php?Step=4'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["D_Heading"]."</a>
                              $DoneDD
                           </h6>
                           <table class='SectionList' width='100%'>
                              <col width='250px'>
                              <col width=''>
                              <tr>
                                 <td style='padding-left:0px;' colspan='2'>
                                    <table width='100%' cellspacing='0' cellpadding='0' border='0' >
                                    <tr>
                                       <th style='padding:4px 4px;' align='center' width='20px'>#</th> 
                                       <th style='padding:4px 4px;' align='left'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["arrDocs_Filename"]."</th> 
                                    </tr>
                                    $Documents_Table
                                    </table>
                                 </td>
                              </tr>
                           </table>
                        </div>
                        $VineyardBlocks

                        <div class='reviewSection'>
                           <h6 class='SectionHeader'>
                              <a href='registration.member.php?Step=5'>".$_TRANSLATION[$_SESSION[LANGUAGE]]["N_Heading"]."</a>
                           </h6>

                           <textarea name='txtRegistrationNotes' class='controlText' style='width:770px;'></textarea>
                        </div>
                        <div>
                            <h6></h6>
                        </div>
                     </div>";
      return $strContent;
   }
 
   ## IF D1 / D4 GO TO S1 DIRECTLY
   ## IF D2 / NOTICE OF FARM BUY SENT TO OWNER FOR CONFIRMATION
   ## IF D3 / NOTICE OF FARM SUB DIV SENT TO OWNER FOR CONFIRMATION 
   function GetCompletedContent($RegistrationType)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings,$_TRANSLATION;

      $_TRANSLATION["EN"]["CompletedSubDiv"] = "To continue with SAWIS 2 please <input class='controlButton' style='width:90px; margin-left:10px;' value='Click Here' />";
      $_TRANSLATION["AF"]["CompletedSubDiv"] = "To continue with SAWIS 2 please <input class='controlButton' style='width:90px; margin-left:10px;' value='Click Here' />";
      $_TRANSLATION["EN"]["CompletedConsolidated"] = "To continue with SAWIS 2 please <input class='controlButton' style='width:90px; margin-left:10px;' value='Click Here' />";
      $_TRANSLATION["AF"]["CompletedConsolidated"] = "To continue with SAWIS 2 please <input class='controlButton' style='width:90px; margin-left:10px;' value='Click Here' />";

      ## GET CURRENT ARGUMENTS ARRAY
      if($RegistrationType == "SubDiv")
      {
         $Content = $_TRANSLATION[$_SESSION[LANGUAGE]]["CompletedSubDiv"];
      }
      else if($RegistrationType == "Consolidate")
      {
         $Content = $_TRANSLATION[$_SESSION[LANGUAGE]]["CompletedConsolidated"];
      } 
      
      return $Content;
   }

   function Save($FarmID, $step)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $DT;

     //print_rr($_POST[arrConsolidateFarms]);die();
      ## INI
      $dbFarm = new NemoDatabase("tmpFarm", $FarmID, null, 0);

      //print_rr($step);
 
      ## PROSESS NAME
      $strProcessName = "S2 Registration"; 
      //print_rr($step);
      switch ($step)
      {

         ## STORE FARM DETAILS IN SESSION if FARM ID IS NOT SET
         case 1:

            ## IF FARM ID IS NOT SET STORE VALUES IN SESSION UNTIL NEXT SAVE ELSE SAVE IN DATABASE
            $Args = unserialize($dbFarm->Fields["RegistrationArgs"]); 
            
            $_SESSION[S2REGISTRATION]->refMembershipType = $_POST[refMembershipType];

            switch ($_POST[refMembershipType])
            {
               case "Buy":
                  $_SESSION[S2REGISTRATION]->FarmFacility =  $Args["FarmFacility"] = $_POST[D2FarmID];
               break;

               case "SubDiv":
                  $_SESSION[S2REGISTRATION]->FarmFacility  =  $Args["FarmFacility"] = $_POST[D3FarmID];
               break;

               case "Consolidate":
                  $_SESSION[S2REGISTRATION]->FarmFacility  =  $Args["FarmFacility"] = $_POST[arrConsolidateFarms];
               break;

               default:
                  unset($Args["FarmFacility"]);
               break;
            }

            if(!isset($FarmID))
            {
               $_SESSION[S2REGISTRATION]->SaveRegType = "1";
            }
            else
            {
               $xdb->doQuery("UPDATE tmpFarm SET RegistrationArgs = '". serialize($Args) ."', RegistrationType = '". $_POST[refMembershipType] ."' WHERE FarmID = '$FarmID'",0);
            }

            break;

         ## USER DETAILS
         case 2:

            ## GET MEMBER DETAILS 
            $rowMember = $xdb->getRowSQL("SELECT * FROM tblMember WHERE MemberID = ". $_SESSION[USER]->MEMBERID);
            if(!$rowMember){
               $rowMember = $xdb->getRowSQL("SELECT * FROM tmpMember WHERE MemberID = ". $_SESSION[USER]->MEMBERID);   
            }

            ## GET THE HIGHEST CURENT FARM ID AND ADD 1000

            $MaxID = GetNewFarmID(1); ## IF ARGUMENT IS 1 IT WILL LOOK AT TEMP FARM TABLE 


            // $MaxID = $xdb->getRowSQL("SELECT MAX(FarmID) AS MaxID FROM tmpFarm");
            // $MaxID = $MaxID->MaxID + 1000;


            ## SET FIELDS THAT NEEDS TO SAVE IN FARM TABLE
            if(!isset($FarmID))
            {
               $dbFarm->blnCustomID = true;
               $dbFarm->Fields["FarmID"] = $MaxID;
               $FarmID = $MaxID;
            }

            $dbFarm->Fields["refInspectorID"] = "NULL";
            $dbFarm->Fields["strFarm"] = $_POST["strFarm"]; 
            $dbFarm->Fields["strNearestTown"] = $_POST["strNearestTown"]; 
            $dbFarm->Fields["txtPhysicalAddress"] = $_POST["txtPhysicalAddress"];
            //copy fields from member
            $dbFarm->Fields["strContact"] = "$rowMember->strTitle $rowMember->strSurname, $rowMember->strName";
            $dbFarm->Fields["strTitle"] = $rowMember->strTitle;
            $dbFarm->Fields["strName"] = $rowMember->strName;
            $dbFarm->Fields["strSurname"] = $rowMember->strSurname;
            $dbFarm->Fields["strTel"] = $rowMember->strTel;
            $dbFarm->Fields["strCell"] = $rowMember->strCell;
            $dbFarm->Fields["strFax"] = $rowMember->strFax;
            $dbFarm->Fields["strEmail"] = $rowMember->strEmail;
            $dbFarm->Fields["strWebsiteURL"] = $rowMember->strWebsiteURL;
            $dbFarm->Fields["strStatus"] = "1.0 - New";
            $dbFarm->Fields["dtRegistered"] = $DT;
            $dbFarm->Fields["strLastUser"] = $strProcessName;

            $Args = unserialize($dbFarm->Fields["RegistrationArgs"]);
            if($_SESSION[S2REGISTRATION]->SaveRegType == "1")
            {
               $Args["FarmFacility"] = $_SESSION[S2REGISTRATION]->FarmFacility;
               $dbFarm->Fields["RegistrationType"] = $_SESSION[S2REGISTRATION]->refMembershipType;

               ## UNSET SESSION VARS
               unset($_SESSION[S2REGISTRATION]->SaveRegType);
            }

            ## SAVE TO FARM TABLE
            $result = $dbFarm->Save(0,1); 
            $xdb->doQuery("UPDATE tmpFarm SET RegistrationArgs = '". serialize($Args) ."' WHERE FarmID = '$FarmID'",1);

            ## UNSET SESSION VARS
            $_SESSION[S2REGISTRATION]->FarmID = $FarmID;
//die("20151019 - CHECKED - pj");
            break;

         ## TITLE DEAD DETAILS
         case 3: 
            ## SET FIELDS THAT NEEDS TO SAVE IN USER TABLE
            //$dbFarm->Fields["refInspectorID"] = "NULL";
            //$dbFarm->Fields["dblArea"] = $_POST["dblArea"];

            $Args = unserialize($dbFarm->Fields["RegistrationArgs"]);

            $Args["arrAN_ID"] = $_POST[arrAN_ID];
            $Args["arrAN_Number"] = $_POST[arrAN_Number];
            $Args["arr_Owner"] = $_POST[arr_Owner];
            $Args["arrAD_ID"] = $_POST[arrAD_ID];
            $Args["arrAD_Field1"] = $_POST[arrAD_Field1];
            $Args["arrAD_Field2"] = $_POST[arrAD_Field2];
            $Args["arrAD_Field3"] = $_POST[arrAD_Field3];
            $Args["arrAD_Field4"] = $_POST[arrAD_Field4];
            $Args["arrAD_Field5"] = $_POST[arrAD_Field5];

            ## UPDATE
            //$result = $dbFarm->Save(0,0);

            $xdb->doQuery("UPDATE tmpFarm SET dblArea = ". $xdb->qs($_POST["dblArea"]) .", RegistrationArgs = '". serialize($Args) ."' WHERE FarmID = '$FarmID'",0);
//die("20151019 - CHECKED - pj");
            break;

         ## DOCUMENTS DETAILS SAVE
         case 4:
//WIP - jac
            print_rr($_POST);
            // print_rr($_FILES);



            foreach($_POST[arrID] AS $ID => $Val)
            {
               //CHECK FILE SIZE
              // print_rr($_FILES['arr_strFile']);die;
               // if($_FILES['arr_strFile']['size'][$ID] > 5500000)
               //    $validFileSize = false;
               // else
               //    $validFileSize = true;

               // //ALLOW CERTAIN FILE FORMATS
               // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "tiff" && $imageFileType != "pdf")
               //    $validFileType = false;
               // else
               //    $validFileType = true;


               $dbDocument = new NemoDatabase("tblDocument", $DocumentID, null, 0);
               if($_FILES['arr_strFile']['name'][$ID] != "")
               {
                  chmod($_FILES['arr_strFile']['tmp_name'][$ID] , 0777);
                  $strFileName = $_SESSION[S2REGISTRATION]->FarmID ."_". $_FILES["arr_strFile"]["name"][$ID];

                  $strPath = "./documents/";

                  $dbDocument->Fields["EntityType"] = "FarmTMP";
                  $dbDocument->Fields["refEntityID"] = $_SESSION[S2REGISTRATION]->FarmID;
                  $dbDocument->Fields["strDocumentType"] = $_POST["arr_ddlType"][$ID];
                  $dbDocument->Fields["strFilename"] = $_SESSION[S2REGISTRATION]->FarmID ."_".$_FILES["arr_strFile"]["name"][$ID];
                  $dbDocument->Fields["dtUploaded"] = date("Y-m-d");
                  $dbDocument->Fields["strLastUser"] = "test";

                  move_uploaded_file($_FILES['arr_strFile']['tmp_name'][$ID], $strPath . $strFileName);
               }
               $result = $dbDocument->Save();
            }
            break;

         default:
            # code...
            break;

      }
      if(isset($FarmID))
      {
         ## GET FARM DETAILS
         $UpdatedFarmDetails = $xdb->getRowSQL("SELECT * FROM tmpFarm WHERE FarmID = '$FarmID'");
         $countDocuments = $xdb->getRowSQL("SELECT COUNT(*) AS intCount FROM tblDocument WHERE refEntityID = '$FarmID' AND EntityType = 'FarmTMP'",0)->intCount;

         ## GET REGISTRATION STATUS VALUE
         if($UpdatedFarmDetails->RegistrationStatus == "")
         {
            $UpdatedFarmDetails->RegistrationStatus = "00000";
         }

         ## SPLIT REGISTRATION STATUS
         $SplitStatus = str_split($UpdatedFarmDetails->RegistrationStatus);

         ## GET CORRECT POSSITION TO CHANGE
         $arrPos = $step-1;

         if($UpdatedFarmDetails->RegistrationType != NULL)
         {
            $SplitStatus[0] = 1;
         }

         $SplitStatus[$arrPos] = 1;

         $arrStatus[3] = ($countDocuments>0)? 1:0;

         $SplitStatus = implode($SplitStatus);

         ## UPDATE MEMBER TABLE
         $xdb->doQuery("UPDATE tmpFarm SET RegistrationStatus = '$SplitStatus' WHERE FarmID = $FarmID");
      }

      return "some random content";
   }

   ## PROCESSES ON SUBMIT
//TODO
   public static function Submit($MemberID, $FarmID)
   {  
      
      include_once("_framework/_nemo.email.cls.php");
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $DT;

      ## UPDATE FARM STATUS TO 1.2 AND SAVE NOTES

      $xdb->doQuery("UPDATE tmpFarm SET txtRegistrationNotes = ".$xdb->qs($_POST[txtRegistrationNotes]).", strStatus = '1.1 - Initial Submision' WHERE FarmID = $FarmID"); 
      
      ## GET MEMBER DETAILS 
      $rowMember = $xdb->getRowSQL("SELECT * FROM tblMember LEFT JOIN tblMemberType ON tblMember.refMemberTypeID = tblMemberType.MemberTypeID WHERE MemberID = $MemberID");
      //print_rr($rowMember);die();
     ## INSERT BUSINESS RELATIONSHIP
      if($rowMember->strMemberTypeCode == "CELPRD")
      {
         $xdb->doQuery("INSERT IGNORE INTO tmpMemberRelationship (strType, refMemberID, refEntityID, refMemberTypeID)
                        VALUES ('Farm to Member', $MemberID, $FarmID, 17)"); // 17 == FRMCMM
      }else{
         $xdb->doQuery("INSERT IGNORE INTO tmpMemberRelationship (strType, refMemberID, refEntityID, refMemberTypeID)
                        VALUES ('Farm to Member', $MemberID, $FarmID, 19)"); // 19 == FRMOWN
      }

      //Send Notification of new Grape Producer email to Sawis for approval.
      ## EMAIL S2 ADMIN: Notification of New Grape Producer to Admin
      //GET FARM NAME
      $FarmName = $xdb->getRowSQL("SELECT strFarm, RegistrationType FROM tmpFarm WHERE FarmID = ".$FarmID,0);
      $nemoEmail = new NemoEmail($SystemSettings["Email S2 Applications To"], "" , 0);
      $nemoEmail->LoadEmailTemplate("Notice of new Grape Producer");

      $arrValues[DisplayName] = "Admin";
      $arrValues[FarmID] = $FarmID;
      $arrValues[FarmName] = $FarmName->strFarm;
      $arrValues[FarmType] = $FarmName->RegistrationType;
      $url = $SystemSettings[BASE_URL]."farm.pending.php?FarmID=$FarmID&Action=Edit";
      $arrValues[URL] = "<a href='$url'>$url</a>";

      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
      //print_rr($nemoEmail);
      $nemoEmail->Send();
      unset($nemoEmail);
      ###############END OF EMAIL################

   
      ## TICKET: SEND NEW GRAPE PRODUCER REGISTRATION EMAIL TO APPLICANT AND CC MEMEBER 
      $nemoEmail = new NemoEmail($_SESSION[USER]->EMAIL, "" , 0);  
      $nemoEmail->LoadEmailTemplate("New Grape Producer Registration ". $_SESSION[LANGUAGE]);

      $arrValues[DisplayName] = $_SESSION[USER]->USERNAME;
      $nemoEmail->Substitute($arrValues);

      ## SET HEADERS
      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
      $nemoEmail->addHeader("CC", $rowMember->strEmail);
      $nemoEmail->Cc = $rowMember->strEmail;
      
      $nemoEmail->Send();
      unset($nemoEmail); 

      if($_SESSION[S2REGISTRATION]->refMembershipType == "Buy" || $_SESSION[S2REGISTRATION]->refMembershipType == "SubDiv")
      {
         ## GET MEMBER AND FARM DETAILS
         $rstRegistrationArgs = $xdb->getRowSQL("SELECT RegistrationArgs FROM tmpFarm WHERE FarmID = ".$FarmID,0);
         $arrRegistrationArgs = unserialize($rstRegistrationArgs->RegistrationArgs);

         $OwnerOfFarmID = $arrRegistrationArgs[FarmFacility]; ## FARM YOU ARE BUYING / TRANSFERRING
         $MemberDetails = $xdb->getRowSQL("  SELECT tblFarm.strFarm AS 'FarmName', tblFarm.strEmail AS 'FarmEmail', tblMember.strName AS 'MemberName', tblMember.strEmail AS 'MemberEmail'
                                             FROM vieMemberRelationship 
                                             INNER JOIN tblFarm ON vieMemberRelationship.EntityID = tblFarm.FarmID
                                             INNER JOIN tblMember ON vieMemberRelationship.MemberID = tblMember.MemberID
                                             WHERE vieMemberRelationship.EntityID = $OwnerOfFarmID  AND (vieMemberRelationship.strMemberTypeCode IN('FRMOWN','FRM000'))
                                             GROUP BY tblFarm.strEmail, tblMember.strName, tblMember.strEmail",0);
      }
 
      switch($_SESSION[S2REGISTRATION]->refMembershipType)
      {   
         case "Buy": //D2 
            ## SEND NOTIFICATION OF FARM TRANSFER TO MEMBER(FARM OWNER) AND CC FARM EMAIL 
            $nemoEmail = new NemoEmail($MemberDetails->MemberEmail, "" , 0);
            $nemoEmail->LoadEmailTemplate("Notice Farm Transfer ".$_SESSION[LANGUAGE]);
            
            ## REPLACE STRINGS (DISPLAY NAME, FARM ID, FARMNAME, URL)
            $arrValues[DisplayName] = $MemberDetails->MemberName;
            $arrValues[FarmID] = $OwnerOfFarmID;
            $arrValues[FarmName] = $MemberDetails->FarmName;
            $url = $SystemSettings[BASE_URL]."farm.pending.transfer.php";
            $arrValues[url] = "$url";
            $nemoEmail->Substitute($arrValues);

            ## SET HEADERS
            $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
            $nemoEmail->From = $SystemSettings["SMTP Send As"];
            $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
            $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
            $nemoEmail->addHeader("CC", $MemberDetails->FarmEmail);
            $nemoEmail->Cc = $MemberDetails->FarmEmail;
 
            $nemoEmail->Send();
            unset($nemoEmail);
            break;   

         case "SubDiv": //D3
            ##S END NOTIFICATION OF FARM SUB DIVISION TO MEMBER(FARM OWNER) AND CC FARM EMAIL
            $nemoEmail = new NemoEmail($MemberDetails->MemberEmail, "" , 0);
            $nemoEmail->LoadEmailTemplate("Notice Farm Subdivision ".$_SESSION[LANGUAGE]);
            
            ## REPLACE STRINGS (DISPLAY NAME, FARM ID, FARMNAME, URL)
            $arrValues[DisplayName] = $MemberDetails->MemberName;
            $arrValues[FarmID] = $OwnerOfFarmID;
            $arrValues[FarmName] = $MemberDetails->FarmName;
            $url = $SystemSettings[BASE_URL]."farm.pending.subdivision.php";
            $arrValues[url] = "$url";
            $nemoEmail->Substitute($arrValues);

            ## SET HEADERS
            $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
            $nemoEmail->From = $SystemSettings["SMTP Send As"];
            $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
            $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
            $nemoEmail->addHeader("CC", $MemberDetails->FarmEmail);
            $nemoEmail->Cc = $MemberDetails->FarmEmail;
            
            $nemoEmail->Send();
            unset($nemoEmail);
            break;

         ## D4 :: CONSOLIDATE
         case "Consolidate":
            foreach($_SESSION[S2REGISTRATION]->FarmFacility AS $oldFarmID => $Status)
            {
               CopyBlocks($oldFarmID, $FarmID); 
            }
            ## NEED TO GO TO S1 Wizzard [@controller]
            break;
      }


      unset($_SESSION[S2REGISTRATION]->refMembershipType);

   }

}
?>