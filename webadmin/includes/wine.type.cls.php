<?php
include_once("_framework/_nemo.list.cls.php");

/*


*/

//Additional / page specific translations

class WineType extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      $this->Filters[frWineType]->tag = "select";
      $this->Filters[frWineType]->html->value = "0";
      $this->Filters[frWineType]->html->class = "controlText";
      $this->Filters[frWineType]->sql = "SELECT '-1' AS ControlValue, '- All -' AS ControlText
         UNION ALL
            SELECT TypeCode AS ControlValue, strWineType AS ControlText
            FROM vieWineType_EN
            WHERE 1=1
         ORDER BY ControlText ASC";
      $this->Filters[frWineType]->html->value = -1;

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "0";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL SELECT 1 AS ControlValue, 'Active' AS ControlText
          UNION ALL SELECT 0 AS ControlValue, 'Inactive' AS ControlText
         ORDER BY ControlText ASC";
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
         $Where .= " AND (tblWineType.WineTypeCode $like
                            OR tblWineType.EN_strCategory $like
                            OR tblWineType.AF_strCategory $like
                            OR tblWineType.EN_strSubCategory $like
                            OR tblWineType.AF_strSubCategory $like
                            OR tblWineType.EN_strAlcoholContent $like
                            OR tblWineType.AF_strAlcoholContent $like
                            OR tblWineType.EN_strDescription $like
                            OR tblWineType.AF_strDescription $like
                            OR tblWineType.EN_strType $like
                            OR tblWineType.AF_strType $like
                            OR tblWineType.EN_strSubType $like
                            OR tblWineType.AF_strSubType $like
                            OR tblWineType.EN_strSubDescription $like
                            OR tblWineType.AF_strSubDescription $like
                            OR tblWineType.txtNotes $like)";
      }

      if($this->Filters[frWineType]->html->value != -1)
      {
        $Where .= " AND tblWineType.TypeCode  = '" . $this->Filters[frWineType]->html->value ."'";
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND tblWineType.blnActive = ". $this->Filters[frStatus]->html->value ."";
      }

      $this->ListSQL("
              SELECT tblWineType.WineTypeID, tblWineType.WineTypeCode AS 'Wine Type Code', tblWineType.EN_strCategory AS 'Category',
               tblWineType.EN_strSubCategory AS 'Sub Category', tblWineType.EN_strAlcoholContent AS 'Alcohol Content',
               tblWineType.EN_strDescription AS 'Description', tblWineType.EN_strType AS 'Wine Type', tblWineType.EN_strSubType AS 'Sub Type',
               tblWineType.EN_strSubDescription AS 'Sub Description', tblWineType.blnCertification AS 'Certifiable',
               tblWineType.blnActive AS 'Active', tblWineType.strLastUser AS 'Last User', tblWineType.dtLastEdit AS 'Last Edit'
              FROM tblWineType
              WHERE 1=1 $Where
              ORDER BY tblWineType.WineTypeID",0);

      return $this->renderTable("Wine Type List");
   }

   public static function Save(&$WineTypeID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblWineType", $WineTypeID, null, 0);
      unset($db->multiple_key); //issue with multikey ?!?!?! - pj
//print_rr($db);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($WineTypeID == 0) 
      {
         $WineTypeID = $db->ID[WineTypeID];
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
         $xdb->doQuery("UPDATE tblWineType SET blnActive = 0 WHERE WineTypeID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
