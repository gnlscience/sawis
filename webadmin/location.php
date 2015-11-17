<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/location.cls.php");




   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&LocationID=$LocationID");
         break;
      case "Save":
         $Message = Location::Save($LocationID);
         break;
      case "Delete":
         $Message = Location::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("dwdLocation", $LocationID, "WOCode");

         $page->Fields["LocationID"]->Control->type = "hidden";

         $page->Fields["GeoCode"]->Control->onchange="jsChangeLocationID();";
         $page->Fields["RegionCode"]->Control->onchange="jsChangeLocationID();";
         $page->Fields["DistrictCode"]->Control->onchange="jsChangeLocationID();";
         $page->Fields["WardCode"]->Control->onchange="jsChangeLocationID();";

         $page->Fields["blnLockLocationID"]->Control->onchange="jsChangeLockLocationID();";         

         $strGeo = $page->Fields["strGeo"]->Control->value;
         $strRegion = $page->Fields["strRegion"]->Control->value;
         $strDistrict = $page->Fields["strDistrict"]->Control->value;
         $strWard = $page->Fields["strWard"]->Control->value;

         $page->Fields["WOCode"]->Control->comment = "$strGeo \ $strRegion \ $strDistrict \ $strWard";

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave().
         js("
            $( document ).ready(function() 
               {
                  jsChangeLocationID();
                  jsChangeLockLocationID();

                  $('#WOCodeOld').parent().parent().after('<tr><td style=\"background-color:#ccc\" colspan=\"2\"><b>Geographical Location</b></td></tr>');
                  $('#AF_strGeo').parent().parent().after('<tr><td style=\"background-color:#ccc\" colspan=\"2\"><b>Region</b></td></tr>');
                  $('#AF_strRegion').parent().parent().after('<tr><td style=\"background-color:#ccc\" colspan=\"2\"><b>District</b></td></tr>');
                  $('#AF_strDistrict').parent().parent().after('<tr><td style=\"background-color:#ccc\" colspan=\"2\"><b>Ward</b></td></tr>');
                  $('#AF_strWard').parent().parent().after('<tr><td style=\"background-color:#ccc\" colspan=\"2\"><b>Other</b></td></tr>');
               });

               function jsChangeLocationID()
               {
                  if($('#blnLockLocationID').is(':checked')) {
                     $('#WOCode').attr('readonly', false);
                  } else {
                     $('#WOCode').hide('fast');
                     $('#WOCode').attr('readonly', true);
                     var WOCode = $('#GeoCode').val() + $('#RegionCode').val() + $('#DistrictCode').val() + $('#WardCode').val();
                     $('#WOCode').val(WOCode);
                     $('#WOCode').show('fast');
                  }
               };

               function jsChangeLockLocationID()
               {
                  if($('#blnLockLocationID').is(':checked')) {
                     $('#WOCode').attr('readonly', false);
                  } else {
                     $('#WOCode').attr('readonly', true);
                  }
               };               
         ");
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new Location("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;           
      default:
         $page = new Location(array("LocationID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
