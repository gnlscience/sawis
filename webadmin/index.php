<?php
//20150611 - changed to home from index.php - pj

   include_once("_framework/_nemo.basic.cls.php");

   $page = new NemoBasic();

   //unset SESSION
   //unset($_SESSION);
   session_destroy();
//events
   // if($Action == "Redirect")
   // {

   //    $row = $xdb->getRowSQL("SELECT sysUser.*
   //                               FROM sysUser INNER JOIN sysSecurityGroup ON sysSecurityGroup.SecurityGroupID = sysUser.refSecurityGroupID
   //                               WHERE strEmail = ".$xdb->qs($strUsername)." AND strPasswordMD5 = ".$xdb->qs($strPasswordMD5),1);
   //    $strPassword = $row->strPassword;
   //    $Action = "Login";
   // }

   if(isset($username))
      $strUsername = $username;

   if($_POST[strPassword])
   {
      $strPasswordMD5 = md5($_POST[strPassword]);
   }

   if($Action == "Login")
   {/*do the login process
      if username
         if password md5
            set session
   */         
         session_start();

         $row = $xdb->getRowSQL("SELECT sysUser.*
                                 FROM sysUser INNER JOIN sysSecurityGroup ON sysSecurityGroup.SecurityGroupID = sysUser.refSecurityGroupID
                                 WHERE strEmail = ".$xdb->qs($strUsername)." AND sysSecurityGroup.blnActive = 1",0); 

         if($row)
         {
            if($row->blnActive == 1)
            {//vd($row->strPasswordMD5); vd($strPasswordMD5); die;
               if($row->strPasswordMD5 == $strPasswordMD5)
               {//print_rr($row);

                  $_SESSION[USER]->ID = $row->UserID;
                  $_SESSION[USER]->USERNAME = $row->strUser;
                  $_SESSION[USER]->EMAIL = $row->strEmail;
                  $_SESSION[USER]->SECURITYGROUPID = $row->refSecurityGroupID;
                  $_SESSION[USER]->MEMBERID = $row->refMemberID;
   //print_rr($_SESSION); die;
                  //sawis only
                  $strSettingLanguage = "strSetting:Language";
                  $_SESSION[USER]->LANGUAGE = strtoupper(substr($row->$strSettingLanguage,0,2));

                  tblLoginInsert("Login successful");

                  switch($Nav)
                  {
                     case "SAWIS2":
                        $nav = "registration.farm.php";
                        break;
                     default:
                        $nav = "home.php";
                  }
//vd($nav);
                  header("Location: $nav"); //20150611 - changed to home from index.php - pj
                  die;
               }
               else
               {
                  tblLoginInsert("Login failed: Incorrect password");
                  $M = "Username or Password is incorrect.";
                  $T = "warning";

                  $M .= CheckSecurityLock($row->UserID);
               }

            }
            else
            {
               tblLoginInsert("Login failed: Inactive User");
               $M = "User account is inactive.";
               $T = "warning";
            }
         }
         else
         {
            tblLoginInsert("Login failed: User not found or SG not active");
            $M = "Login Details are invalid.";
            $T = "warning";
         }
   }

   if($u!="")
      $strUsername = $u;
//var_dump($_SESSION);

//page start

   $page->Message->Text = $M;
   $page->Message->class = $T;
//print_rr($page);

   $attrLogin->onclick="return Validate();";
   $attrLogin->Class="controlButton";
   $attrText->Class="controlText";

   if($_GET[LoginMsg] == "resetSuccess")
   {
      $LoginMsg = "  <tr><td colspan='2' style='height:10px;'></td></tr>
                     <tr>
                        <td style='width:25%;' align='right'> </td>
                        <td style='width:75%; color:Green; font-size:12px;'>Password Has Been Reset. Please login with new password.</td>
                     </tr>";
   }
   else if($_GET[LoginMsg] == "resetError")
   {
      $LoginMsg = "  <tr><td colspan='2' style='height:10px;'></td></tr>
                     <tr>
                        <td style='width:25%;' align='right'> </td>
                        <td style='width:75%; color:red;  font-size:12px;'>Password Reset Failed. Please try again.</td>
                     </tr>";
   }
   else
   {
      $LoginMsg = "";
   }

   $a = "35%";
   $page->Content = " 
   <div class='dora-LoginBox'>
      <table id='rrr'>
         <caption>Login</caption>
         $LoginMsg 
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td style='width:25%;' align='right'>Email:</td>
            <td style='width:75%;'>". $page->controls->createControl("", "strUsername","",$strUsername, $attrText) ."</td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td align='right'>Password:</td>
            <td>". $page->controls->createControl("password", "strPassword","","", $attrText) ."</td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td></td>
            <td>
            	". $page->controls->createControl("submit", "Action","","Login", $attrLogin) ." 
            	<a style='margin-left:10px;' href='#' onclick='jsResetPassword();'>Forgot Password?</a> 
            	<a style='margin-left:10px;' href='registration.member.php' >Register User?</a>
            </td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr> 

      </table> 

      <div id='resetPassword' style='display:none; '>
         <table  style='background-color:#eaeaea !important; border-top:solid 1px #999 !important; margin-bottom:0px !important;'>  
            <tr><td colspan='2' style='height:10px;'></td></tr> 
            <tr> 
               <td colspan='2' align='center' style='width:75%;'>Please submit your email in order to recieve the password reset process.</td>
            </tr>
            <tr><td colspan='2' style='height:10px;'></td></tr> 
            <tr>
               <td style='width:25%;' align='right'>Email:</td>
               <td style='width:75%;'>". $page->controls->createControl("", "strEmail","",$strEmail, $attrText) ."</td>
            </tr>
            <tr><td colspan='2' style='height:10px;'></td></tr>
            <tr>
               <td></td>
               <td><input class='controlButton' type='button' value='Reset Password' name='resetPassword' onclick='jsStartResetProcedure();''> <img id='loaderGif' style='display:none; margin: -10px 20px;' width='30px' height='30px' src='images/loadingAnimation.gif' /></td>
            </tr>
            <tr><td colspan='2' style='height:10px;'></td></tr>
         </table>
      </div>
      <div align='center' id='success' style='display:none; background-color:#2ba800; color:#ffffff;padding:10px; '>
          Reset Email Has Been send.
      </div>
      <div align='center' id='error' style='display:none; background-color:#c80000; color:#ffffff;padding:10px; font-weight:bold;'>
           
      </div>
   </div>

   "
   .js("

         function jsStartResetProcedure()
         {
            $('#loaderGif').show('fast').delay( 800 );
            $('#success').slideUp('fast');
            $('#error').slideUp('fast');

            Email = trim($('#strEmail').val());  
            if(Email == '')
            {
               $('#error').html('Please type in your email address.');
               $('#error').slideDown('fast');
            }
            else
            {
               $.ajax(
               {
                  type: 'POST',
                  url: 'ajaxfunctions.php',
                  data: 'header=text&type=SendResetEmail&strEmail=' + Email,
                  success: function(data)
                  { 
                     if(data == 1)
                     {  
                        $('#success').html('Reset Email Has Been send to <span style=\"font-weight:bold\">' + Email + '</span>.')
                        $('#success').slideDown('fast').delay( 800 );
                     }    
                     else if(data == 0)
                     {
                        $('#error').html('<span style=\"font-weight:bold\">' + Email + '</span> does not exist in our database.')
                        $('#error').slideDown('fast').delay( 800 );
                     }                       
                  }
               });
            }
             $('#loaderGif').hide('fast');
         }

         function jsResetPassword()
         {
            if($('#resetPassword').is(':visible'))
            {
               $('#resetPassword').slideUp('fast');
            }
            else
            {
               $('#resetPassword').slideDown('fast');
            }
           
         }

         function Validate()
         {
            msg = '';

            return true;
         }

         if(d('strUsername').value == '')
         {
            d('strUsername').focus();
         }else{
            d('strPassword').focus();
         }

         ");

   $page->Display();

?>