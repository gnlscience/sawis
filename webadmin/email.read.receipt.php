<?php
/*
   //20150723 - Upgrade to be php 5.6 compliant - Christiaan
*/
   include_once("_nemo.basic.cls.php");


   $page = new NemoBasic();
   if(isset($_REQUEST[UID]))
   {
      $xdb->doQuery("UPDATE tblEmail SET strStatus = 'Read', strLastUser = 'Email Read Receipt' WHERE UniqueID = ". $xdb->qs($_REQUEST[UID])." AND strStatus NOT IN('Error')",0);
   }

   $rowEmail = $xdb->getRowSQL("SELECT * FROM tblEmail WHERE UniqueID = ". $xdb->qs($_REQUEST[UID]));
   if($rowEmail)
   {  
      $arr = explode('IP=', $rowEmail->txtBody);
      $str = $arr[1];
      $refEncryptID = strtok($str,  '&');
      //print_rr($refEncryptID);

      //CHANGE TO TOOLS DATABASE
      // $db = mysql_connect(    $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->hostname, 
      //                         $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->username,
      //                         $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->password);
      //       mysql_select_db(  $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->database); 
      $db = 
      $MYSQLI = mysqli_connect($DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->hostname
                              , $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->username
                              , $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->password
                              , $DATABASE_SETTINGS2[$SystemSettings[SERVER_NAME]]->database);

      $xdb->doQuery("UPDATE catSurveyEmailResponse SET strStatus = 'Read' WHERE refEncryptID = ". $xdb->qs($refEncryptID) ." AND strStatus NOT IN('Completed', 'Deregistered')",0);

      //CHANGE TO ACADEMY DATABASE
      // $db = mysql_connect(    $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->hostname,
      //                         $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->username,
      //                         $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->password);
      //       mysql_select_db(  $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->database);
      $db = 
      $MYSQLI = mysqli_connect($DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->hostname
                              , $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->username
                              , $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->password
                              , $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->database); 

   }

   $im = imagecreatetruecolor(1, 1);
   $text_color = imagecolorallocate($im, 255, 20, 147);

   // Set the content type header - in this case image/jpeg
   header('Content-Type: image/jpeg');

   // Output the image
   imagejpeg($im);

   // Free up memory
   imagedestroy($im);

?>
