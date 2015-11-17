<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/cultivar.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&CultivarID=$CultivarID");
         break;
      case "Save":
         $Message = Cultivar::Save($CultivarID);
         break;
      case "Delete":
         $Message = Cultivar::Delete($_POST[chkSelect]);
         break;
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;

         $page->AssimulateTable("tblCultivar", $CultivarID, "strCultivar");

         $page->Fields["CultivarID"]->Control->type = "hidden";

         // Build a dropdown list of WineType
         $page->Fields["strWineTypeTypeCode"]->Control->tag = "Select";
         $page->Fields["strWineTypeTypeCode"]->Control->class = "controlText";
         $page->Fields["strWineTypeTypeCode"]->sql = "
                        SELECT TypeCode AS ControlValue, strWineType AS ControlText
                        FROM vieWineType_EN 
                        ORDER BY ControlText ASC";
         $ddlWineType = "
                           SELECT id='strWineType' class='controlText' tag='Select' style='text-align: left;' value='' is_nullable='NO' comment='' name='strWineType'>
                           <option value='0' style='text-align: left;'>- Select -</option>";
               
         $rst = $xdb->doQuery($page->Fields["strWineTypeTypeCode"]->sql);
              
         while($row = $xdb->fetch_object($rst))
         {
            $checked = "";
            if($row->ControlValue == $page->Fields["strWineTypeTypeCode"]->Control->value )
            {
               $checked = "selected = true";
            }
            $ddlWineType .="<option value='$row->ControlValue' style='text-align: left;' $checked>$row->ControlText</option>";
         }
         $ddlWineType .= "</select>";        
         $page->Fields["strWineTypeTypeCode"]->Control->innerHTML = $ddlWineType;
         // ----- Build a dropdown list of WineType

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new Cultivar("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new Cultivar(array("CultivarID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
