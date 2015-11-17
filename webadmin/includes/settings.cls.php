<?php
include_once("_framework/_nemo.list.cls.php");

class Settings extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      parent::__construct($DataKey);
   }
   public function getList()
   {
      $this->ListSQL("SELECT sysSettings.SettingID, sysSettings.strSetting as 'Setting', sysSettings.strValue as 'Value', sysSettings.strComment as 'Comment', sysSettings.strLastUser as 'Last User',
                        sysSettings.dtLastEdit as 'Last Edit'
                     FROM sysSettings
                     GROUP BY sysSettings.strSetting,sysSettings.strValue,
                              sysSettings.strLastUser,sysSettings.dtLastEdit
                     ORDER BY sysSettings.strSetting",0,"","Edit");

      return $this->renderTable("Advance Settings List");
   }
   public static function Save(&$SettingID)
   {
      $db = new NemoDatabase("sysSettings", $SettingID, null, 0);

      $db->Fields[strValue] = $_POST[strValue];
      $db->Fields[strComment] = $_POST[strComment];
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      $result = $db->save();
      if($SettingID == 0) $SettingID = $db->ID[SettingID];

      //print_rr($result);
      if($result->Error == 1)
      {
         return $result->Message;
      }else{
         return "Details Saved.";
      }
   }

   //20140716 Maanie - Executes all the views in the system settings table
   public static function CreateViews()
   {
      global $xdb;

      //initialize variables
      $sql = "SELECT strValue FROM sysSettings WHERE LEFT(strSetting, 3) = 'vie'";

      //Run query that gets all the rows from the sysSettings table where LEFT(strSetting, 3) = 'vie'
      $rstVie = $xdb->doQuery($sql, 0);

      //Foreach row, get the strValue and execute the view
       while($rowVie = $xdb->fetch_object($rstVie))
       {
         $xdb->doQuery("$rowVie->strValue", 0);
       }
       
       return "Views Created.";       
   }
}
?>
