<?php
   //include_once("_nemo.basic.cls.php");
   include_once("_framework/_nemo.cls.php");
   //print_rr($_SESSION);die;
   if(!isset($_SESSION[USER]) || $MID == "s44" || $MID == "r10")
   {
      $u = $_SESSION[USER]->EMAIL;
      $page = new NemoBasic();
      //session_destroy();
      unset($_SESSION[USER]);
   }else{
      $page = new Nemo();
   }
//print_rr($MID);
//events
//page start
//Meassage Content:
   switch($MID)
   {//no default case!
      case "s13":
         $strMessage = "Your session has expired. Click <a href='index.php'>here</a> to log in again.";
         $T = "restricted";
         break;
      case "s44":
         $strMessage = "You have successfully been logged out. Click <a href='index.php?u=$u'>here</a> to log in again.";
         break;
      case "r09":
         $strMessage = "You do not have sufficient access to view this page! [". htmlentities($p) .":". $page->SystemSettings[USER]->SECURITYGROUPID ."]";
         $T = "restricted";
         break;
      case "r10":
         $strMessage = "An internal error has occurred! Please contact the Systems Administrator. [". htmlentities($p) .":". $page->SystemSettings[USER]->SECURITYGROUPID ."]";
         $T = "error";
         break;
   }

   if($strMessage != "")
   {
      $page->Message->Text = $strMessage;
   }elseif($M != ""){
      $page->Message->Text = htmlentities($M);
   }
   $page->Display("layout.menu.incl.php");//use this layout


?>
