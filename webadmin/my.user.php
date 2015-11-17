<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.cls.php");
   include_once("includes/my.user.cls.php");

   $page = new Nemo();

//events
   switch($Action){
      case "Reload":
         windowLocation("?Action=Edit&UserID=$UserID");
         break;
      case "Save":
         $Message = User::Save($UserID);
         break;
      case "Delete":
         $Message = User::Delete($_POST[chkSelect]);
         break;
   }
//nav

   switch($Action){
      case "Save":
      case "Edit":
      case "New":
         $MemberID = $_SESSION['USER']->MEMBERID;
         $page = new NemoDetails();

         $page->AssimulateTable("sysUser", $UserID, "strUser");
         //print_rr($page->Fields);

         $page->Fields["UserID"]->Control->type = "hidden";
         $page->Fields["UserID"]->Control->comment = "";

         //Add dropdown to select default security role to populate user function table for new user
         $page->Fields["refRoleID"]->Label = "Default role";
         $page->Fields["refRoleID"]->Control->comment = "Select the default role for the user.";
         $page->Fields["refRoleID"]->Control->tag = "Select";
         $page->Fields["refRoleID"]->Control->class = "controlText";
         $page->Fields["refRoleID"]->Control->id = "refRoleID";
         $page->Fields["refRoleID"]->Control->name = "refRoleID";

         $page->Fields["refRoleID"]->sql = "
                        SELECT RoleID AS ControlValue, strRole AS ControlText
                        FROM sysRole
                        ORDER BY ControlText ASC";
         $ddlRole = "
                           SELECT id='refRoleID' class='controlText' tag='Select' style='text-align: left;' value='' is_nullable='NO' comment='' name='refRoleID'>
                           <option value='0' style='text-align: left;'>- Select -</option>";
               
         $rst = $xdb->doQuery($page->Fields["refRoleID"]->sql);

         while($row = $xdb->fetch_object($rst))
         {
            $selected = "";
            if($row->ControlValue == $page->Fields["refRoleID"]->Control->value )
            {
                $selected = "selected";
            }
            $ddlRole .="<option value='$row->ControlValue' $selected style='text-align: left;'>$row->ControlText</option>";
         }
         $ddlRole .= "</select>";  
         $page->Fields["refRoleID"]->Control->innerHTML = $ddlRole;

         //Modify existing dropdown for security group and limit it to Member Superuser and Member User
         $page->Fields["refSecurityGroupID"]->sql = "
                        SELECT 0 AS ControlValue, '-  Select  -' AS ControlText
                        UNION ALL
                        SELECT `SecurityGroupID` AS ControlValue, sysSecurityGroup.strSecurityGroup AS ControlText
                        FROM `sysSecurityGroup`
                        WHERE `SecurityGroupID` = '11' OR `SecurityGroupID` = '12'
                        ORDER BY ControlText ASC";
         $ddlSecurityGroup = "
                           SELECT id='refSecurityGroupID' class='controlText' tag='Select' style='text-align: left;' value='' is_nullable='NO' comment='' name='refSecurityGroupID'>
                           <option value='0' style='text-align: left;'>- Select -</option>";
               
         $rst = $xdb->doQuery($page->Fields["refSecurityGroupID"]->sql);

         while($row = $xdb->fetch_object($rst))
         {
            $selected = "";
            if($row->ControlValue == $page->Fields["refSecurityGroupID"]->Control->value )
            {
                $selected = "selected";
            }
            $ddlSecurityGroup .="<option value='$row->ControlValue' $selected style='text-align: left;'>$row->ControlText</option>";
         }
         $ddlSecurityGroup .= "</select>";  
         $page->Fields["refSecurityGroupID"]->Control->innerHTML = $ddlSecurityGroup;


         $page->Fields["refMemberID"]->Control->comment = "";
         $page->Fields["refMemberID"]->Control->type = "hidden";
         $page->Fields["refMemberID"]->Control->value = $MemberID;

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
         $page->Fields["strPassword"]->ORDINAL_POSITION = $page->Fields["strEmail"]->ORDINAL_POSITION +0.1;

         //Build strPasswordConfirm Element
         $page->Fields["strPasswordConfirm"] = nCopy($page->Fields["strPassword"]);
         $page->Fields["strPasswordConfirm"]->Label = "Confirm Password";
         $page->Fields["strPasswordConfirm"]->COLUMN_NAME = "strPasswordConfirm";
         $page->Fields["strPasswordConfirm"]->Control->name = "strPasswordConfirm";
         $page->Fields["strPasswordConfirm"]->Control->id = "strPasswordConfirm";
         $page->Fields["strPasswordConfirm"]->Control->onchange = "if($('#strPassword').val() != $('#strPasswordConfirm').val() && $('#strPassword').val() != ''){ alert('Passwords do not match.');}";
         $page->Fields["strPasswordConfirm"]->ORDINAL_POSITION = $page->Fields["strEmail"]->ORDINAL_POSITION +0.2;
         $page->Fields["strPasswordMD5"]->jsValidate = "";
         $page->Fields["strPasswordMD5"]->Control->type = "hidden";
 
         $page->Fields["Profile:PicturePath"]->Control->type = "file";
         $page->Fields["Profile:PicturePath"]->Control->class = "controlFile";
         $page->Fields["Profile:PicturePath"]->jsValidate = "";

         $profilePic = "<img src='". $page->SystemSettings[ProfileImageDirAdmin].$page->Fields["Profile:PicturePath"]->VALUE ."' title='". $page->Fields["strDisplayName"]->VALUE ."' target=_blank width='100px' class='userPP'style='' onLoad='positionProfilePic(this);'>";
        
         $JS =  "if( $('#strPassword').val() != $('#strPasswordConfirm').val())
                  { 
                     msg += '\\nNew password and confirm password (do not match)'; 
                  }
               " ;     

         $page->renderControls();
         $page->ContentLeft = $page->renderTable($page->ToolBar->Label) . $page->getJsNemoValidateSave($JS);

         if($Action != "New") {
            $page->Content = User::getRoleEntities($UserID);
         }
         if($Action == "New") {
            $page->Content = User::getRoleEntities();
         }

         break;
      default:
         $page = new User(array("UserID"));
      // print_rr($_SESSION);die;
         $page->Content = $page->getList();
         break;
   }

   $page->Message->Text = $Message;
   $page->Display();
?>
