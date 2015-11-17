<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/settings.cls.php");

   $page = new Nemo();
//events
   switch($Action)
   {
      case "Save":
         $page->Message->Text = $Message = Settings::Save($SettingID);
         break;
      case "Create Views":
         $Message = Settings::CreateViews();
         break;
   }
//nav
   switch($Action)
   {
      case "Save":
      case "Edit":
         $page = new NemoDetails();
         $page->ToolBar->Buttons[btnReload]->blnShow = 0;

         $page->Message->Text = $Message;
         $page->AssimulateTable("sysSettings", $SettingID);
         $page->Fields["SettingID"]->Control->type = "hidden";
         $page->Fields["strSetting"]->Control->class = "controlText controlLabel";
         $page->Fields["strSetting"]->Control->readonly = "readonly";

         $page->ToolBar->Label = $page->ToolBar->Label .": ". $page->Fields["strSetting"]->Control->value;
         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();
         break;
      default:
         $securitygroup = new Settings(array("SettingID"));

         $page->ToolBar->Buttons["btnNew"]->Control->value = "Create Views";
         $page->ToolBar->Buttons["btnNew"]->blnShow = 1;

         $page->Content = $securitygroup->getList();
   }
   $page->Message->Text = $Message;
   $page->Display();
?>
