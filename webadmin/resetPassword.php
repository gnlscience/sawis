<?php

   include_once("_framework/_nemo.basic.cls.php"); 

   unset($_SESSION[USER]);

   $page = new NemoBasic();
 
 
## EVENTS

   if($Action == "ResetSave")
   {

      $row = $xdb->getRowSQL("SELECT * FROM sysUser  
                              WHERE strEmail = ".$xdb->qs($strUsername)." AND strPasswordMD5 = ".$xdb->qs($_POST[ResetKey]));

      if(isset($row->strPasswordMD5))
      { 
         $xdb->doQuery("UPDATE sysUser SET strPasswordMD5 = '".md5($_POST[strPassword])."' 
                        WHERE strEmail = ".$xdb->qs($row->strEmail));

         windowLocation("index.php?LoginMsg=resetSuccess");
      }
      else
      {
         windowLocation("index.php?LoginMsg=resetError");
      } 
   } 

   $strEmail = $_GET[email];
   $strResetKey = $_GET[resetKey]; 

   $attrLogin->onclick="return Validate();";
   $attrLogin->Class="controlButton";
   $attrText->Class="controlText";

## PAGE START
      
   $page->Content = " 
   <div class='dora-LoginBox'>
      <table id='rrr'>
         <caption>Reset Password</caption>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td style='width:25%;' align='right'>Email:</td>
            <td style='width:75%;'><input id='strUsername' class='controlText' type='text' value='$strEmail' name='strUsername' ></td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td align='right'>Password:</td>
            <td><input id='strPassword' class='controlText' type='Password' value='' name='strPassword' onkeyup='passwordStrength(this.value)' onchange='jsVerify()'></td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td align='right'>Verify Password:</td>
            <td>
               <input id='strVerifyPassword' class='controlText' type='Password' value='' name='strVerifyPassword' onchange='jsVerify()'>
               <img id='verify' src='images/icon-correct.png' width='15px' style='display:none; position:relative;  left:-22px; top:4px;'/>
            </td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td></td>
            <td><div id='validateMsg' style='display:none; Color:red;'></div></td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr>
         <tr>
            <td>
               <input type='hidden' value='$strEmail' name='ResetEmail' />
               <input type='hidden' value='$strResetKey' name='ResetKey' />
            </td>
            <td><input id='Action' class='controlButton' type='submit' value='ResetSave' name='Action' onclick='return Validate();'> </td>
         </tr>
         <tr><td colspan='2' style='height:10px;'></td></tr> 

      </table> 
      <div class='BarWrapper'>
         <div id='passwordStrength' class='strength0'>
            <div id='passwordDescription'>Password not entered</div>
         </div>
      </div> 
      <div align='center' id='success' style='display:none; background-color:#2ba800; color:#ffffff;padding:10px; '>
          Reset Email Has Been send.
      </div>
      <div align='center' id='error' style='display:none; background-color:#c80000; color:#ffffff;padding:10px; font-weight:bold;'>
           
      </div>
   </div>

   "
   .js("

         function jsVerify()
         {
            password = $('#strPassword').val();
            passwordVerify = $('#strVerifyPassword').val(); 

            if(password == passwordVerify)
            {
               $('#verify').show();
            }
            else
            {
               $('#verify').hide();
            }
         }
         function passwordStrength(password)
         {
            var desc = new Array();
            desc[0] = 'Very Weak';
            desc[1] = 'Weak';
            desc[2] = 'Better';
            desc[3] = 'Medium';
            desc[4] = 'Strong';
            desc[5] = 'Strongest';

            var score   = 0;

            //if password bigger than 6 give 1 point
            if (password.length > 6) score++;

            //if password has both lower and uppercase characters give 1 point   
            if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;

            //if password has at least one number give 1 point
            if (password.match(/\d+/)) score++;

            //if password has at least one special caracther give 1 point
            if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )  score++;

            //if password bigger than 12 give another 1 point
            if (password.length > 12) score++;

             document.getElementById('passwordDescription').innerHTML = desc[score];
             document.getElementById('passwordStrength').className = 'strength' + score;
         }
 

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
            email = $('#strUsername').val();
            password = $('#strPassword').val();
            passwordVerify = $('#strVerifyPassword').val(); 


            if((email == '') || (email == ' '))
            {
               msg = 'Email Address Needed';
               $('#validateMsg').html(msg);
               $('#validateMsg').show();
               return false;
            }
            else
            {
               if((password == '') || (password == ' '))
               {
                  msg = 'No Password Entered';
                  $('#validateMsg').html(msg);
                  $('#validateMsg').show();
                  return false;
               }
               else
               {
                  if(password == passwordVerify)
                  {
                     return true;
                  }
                  else
                  {
                     msg = 'Passwords does not match';
                     $('#validateMsg').html(msg);
                     $('#validateMsg').show();
                     return false;
                  }
               }
            }


           


            
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
