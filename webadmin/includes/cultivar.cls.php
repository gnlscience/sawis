<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Cultivar extends NemoList
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
         $Where .= " AND (tblCultivar.CultivarCode $like
                            OR tblCultivar.strCultivarWebReference $like
                            OR tblCultivar.strCultivar $like
                            OR tblCultivar.EN_strCultivar $like
                            OR tblCultivar.AF_strCultivar $like
                            OR tblCultivar.txtNotes $like)";
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND tblCultivar.blnActive = ". $this->Filters[frStatus]->html->value ."";
      }

      
      $this->ListSQL("
            SELECT tblCultivar.CultivarID, tblCultivar.CultivarCode AS 'Cultivar Code', tblCultivar.strCultivar AS 'Cultivar',
             tblCultivar.strCultivarWebReference AS 'Web Reference',
             concat(tblCultivar.strWineTypeTypeCode, ' - ', IFNULL(tblWineType.EN_strType,'')) AS 'Wine Type',
             tblCultivar.blnCertifiable AS 'Certifiable', tblCultivar.blnBlancDeNoir AS 'Blanc de Noir',
             tblCultivar.blnRootstock AS 'Rootstock', tblCultivar.blnDistillWine AS 'Distill Wine',
             tblCultivar.blnWine AS 'Wine', tblCultivar.blnActive AS 'Active',
             tblCultivar.strLastUser AS 'Last User', tblCultivar.dtlastEdit AS 'Last Edit'
            FROM tblCultivar LEFT JOIN tblWineType ON tblCultivar.strWineTypeTypeCode = tblWineType.TypeCode
            WHERE 1=1 $Where
            GROUP BY tblCultivar.CultivarID
            ORDER BY tblCultivar.strCultivar",0);

      return $this->renderTable("Cultivar List");
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
         return "Records deactivated. ";
      }
   }
}
?>
