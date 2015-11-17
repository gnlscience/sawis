<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class SingleVineyard extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      $this->Filters[frCultivar]->tag = "select";
      $this->Filters[frCultivar]->html->value = "-1";
      $this->Filters[frCultivar]->html->class = "controlText";
      $this->Filters[frCultivar]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT vieSingleVineyardBlock.CultivarCode AS 'ControlValue', vieSingleVineyardBlock.strCultivar AS 'ControlText'
            FROM vieSingleVineyardBlock
            GROUP BY vieSingleVineyardBlock.CultivarCode, vieSingleVineyardBlock.strCultivar
            ORDER BY vieSingleVineyardBlock.strCultivar)";      

      $this->Filters[frRootStock]->tag = "select";
      $this->Filters[frRootStock]->html->value = "-1";
      $this->Filters[frRootStock]->html->class = "controlText";
      $this->Filters[frRootStock]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT vieSingleVineyardBlock.CultivarRootStockCode AS 'ControlValue', vieSingleVineyardBlock.strCultivarRootStock AS 'ControlText'
            FROM vieSingleVineyardBlock
            GROUP BY vieSingleVineyardBlock.CultivarRootStockCode, vieSingleVineyardBlock.strCultivarRootStock
            ORDER BY vieSingleVineyardBlock.strCultivarRootStock)";  

      $this->Filters[frIrrigation]->tag = "select";
      $this->Filters[frIrrigation]->html->value = "-1";
      $this->Filters[frIrrigation]->html->class = "controlText";
      $this->Filters[frIrrigation]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT vieSingleVineyardBlock.IrrigationCode AS 'ControlValue', vieSingleVineyardBlock.strIrrigation AS 'ControlText'
            FROM vieSingleVineyardBlock
            GROUP BY vieSingleVineyardBlock.IrrigationCode, vieSingleVineyardBlock.strIrrigation
            ORDER BY vieSingleVineyardBlock.strIrrigation)";  

      $this->Filters[frTrellis]->tag = "select";
      $this->Filters[frTrellis]->html->value = "-1";
      $this->Filters[frTrellis]->html->class = "controlText";
      $this->Filters[frTrellis]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT vieSingleVineyardBlock.TrellisCode AS 'ControlValue', vieSingleVineyardBlock.strTrellis AS 'ControlText'
            FROM vieSingleVineyardBlock
            GROUP BY vieSingleVineyardBlock.TrellisCode, vieSingleVineyardBlock.strTrellis
            ORDER BY vieSingleVineyardBlock.strTrellis)";  

      $this->Filters[frBlockStatus]->tag = "select";
      $this->Filters[frBlockStatus]->html->value = "-1";
      $this->Filters[frBlockStatus]->html->class = "controlText";
      $this->Filters[frBlockStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT vieSingleVineyardBlock.strBlockStatus AS 'ControlValue', vieSingleVineyardBlock.strBlockStatus AS 'ControlText'
            FROM vieSingleVineyardBlock
            GROUP BY vieSingleVineyardBlock.strBlockStatus, vieSingleVineyardBlock.strBlockStatus
            ORDER BY vieSingleVineyardBlock.strBlockStatus)";   

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "-1";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT vieSingleVineyardBlock.strStatus AS 'ControlValue', vieSingleVineyardBlock.strStatus AS 'ControlText'
            FROM vieSingleVineyardBlock
            GROUP BY vieSingleVineyardBlock.strStatus, vieSingleVineyardBlock.strStatus
            ORDER BY vieSingleVineyardBlock.strStatus)";      

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;
      
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (vieSingleVineyardBlock.strFarm $like
                            OR vieSingleVineyardBlock.strSingleVineyard $like
                            OR vieSingleVineyardBlock.strCultivar $like
                            OR vieSingleVineyardBlock.strCultivarRootStock $like
                            OR vieSingleVineyardBlock.strIrrigation $like
                            OR vieSingleVineyardBlock.strTrellis $like)";
      }

      if($this->Filters[frCultivar]->html->value != -1)
      {
        $Where .= " AND CultivarCode = '". $this->Filters[frCultivar]->html->value ."'";
      }

      if($this->Filters[frRootStock]->html->value != -1)
      {
        $Where .= " AND CultivarRootStockCode = '". $this->Filters[frRootStock]->html->value ."'";
      }

      if($this->Filters[frIrrigation]->html->value != -1)
      {
        $Where .= " AND IrrigationCode = '". $this->Filters[frIrrigation]->html->value ."'";
      }

      if($this->Filters[frTrellis]->html->value != -1)
      {
        $Where .= " AND TrellisCode = '". $this->Filters[frTrellis]->html->value ."'";
      }

      if($this->Filters[frBlockStatus]->html->value != -1)
      {
        $Where .= " AND strBlockStatus = '". $this->Filters[frBlockStatus]->html->value ."'";
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }
                                    
      $this->ListSQL("
              SELECT vieSingleVineyardBlock.SingleVineyardID, vieSingleVineyardBlock.SingleVineyardID AS 'Single Vineyard ID',
               vieSingleVineyardBlock.strSingleVineyard AS 'Single Vineyard', vieSingleVineyardBlock.strFarm AS 'Farm',
               vieSingleVineyardBlock.strCultivar AS 'Cultivar', vieSingleVineyardBlock.intYearPlanted AS 'Year Planted',
               vieSingleVineyardBlock.strCultivarRootstock AS 'Root Stock', vieSingleVineyardBlock.strIrrigation AS 'Irrigation',
               vieSingleVineyardBlock.strTrellis AS 'Trellis', vieSingleVineyardBlock.SumOfdblHectare AS 'Hectare',
               vieSingleVineyardBlock.SumOfintVinesClosing AS 'Vines Closing', vieSingleVineyardBlock.strBlockStatus AS 'Block Status',
               vieSingleVineyardBlock.strStatus AS 'Status'
              FROM vieSingleVineyardBlock
              WHERE 1=1 $Where
              ORDER BY vieSingleVineyardBlock.strSingleVineyard",0);

      return $this->renderTable("Single Vineyard List");
   }

   public static function Save(&$SingleVineyardID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblSingleVineyard", $SingleVineyardID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($SingleVineyardID == 0) 
      {
         $SingleVineyardID = $db->ID[SingleVineyardID];
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
         $xdb->doQuery("UPDATE tblSingleVineyard SET blnActive = 0 WHERE SingleVineyardID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
