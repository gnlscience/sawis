<?php
   //2015-06-18 Remove inspector and refMemberID from my.profile - christiaan
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/user.cls.php");

   $page = new Nemo();
//events
   switch($Action){
      case "Save":
         //$page->fields
         $Message = User::SaveMyProfile($_SESSION[USER]->ID);
         break;
   }

   $page = new NemoDetails();

   $page->AssimulateTable("sysUser", $_SESSION[USER]->ID, "strUser");

   $page->Fields["UserID"]->Control->type = "hidden";
   $page->Fields["UserID"]->Control->comment = "";
   
   $page->Fields["strUser"]->Control->readonly = "readonly";
   $page->Fields["strUser"]->Control->class = "controlText controlLabel";

   $rowSecurityGroup = $xdb->getRowSQL("SELECT * FROM sysSecurityGroup WHERE SecurityGroupID = " . $page->Fields["refSecurityGroupID"]->Control->value);
 
   $page->Fields["refSecurityGroupID"]->Control->tag = "input";
   $page->Fields["refSecurityGroupID"]->Control->innerHTML = " ";
   $page->Fields["refSecurityGroupID"]->Control->readonly = "readonly";
   $page->Fields["refSecurityGroupID"]->Control->value = $rowSecurityGroup->strSecurityGroup;
   $page->Fields["refSecurityGroupID"]->Control->class = "controlText controlLabel";

   $page->Fields["strEmail"]->Control->readonly = "readonly";
   $page->Fields["strEmail"]->Control->class = "controlText controlLabel";

   //Build strPassword Element
   $page->Fields["strPassword"] = nCopy($page->Fields["strPasswordMD5"]);
   $page->Fields["strPassword"]->Label = "New Password";
   $page->Fields["strPassword"]->COLUMN_NAME = "strPassword";
   $page->Fields["strPassword"]->Control->name = "strPassword";
   $page->Fields["strPassword"]->Control->id = "strPassword";
   $page->Fields["strPassword"]->Control->value = "";
   $page->Fields["strPassword"]->jsValidate = "";
   $page->Fields["strPassword"]->Control->tag = "input";
   $page->Fields["strPassword"]->Control->type = "password";
   $page->Fields["strPassword"]->Control->autocomplete = "off";
   $page->Fields["strPassword"]->Control->class = "controlText";
   $page->Fields["strPassword"]->Control->onchange = "if($('#strPassword').val() != $('#strPasswordConfirm').val() && $('#strPasswordConfirm').val() != ''){ alert('Passwords do not match.');}";
   $page->Fields["strPassword"]->ControlHTML = "";
   $page->Fields["strPassword"]->ORDINAL_POSITION = $page->Fields["strEmail"]->ORDINAL_POSITION +0.2;
   
   //Build strOldPassword Element
   $page->Fields["strOldPassword"] = nCopy($page->Fields["strPassword"]); 
   $page->Fields["strOldPassword"]->Label = "Old Password";
   $page->Fields["strOldPassword"]->COLUMN_NAME = "strOldPassword";
   $page->Fields["strOldPassword"]->Control->name = "strOldPassword";
   $page->Fields["strOldPassword"]->Control->id = "strOldPassword";
   $page->Fields["strOldPassword"]->Control->onchange = "";
   $page->Fields["strOldPassword"]->ORDINAL_POSITION = $page->Fields["strEmail"]->ORDINAL_POSITION +0.1;

   //Build strPasswordConfirm Element
   $page->Fields["strPasswordConfirm"] = nCopy($page->Fields["strOldPassword"]);
   $page->Fields["strPasswordConfirm"]->Label = "Confirm Password";
   $page->Fields["strPasswordConfirm"]->COLUMN_NAME = "strPasswordConfirm";
   $page->Fields["strPasswordConfirm"]->Control->name = "strPasswordConfirm";
   $page->Fields["strPasswordConfirm"]->Control->id = "strPasswordConfirm";
   $page->Fields["strPasswordConfirm"]->Control->onchange = "if($('#strPassword').val() != $('#strPasswordConfirm').val() && $('#strPassword').val() != ''){ alert('Passwords do not match.');}";
   $page->Fields["strPasswordConfirm"]->ORDINAL_POSITION = $page->Fields["strEmail"]->ORDINAL_POSITION +0.3;

   $page->Fields["strPasswordMD5"]->jsValidate = "";
   $page->Fields["strPasswordMD5"]->Control->type = "hidden";

   $page->Fields["Profile:PicturePath"]->Control->type = "file";
   $page->Fields["Profile:PicturePath"]->Control->class = "controlText controlFile";
   $page->Fields["Profile:PicturePath"]->jsValidate = "";

   $page->Fields["strSetting:Language"]->Control->comment = "";

   $profilePic = "<img src='". $page->SystemSettings[ProfileImageDirAdmin].($page->Fields["Profile:PicturePath"]->VALUE==""?"blank.jpg":$page->Fields["Profile:PicturePath"]->VALUE)."' title='". $page->Fields["strDisplayName"]->VALUE ."' target=_blank width='100px'
            class='userPP'style='' >";//onLoad='positionProfilePic(this)';

   $page->Fields["txtActive"] = nCopy($page->Fields["blnActive"]);
   $page->Fields["txtActive"]->Control->type = "text";
   $page->Fields["txtActive"]->Control->class = "controlText controlLabel";
   $page->Fields["txtActive"]->Control->readonly = "readonly";
   $page->Fields["txtActive"]->Control->value = $page->Fields["blnActive"]->Control->value==1?"Yes":"No";

   $page->Fields["blnActive"]->Control->type = "hidden";

   //2015-06-18 Remove inspector and refMemberID from my.profile - christiaan
   unset($page->Fields["blnInspector"]);
   unset($page->Fields["refMemberID"]);

   $JS =  "if( $('#strPassword').val() != $('#strPasswordConfirm').val())
            { 
               msg += '\\n New password and confirm password (do not match)'; 
            }
         " ;
   
   $page->ToolBar->Buttons[btnExport]->blnShow = 0;
   $page->ToolBar->Buttons[btnReload]->blnShow = 0;
   $page->ToolBar->Buttons[btnClose]->blnShow = 0;

   $page->intColumns = 1;
   //Hide unnecessary fields
   //unset($page->Fields["UserID"],$page->Fields["refSecurityGroupID"],$page->Fields["blnActive"]);

   $page->renderControls();

   $page->ContentLeft = $page->renderTable($page->ToolBar->Label).$page->getJsNemoValidateSave($JS);
   $page->ContentRight = $profilePic;
   // $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave()
   //    ."<i>*Note that updating your email address or password will update your details in the celestis webadmin where the email address is an exact match!</i>";

   $page->Message->Text = $Message;

   $page->Display();
?>
