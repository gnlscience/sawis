<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `sysUser` drop FOREIGN KEY `sysUser.strSecurityGroup`;
ALTER TABLE `sysUser` ADD CONSTRAINT `sysUser.strSecurityGroup` FOREIGN KEY (`refSecurityGroupID`) REFERENCES `sysSecurityGroup` (`SecurityGroupID`) ON UPDATE CASCADE ON DELETE CASCADE;

*/
//2015-06-18 checkChangePassword() on my profile save found in system.functions.inc.php - christiaan

class User extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

      $this->Filters[frSecurityGroup]->tag = "select";
      $this->Filters[frSecurityGroup]->html->value = "-1";
      $this->Filters[frSecurityGroup]->html->class = "controlText";
      $this->Filters[frSecurityGroup]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
                      UNION ALL
                         SELECT SecurityGroupID AS ControlValue, strSecurityGroup AS ControlText
                         FROM sysSecurityGroup
                         WHERE 1=1
                      ORDER BY ControlText ASC";

      $this->Filters[frInspector]->tag = "select";
      $this->Filters[frInspector]->html->value = "-1";
      $this->Filters[frInspector]->html->class = "controlText";
      $this->Filters[frInspector]->sql = "SELECT -1 AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT 1 AS ControlValue, 'Yes' AS ControlText
                        UNION ALL
                        SELECT 0 AS ControlValue, 'No' AS ControlText
                        ORDER BY ControlText ASC";

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "-1";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = "SELECT -1 AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT 1 AS ControlValue, 'Active' AS ControlText
                        UNION ALL
                        SELECT 0 AS ControlValue, 'Inactive' AS ControlText
                        ORDER BY ControlText ASC";

      parent::__construct($DataKey);
   }

   public function getList()
   {

      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")";
         $Where .= " AND (sysUser.UserID $like OR sysUser.strUser $like OR sysUser.strEmail $like)";
      }

      if($this->Filters[frSecurityGroup]->html->value != -1)
      {
         $Where .= " AND sysUser.refSecurityGroupID = ". $this->Filters[frSecurityGroup]->html->value;
      }

      if($this->Filters[frInspector]->html->value != -1)
      {
         $Where .= " AND sysUser.blnInspector = ". $this->Filters[frInspector]->html->value;
      }      

      if($this->Filters[frStatus]->html->value != -1)
      {
         $Where .= " AND sysUser.blnActive = ". $this->Filters[frStatus]->html->value;
      }

      $this->ListSQL("SELECT sysUser.UserID, 
                              sysUser.strUser AS User,
                              sysSecurityGroup.strSecurityGroup AS 'Security Group',
                              sysUser.strEmail AS Email,
                              MAX(sysLogin.strDateTime) AS 'Date Last Logged In',
                              sysUser.blnInspector AS Inspector,
                              sysUser.blnActive AS Active,
                              sysUser.strLastUser AS 'Last User',
                              sysUser.dtLastEdit AS 'Last Edit'
                     FROM sysUser INNER JOIN sysSecurityGroup ON sysUser.refSecurityGroupID = sysSecurityGroup.SecurityGroupID LEFT JOIN sysLogin ON sysUser.strEmail = sysLogin.strUsername
                     WHERE 1=1 $Where
                     GROUP BY sysUser.strUser
                     ORDER BY sysUser.strUser",0);

      return $this->renderTable("User List");
   }

   public function getMemberUserList($MemberID)
   {//MEMBER DETAILS SUB LIST ONLY

      $Where .= " AND sysUser.refMemberID = '$MemberID'";
      
      $this->ListSQL("SELECT sysUser.UserID, 
                              sysUser.strUser AS User,
                              sysSecurityGroup.strSecurityGroup AS 'Security Group',
                              sysUser.strEmail AS Email,
                              MAX(sysLogin.strDateTime) AS 'Date Last Logged In',
                              sysUser.blnInspector AS Inspector,
                              sysUser.blnActive AS Active,
                              sysUser.strLastUser AS 'Last User',
                              sysUser.dtLastEdit AS 'Last Edit'
                     FROM sysUser INNER JOIN sysSecurityGroup ON sysUser.refSecurityGroupID = sysSecurityGroup.SecurityGroupID LEFT JOIN sysLogin ON sysUser.strEmail = sysLogin.strUsername
                     WHERE 1=1 $Where
                     GROUP BY sysUser.strUser
                     ORDER BY sysUser.strUser",0,"user.php", "Edit&RETURN_URL=". urlencode("member.php?Action=Edit&MemberID=$MemberID"));

      return $this->renderTable("Member User List");
   }

   public static function Save(&$UserID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $PHP_SELF, $DATABASE_SETTINGS, $SystemSettings;
      $db = new NemoDatabase("sysUser", $UserID, null, 0);

      $db->SetValues($_POST);
      
      if($_POST[strPassword] != "" && $_POST[strPasswordConfirm] != "" )
      {
         $db->Fields[strPasswordMD5] = md5($_POST[strPassword]);
      }
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      if($UserID == 0){
         $db->Fields[strFirstUser] = $_SESSION['USER']->USERNAME;
         $db->Fields[dtFirstEdit] = date("Y-m-d H:i:s");
      }

      if($_FILES['Profile:PicturePath']['name'] != "" && $UserID != 0)
      {//current: cel_a/wa/_ need to go to cel_a/training/profilepictures/
         chmod($_FILES['Profile:PicturePath']['tmp_name'] , 0777);
         $strFileName = str_pad($UserID, 5,"0", STR_PAD_LEFT) ."_". $_FILES["Profile:PicturePath"]["name"];

         $strPath = $SystemSettings[ProfileImageDirAdmin]; //$strPath = "../../profliepictures/";

         $db->Fields["Profile:PicturePath"] = $strFileName;

         move_uploaded_file($_FILES['Profile:PicturePath']['tmp_name'], $strPath . $strFileName);
      }

      $result = $db->Save();
      if($UserID == 0) $UserID = $db->ID[UserID];

      //print_rr($_FILES);
      //NB! Place after initial save as a new user wont have a UserID yet - christiaan
      // if($_FILES['Profile:PicturePath']['name'] != "" && $UserID != 0)
      // {
      //    chmod($_FILES['Profile:PicturePath']['tmp_name'] , 0777);
      //    $strFileName = str_pad($UserID, 5,"0", STR_PAD_LEFT) ."_". $_FILES["Profile:PicturePath"]["name"];

      //    $strPath = $SystemSettings[ProfileImageDirAdmin]; //$strPath = "../../profliepictures/";
      //    //$db->Fields["Profile:PicturePath"] = $strFileName;
      //    $xdb->doQuery("UPDATE sysUser SET `Profile:PicturePath` = '$strFileName' WHERE UserID = $UserID");

      //    move_uploaded_file($_FILES['Profile:PicturePath']['tmp_name'], $strPath . $strFileName);
      // }

      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details Saved.";
      }
   }

   public static function SaveMyProfile(&$UserID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $PHP_SELF, $DATABASE_SETTINGS, $SystemSettings;
      $db = new NemoDatabase("sysUser", $UserID, null, 0);

      if($_POST[strPassword] != ""){
         if(checkChangePassword($db->Fields[strPasswordMD5], $_POST[strOldPassword], $_POST[strPassword], $_POST[strPasswordConfirm]))
         {
            $db->Fields["strPasswordMD5"] = md5($_POST["strPassword"]);
         }
         else
         {
            $errPasswordMessage = "Password not saved. The old password does not match the current password in the database. ";
         }
      }

      $db->Fields["strUser"] = $_POST["strSurname"] .", ". $_POST["strName"];
      $db->Fields["strName"] = $_POST["strName"];
      $db->Fields["strSurname"] = $_POST["strSurname"];
      $db->Fields["strTel"] = $_POST["strTel"];
      $db->Fields["strCell"] = $_POST["strCell"];
      $db->Fields["strFax"] = $_POST["strFax"];

      //print_rr($_FILES);
      if($_FILES['Profile:PicturePath']['name'] != "" && $UserID != 0)
      {//current: cel_a/wa/_ need to go to cel_a/training/profilepictures/
         chmod($_FILES['Profile:PicturePath']['tmp_name'] , 0777);
         $strFileName = str_pad($UserID, 5,"0", STR_PAD_LEFT) ."_". $_FILES["Profile:PicturePath"]["name"];

         $strPath = $SystemSettings[ProfileImageDirAdmin]; //$strPath = "../../profliepictures/";

         $db->Fields["Profile:PicturePath"] = $strFileName;

         move_uploaded_file($_FILES['Profile:PicturePath']['tmp_name'], $strPath . $strFileName);
      }
      
      $db->Fields["strSetting:Language"] = $_POST["strSetting:Language"];
      $db->Fields["strLastUser"] = $_SESSION[USER]->USERNAME;    

      $result = $db->Save(0,0);   

      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details Saved. $errPasswordMessage";
      }
   }

   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         //$xdb->doQuery("DELETE FROM sysUser WHERE UserID = ". $xdb->qs($key));
         $xdb->doQuery("UPDATE sysUser SET blnActive = 0 WHERE UserID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }

}
?>
