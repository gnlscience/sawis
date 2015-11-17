<?php
include_once("_framework/_nemo.details.cls.php");

class NemoReport extends Nemo
{

   public $Reports = array();
   public $Filters = array();

   public function __construct()
   {
      parent::__construct();

      //$this->iniReports();
      //$this->iniFilters();

      $this->ToolBar->Buttons[btnExport]->blnShow = 1;
   }

//*************************
//***PUBLIC FUNCTIONS******
//*************************
   public function addReport($Label, $File, $arrFilters=null, $Section="", $arrAdditional=null)
   {
      $Report->Label = $Label;
      $Report->html->name = "radReport";
      $Report->html->value =
      $Report->html->id =
      $Report->File = $File;
      $Report->html->type = "radio";

      if(is_array($arrFilters)){
      foreach($arrFilters as $idx => $Filter)
         {
            $Report->Filters[$idx] = $Filter;
            //js
            $js .= "$comma'$Filter'";
            $comma = ",";

         }
         $js = "jsDisableAllFilterControls(); jsEnableFilterControls(new Array($js));";
      }else{
         $js = "jsEnableAllFilterControls();";
      }
      $Report->html->onclick = $js;

      if($_POST[radReport] == $File)
         $Report->html->checked = "checked";

      //
      //print_rr($this->Pages[$File]);
      if($this->Pages[$File])
         if($this->Pages[$File]->Security->blnView != 1)
            $Report->html->disabled = "security disabled";

      //vd(file_exists("includes/reports/$File.php"));
      if(!file_exists("includes/$File.php")){
         $Report->html->disabled = "file disabled";
         $Report->html->class = "textComment";
      }
      if(is_array($arrAdditional)){
         $Report->arrAdditional = $arrAdditional;
      }

      $this->Reports[$Section]->Report[count($this->Reports[$Section]->Report)] = $Report;
   }

   public function iniReports()
   {
      //var_dump(count($this->Reports));
      if(count($this->Reports) == 0){
         $this->Message->class = "warning";
         $this->Message->Text = "No Reports have been set up.";
      }
   }

   public function iniFilters()
   {
      parent::iniFilters();
   }

//*************************
//***PROTECTED FUNCTIONS***
//*************************

   protected function ToolBar()
   {
      //$this->renderToolbar();

      return "
      <div id='divToolBar'>
         <table class='tblBlank' style='margin: 0px;' width='100%' >
         <tr>
            <td>
               <table class='tblBlank' width='100%'>
                  <tr>
                     <td width='1%' ><img src='images/extraimages/header/icon-48-module.png' height='44'></img></td>
                     <td width='9%' align=left style='padding: 16px;' nowrap><span class='textHeading'>". $this->ToolBar->Label ."</span></td>
                     ". $this->renderReports() ."
                     <td width='20%'></td>
                  </tr>
               </table>
            </td>
         </tr>
         </table>
      </div>
      <div id='divToolBar' style='padding:0 2 8 2px;'>
         <table class='tblBlank' style='margin: 0px;'>
         <tr>
            <td>
               <table class='tblBlank'>
                  <tr>
                     <td><img src='images/extraimages/header/icon-48-module.png' height='44'></img></td>
                     <td style='padding: 16px;' nowrap><span class='textHeading'>Filters</span></td>
                     <td style='position: relative; border: 0px dashed orange; padding: 0px;'>". $this->ToolBar->Block . str_replace("Filter'", "Run Report'" , parent::renderFilters()) ."</td>
                  </tr>
               </table>
            </td>
            <td class='divButtons' align=right width='100%'>
               <table border='0' id='toolbar' class='toolbar' style='border:0px solid black;'>
                  ". parent::renderToolbarButtons() ."
               </table>
            </td>
         </tr>
         </table>
      </div>
      ";

   }


//*************************
//***PRIVATE FUNCTIONS*****
//*************************

   private function renderReports()
   {//note $this->Filters refers to the public Filters collection in the Child-Class, eg. Client->Filters
      //print_rr($this->Pages[$this->SystemSettings[SCRIPT_NAME]]->Filters);
      global $SP, $BR;
//INI
      $strFilterControls = "";

//go:
      $width = floor(70/count($this->Reports)/10)*10;
//print_rr($this->Reports);
      if(count($this->Reports)>0){//
         $i = 0;
         foreach($this->Reports as $Section => $Reports){
            $strReportControls .= "
            <td style=' padding: 0px; width: $width%' valign=top >
               <table class='tblBlank' style='margin-bottom: 0px;' width='80%'>
                  ". ($Section != "" ? "<caption>$Section</caption>" : "") ."
                  ";
            foreach($Reports->Report as $j => &$report){
               //print_rr($report);
               $report->ControlHTML = NemoControl::renderControl("input", $report->html);

               $strReportControls .= "
                  <tr>
                     <td nowrap align='center'>". $report->ControlHTML ."</td>
                     <td nowrap><label for=\"". $report->html->id ."\" class='". $report->html->class ."'>". NemoDetails::cleanColumnName($report->Label) ."</label></td>";
               $x = 0;
               if(is_array($report->arrAdditional))
               {
                  foreach($report->arrAdditional as $idx => $html)
                  {
                     $strReportControls .= "
                     <td width='". (60/count($report->arrAdditional)) ."%'>$html</td>";
                  }
                  $x = count($report->arrAdditional);
               }else{
                  $strReportControls .= "
                     <td width='60%'></td></tr>";
               }

            }
            $strReportControls .= "</table></td>";
         }

      //print_rr($this->Reports);
         return $strReportControls;
      }
   }


}




?>
