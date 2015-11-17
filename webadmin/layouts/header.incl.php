<?php
if(($this->SystemSettings[SCRIPT_NAME] == "index.php") || ($this->SystemSettings[SCRIPT_NAME] == "registration.member.php") || ($this->SystemSettings[SCRIPT_NAME] == "message.php") || ($this->SystemSettings[SCRIPT_NAME] == "resetPassword.php"))
{
   $CustomBackground = "background: url(images/".$this->SystemSettings[LoginBG].") no-repeat center center fixed;";
}
else
{
   $CustomBackground = "background: url(images/1.jpg) no-repeat center center fixed;";
}

 
$dod = "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Frameset//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd'>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>"; ## ALTERNATE NOT USED

echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>

<html xmlns='http://www.w3.org/1999/xhtml'>
<head>

   <!-- PAGE TITLE -->
   <title>". $this->getTitle() ."</title>

   <!-- META DATA -->
   <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

   <!-- FAV ICON -->
   <link rel='shortcut icon' href='". $this->SystemSettings[BASE_URL] ."/images/icon.ico' />

   <!-- CSS INCLUDES --> 
   <link rel='stylesheet' href='css/menu_style.css' type='text/css' />";

   //include_once "_framework/_nemo.css.php";
   echo "<link rel='stylesheet' href='css/colourlessNEW.css' type='text/css' />";
   echo "  
   <link rel='stylesheet' href='css/toolbar.css' type='text/css' />
   <link rel='stylesheet' href='css/menu_tree.css' type='text/css' />
   <link rel='stylesheet' href='css/smoothness/jquery-ui-1.8.6.custom.css' type='text/css' />
    
   <script type='text/javascript' src='js/jquery.js'></script>
   <script type='text/javascript' src='js/passwordstrength.js'></script>
   <script type='text/javascript' src='js/spin.js'></script>
   <script type='text/javascript' src='js/jquery.inputmask.js'></script>
   <script type='text/javascript' src='js/jquery-ui-1.8.6.custom.min.js'></script>
   <script type='text/javascript' src='js/scripts.js'></script>
   <!--<script type='text/javascript' src='js/maxlength.js'></script>-->
   <script type='text/javascript' src='js/jquery.touchSwipe.min.js'></script>

   <!-- CUSTOM SCRIPTS -->
   <script>
      $(function()
      {
         //$(function() {
         //   $('input[class*=datePicker]').datepicker({dateFormat: 'yy-mm-dd'});
         //});
         $(document).ready(function() {
            $(function() {
               $('.tblNemoList tr').hover(function() {
                  $(this).css('background-color', '#ffffdd');
               },
               function() {
                  $(this).css('background-color', '#FFFFFF');
               });
            });
         });
      });

      function setLanguage(currentURL, strLanguage)
      {

         $.ajax(
         {
            type: 'POST',
            url: 'ajaxfunctions.php',
            data: 'header=text&type=setLanguage&strLanguage='+ strLanguage,
            success: function(data)
            {//alert('arrg');
               window.location.href = currentURL;
            }
         });
         
         
      }

      function setLanguageWithoutUser(currentURL, strLanguage)
      {

         $.ajax(
         {
            type: 'POST',
            url: 'ajaxfunctions.php',
            data: 'header=text&type=setLanguageWithoutUser&strLanguage='+ strLanguage,
            success: function(data)
            {
               window.location.href = currentURL;
            }
         });
         
         
      }

   </script> ". js(getJSRemoveAlpha()) ."

   <!-- COLOR SCHEME -->
   <style type='text/css'>

   body
   {
      $CustomBackground
   }
   :root 
   {  
      --SiteColorMain: ".$this->SystemSettings[SiteColorMain].";
      --SiteColorLight: ".$this->SystemSettings[SiteColorLight].";
      --SiteColorDark: ".$this->SystemSettings[SiteColorDark].";

      --SiteColorAlt: ".$this->SystemSettings[SiteColorAlt].";
      --SiteColorAlt2: ".$this->SystemSettings[SiteColorAlt2].";
      --SiteColorAlt3: ".$this->SystemSettings[SiteColorAlt3].";
 
   }
 
   </style>
</head>
";
?>