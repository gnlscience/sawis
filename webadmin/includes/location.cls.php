<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Location extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {

      $this->Filters[frGeo]->tag = "select";
      $this->Filters[frGeo]->html->value = "0";
      $this->Filters[frGeo]->html->class = "controlText";
      $this->Filters[frGeo]->sql = " SELECT '-1' AS ControlValue, '- All -' AS ControlText
          UNION ALL
             SELECT WOCode AS ControlValue, strLocation AS ControlText
             FROM vieLocGeo_EN
             WHERE 1=1
          ORDER BY ControlText ASC";
      $this->Filters[frGeo]->html->onchange = "jsGeoChange();";          
      $this->Filters[frGeo]->html->value = -1;

      $this->Filters[frRegion]->tag = "select";
      $this->Filters[frRegion]->html->value = "0";
      $this->Filters[frRegion]->html->class = "controlText";
      $this->Filters[frRegion]->sql = " SELECT '-1' AS ControlValue, '- All -' AS ControlText
          UNION ALL
             SELECT WOCode AS ControlValue, strLocation AS ControlText
             FROM vieLocRegion_EN
             WHERE 1=1
          ORDER BY ControlText ASC";
      $this->Filters[frRegion]->html->onchange = "jsRegionChange();";  
      $this->Filters[frRegion]->html->value = -1;       

      $this->Filters[frDistrict]->tag = "select";
      $this->Filters[frDistrict]->html->value = "0";
      $this->Filters[frDistrict]->html->class = "controlText";
      $this->Filters[frDistrict]->sql = " SELECT '-1' AS ControlValue, '- All -' AS ControlText
          UNION ALL
             SELECT WOCode AS ControlValue, strLocation AS ControlText
             FROM vieLocDistrict_EN
             WHERE 1=1
          ORDER BY ControlText ASC";
      $this->Filters[frDistrict]->html->value = -1;  

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
         $Where .= " AND (vieLocation.strLocation $like
                            OR vieLocation.EN_strLocation $like
                            OR vieLocation.AF_strLocation $like
                            OR strCertifyBlendAs $like
                            OR vieLocation.LocationID $like
                            OR vieLocation.WOCode $like)";
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND blnActive = ". $this->Filters[frStatus]->html->value ."";
      }

      if($this->Filters[frGeo]->html->value != -1)
      {
         //$Where .= " AND mid(WOCode,1,2)  = '" . $this->Filters[frGeo]->html->value ."'";
        $Where .= " AND GeoCode  = '" . $this->Filters[frGeo]->html->value ."'";
      }

      if($this->Filters[frRegion]->html->value != -1)
      {
         // $Where .= " AND mid(WOCode,1,5)  = '" . $this->Filters[frRegion]->html->value ."'";
        $Where .= " AND RegionCode  = '" . substr($this->Filters[frRegion]->html->value,2,3) ."'";
      }

      if($this->Filters[frDistrict]->html->value != -1)
      {
         //$Where .= " AND mid(WOCode,1,8)  = '" . $this->Filters[frDistrict]->html->value ."'";
        $Where .= " AND DistrictCode  = '" . substr($this->Filters[frDistrict]->html->value,5,3) ."'";
      }

      $this->ListSQL("
         SELECT LocationID, WOCode AS 'Location ID', strLocation AS Location, strCertifyBlendAs AS 'Certify Blend As',
          CONCAT(intStartVintage, ' - ', intEndVintage) AS 'Vintage', if(blnActive=1,'Yes','No') AS Active, strLastUser AS 'Last User',
          dtLastEdit AS 'Last Edit'
         FROM vieLocation
         WHERE 1=1 $Where
         ORDER BY LocationID, Location",0);

      return $this->renderTable("WO Location List").
   js("
      $( document ).ready(function() 
      {
          jsReloadRegionDDL($('#frGeo').val());
          jsReloadDistrictDDL($('#frRegion').val());        
      });

      function jsGeoChange()
      {
          var GeoID = $('#frGeo').val();
          jsReloadRegionDDL(GeoID);
          jsReloadDistrictDDL('');
      }

      function jsRegionChange()
      {
          var RegionID = $('#frRegion').val();
          jsReloadDistrictDDL(RegionID);
      }

      function jsReloadRegionDDL(GeoID)
      {
         var ddlRegion = $('#frRegion');
         var RegionID = ddlRegion.val();

         $.ajax({
            type: 'GET',
            url: 'ajaxfunctions.php',
            data: 'type=getRegionXML&GeoID='+ GeoID +'&RegionID='+ RegionID,
            success: function(data)
            {
               try{
                  ddlRegion.hide('fast');
                  ddlRegion.children('option:not(:first)').remove();

                  var rows = data.getElementsByTagName('row');
                  for(i = 0; i < rows.length; i+=1)
                  {
                     var row = rows[i];
                     var isSelected = false;
                     if(row.getElementsByTagName('selected')[0].textContent == 'true')
                        isSelected = true;

                     removePlus = row.getElementsByTagName('text')[0].textContent;
                     var removePlus = removePlus.replace(/\+/g, ' ');

                     ddlRegion.append(
                        $('<option></option>')
                           .attr('value', row.getElementsByTagName('value')[0].textContent)
                           .attr('selected',isSelected)
                           .text(urldecode(removePlus)));
                  }

               }catch(e){
                alert(e);
               }finally{
                  ddlRegion.show('fast');                     
               }
            }
         }); 
      }//eoF      

      function jsReloadDistrictDDL(RegionID)
      {
         // var RegionID = $('#frRegion').val();
         var ddlDistrict = $('#frDistrict');
         var DistrictID = ddlDistrict.val();

         $.ajax({
            type: 'GET',
            url: 'ajaxfunctions.php',
            data: 'type=getDistrictXML&RegionID='+ RegionID +'&DistrictID='+ DistrictID,
            success: function(data)
            {
               try{
                  ddlDistrict.hide('fast');
                  ddlDistrict.children('option:not(:first)').remove();

                  var rows = data.getElementsByTagName('row');
                  for(i = 0; i < rows.length; i+=1)
                  {
                     var row = rows[i];
                     var isSelected = false;
                     if(row.getElementsByTagName('selected')[0].textContent == 'true')
                        isSelected = true;

                     removePlus = row.getElementsByTagName('text')[0].textContent;
                     var removePlus = removePlus.replace(/\+/g, ' ');

                     ddlDistrict.append(
                        $('<option></option>')
                           .attr('value', row.getElementsByTagName('value')[0].textContent)
                           .attr('selected',isSelected)
                           .text(urldecode(removePlus)));
                  }

               }catch(e){
                alert(e);
               }finally{
                  ddlDistrict.show('fast');                     
               }
            }
         }); 
      }//eoF            
   ");
   }

   public static function Save(&$LocationID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("dwdLocation", $LocationID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($LocationID == 0) 
      {
         $LocationID = $db->ID[LocationID];
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
         $xdb->doQuery("UPDATE dwdLocation SET blnActive = 0 WHERE LocationID = ". $xdb->qs($key));
      }
         return "Records deactivated. ";
      }
   }
}
?>
