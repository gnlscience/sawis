<?php

/*
 * 20121204 - hiding hidden filter vars - pj
 */


include_once("_framework/_nemo.basic.cls.php");
include_once("_framework/_nemo.menu.cls.php");

class Nemo extends NemoBasic
{
   public $Pages; // $Pages[URL]->Security->blnX = X;
                  // $Pages[URL]->Filter[X] = X;
                  // $Pages[URL]->Sort[]
                  // $Pages[URL]->PageNumber = p

   public $Menu;
   public $Entity;

   public $LastVisited = array();

   public $ToolBar;
   protected $Buttons = array();

   public function __construct()
   {
      parent::__construct();

      if(!isset($_SESSION[USER]))
      {
         //windowLocation($this->SystemSettings[SessionExRedirect]);
         windowLocation('index.php');
         exit();
      }

      $this->iniSecurity(); //must be after $_SESSION load
      $this->iniSort();
      $this->iniFilters();
      $this->iniLastVisited();
      $this->iniToolbar();
      $this->iniPaging();

      translateToolbar($this, $_SESSION[USER]->LANGUAGE);

   }

//*************************
//***PUBLIC FUNCTIONS******
//*************************

   public function Display($index="")
   {

      if(empty($index))
         $index = "layout.back.incl.php";

      include_once($this->Layouts[$index]);
   }




//*************************
//***PROTECTED FUNCTIONS***
//*************************
   protected function Header()
   {//override basic.header()
      global $_LANGUAGE, $_TRANSLATION, $BR;

      $db = "";
      if($this->SystemSettings[SERVER_NAME] == "lamp"){
         global $DATABASE_SETTINGS;
         $db = ": ". $DATABASE_SETTINGS[lamp]->database;
      }

      if(is_array($_LANGUAGE) && $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["lblLogout"] != "")
      {
         $lblLogout = $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["lblLogout"];
      }else{
         $lblLogout = "Log out";
      }

      $header .= " <div class='dora-HeaderBar2'>
                  <div class='dora-HeaderLogo'><img width='130px' src='images/logoSmall.png' /></div>
                  <div class='dora-HeaderName'>
                     <h1>". $this->getBrand() ."</h1>
                  </div> 
                  <div class='dora-HeaderUser'>
                     <div style='position:relative; float:left; padding-right:20px; border-right:groove 1px #333; margin-top:8px;'>
                        <a href='". $this->SystemSettings[LogoutRedirect] ."'>
                           <b class='logoutWord'>$lblLogout: ". $this->SystemSettings[USER]->USERNAME ."</b>
                           <img width='31px' height='31px' src='images/icon-logout.png' /> 
                        </a>
                     </div>";
      if(is_array($_LANGUAGE))
      {
         $header .= "<div style='position:relative; float:left; padding-left:20px; margin-top:8px;' align='right'>";
         foreach ($_LANGUAGE as $Lang => $obj) {
            if($obj->IconHTML)
            {
               $lang = "<img ". NemoControl::renderAttributes($obj->IconHTML) ." />";
            }else{
               $lang = $obj->strLanguage;
            }
            $header .= "<a href='#' onclick=\"setLanguage('". $this->SystemSettings[FULL_URL] ."','$obj->strLanguage')\">
                           <div class='langBtn'>  $lang $BR $obj->strLanguage</div>
                        </a>";
         }
                        
           $header .= "<div style='clear:both;'></div></div>";

      }
 
      $header .= " </div>
               </div>";
      return $header;
      // return "<div class='dora-HeaderBar'>
      //             <div class='dora-HeaderLogo'><img src='images/logo.png' /></div>
      //             <div class='dora-HeaderName'>
      //                <B>".$this->SystemSettings["Brand"]."</B>
      //                <div style='font-size:12px; border-top:solid 1px #ccc; width:100%;'>Dora</div>
      //             </div> 
      //             <div class='dora-HeaderUser'><a href='". $this->SystemSettings[LogoutRedirect] ."'>Logout: ". $this->SystemSettings[USER]->USERNAME ."</a></div>
      //          </div>";
   }

   protected function Logout()
   {//gets called from layout.basic
      return "";
   }

   protected function SideBar($BlackWhite = "white")
   {//gets called from layout.basic

      global $_LANGUAGE, $_TRANSLATION;
      if(is_array($_LANGUAGE))
      {
         $this->Menu = new NemoMenuTranslator($this, $_SESSION[USER]->LANGUAGE);
      }else{
         $this->Menu = new NemoMenu($this);
      }
      //$this->Menu->BuildSideBar();
      
      if($_SESSION[sidebar] == "true")
      {
         $marginLeft = "margin-left:-23px;";
      }
      else if($_SESSION[sidebar] == "false")
      {
         $marginLeft = "margin-left:-240px;";
      } 

      return "
      <style type='text/css'>
      .content 
      {
         padding-left: 0px;
         margin-right:00px;
         top:0; 
            min-height:100%;
      position:relative;
      }
      </style>
 
      <script type='text/javascript'>
      
         $(document).ready(function() 
         {
            sidebarStatus = false;
            $('.sidebar-toggle').click(function() 
            {
               if (sidebarStatus == false) 
               {
                  $('#toggleArrowImg').attr('src', 'images/icon_arrow_right.png');
                  $('.sidebar').animate(
                  {
                     marginLeft: '-23px',
                     opacity: '1'
                  }, 500);
                  $('.content').animate(
                  {
                     marginLeft: '217px',
                     opacity: '1'
                  }, 500);
                  sidebarStatus = true;
               }
               else 
               {
                  $('#toggleArrowImg').attr('src', 'images/icon_arrow_left.png');
                  $('.sidebar').animate(
                  {
                     marginLeft: '-240px',
                     opacity: '1'
                  }, 500);
                  $('.content').animate(
                  {
                     marginLeft: '0px',
                     opacity: '1'
                  }, 500);
                  sidebarStatus = false;
               }

               $.ajax(
               {
                  type: 'POST',
                  url: 'ajaxfunctions.php',
                  data: 'header=text&type=SetSidebarDisplay&strDisplay=' + sidebarStatus,
                  success: function(data)
                  { 
                                           
                  }
               });


            });
         });

      </script>

      <div class='sidebar' style='$marginLeft'>
         <div class='sidebar-toggle'>
            <img id='toggleArrowImg' style='position:relative; top:40%; left:2px;' src='images/icon_arrow_left.png' />
         </div> 
         <div class='Circle'><img src='images/profile_$BlackWhite.png' class='profile_placeholder' /></div>
         ".$this->Menu->BuildSideBar()."
      </div>";

   }


   protected function Menu()
   {//gets called from layout.basic
      global $_LANGUAGE, $_TRANSLATION;
      if(is_array($_LANGUAGE))
      {
         $this->Menu = new NemoMenuTranslator($this, $_SESSION[USER]->LANGUAGE);
      }else{
         $this->Menu = new NemoMenu($this);
      }

      //$this->Menu = new NemoMenu($this);
      $this->Menu->BuildMenu();

      //print_rr($this->Menu);

      return "
      <div id=divMenu class='menu'>
        ". $this->Menu->getControl() ."
        <div style='clear:both;'></div>
      </div id=divMenu>";
   }

   protected function LastVisited()
   {//gets called from layout
      $pipe = "";
      if(count($this->LastVisited) > 0){
      foreach($this->LastVisited as $idx => $Entity)
      {
         $strLastVisited .= "$pipe<a href='$Entity->URL'>$Entity->Name</a>";
         $pipe = " | ";
      }
      }

      return "
      <div id=divLastVisited>
         Last Visited: ". $strLastVisited ."
      </div class=Logout>";
   }

   protected function ToolBar()
   {
      //$this->renderToolbar();

      return "
      <div id='divToolBar'>
         <div style='float:left;'>
            <table class='tblBlank' style='margin-bottom:0px;'>
               <tr> 
                  <td align='left'  style='border-right: 1px groove #333; padding: 19px 19px 19px 15px;'><span class='textHeading'>". $this->ToolBar->Label ."</span></td>
                  <td style='position: relative; border: 0px dashed orange; padding: 0px;'>". $this->ToolBar->Block . $this->renderFilters() ."</td>
               </tr>
            </table>
         </div>
         <div class='divButtons'>
            ". $this->renderToolbarButtons() ."
         </div>
         <div style='clear:both'></div>
      </div>
      ";

   }



//*************************
//***PRIVATE FUNCTIONS*****
//*************************

   private function iniSecurity()
   {
      global $_LANGUAGE, $_TRANSLATION;

      if(is_array($_LANGUAGE))
      {
         $strEntity = $_SESSION[USER]->LANGUAGE ."_strEntity";
      }else{
         $strEntity = "strEntity";
      }
      $rst = $this->db->doQuery("SELECT sysMenuLevel2.$strEntity AS Entity, sysMenuLevel2.strUrl AS Url, sysSecurity.blnView, sysSecurity.blnDelete, sysSecurity.blnSave, sysSecurity.blnNew, sysSecurity.blnSpecial
                                  FROM sysSecurity INNER JOIN sysMenuLevel2 ON sysSecurity.refMenulevel2ID = sysMenuLevel2.MenuLevel2ID
                                  WHERE sysSecurity.refSecurityGroupID=". $_SESSION[USER]->SECURITYGROUPID ."",0); 
      while($row = $this->db->fetch())
      {
         $this->Pages[$row->Url]->Security = $row;
         $this->Pages[$row->Url]->Entity = $row->Entity;
      }
      $this->Security = $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Security;

      //print_rr($this->SystemSettings);
      if($this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Security->blnView != 1){
         if($_SESSION[intAttempt] > 5){
            windowLocation("message.php?MID=r10&p=". $this->SystemSettings[SCRIPT_NAME] ."");
         }
         else{
            windowLocation("message.php?MID=r09&p=". $this->SystemSettings[SCRIPT_NAME] ."");
         }

         $_SESSION[intAttempt] += 1;
         //print_rr($this->Pages);
      }else{
         $_SESSION[intAttempt] = 0;
      }

      //set Entity
      //print_rr($this->Pages[$this->SystemSettings[SCRIPT_NAME]]);
      $this->Entity->Name = $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Entity;
      $this->Entity->URL = $this->SystemSettings[FULL_URL];
   }

   private function iniSort()
   {
      //set sort
      if($_GET[exeClearSort] || $_REQUEST[Action] == "Clear")
      {
         unset($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Sort);
      }
      if($_GET[srtNew] != "")
      {
         $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Sort[srtNew] = $_GET[srtNew];
         $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Sort[srtCurrent] = $_GET[srtCurrent];
         $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Sort[srtDir] = $_GET[srtDir];
      }

      //load sort
      if(count($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Sort) > 0){
      foreach($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Sort as $key => $value)
      {//? Do we care about other pages' filters at this point? no
         $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Sort[$key] = $value;
      }}
   }

   protected function iniFilters()
   {  /*notes: filters have 2 stages. stage 1, iniFilters, executes onConstruct of the Nemo. stage 2, renderFilters(), executes onDisplay() of Nemo
      *     : $this->Filters referst to the public Filters collection in the Child-Class, eg. Client->Filters
      *
      *
      */

      //save || delete session filters
      if(($_REQUEST[Action] == "Filter" || $_POST[Action] == "Run Report") && count($this->Filters) >0 )
      {//&& because $this->Filters doesn't have an ini value when $page = new Nemo(), but has in $page = new Client() [extends Nemo]
         foreach($this->Filters as $key => &$filter)
         {
            $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Filters[$key] = $_REQUEST[$key];
         }//20110916 - changed post to request - pj
      }
      //print_rr($_SESSION[PAGES]);
      //print_rr($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Filters);
      if($_REQUEST[Action] == "Clear")
      {
         unset($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Filters);
      }

      //load filter values from session
      if(count($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Filters) > 0){
      foreach($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Filters as $key => $value)
      {//? Do we care about other pages' filters at this point? no
         $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Filters[$key] = $value;
      }}

      //build filter controls
      if(count($this->Filters)>0){
      foreach($this->Filters as $key => &$filter)
      {
         $filter->html->name = $filter->html->id = $key;
         if($this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Filters[$key] != ""){//load filter value from session
            $filter->html->value = $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Filters[$key];
            if($filter->html->type == "checkbox")
               $filter->html->checked = "checked";
         }

         if($filter->tag == "select"){//load options from sql
            //echo $filter->sql;
            $filter->html->innerHTML = $this->db->ListOptions($filter->sql, $filter->html->value);
         }

         //print_rr($this->Filters[$key]);
      }}
   }

   private function iniPaging()
   {//new 20111010 - pj
      if($_REQUEST[Action] == "Filter" || $_GET[exeClearSort] || $_REQUEST[Action] == "Clear" || $_GET[srtNew] != "")
      {//if new sorting, restart paging
         unset($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Paging);
      }

      if($_GET[pagPageNumber] != "")
      {
         $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Paging[PageNumber] = $_GET[pagPageNumber];
      }

      if($_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Paging[PageNumber] < 1)
         $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Paging[PageNumber] = 1;

      $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->PageNumber = $_SESSION[PAGES]->Entity[$this->SystemSettings[SCRIPT_NAME]]->Paging[PageNumber];

   }

   protected function renderFilters()
   {//note $this->Filters referst to the public Filters collection in the Child-Class, eg. Client->Filters
      //print_rr($this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Filters);
      include_once("_nemo.details.cls.php");
      
      global $SP, $BR, $_LANGUAGE, $_TRANSLATION;
//INI
      $strFilterControls = ""; 
//go:
      if(count($this->Filters)>0){//
         $i = 0;
         foreach($this->Filters as $key => &$filter)
            {//print_rr($filter);
            $filter->ControlHTML = NemoControl::renderControl($filter->tag, $filter->html);

            if($filter->tag == "hidden")
            {//20121204 - hiding hidden filter vars - pj
               $strFilterControls .= $filter->ControlHTML;
            }else{
               if($i % 3 == 0 && $i != 0)
               {
                  $strFilterControls .= "</tr><tr>";
               }

               if($filter->label != "" && $_TRANSLATION[$_SESSION[USER]->LANGUAGE][$key] != "")
                  $filter->label = $_TRANSLATION[$_SESSION[USER]->LANGUAGE][$key];

               if($filter->label != "" && $_TRANSLATION[$_SESSION[USER]->LANGUAGE][$filter->label] != "")
                  $filter->label = $_TRANSLATION[$_SESSION[USER]->LANGUAGE][$filter->label];    

               if($filter->label == "") 
                  $filter->label = $key;

               $strFilterControls .= "
                  <td align='right' nowrap><label for=\"$key\">". NemoDetails::cleanColumnName($filter->label) .":</label></td>
                  <td align='left'>". $filter->ControlHTML ."</td>";
               $i++;
            }
         }

         $strFilterControls .= "
            <td rowspan=2 valign=middle align=center ></td>";

      //print_rr($this->Filters);

         if(is_array($_LANGUAGE))
         {
            return "<table class='tblBlank' style='margin-bottom: 0px; padding-top:0px;'><tr>$strFilterControls</tr></table>
            </td>
            <td nowrap><input style=' ' type=submit name='Action' id='btnFilter' class='controlButton' value='". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["btnFilter"] ."'>
             $SP<input style=' ' type=submit name='Action' id='btnClear' class='controlButton' value='". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["btnClear"] ."'>"; //</td> ends outside in the calling function
         }else{


            return "<table class='tblBlank' style='margin-bottom: 0px; padding-top:0px;'><tr>$strFilterControls</tr></table>
            </td>
            <td nowrap><input style=' ' type=submit name='Action' id='btnFilter' class='controlButton' value='Filter'>
             $SP<input style=' ' type=submit name='Action' id='btnClear' class='controlButton' value='Clear'>"; //</td> ends outside in the calling function
         }
      }
   }

   private function iniLastVisited()
   {

      if(count($_SESSION[LASTVISITED]) > 0){
      foreach($_SESSION[LASTVISITED] as $idx => $value){
         array_push($this->LastVisited, $value);
      }
      }
//print_rr($this->Entity);
      if($this->Entity->Name != "")
      {
         $this->pushLastVisited($this->Entity);
      }

      if(count($this->LastVisited) > $this->SystemSettings[LastVisited])
      {
         while(count($this->LastVisited) > $this->SystemSettings[LastVisited])
         {
            array_pop($this->LastVisited);
         }
      }



      $_SESSION[LASTVISITED] = $this->LastVisited;

      //print_rr($_SESSION[LASTVISITED]);
      //print_rr($this->LastVisited);

   }

   protected function pushLastVisited($Entity)
   {
//unset($_SESSION[LASTVISITED], $this->LastVisited); die;
      if($this->LastVisited[0] != $Entity){
         $this->LastVisited = array_reverse($this->LastVisited);
         array_push($this->LastVisited, $Entity);
         $this->LastVisited = array_reverse($this->LastVisited);         
         //print_rr($this->LastVisited); if($_SESSION[USER]->ID=-1) die;
      }
      //print_rr($this->LastVisited);
      $_SESSION[LASTVISITED] = $this->LastVisited;
   }

   public function overrideLastVisited($Entity)
   {
      $this->LastVisited = array_reverse($this->LastVisited);
      array_pop($this->LastVisited);
      array_push($this->LastVisited, $Entity);
      $this->LastVisited = array_reverse($this->LastVisited);

      $_SESSION[LASTVISITED] = $this->LastVisited;

   }

   protected function renderToolbarButtons()
   {
//print_rr($this->ToolBar->Buttons);

      $arrPageToolbar = array();
      foreach($this->ToolBar->Buttons as $id => $control)
      {
         if($control->blnShow == 1)
         {

            $arrPageToolbar[$control->intOrder][] = $control;
            
         }
      }
      ksort($arrPageToolbar); 
      foreach($arrPageToolbar AS $intOrderL1 => $arrPageToolbarL2)
      {
         foreach($arrPageToolbarL2 AS $intOrderL2 => $ToolbarItem)
         {
            $ToolbarItem->Control->class = $ToolbarItem->Control->class . " " . $ToolbarItem->Span->class;
            $button = NemoControl::renderControlToolbar($ToolbarItem->Control->tag, $ToolbarItem->Control);
            //$span = NemoControl::renderControlToolbar($ToolbarItem->Span->tag, $ToolbarItem->Span);
             
            $toolbar .= "
            <td > 
               $button
            </td >
            ";
         }//L2
      }//L1

      return "
      <table border='0' id='toolbar' class='toolbar' style='margin-top:7px; border:0px purple dashed;'>
         <tr>
            $toolbar
         </tr>
      </table>
      ";
      //print_rr($this->ToolBar->Buttons);

   }

   protected function iniToolbar()
   {
      //print_rr($this);

      $this->Buttons[btnPreview]->Control->class = "linkbutton";
      $this->Buttons[btnPreview]->Control->value = "Preview";
      $this->Buttons[btnPreview]->Span->class= "icon-32-search"; 

      //default 4
      $this->Buttons[btnReload]->Control->class = "linkbutton";
      $this->Buttons[btnReload]->Control->value = "Reload";
      $this->Buttons[btnReload]->Span->class= "icon-32-refresh"; 

      $this->Buttons[btnNew2]->Control->class = "linkbutton";
      $this->Buttons[btnNew2]->Control->value = "New";
      $this->Buttons[btnNew2]->Span->class= "icon-32-edit"; 

      $this->Buttons[btnNew]->Control->class = "linkbutton";
      $this->Buttons[btnNew]->Control->value = "New";
      $this->Buttons[btnNew]->Span->class= "icon-32-new"; 

      $this->Buttons[btnDelete]->Control->class = "linkbutton";
      $this->Buttons[btnDelete]->Control->value = "Delete";
      $this->Buttons[btnDelete]->Control->onclick = "return confirm(\"Are you sure you want to delete ". $this->Entity->Name ."(s) and related entities?\");";
      $this->Buttons[btnDelete]->Span->class = "icon-32-trash"; 

      $this->Buttons[btnRevert]->Control->class = "linkbutton";
      $this->Buttons[btnRevert]->Control->value = "Revert";
      $this->Buttons[btnRevert]->Control->onclick = "";
      $this->Buttons[btnRevert]->Span->class = "icon-32-revert"; 

      $this->Buttons[btnRevert2]->Control->class = "linkbutton";
      $this->Buttons[btnRevert2]->Control->value = "Revert2";
      $this->Buttons[btnRevert2]->Control->onclick = "";
      $this->Buttons[btnRevert2]->Span->class = "icon-32-revert"; 

      $this->Buttons[btnSend]->Control->class = "linkbutton";
      $this->Buttons[btnSend]->Control->value = "Send";
      $this->Buttons[btnSend]->Span->class = "icon-32-send"; 

      $this->Buttons[btnSave]->Control->class = "linkbutton";
      $this->Buttons[btnSave]->Control->value = "Save";
      $this->Buttons[btnSave]->Control->onclick = "return jsNemoValidateSave();";
      $this->Buttons[btnSave]->Span->class = "icon-32-save"; 


      $this->Buttons[btnClose]->Control->class = "linkbutton";
      $this->Buttons[btnClose]->Control->value = "Close";
      $this->Buttons[btnClose]->Span->class = "icon-32-cancel";    

      $this->Buttons[btnExport]->Control->class = "linkbutton";
      $this->Buttons[btnExport]->Control->value = "Export";
      $this->Buttons[btnExport]->Span->class = "icon-32-export"; 

      //extra
      $this->Buttons[btnNext]->Control->class = "linkbutton";
      $this->Buttons[btnNext]->Control->value = "Next";
      $this->Buttons[btnNext]->Span->class = "icon-32-forward"; 

      $this->Buttons[btnBack]->Control->class = "linkbutton";
      $this->Buttons[btnBack]->Control->value = "Back";
      $this->Buttons[btnBack]->Span->class = "icon-32-back"; 

      $this->Buttons[btnHelp]->Control->class = "linkbutton";
      $this->Buttons[btnHelp]->Control->value = "Help";
      $this->Buttons[btnHelp]->Span->class = "icon-32-help"; 

      $this->Buttons[btnFinalize]->Control->class = "linkbutton";
      $this->Buttons[btnFinalize]->Control->value = "Finalize";
      $this->Buttons[btnFinalize]->Span->class = "icon-32-finalize"; 

      $this->Buttons[btnApply]->Control->class = "linkbutton";
      $this->Buttons[btnApply]->Control->value = "Apply";
      $this->Buttons[btnApply]->Span->class = "icon-32-apply "; 
  
      $counter = 1;
      foreach($this->Buttons as $id => $control)
      {

         $this->ToolBar->Buttons[$id]->Control = nCopy($control->Control);
         $this->ToolBar->Buttons[$id]->Control->id = $id;
         $this->ToolBar->Buttons[$id]->Control->name = "Action";
         $this->ToolBar->Buttons[$id]->Control->type = "submit";
         $this->ToolBar->Buttons[$id]->Control->tag = "input";

         $this->ToolBar->Buttons[$id]->Span = nCopy($control->Span);
         $this->ToolBar->Buttons[$id]->Span->tag = "span";
         $this->ToolBar->Buttons[$id]->Span->title = $control->Control->value;

         $this->ToolBar->Buttons[$id]->blnShow = $control->blnShow;
         $this->ToolBar->Buttons[$id]->intOrder = $counter;

         $counter++;
      }
      //print_rr($this->ToolBar->Buttons);
      $this->ToolBar->Label = $this->Entity->Name;
   }


   public function getBrand()
   {
      global $_LANGUAGE, $_TRANSLATION;
      if(is_array($_LANGUAGE) && $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Brand"] != "")
         return $this->SystemSettings["Brand"] = $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Brand"];
      else
         return $this->SystemSettings["Brand"];
   }
   public function getTitle()
   {
      global $_LANGUAGE, $_TRANSLATION;
      if(is_array($_LANGUAGE) && $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Title"] != "")
         return $this->SystemSettings["Title"] = $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Title"];
      else
         return $this->SystemSettings["Title"];
   }
   protected function Footer()
   {//gets called from layout.basic
      global $_LANGUAGE, $_TRANSLATION;

      if(is_array($_LANGUAGE) && $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["lblDevelopedBy"] != "")
      {
         $lblDevelopedBy = $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["lblDevelopedBy"];
      }else{
         $lblDevelopedBy = "Developed by";
      }

      return "  <span style='margin:10px;'>
                  $lblDevelopedBy:
               </span>";
   }

}




?>
