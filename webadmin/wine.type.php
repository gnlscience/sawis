<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/wine.type.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&WineTypeID=$WineTypeID");
         break;
      case "Save":
         $Message = WineType::Save($WineTypeID);
         break;
      case "Delete":
         $Message = WineType::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblWineType", $WineTypeID, "WineTypeCode");

         $page->Fields["WineTypeID"]->Control->type = "hidden";

         $page->Fields["strCategoryCode"]->Control->onchange="jsChangeWineTypeCode();";
         $page->Fields["strSubCategoryCode"]->Control->onchange="jsChangeWineTypeCode();";
         $page->Fields["strAlcoholContentCode"]->Control->onchange="jsChangeWineTypeCode();";
         $page->Fields["strDescriptionCode"]->Control->onchange="jsChangeWineTypeCode();";
         $page->Fields["TypeCode"]->Control->onchange="jsChangeWineTypeCode();";
         $page->Fields["strSubTypeCode"]->Control->onchange="jsChangeWineTypeCode();";
         $page->Fields["strSubDescriptionCode"]->Control->onchange="jsChangeWineTypeCode();";

         // // Build a dropdown list of WineType
         // $page->Fields["strWineTypeTypeCode"]->Control->tag = "Select";
         // $page->Fields["strWineTypeTypeCode"]->Control->class = "controlText";
         // $page->Fields["strWineTypeTypeCode"]->sql = "
         //                SELECT TypeCode AS ControlValue, strWineType AS ControlText
         //                FROM vieWineType_EN 
         //                ORDER BY ControlText ASC";
         // $ddlWineType = "
         //                   SELECT id='strWineType' class='controlText' tag='Select' style='text-align: left;' value='' is_nullable='NO' comment='' name='strWineType'>
         //                   <option value='0' style='text-align: left;'>- Select -</option>";
               
         // $rst = $xdb->doQuery($page->Fields["strWineTypeTypeCode"]->sql);
              
         // while($row = $xdb->fetch_object($rst))
         // {
         //    $checked = "";
         //    if($row->ControlValue == $page->Fields["strWineTypeTypeCode"]->Control->value )
         //    {
         //       $checked = "selected = true";
         //    }
         //    $ddlWineType .="<option value='$row->ControlValue' style='text-align: left;' $checked>$row->ControlText</option>";
         // }
         // $ddlWineType .= "</select>";        
         // $page->Fields["strWineTypeTypeCode"]->Control->innerHTML = $ddlWineType;
         // // ----- Build a dropdown list of WineType

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave().
         js("
            $( document ).ready(function() 
               {
                  jsChangeWineTypeCode();
               });

               function jsChangeWineTypeCode()
               {
                  $('#WineTypeCode').hide('fast');

                  var WineTypeCode = $('#strCategoryCode').val() + $('#strSubCategoryCode').val() +
                                     $('#strAlcoholContentCode').val() + $('#strDescriptionCode').val() + 
                                     $('#TypeCode').val() + $('#strSubTypeCode').val() + $('#strSubDescriptionCode').val();

                  $('#WineTypeCode').val(WineTypeCode);
                  $('#WineTypeCode').show('fast');
               };            
         ");
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new WineType("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new WineType(array("WineTypeID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
