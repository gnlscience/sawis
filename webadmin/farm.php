<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/farm.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&FarmID=$FarmID");
         break;
      case "Save": 
         $Message = Farm::Save($FarmID);
         break;
      case "Delete":
         $Message = Farm::Delete($_POST[chkSelect]);
         break;
   }

//print_rr($_POST);  die;
/*print_rr($_GET);  
print_rr($_REQUEST);*/ 
//print_rr($_SESSION);  

//redirect
   if($_POST[RETURN_URL] != "")
   {
      $url = $_POST[RETURN_URL] ."&". $_POST[RETURN_VAR] ."=". $$_POST[RETURN_VAR]; //vd($url); die;
      windowLocation($url);
   }

//nav
   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         //$page->hideOtherLanguage = true;
         $page->AssimulateTable("tblFarm", $FarmID, "strFarm");
         $page->Fields["FarmID"]->Control->type = "hidden";
         // Build a dropdown list of Inspector
         /*$page->Fields["refInspectorID"]->Control->type = "";
         $page->Fields["refInspectorID"]->Control->tag = "Select";
         $page->Fields["refInspectorID"]->Control->class = "controlText";*/
         /*$page->Fields["refInspectorID"]->sql = "
                        SELECT UserID AS ControlValue, strUser AS ControlText
                        FROM sysUser 
                        ORDER BY ControlText ASC";*/
         $page->Fields["refInspectorID"]->sql = Farm::sqlInspectorDDL();
         //$page->Fields["refInspectorID"]->html->innerHTML = Farm::sqlInspectorDDL();
         //$page->Fields["refInspectorID"]->Control->type = "";
         //$page->Fields["refInspectorID"]->Control->tag = "Select";
         //$page->Fields["refInspectorID"]->Control->class = "controlText";
         //$page->Fields["refInspectorID"]->html->value = "0"; 
         //$page->Fields["refInspectorID"]->html->class = "controlText";         
         //$page->Fields["refInspectorID"]->sql = "";
         //$page->Fields["refInspectorID"]->html->innerHTML = Farm::sqlInspectorDDL();
         //print_rr($page->Fields["refInspectorID"]);
                
         $page->Fields["dtRegistered"]->Label = "Date Registered";
         $page->Fields["RegistrationStatus"]->Control->type = "hidden";

         $page->Fields["RegistrationArgs"]->Control->type = "hidden";
         unset($page->Fields["RegistrationArgs"]);

         $page->Fields["RegistrationType"]->Control->type = "hidden";
         unset($page->Fields["RegistrationType"]);         
         //if adding a new farm from a member details page
         if($_REQUEST[RETURN_URL] != ""){
            $hMemberID = "<input type='hidden' name='MemberID' value='$MemberID' />";
         }        
         
         // $ddlInspector = "
         //                   SELECT id='refInspectorID' class='controlText' tag='Select' style='text-align: left;' value='' is_nullable='NO' comment='' name='refInspectorID'>
         //                   <option value='0' style='text-align: left;'>- Select -</option>";
               
         // $rst = $xdb->doQuery($page->Fields["refInspectorID"]->sql);
              
         // while($row = $xdb->fetch_object($rst))
         // {
         //    $checked = "";
         //    if($row->ControlValue == $page->Fields["refInspectorID"]->Control->value )
         //    {
         //       $checked = "selected = true";
         //    }
         //    $ddlInspector .="<option value='$row->ControlValue' style='text-align: left;' $checked>$row->ControlText</option>";
         // }
         // $ddlInspector .= "</select>";        
         // $page->Fields["refInspectorID"]->Control->innerHTML = $ddlInspector;
         // ----- Build a dropdown list of Inspector

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label).$hMemberID.$page->getJsNemoValidateSave();
         break;
      case "Export":
         header("Content-type: application/ms-excel");
         header("Content-Disposition: attachment; filename=". str_replace(" ","",$page->Entity->Name) ."_".date("YmdHis").".xls");
         $page = new Farm("");
         $page->isPageable = 0;
         echo $page->getList();
         die;
         break;         
      default:
         $page = new Farm(array("FarmID"));
         $page->isPageable = 1;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
