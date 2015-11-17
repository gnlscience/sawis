<?
	//CLEAR THIS FILE FOR EVERY NEW PROJECT
   include_once("_framework/_nemo.database2.cls.php");

   function getNewEmails()  
   {//sidebar menu functions
      return 6;
   }

   function getNewTickets()  
   {
      return 1;
   }

    ## GET INFO OR DETAILS FOR EACH FIELD
   function GetTooltip($faqID, $TooltipType)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings; 
      ## GET FAQID DETAILS FROM CORRECT LANGUAGE VIEW
      $faqDetails = $xdb->getRowSQL("SELECT * FROM vieFAQ_".$_SESSION[LANGUAGE]." WHERE FAQID = $faqID");

      ## TOOTIP RETURN
      return $faqDetails->txtFAQ;
   }


   function recordBlockMovement($BlockID, $UserID, $intYear, $dtMovement, $strType, $intVines, $txtComments, $txtNotes, $dblHectare=null)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DT, $DATABASE_SETTINGS, $SystemSettings;

      //open block record, calculate Ha change
      if($dblHectare === null){
         $rowBlock = $xdb->getRowSQL("SELECT * FROM tblBlock WHERE BlockID = '$BlockID' AND intYear = '$intYear'", 0); 
         $dblDensity = $rowBlock->dblVineDensity;
         $dblHectare = round($intVines / $dblDensity,4);
      }

      $bdb = new NemoDatabase("tblBlockMovement", null, null, 0);
      $bdb->Fields[refBlockID] = $BlockID;
      $bdb->Fields[refUserID] = $UserID;
      $bdb->Fields[dtMovement] = $dtMovement;
      $bdb->Fields[strType] = $strType;
      $bdb->Fields[intVines] = $intVines;
      $bdb->Fields[dblHectare] = $dblHectare;
      $bdb->Fields[intYear] = $intYear;
      $bdb->Fields[txtComments] = $txtComments;
      $bdb->Fields[txtNotes] = $txtNotes;
      $bdb->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      $bdb->Save(0,1);

   }
   function GetNewFarmID($blnTemp=0)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DT, $DATABASE_SETTINGS, $SystemSettings;

      if($blnTemp == 0)
      {
         $tableRequired = "tblFarm";
      }
      else
      {
         $tableRequired = "tmpFarm";
      }

      $NewFarmID = $xdb->getRowSQL("SELECT MAX(FarmID) AS MaxID FROM $tableRequired");
 
      $NewFarmID = substr(($NewFarmID->MaxID + 1000), 0, -3);
      $NewFarmID = $NewFarmID."000";

      return $NewFarmID;
   }

   
   function calculateHectare($dblRowSpacingNarrow, $dblRowSpacingWide, $dblVineSpacing, $intVines)
   {
      /*
      [re: FW: sawis: block cals ] - 20150517
(N+W)/2 x S x V /100m^2= A
e.g. 
N = 1m
W = 2m
S = 1m
V = 108
(1m+2m)/2 x 1m x 108/10 000 = 0.0162 Ha

      */
      $dblHectare = round(($dblRowSpacingNarrow + $dblRowSpacingWide) / 2 * $dblVineSpacing * $intVines / 10000,4);

      return $dblHectare;
   }

   function setBlockDensity($BlockID, $intYear, $dblHectare, $intVines)
   {//d = v / ha
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;

      $dblDensity = round($intVines / $dblHectare, 4);
      $xdb->doQuery("UPDATE tblBlock SET dblVineDensity = '$dblDensity' WHERE BlockID = '$BlockID' AND intYear = '$intYear'",1);

      return $dblDensity;
   }


   function CopyBlocks($FarmIDFrom, $FarmIDTo, $arrSelectedBlocks="",$blnTemp=0, $blnNotIn=0)
   {

      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings; 
 
      $comma = "";
      $blockIDs = "";
      if($arrSelectedBlocks != "")
      { 
         foreach($arrSelectedBlocks AS $ID)
         {
            $blockIDs .= "$comma '$ID'";
            $comma = ",";
         }

         if($blnNotIn == 0)
         {
            $status = "IN";
         }
         else
         {
            $status = "NOT IN";
         }
         $Where = "AND BlockID $status($blockIDs)";

      }  
      $rst = $xdb->doQuery("SELECT * FROM tblBlock WHERE refFarmID = $FarmIDFrom $Where");
      while($row = $xdb->fetch_object($rst))
      {
         $xdb->doQuery("INSERT IGNORE INTO tmpBlock 
                        (refFarmID, intYear, BlockID, strBlock, strDescription, refSingleVineyardID, refCultivarID, refCultivarRootstockID, refIrrigationID, refTrellisID,
                        intYearPlanted, dblHectare, dblRowSpacingWide, dblRowSpacingNarrow, dblVineSpacing, dblVineDensity, strGPS, strStatus, intVinesOpening, intVinesUprooted, intVinesGrafted,
                        intVinesAmmended, intVinesClosing, dblHectareUprooted, dblHectareGrafted, dblHectareAmmended, intYearUprooted, intYearGrafted, intYearAmmended, txtNotes)
               VALUES ( $FarmIDTo,
                        '".qs($row->intYear)."',
                        '".qs($row->BlockID)."',
                        '".qs($row->strBlock)."',
                        '".qs($row->strDescription)."',
                        '".qs($row->refSingleVineyardID)."',
                        '".qs($row->refCultivarID)."',
                        '".qs($row->refCultivarRootstockID)."',
                        '".qs($row->refIrrigationID)."',
                        '".qs($row->refTrellisID)."',
                        '".qs($row->intYearPlanted)."',
                        '".qs($row->dblHectare)."',
                        '".qs($row->dblRowSpacingWide)."',
                        '".qs($row->dblRowSpacingNarrow)."',
                        '".qs($row->dblVineSpacing)."',
                        '".qs($row->dblVineDensity)."',
                        '".qs($row->strGPS)."',
                        'New',
                        '".qs($row->intVinesOpening)."',
                        '".qs($row->intVinesUprooted)."',
                        '".qs($row->intVinesGrafted)."',
                        '".qs($row->intVinesAmmended)."',
                        '".qs($row->intVinesClosing)."',
                        '".qs($row->dblHectareUprooted)."',
                        '".qs($row->dblHectareGrafted)."',
                        '".qs($row->dblHectareAmmended)."',
                        '".qs($row->intYearUprooted)."',
                        '".qs($row->intYearGrafted)."',
                        '".qs($row->intYearAmmended)."',
                        '".qs($row->txtNotes)."'
                        )");
      } 
   }

?>
