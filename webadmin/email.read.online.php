<?php
/*
   $_SESSION[USER]->ID = "1";
   $_SESSION[USER]->USERNAME = "pj";
   $_SESSION[USER]->EMAIL = "pj@overdrive.co.za";
   $_SESSION[USER]->SECURITYGROUPID = "1";

   buttons from http://cooltext.com/
   //20150723 - Upgrade to be php 5.6 compliant - Christiaan
*/
   include_once("_framework/_nemo.basic.cls.php");


   $page = new NemoBasic();


# IF YOU VIEW EMAIL ONLINE CHANE EMAIL STATUS TO READ
   if(isset($_REQUEST[UID]))
   {
      $xdb->doQuery("UPDATE tblEmail SET strStatus = 'Read', strLastUser = 'Email Read Receipt' WHERE UniqueID = ". $xdb->qs($_REQUEST[UID])." AND strStatus NOT IN('Error')");
   }

//print_rr($page);

   $strEmailBody = "<span style='text-align: center;'>The requested email could not be retrieved.</span>";
   if($UID != "")
   {
      if($rowEmail = $xdb->getRowSQL("SELECT * FROM tblEmail WHERE UniqueID = ". $xdb->qs($UID)))
      {
         if($rowEmail->strCC != "")
            $strCC = "
            <span class='textGraphite'>CC:</span> ". htmlentities($rowEmail->strCC) ."$BR";
         $ifEmailFoundStyle = "text-align: left;";
         //not h1, span the heading
         $strEmailHeader = "
            <span class='heading textColour' style='width: 100%;'>".htmlentities($rowEmail->strSubject)."</span>
            $BR
            $BR
            <span class='textGraphite'>From:</span> ". htmlentities($rowEmail->strFrom) ."$BR
            <span class='textGraphite'>To:</span> ". htmlentities($rowEmail->strTo) ."$BR
            $strCC
            <span class='textGraphite'>Date:</span> $rowEmail->dtEmail$BR
            <span class='textGraphite'>Status:</span> $rowEmail->strStatus$BR
            $BR";
         $strEmailBody = $rowEmail->txtBody;

         //replacing using regex between two words: http://stackoverflow.com/questions/30407850/deleting-text-between-two-strings-in-php-using-preg-replace
         //remove multipart boundry from readonline for header
         $strEmailBody = preg_replace('/--==Multipart_Boundary_x[\s\S]+?Encoding: 7bit/', '', $strEmailBody);

         //remove multipart boundry from readonline for attachments
         $strEmailBody = preg_replace('/--==Multipart_Boundary_x[\s\S]+.*/', '', $strEmailBody); //dont use ? to limit it as we want the regex to be greedy

         
         //preserve html code and only format the special characters : http://stackoverflow.com/questions/4776035/convert-accents-to-html-but-ignore-tags
         $strEmailBody = htmlspecialchars_decode(htmlentities($strEmailBody, ENT_NOQUOTES, 'ISO8859-1'), ENT_NOQUOTES);

      }
   }

   $page->Content = "
      $BR
      $BR
      <div xstyle='background: none; width: inherit; height: inherit !important;' class='blokkie' >
         <p style='$ifEmailFoundStyle'>
            $strEmailHeader
            $strEmailBody
         </p>
      </div>
      $BR
      $BR
   ";
   # ADDED MAX WITH TO FOOTER SO THAT NO OVERFLOW HAPPENS - JACQUES - 20131119
   echo "   <style>
                  #content img{
                     max-width: 100%
                  }
            </style>";

   $page->Display("layout.online.email.incl.php");//"layout.online.email.incl.php"

?>