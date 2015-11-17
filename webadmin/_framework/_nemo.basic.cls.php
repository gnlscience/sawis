<?php
include_once("system.php");
include_once("_framework/_nemo.control.cls.php");


class NemoBasic
{
   public $SystemSettings = array();
   public $Content;
   public $Layout = "layout.basic.incl.php";
   public $controls;
   public $db;

   public $Message;

   public function __construct()
   {
      global $SystemSettings;

      $this->SystemSettings = $SystemSettings;
      $this->db = new NemoDatabase("");

      if(is_array($_SESSION))
      {//extend the $session into the SysSettings
         foreach($_SESSION as $key => $value)
         {
            $this->SystemSettings[$key] = $value;
         }
      }

      if(is_dir("layouts")){
         $dir = "";
      }else{
         $dir = "../";
      }
      self::loadFileArray("Layouts",$dir. "layouts");//wc
      self::loadFileArray("Modules",$dir. "modules");

      $this->controls = new NemoControl();
   }

   private function __autoload()
   {// only applies to zend

   }

   public function Display()
   {
      include_once($this->Layouts[$this->Layout]);
   }

   function loadFileArray($variable,$strDir)
   {//wc
      $this->$variable = self::readDirectory($strDir);
   }

   function readDirectory($strDir)
   {// wc
      $arrFiles = array();

      if ($handle = opendir($strDir))
      {
         while (false !== ($file = readdir($handle)))
         {
            if ($file != "." && $file != "..")
            {
               $arrFiles[$file] = $strDir."/".$file;
            }
         }
         closedir($handle);
      }

      return $arrFiles;
   }

   // protected function headTitle() //replaced by getTitle()
   // {//gets called from layout.basic
   //    return $this->SystemSettings["Title"];
   // }

   protected function Header()
   {//gets called from layout.basic
      global $_LANGUAGE, $_TRANSLATION, $BR;

      $db = "";
      if($this->SystemSettings[SERVER_NAME] == "lamp"){
         global $DATABASE_SETTINGS;
         $db = ": ". $DATABASE_SETTINGS[lamp]->database;
      }
      
      $header = " <div class='dora-HeaderBar'>
                     <div class='dora-HeaderLogo'><img style='width:130px !important' src='images/logoSmall.png' /></div>
                     <div class='dora-HeaderName'> <h1 style='padding-top:12px;'>". $this->getBrand() ."</h1> </div> 
                     <div class='dora-HeaderUser'>";

      if($this->SystemSettings[SCRIPT_NAME] == "registration.member.php")
      {
         if(is_array($_LANGUAGE))
         {
            $header .= "<div style='position:relative; top:8px; float:left; padding-left:20px;' align='right'>";
            $header .= "<a href='index.php' >
                              <div class='headerLoginBtn'> Login</div>
                           </a> ";
            foreach ($_LANGUAGE as $Lang => $obj) {
               if($obj->IconHTML)
               {
                  $lang = "<img ". NemoControl::renderAttributes($obj->IconHTML) ." />";
               }else{
                  $lang = $obj->strLanguage;
               }
               $header .= "<a href='#' onclick=\"setLanguageWithoutUser('". $this->SystemSettings[FULL_URL] ."','$obj->strLanguage')\">
                              <div class='langBtn'>  $lang $BR $obj->strLanguage</div>
                           </a> ";
            }
                           
            $header .= "<div style='clear:both;'></div></div>";

         }
      }
      
      $header .= "   </div>
                  </div>";
      return $header; 
 
   }
   protected function Sandbox()
   {
      return "
      <div id='divContent'>
         ". $this->Content ."
      </div>";
   }
   protected function Footer() //20150310 - translatable footer moved to _nemo.cls - pj
   {//gets called from layout.basic
      return "  <span style='margin:10px;'>
                  Developed by :
               </span>";
   }
   protected function Message()
   {

      switch($this->Message->class)
      {
         case "warning":
         case "restricted":
            $this->Message->class = "divWarning";
            break;
         case "error":
            $this->Message->class = "divError";
            break;
         case "success":
         case "good":
            $this->Message->class = "divGood";
            break;
         case "":
            $this->Message->class = "divMessage";
            break;
      }
      if($this->Message->Text != "")
      return "
      <div id='divMessage'>
         <div class='". $this->Message->class ."'>
         ". $this->Message->Text ."
         </div>
      </div id=message>
      ";
   }

   public function getBrand() //20150310 - translatable moved to _nemo.cls - pj
   {
      return $this->SystemSettings["Brand"];
   }
   public function getTitle() //20150310 - translatable moved to _nemo.cls - pj
   {
      return $this->SystemSettings["Title"];
   }

}




?>
