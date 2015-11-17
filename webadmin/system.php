<?php
//20141126 - new mysqli class - http://php.net/manual/en/mysqlinfo.api.choosing.php - pj

   ini_set('display_errors', '1');
   error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING);


   $DATABASE_SETTINGS["lamp"]->hostname = "localhost";
   $DATABASE_SETTINGS["lamp"]->username = "root";
   $DATABASE_SETTINGS["lamp"]->password = "123ert";
   $DATABASE_SETTINGS["lamp"]->database = "sawis";
   $DATABASE_SETTINGS["lamp"]->sessionpath = $_SERVER['DOCUMENT_ROOT']."/sawis/webadmin/SESSION/";
   
//demo 
   // $DATABASE_SETTINGS["www.overdrive.co.za"]->hostname = "cmysql5-5.wadns.net";
   // $DATABASE_SETTINGS["www.overdrive.co.za"]->username = "usr_sawis";
   // $DATABASE_SETTINGS["www.overdrive.co.za"]->password = "P@ssw0rd";
   // $DATABASE_SETTINGS["www.overdrive.co.za"]->database = "sil27_Sawis";
   // $DATABASE_SETTINGS["www.overdrive.co.za"]->sessionpath = $_SERVER['DOCUMENT_ROOT']."/Sawis/webadmin/SESSION/";
   // if($_SERVER[SERVER_NAME] == "overdrive.co.za") $_SERVER[SERVER_NAME] = "www.overdrive.co.za";

   $DATABASE_SETTINGS["dev.overdrive.co.za"]->hostname = "cmysql5-5.wadns.net";
   $DATABASE_SETTINGS["dev.overdrive.co.za"]->username = "usr_sawis";
   $DATABASE_SETTINGS["dev.overdrive.co.za"]->password = "P@ssw0rd";
   $DATABASE_SETTINGS["dev.overdrive.co.za"]->database = "sil27_Sawis";
   $DATABASE_SETTINGS["dev.overdrive.co.za"]->sessionpath = $_SERVER['DOCUMENT_ROOT']."/sawis/webadmin/SESSION/";
   //if($_SERVER[SERVER_NAME] == "overdrive.co.za") $_SERVER[SERVER_NAME] = "www.overdrive.co.za";
 

//live
   $DATABASE_SETTINGS["10.0.0.149"]->hostname = "srv22-050";
   $DATABASE_SETTINGS["10.0.0.149"]->username = "root";
   $DATABASE_SETTINGS["10.0.0.149"]->password = "Sil05Adm!";
   $DATABASE_SETTINGS["10.0.0.149"]->database = "sawis";
   $DATABASE_SETTINGS["10.0.0.149"]->sessionpath = $_SERVER['DOCUMENT_ROOT'] ."/sawis/SESSION/";

   if($_SERVER[SERVER_NAME] == "sawis-online.co.za") $_SERVER[SERVER_NAME] = "www.sawis-online.co.za";
   $DATABASE_SETTINGS["www.sawis-online.co.za"]->hostname = "srv22-050";
   $DATABASE_SETTINGS["www.sawis-online.co.za"]->username = "root";
   $DATABASE_SETTINGS["www.sawis-online.co.za"]->password = "Sil05Adm!";
   $DATABASE_SETTINGS["www.sawis-online.co.za"]->database = "sawis";
   $DATABASE_SETTINGS["www.sawis-online.co.za"]->sessionpath = $_SERVER['DOCUMENT_ROOT'] ."/sawis/SESSION/";


   $SystemSettings[SERVER_NAME] = $_SERVER[SERVER_NAME]; 
   $strTemp = explode("/", $_SERVER[SERVER_PROTOCOL]);
   $SystemSettings[SERVER_PROTOCOL] = strtolower($strTemp[0]);
   $strTemp = array_reverse(explode("/", $_SERVER[SCRIPT_NAME]));
   $SystemSettings[SCRIPT_NAME] = $strTemp[0];
   $SystemSettings[REQUEST_URI] = $_SERVER[REQUEST_URI];

   $SystemSettings[FULL_URL] = $SystemSettings[SERVER_PROTOCOL] ."://". $SystemSettings[SERVER_NAME] . $_SERVER[REQUEST_URI];
   $SystemSettings[FULL_PATH] = $SystemSettings[SERVER_PROTOCOL] ."://". $SystemSettings[SERVER_NAME] . $_SERVER[SCRIPT_NAME];
   $SystemSettings[BASE_URL] = $SystemSettings[SERVER_PROTOCOL] ."://". $SystemSettings[SERVER_NAME] . str_replace($SystemSettings[SCRIPT_NAME], "", $_SERVER[SCRIPT_NAME]);
   $SystemSettings[argv] = $_SERVER[argv];

   ini_set("session.gc_maxlifetime","7200");
   ini_set("session.save_path", $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->sessionpath);
   session_start();

   include_once "_framework/system.functions.inc.php";
   include_once "_framework/_nemo.translations.inc.php";
   include_once("_framework/_nemo.menu.cls.php");
   include_once("_framework/_nemo.menu.translator.cls.php");
   include_once "project.functions.inc.php";
   global $xdb, $HTTP, $MYSQLI;
   
   $HTTP = $SystemSettings[SERVER_PROTOCOL]; //might not work, set manually // = "http://";

   //20141126 - new mysqli class - http://php.net/manual/en/mysqlinfo.api.choosing.php - pj
   $db = 
   $MYSQLI = mysqli_connect($DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->hostname
                       , $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->username
                       , $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->password
                       , $DATABASE_SETTINGS[$SystemSettings[SERVER_NAME]]->database);

   if(mysqli_connect_errno()) {
      $SystemMessage->Message = "Database Connection has Failed! [". mysqli_connect_error() ."]";
      $SystemMessage->Type = "Critical Error";
      //print_rr($DATABASE_SETTINGS);
      //print_rr($SystemSettings);
      print_rr($SystemMessage);
      
   }else{
      $xdb = new NemoDatabase("sysSettings");
      //$row = $xdb->getRowSQL("SELECT * FROM sysSettings WHERE strSetting = 'Rows per Page'");
      //$arrSys["Rows per Page"] = $row->strValue;
      $xdb->getList();
      while($row = $xdb->fetch())
      {
         $strSetting = $row->strSetting;
         $SystemSettings[$strSetting] = $row->strValue;
      }
   }
   
?>
