<?php

// 20140108 - Added SearchArticles function for ajax request in mod.note.php

include_once("system.php");
//make sure that the ajax request sends type
$type = $_REQUEST["type"];

switch($type)
{ 
   case "exampleMethod":
      $strOutput = exampleMethod($_REQUEST["ID"]);
      break;
   case "GetTownList":
      $strOutput = GetTownList($_REQUEST["strTownName"]);
      break;
   case "SendResetEmail":
      $strOutput = SendResetEmail($_REQUEST["strEmail"]);
      break;
   case "emailExist":
      $strOutput = emailExist($_REQUEST["strEmail"]);
      break;
   case "RegInProcess":
      $strOutput = RegInProcess($_REQUEST["strEmail"], $_REQUEST["strPassword"]);
      break;
   case "getMemberDetails":
      $strOutput = getMemberDetails(qs($_REQUEST["tmpMemberID"]));
      break;
   case "GetBusinessName":
      $strOutput = GetBusinessName(qs($_REQUEST["strMember"]));
      break;
   case "GetRegistrationNumber":
      $strOutput = GetRegistrationNumber(qs($_REQUEST["strRegistrationNumber"]));
      break;
   case "GetVATRegistrationNumber":
      $strOutput = GetVATRegistrationNumber(qs($_REQUEST["strVATRegistrationNumber"]));
      break;
   case "getFarmDetails":
      $strOutput = getFarmDetails(qs($_REQUEST["tmpFarmID"]));
      break;
   case "setLanguage":
      $strOutput = setLanguage($_REQUEST["strLanguage"]);
      break;
   case "setLanguageWithoutUser":
      $strOutput = setLanguageWithoutUser($_REQUEST["strLanguage"]);
      break;
    case "SetSidebarDisplay":
      $strOutput = SetSidebarDisplay($_REQUEST["strDisplay"]);
      break;
    case "getRegionXML";
         $strOutput = getRegionXML(qs($_REQUEST[GeoID]), qs($_REQUEST[RegionID]));
         break;    
    case "getDistrictXML";
         $strOutput = getDistrictXML(qs($_REQUEST[RegionID]), qs($_REQUEST[DistrictID]));
         break;              

   case "DeleteDocument";
         $strOutput = DeleteDocument(qs($_REQUEST[DocumentID]));
         break; 

   case "GetTradingNameFarm";
          $strOutput = GetTradingNameFarm(qs($_REQUEST["strFarm"]));
          break;

   case "GetBusinessNameFarm";
          $strOutput = GetBusinessNameFarm(qs($_REQUEST["strRegisteredBusinessName"]));
          break;

   case "GetRegistrationNumberFarm";
      $strOutput = GetRegistrationNumberFarm(qs($_REQUEST["strRegistrationNumber"]));
      break;
   case "GetVATRegistrationNumberFarm";
      $strOutput = GetVATRegistrationNumberFarm(qs($_REQUEST["strVATRegistrationNumber"]));
      break;
   case "removeFile";
      $strOutput = removeFile(qs($_REQUEST["DocID"]));
      break;
   case "API":
      $strOutput = apiGenerateDataDefinitions("dwdLocation");
      break;

}

if(!isset($REFERENCE_INCLUDE)){

if($_REQUEST['header'] == "")
   header("Content-type: text/xml");

//strOutput is html so format the string out as html
$strOutput = str_replace("&" , "&amp;" , $strOutput);
echo $strOutput;
die;
}

function apiGenerateDataDefinitions($Entity)
{
   global $xdb, $SystemSettings;
                                
   $rst = $xdb->doQuery("DESCRIBE $Entity",0);

   while($row = $xdb->fetch_object($rst))
   {     
      $xml .= parseXmlColumn($row,0);
   }
   
   return "<data><columns>$xml</columns></data>";
}

function removeFile($DocID)
{
   global $xdb, $SystemSettings;

   $DocDetails = $xdb->getRowSQL("SELECT * FROM tblDocument WHERE DocumentID = $DocID");

   $file = "js/jQuery-File-Upload-9.11.2/server/php/files/$DocDetails->name";
   $thumb = "js/jQuery-File-Upload-9.11.2/server/php/files/2thumbnail/$DocDetails->name";
   unlink($file); 
   unlink($thumb); 

   $xdb->doQuery("DELETE FROM tblDocument WHERE DocumentID = $DocID");

   return 1;

}

function GetTownList($strTownName)
{
   global $xdb, $SystemSettings;

   $Item = "";
   $counter = "0";
   $rst = $xdb->doQuery("SELECT * FROM tblTown WHERE strTown LIKE '%$strTownName%' ORDER BY strTown ASC");
   while($row = $xdb->fetch_object($rst))
   {
      $counter++;

      $Item .= "<div class='predictiveItem' onclick='jsPopulateTown(\"$row->strTown\");'>$row->strTown</div>";
   }

   if($Item == "")
   {
      $Item = "<div class='predictiveItem' style='font-weight:bold'>No Results</div>";
   }
   return $Item ;
}

function DeleteDocument($DocumentID)
{
   global $xdb, $SystemSettings;

   $DocumentRow = $xdb->getRowSQL("SELECT * FROM tblDocument WHERE DocumentID = $DocumentID");
   $file = "documents/$DocumentRow->strFilename";
   unlink($file);

   $xdb->doQuery("DELETE FROM tblDocument WHERE DocumentID = $DocumentID");
   return $DocumentRow->strFilename;
   

}

function getFarmDetails($tmpFarmID)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblFarm
                           WHERE tblFarm.FarmID = \"".qs($tmpFarmID)."\"");
   
   $rowDetails = $xdb->fetch_object($rst);
 
   return $rowDetails->strFarm;
}


function GetBusinessName($strMember)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblMember
                           WHERE tblMember.strMember = \"".qs($strMember)."\"");
   
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);

   if($row == 1)
   {   
       return 1;
   }
   else
   { 
       return 0;
   }
  
}



function GetRegistrationNumber($strRegistrationNumber)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblMember
                           WHERE tblMember.strRegistrationNumber = \"".qs($strRegistrationNumber)."\"");
   
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);
 
   if($row == 1)
   {   
       return 1;
   }
   else
   { 
       return 0;
   }
}

function GetVATRegistrationNumber($strVATRegistrationNumber)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblMember
                           WHERE tblMember.strVATRegistrationNumber = \"".qs($strVATRegistrationNumber)."\"");
   
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);
 
   if($row == 1)
   {   
       return 1;
   }
   else
   { 
       return 0;
   }
}

function getMemberDetails($tmpMemberID)
{
  global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblMember
                           WHERE tblMember.MemberID = \"".qs($tmpMemberID)."\"");
  


    $rowDetails = $xdb->fetch_object($rst);

   return $rowDetails->strMember;
}

//20150311 - Change sidebar onload display - Jacques
function SetSidebarDisplay($strDisplay)
{
   global $xdb;

   $_SESSION[sidebar] = $strDisplay;  
}

//20150310 - changeLanguage - pj
function setLanguage($strLanguage)
{
   global $xdb;

   $field = "strSetting:Language";
   $row = $xdb->getRowSQL("UPDATE sysUser SET `strSetting:Language` = ". $xdb->qs($strLanguage) ." WHERE UserID = ". $_SESSION[USER]->ID );

   $_SESSION[LANGUAGE] = 
   $_SESSION[USER]->LANGUAGE = strtoupper(substr($strLanguage,0,2));

   return $_SESSION[USER]->LANGUAGE;
}

//20150519 - changeLanguage without an user - jj
function setLanguageWithoutUser($strLanguage)
{
   global $xdb; 

   $_SESSION[LANGUAGE] = strtoupper(substr($strLanguage,0,2));

   return $_SESSION[LANGUAGE];
}

## PSSWORD RESET PROCEDURE - jj
function SendResetEmail($strEmail)
{
	global $xdb, $SystemSettings;

	$rst = $xdb->doQuery("SELECT * FROM sysUser WHERE strEmail = '$strEmail'");
	$row = $xdb->num_rows($rst);
	$rowDetails = $xdb->fetch_object($rst);

	if($row == 1)
	{
		$ResetKey = Obfuscate();

		include_once("_framework/_nemo.email.cls.php");
		## ADD RECORD TO RESET PASSWORD TABLE
		$nemoEmail = new NemoEmail($strEmail , "" , 0);
		$nemoEmail->LoadEmailTemplate("Reset Password");

		$arrValues[DisplayName] = $rowDetails->strUser;
      $arrValues[Link] = "<a href='".$SystemSettings[BASE_URL]."/resetPassword.php?email=$strEmail&resetKey=$ResetKey'>Reset Password</a>"; 

      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
      $nemoEmail->From = $SystemSettings["SMTP Send As"];

      $nemoEmail->Send();

      $xdb->doQuery("	UPDATE sysUser SET strPasswordMD5 = '$ResetKey' 
     				         WHERE strEmail = '$strEmail'");

		## SEND EMAIL
	}
	else
	{
		## DO NOTHING 
	}
	return $row;
}

function emailExist($strEmail)
{
   global $xdb, $SystemSettings;

   $rst = $xdb->doQuery("  SELECT * FROM sysUser 
                           WHERE sysUser.strEmail = ".$xdb->qs($strEmail));
   $row = $xdb->num_rows($rst);

   if($row){   
      return 1;   
   }else{ 
      return 0;
   }   
}

## GET REGISTRATION STATUS OF USER PROCEDURE - jj
function RegInProcess($strEmail, $strPassword)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
  /* Removed by clayton 2015/11/09
      was not returning users not in members
  */ 
/*   $rst = $xdb->doQuery("  SELECT * FROM sysUser INNER JOIN tblMember ON sysUser.refMemberID = tblMember.MemberID 
                           WHERE sysUser.strEmail = '".qs($strEmail)."' AND sysUser.strPasswordMD5 = '".md5($strPassword)."' AND tblMember.strStatus = '1.0 - New'");*/
$rst = $xdb->doQuery("  SELECT * FROM sysUser LEFT JOIN tmpMember ON sysUser.refMemberID = tmpMember.MemberID  
                           WHERE sysUser.strEmail = ".$xdb->qs($strEmail)." AND sysUser.strPasswordMD5 = ".$xdb->qs(md5($strPassword)));
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);

   if(!$row){
      ## DO NOTHING 
      $rowDetails->UserID = 0;
      $rowDetails->MemberID = 0;
   }
   return json_encode(array("MemberID"=>$rowDetails->MemberID,"UserID"=>$rowDetails->UserID,"RegistrationStatus"=>$rowDetails->RegistrationStatus,"IsActive"=>$rowDetails->blnActive));
}


function exampleMethod($ID)
{
   global $xdb;

   $row = $xdb->getRowSQL("SELECT field FROM table WHERE ID = ". $ID ." ");

   return $row->field;
}

function getRegionXML($GeoID, $RegionID=0)
{
    global $xdb, $SystemSettings;
                                
   $rst = $xdb->doQuery("SELECT WOCode AS 'value', strLocation AS 'text', IF(WOCode = \"$RegionID\", 'true','false') as 'selected'
      FROM vieLocRegion_".$_SESSION[USER]->LANGUAGE." 
      WHERE (WOParent = \"$GeoID\")
      ORDER BY text ASC ",0); 

   while($row = $xdb->fetch_object($rst))
   {     
      $xml .= parseXmlRow($row);
   }
   
   return "<data><rows>$xml</rows></data>";
}

function getDistrictXML($RegionID, $DistrictID=0)
{
    global $xdb, $SystemSettings;
                                
   $rst = $xdb->doQuery("SELECT WOCode AS 'value', strLocation AS 'text', IF(WOCode = \"$DistrictID\", 'true','false') as 'selected'
      FROM vieLocDistrict_".$_SESSION[USER]->LANGUAGE." 
      WHERE (WOParent = \"$RegionID\")
      ORDER BY text ASC ",0); 

   while($row = $xdb->fetch_object($rst))
   {     
      $xml .= parseXmlRow($row);
   }
   
   return "<data><rows>$xml</rows></data>";
}

function GetTradingNameFarm($strFarm)
{

  
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblFarm
                           WHERE tblFarm.strFarm = \"".qs($strFarm)."\"");

   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);

   if($row == 1)
   {   
       return 1;
   }
   else
   { 
       return 0;
   }
  
}

function GetBusinessNameFarm($strRegisteredBusinessName)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblFarm
                           WHERE tblFarm.strRegisteredBusinessName = \"".qs($strRegisteredBusinessName)."\"");
   
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);

   if($row == 1)
   {   
       return 1;
   }
   else
   { 
       return 0;
   }
  
}

function GetRegistrationNumberFarm($strRegistrationNumber)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblFarm
                           WHERE tblFarm.strRegistrationNumber = \"".qs($strRegistrationNumber)."\"");
   
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);
 
   if($row == 1)
   {   
      return 1;
   }
   else
   { 
      return 0;
   }
}

function GetVATRegistrationNumberFarm($strVATRegistrationNumber)
{
   global $xdb, $SystemSettings;

   ## CHECK IF USER RECORD EXISTS
   $rst = $xdb->doQuery("  SELECT * FROM tblFarm
                           WHERE tblFarm.strVATRegistrationNumber = \"".qs($strVATRegistrationNumber)."\"");
   
   $row = $xdb->num_rows($rst);
   $rowDetails = $xdb->fetch_object($rst);
 
   if($row == 1)
   {   
      return 1;
   }
   else
   { 
      return 0;
   }
}


?>