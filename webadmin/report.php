<?php
   include_once("_framework/_nemo.report.cls.php");
   
   $page = new NemoReport();
   
   //clear session if action clear is in the post
   if($_POST[Action] == "Clear")
   {
      unset($_SESSION[PAGES]->Entity["report.php"]->Filters[radReport]);
   }

   //Check if the Session's report filter is empty, then set the default
   if(!isset($_SESSION[PAGES]->Entity["report.php"]->Filters[radReport]))
   {
      $_SESSION[PAGES]->Entity["report.php"]->Filters[radReport] = "timesheet.rpt";
      $_POST[radReport] =  $_SESSION[PAGES]->Entity["report.php"]->Filters[radReport];
   }

   //Check if the $_POST report filter is empty, then copy the sessions report filter into the $_POST
   if($_POST[radReport] == "" && $_SESSION[PAGES]->Entity["report.php"]->Filters[radReport] != "" && $Action != "Clear")
   {
     // echo "here";
      $_POST[radReport] = $_SESSION[PAGES]->Entity["report.php"]->Filters[radReport];
      $Action = "Run Report";
   }
 

   $page->ToolBar->Columns = 2;

   $page->iniFilters();

   //setup Reports 2
   $page->addReport("Timesheet Totals Report", "timesheet.totals.rpt", array("frClient", "frProject", "frAllDates","frStartDate","frEndDate", "frUser","frTicket"));
   $page->addReport("Timesheet Report with Notes", "timesheet.rpt", array("frClient", "frProject", "frProjectItem","frAllDates","frStartDate","frEndDate", "frUser", "frInvoiced","frTicket"));
   $page->addReport("Timesheet Ticket Report", "ticket.rpt", array("frClient", "frProject", "frProjectItem","frAllDates","frStartDate","frEndDate", "frUser", "frInvoiced","frTicket"));
   

   $page->iniReports(); 
//events
   switch($Action)
   {
      

   }

//event LOAD Report 
   
   include_once("includes/". $_POST[radReport] .".php");
   

//nav
   switch($Action)
   {
      case "Export":

         $strFilename = str_replace(" ", "_", $strFilename);
         header("Content-type: application/x-msdownload");
         header("Content-Disposition: attachment; filename=". $strFilename .".xls"); //gets set inside the report.rpt.php
         header("Pragma: no-cache");
         header("Expires: 0");

         echo $Output;
         die;

         break;
         
      case "Clear":

      default:

         $page->Content .= $Output             
            . js("
               
               ");
   }
   
   $page->Message->Text = $Message;
   $page->Display();

   //set timesheet report session object to have the currently selected report
   $_SESSION[PAGES]->Entity["report.php"]->Filters[radReport] = $_POST[radReport];
           

?>
