<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Rootstock extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "0";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL (SELECT 1 AS ControlValue, 'Active' AS ControlText)
          UNION ALL (SELECT 0 AS ControlValue, 'Inactive' AS ControlText)";
      $this->Filters[frStatus]->html->value = -1;

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;
      
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (vieCultivarRootstock_EN.CultivarCode $like
                            OR vieCultivarRootstock_EN.strCultivar $like
                             OR vieCultivarRootstock_EN.txtNotes $like)";
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND vieCultivarRootstock_EN.blnActive = ". $this->Filters[frStatus]->html->value ."";
      }

      $this->ListSQL("
              SELECT vieCultivarRootstock_EN.CultivarID, vieCultivarRootstock_EN.CultivarCode AS 'Code', vieCultivarRootstock_EN.strCultivar AS 'Cultivar',
               vieCultivarRootstock_EN.txtNotes AS 'Notes', vieCultivarRootstock_EN.blnActive AS 'Active',
               vieCultivarRootstock_EN.strLastUser AS 'Last User', vieCultivarRootstock_EN.dtLastEdit AS 'Last Edit'
              FROM vieCultivarRootstock_EN
              WHERE 1=1 $Where
              ORDER BY vieCultivarRootstock_EN.CultivarCode",0);

      return $this->renderTable("Rootstock List");
   }

   public static function Save(&$CultivarID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblCultivar", $CultivarID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($CultivarID == 0) 
      {
         $CultivarID = $db->ID[CultivarID];
      }
      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details Saved. ";
      }
   }

   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         // $xdb->doQuery("DELETE FROM tblLocation WHERE LocationID = ". $xdb->qs($key));
         $xdb->doQuery("UPDATE tblCultivar SET blnActive = 0 WHERE CultivarID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
