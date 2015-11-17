<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/user.roles.cls.php");

   $page = new Nemo();

//print_rr($_POST);
//events
   switch($Action)
   {
      case "Reload":
         windowLocation("?Action=Edit&RoleID=$RoleID");
         break;
      case "Save":
         $Message = SecurityRole::Save($RoleID);
         break;
      case "Delete":
         $Message = SecurityRole::Delete($_POST[chkSelect]);
         break;
   }
//nav
   switch($Action)
   {
      case "Save":
      case "Edit":
      case "New":
         $page = new NemoDetails();
         $page->Message->Text = $Message;
         $page->AssimulateTable("sysRole", $RoleID);
         $page->Fields["RoleID"]->Control->type = "hidden";

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();

         if($Action != "New") {
            $page->Content = SecurityRole::getRoleEntities($RoleID);
         }
         if($Action == "New") {
            $page->Content = SecurityRole::getRoleEntities();
         }

         break;
      default:
         $page = new SecurityRole(array("RoleID"));
         $page->Content = $page->getList();
   }
   $page->Display();


?>
