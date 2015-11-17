<?php
//20110414 - used for quick set values from POST - pj

Class NemoDatabase
{
   var $Table;
   var $ID = array();
   var $IDType;
   var $NextID = 0;
   var $LastUID = "";

   var $Fields = array();  //array[$filedname] = $value
   var $Rows;
   var $Debug;

   public $rst;

   protected $primary_key = array();
   protected $multiple_key = array();
   protected $unique_key = array();
   public $FieldList = array();       //array[0..~] = $row/definition
   public $sql;

   public function __construct($strTable="", $ID = 0, $unique_keys=array(), $Debug=0)
   {
      $i = 0;
      $this->Table = $strTable;
      if(is_array($ID))
         $this->ID = $ID;
      else
         $this->ID[0] = $ID;

      $this->Debug = $Debug;
      $this->Rows = 0;

      if($strTable != "")
      {
         $rst = $this->doQuery("SELECT * FROM $strTable WHERE 1=0;");
         while($row = mysql_fetch_field($rst))
         {
            $this->FieldList[$i] = $row;
            if($row->numeric == 1)
               $this->Fields[$row->name] = 0;
            else
               $this->Fields[$row->name] = "";

            if($row->primary_key == 1){
               array_push($this->primary_key, $row->name);
               $this->IDType = $row->type;
            }
            //if($row->multiple_key == 1)
               //array_push($this->multiple_key, $row->name);
            if($row->unique_key == 1)
               array_push($this->unique_key, $row->name);
            $i++;
            //print_rr($row);

         }
      }
      if(isset($unique_keys))
         $this->unique_key = $unique_keys;
      $this->getRecord($this->ID);

      if($strTable != "")
         $this->NextID = $this->getRowSQL("SELECT MAX(". $this->primary_key[0] .")+1 AS NextID FROM `$strTable` ")->NextID;

      //print_rr($this);
   }

   public function getRecord($newID)
   {//new: for loading and updating a record
      //print_rr($newID);
      if(array_sum($newID) > 0 || $newID[0] != 0 || $newID[0] != "")
      {//ffs: hack! id combo of (-1, 1, 0) summed = 0. lol
         $where = "";

         foreach($this->primary_key as $idx => $key)
         {//echo "$key: ". $this->ID[$idx];
               //echo "<BR>$idx : $key : ". $newID[$idx];
            if($newID[$idx] !== "")
            {
               $where .= " AND $key = ". $this->qs($newID[$idx]);
               $this->ID[$idx] = $newID[$idx];
            }
         }

         $rst = $this->doQuery("SELECT * FROM $this->Table WHERE 1=1 $where LIMIT 1;");
         while($row = $this->fetch_object($rst))
         {
            //print_rr($row);
            foreach($row as $key => $value)
            {
               $this->Fields[$key] = $value;
            }
         }
      }
   }

   public function Save($blnIgnoreInsert=0, $overrideDebug="", $strUID_prefix="", $blnDisableForeignKeyChecks=0)
   {
      //check unique values
      $where =
      $comma =
      $SET =
      $strFieldList =
      $strValues =
      $and = "";

      if($blnDisableForeignKeyChecks == 1)
      {
         $this->doQuery("SET foreign_key_checks = 0;", $overrideDebug);
      }

      if(count($this->unique_key) > 0)
      {//note this only works for 1 unique or a collection/composite of unique, not multi single uniques
         foreach($this->unique_key as $key)
         {
            $where .= " AND `$key` = ". $this->qs($this->Fields[$key]);
         }
         foreach($this->primary_key as $key)
         {
            $where .= " AND `$key` <> ". $this->qs($this->Fields[$key]);
         }

         $sql = "SELECT * FROM $this->Table WHERE 1=1 $where";
      }else{
         $sql = "SELECT * FROM $this->Table WHERE 1=0 ";
      }

      $rst = $this->doQuery($sql);
      //print_rr($this);
      //die;
      if(mysql_num_rows($rst)>0){
         $response->Error = 1;
         $response->Message = "Unique key Violation, i.e. a similar record already exists. ";
         $response->SQL = $sql;
         $this->response = $response;
         return $this->response;
      }//die;
      //if ID > 0 then UPDATE else INSERT
      $where = "";
      //print_rr($this->ID); $this->ID[0] = 0;
      //vd($this->ID[0] != null); //vd($this->ID[0] != ""); //&& $this->ID[0] != "")); die;//echo array_sum($this->ID); die;
//die;
      if(array_sum($this->ID) > 0 || ($this->ID[0] != null && $this->ID[0] != ""))
      {//update
         if(count($this->primary_key) > 1){
            $blnCompositeKey = 1;
         }else{
            $PK = $this->primary_key;
            $PK = array_pop($PK);
         }

         foreach($this->FieldList as $i => $field)
         {
            $value = $this->Fields[$field->name];
            if($value !== "NULL")
               $value = $this->qs($value);

            if($field->type != "timestamp"){
            if($field->primary_key == 1 && ($this->Fields[$field->name] != null))// && $this->Fields[$field->name] != "" || $this->Fields[$field->name] != 0))         //doesn't matter if updating PK
            {
               //print_rr($field);
               $where .= " $and `$field->name` = $value";
               $and = "AND";
            }
            $SET .= "$comma `$field->name` = $value";
            $comma = ",";
            }
         }

         $sql = "UPDATE `$this->Table` SET $SET WHERE $where";
         $this->doQuery($sql, $overrideDebug);

         $response->Error = 0;
         $response->Message = "Record Updated. ";
         $response->SQL = $sql;
         $response->Rows = $this->Rows;

         if($blnDisableForeignKeyChecks == 1)
         {
            $this->doQuery("SET foreign_key_checks = 1;", $overrideDebug);
         }
         return $response;
      }
      else
      {//insert
         if(count($this->primary_key) > 1){
            $blnCompositeKey = 1;
         }else{
            $PK = $this->primary_key;
            $PK = array_pop($PK);
         }

         foreach($this->FieldList as $i => $field)
         {//print_rr($field);
            $value = $this->Fields[$field->name];

            if($field->type != "timestamp"){//don't assign values to timestamps, they have auto values on update
               $blnAddColumn = 1;
               if($field->primary_key == 1 && $blnCompositeKey == 0)// && $this->Fields[$field->name] != "" || $this->Fields[$field->name] != 0))         //doesn't matter if updating PK
               {
                  $blnAddColumn = 0;
               }
               if($field->primary_key == 1 && $this->IDType == "string")
               {//if PK is a string build a UID
                  $blnAddColumn = 1;
                  $this->LastUID = $value = $strUID_prefix . Obfuscate();
               }

               if($value !== "NULL")
                  $value = $this->qs($value);
               if($blnAddColumn == 1)
               {
                  $strFieldList .= "$comma `$field->name`";
                  $strValues .= "$comma $value";
                  $comma = ",";
               }
            }
         }
         if($blnIgnoreInsert != 0) $strIgnore = "IGNORE";

         $sql = "INSERT $strIgnore INTO `$this->Table` ($strFieldList) VALUES ($strValues)";

         $this->doQuery($sql, $overrideDebug);

         $response->ID = $this->ID[$PK] = $this->getLastID();
         if(!$blnCompositeKey) @$this->$PK = $response->ID;
         if($this->IDType == "string") $response->ID = $this->ID[$PK] = $this->LastUID;

         $response->Error = 0;
         $response->Message = "New Record Inserted. ";
         $response->SQL = $sql;
         $response->Rows = $this->Rows;

         if($blnDisableForeignKeyChecks == 1)
         {
            $this->doQuery("SET foreign_key_checks = 1;", $overrideDebug);
         }
         return $response;
      }
   }

   public function doQuery($sql, $overrideDebug="")
   {

      //$translateTable = array("["=>"`",
      //                        "]"=>"`");//"funny chars" => "new chars". just add into the array
      //$sql = strtr($sql, $translateTable);
      $this->sql = $sql;
      if($overrideDebug == "")
         $overrideDebug = $this->Debug;

      $this->Rows = 0;
      if($overrideDebug != 2)
         $rst = mysql_query($sql) or die(mynl2br($sql) . "<br><br>" . mynl2br(mysql_error() ."<BR><BR><a href='?exeClearSort=true'>Clear Sort Parameters</a>"));
      else
         return mynl2br($sql);
      if(strpos(strtoupper(substr($sql,0,10)), "SELECT ") > -1)
         $this->Rows = $this->num_rows($rst);// or die(strtoupper($sql) . "<br>" . mysql_error());
      else
         $this->Rows = $this->affected_rows();
      if($overrideDebug != 0)
         echo "<BR>". mynl2br($sql) ."<BR>". $this->Rows ." rows<BR>";

      $this->rst = $rst;

      return $rst;
   }

   public function getRowSQL($sql, $overrideDebug="")
   {
      $rst = $this->doQuery($sql, $overrideDebug);
      $row = $this->fetch();

      if($overrideDebug == 1)
         print_rr($row);

      if($this->Rows > 0)
         return $row;
      else
         return null;
   }

   public function getList($where="", $orderby="")
   {//old
      $sql = "SELECT * FROM ". $this->Table ." ". $this->Where($where) ." ". $this->OrderBy($orderby) .";";
      return $this->doQuery($sql);
   }

   public function Delete($ID)
   {//old
       $sql = "DELETE FROM ". $this->Table ." WHERE ". $this->FieldList[0] ." = $ID";
       return $this->doQuery($sql);
   }

   public function getLastID(){
      return mysql_insert_id();
   }

   public function qs($value){
      return "\"". qs($value) ."\"";
   }

   public function Where($where="")
   {
      if($where == "")
         return "";
      else
         return "WHERE $where";
   }

   public function OrderBy($orderby="")
   {
      if($orderby == "")
         return "";
      else
         return "ORDER BY $orderby";
   }

   public function fetch($rst=null){
      if(isset($rst))
         return mysql_fetch_object($rst);
      else
         $this->row = mysql_fetch_object($this->rst);
      return $this->row;
   }

   function ListOptions($sql, $selectedValue)//copy from sql_object
   {
      //SELECT '0' AS ClientID, '- All Clients -' AS strClient UNION SELECT ClientID, strClient FROM tblClient WHERE blnActive = 1 ORDER BY strClient
      $strOption = "";

      $rst = $this->doQuery($sql);
      while($row = $this->fetch_array($rst))
      {
         $strOption .= "<option value='". $row[0] ."' ";
         if(is_array($selectedValue))
         {
            foreach($selectedValue as $value)
            {
               if($row[0] == $value) $strOption .= "selected";
            }
         }else{
            if($row[0] == $selectedValue) $strOption .= "selected";
         }
         $strOption .= ">". htmlentities($row[1]) ."</option>";
      }
      return $strOption;
   }

   public function SetValues($arrValues)
   {//20110414 - used for quick set values from POST - pj
    //20110915 - added if $key like "bln" ? 1:0 - pj
    //20130806 - added object handling - pj

      if(count($this->Fields)>0)
      {
         foreach($this->Fields as $key => $value)
         {
            if(isset($arrValues[$key])){
               $this->Fields[$key] = $arrValues[$key];
            }
//echo "<BR>$key = '". $arrValues[$key] ."' (". strpos($key,"bln") .") >> "; vd($arrVaues[$key]);  echo " >> "; vd($this->Fields[$key]);
            if(strpos($key,"bln") === 0)//triple = !!!
            {//vd($key); vd($arrVaues[$key]);
               if($arrValues[$key] == "checked" || $arrValues[$key] == "1")
               {
                  $this->Fields[$key] = 1;
               }else{
                  $this->Fields[$key] = 0;
               }
            }
         }
      }
   }

//BASIC MYSQL
   public function fetch_object($result){
      return mysql_fetch_object($result);
   }
   public function fetch_field($result){
      return mysql_fetch_field($result);
   }
   public function fetch_array($result){
      return mysql_fetch_array($result);
   }
   public function num_rows($result){
       return mysql_num_rows($result);
   }
   public function affected_rows(){
      return mysql_affected_rows();
   }
}

?>
