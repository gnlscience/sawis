<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/security.group.cls.php");

   $page = new Nemo();

//print_rr($_POST);
//events
   switch($Action)
   {
      case "Reload":
         windowLocation("?Action=Edit&SecurityGroupID=$SecurityGroupID");
         break;
      case "Save":
         $Message = SecurityGroup::Save($SecurityGroupID);
         break;
      case "Delete":
         $Message = SecurityGroup::Delete($_POST[chkSelect]);
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
         $page->AssimulateTable("sysSecurityGroup", $SecurityGroupID);
         $page->Fields["SecurityGroupID"]->Control->type = "hidden";

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave();

         if($Action != "New")
            $page->Content = SecurityGroup::getSecurityEntities($SecurityGroupID);

         break;
      default:
         $page = new SecurityGroup(array("SecurityGroupID"));
         $page->Content = $page->getList($SecurityGroupID);
   }
   $page->Display();


?>
