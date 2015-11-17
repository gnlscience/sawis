<?php
//include_once("_nemo.basic.cls.php");
/*
 * 20110621 - refined anchor tag click - pj
 * 20110621 - added field-type data-transforms - pj
 * 20140205 - add ->Data[$i][html] in order to render attributes in the data TR on renderList() - pj
 */

include_once("_framework/_nemo.cls.php");

class NemoListBasic extends Nemo
{
   public $Data;
   public $Columns;
   public $sql;
   public $sql2;

   public $isSortable = 0;
   public $isSelectable = 1;
   public $isPageable = 0;

   public $intRecords = 0;

   public $DataKey = array();
   public $eval = "";

   private $arrEvalTinyint = array(0 => "No", 1 => "Yes");

   //ok, all this class should do is pick up the sql statement and generate a data array and make a list view of it. no security-able interaction!
   public function __construct()
   {
      parent::__construct();
   }

   public function ListSQL($sql, $Debug = 0)
   {
      $xdb = new NemoDatabase("",null,null,0);

      if($this->isPageable == 1 && $this->SystemSettings["PageSize"] > 0)
      {
         /*
          * check for existing limits, get last (might have limit inside sub Q)
          * remove existing limit
          * count number of rows
          * apply new limit
          *
          */


         $intLengthSQL = strlen($sql);
         $idxLimit = strrpos(strtolower($sql), "limit");

         $limitStart = @round($this->SystemSettings["PageSize"] * $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->PageNumber - $this->SystemSettings["PageSize"],0);

         if($idxLimit > 0 && ($intLengthSQL - $idxLimit) < 20)
         {//remove existing limits and implement new limit
            $sql = substr($sql, 0, $idxLimit);
         }

         $row = $xdb->getRowSQL("SELECT COUNT(*) as intRecords FROM ($sql) AS tblCount ",0);
         $this->intRecords = $row->intRecords;

         $sql .= "
               LIMIT $limitStart, ". $this->SystemSettings["PageSize"];
      }

      $this->sql = nCopy($sql);
      $rst = $xdb->doQuery($sql,$Debug);
      $this->sql2 = $sql;

      //reset
      unset($this->Data, $this->Columns);
      //build columns
      $i = 0;
      
      while($column = $xdb->fetch_field($rst))
      {//20110621 - added field-type data-transforms - pj
         //by db
         //if($column->table != "" && $column->numeric == 1)
         if($column->table != "" && $column->type == MYSQLI_TYPE_TINY)
         {
            //$rstTable = $xdb->doQuery("DESCRIBE `$column->table`",0);
            // if($rstTable){
            // while($rowT = $xdb->fetch_object($rstTable))
            // {//echo "<BR> stripos($rowT->Field, $column->name) : ". stripos($rowT->Field, $column->name);
               // if(stripos($rowT->Type, "tinyint") > -1 && stripos(str_replace($column->name," ",""), $rowT->Field) > -1 || true)
               // {//print_rr($rowT);print_rr($column);
                  $column->eval .= "\$row[\$idxCol] = \$this->arrEvalTinyint[\$row[\$idxCol]];"; //note: can happen only once
                  $column->type = "tinyint";
                  //break;
               //}
            //}}
         }
         //override
         switch($column->type)
         {
            case "tinyint":
            case "date":
               $column->html->align = "center";
               break;
         }
         //print_rr($column);

         //default
         if($column->html->align == "")
         {
            if($column->numeric == 1)
               $column->html->align = "right";
            else
               $column->html->align = "left";
         }

         $column->idx = $i;

         $this->Columns[$column->name] = nCopy($column);

         $i++;
      }
      //print_rr($this->Columns);

      //mysql_data_seek($rst,0);
      while($row = $xdb->fetch_array($rst))
      {
         $this->Data[count($this->Data)] = $row;
      }

      //$this->isSortable = 1;
   }

   public function ListDATA($arrData=null)
   {
      if($arrData != null)
         $this->Data = $arrData;

      //if null then reassimulate columns
         $i=0;

      foreach($this->Data[0] as $idx => $value)
      {//reduild columns

         $this->Columns[$idx]->name = $idx;
         $this->Columns[$idx]->idx = round($i/2.0000001,0);

         $i++;
      }

      foreach($this->Columns as $idx => $column)
      {//checks the data in the first row to determine html->align if columns were not set
         //print_rr($this->Data[0]);

         //print_rr($column);
         //echo is_numeric($this->Data[0][$idx]);
         if(!isset($column->numeric)){
            if(is_numeric($this->Data[0][$idx])){
               $this->Columns[$idx]->numeric = 1;
            }
            else{
               $this->Columns[$idx]->numeric = 0;
            }
         }
         if($this->Columns[$idx]->numeric == 1)
            $this->Columns[$idx]->html->align = "right";
         else
            $this->Columns[$idx]->html->align = "left";
      }
   }

   public function renderList($srtArg=null)
   {
      global $SP;

      if(is_array($_REQUEST[chkSelect]))
      {
         $arrSelect = $_REQUEST[chkSelect];
      }
      //print_rr($_REQUEST);

      $strList .= "<tr><th width='1%'>#</th>";
      foreach($this->Columns as $idxCol => $column)
      {
         $htmlAttr = NemoControl::renderAttributes($column->Header->html);
         if($this->isSortable)
            $strSort = "class='linkbutton2 textColour' onclick=\"window.location.href='?srtNew=$column->name&srtCurrent=".$srtArg[srtCurrent]."&srtDir=".$srtArg[srtDir]."'\"";
         $strList .= "<th $htmlAttr $strSort>$column->name</th>";
      }
      if(count($this->DataKey)>0 && $this->isSelectable == 1) $strList .= "<th width='1%' class='linkbutton2 textColour' onclick='jsToggleSelect();'>Select</th>";
      $strList .= "</tr>";

      if($this->isPageable == 1 && $this->SystemSettings["PageSize"] > 0)
         $i = $this->SystemSettings["PageSize"] * $this->Pages[$this->SystemSettings[SCRIPT_NAME]]->PageNumber - $this->SystemSettings["PageSize"] + 1;
      else
         $i = 1;


      if(count($this->Data)>0){
      foreach($this->Data as $idxRow => $row)
      {
//print_rr($row); die;
         if(count($this->DataKey)>0)
         {//print_rr($this->DataKey);
            $evalkey = $selectKey = $checked = "";
            foreach($this->DataKey as $key => $value)
            {
               $evalkey .= "&$key=". $row[$value];
               $selectKey .= "[". $row[$value] ."]";

            }
            //echo "<BR>$id: $select = ";
            //eval ("echo \$$select;");
            $select = "arrSelect".$selectKey;
            if($selectKey != "[]")//failsafe
               eval("\$checked = \$$select;");

            $chkSelect = "<input type=checkbox name='chkSelect$selectKey' id='chkSelect' value='checked' $checked>";
            //var_dump($evalkey);die;
         }
         if(count($this->DataKey)>0)
         {//20110621 - refined anchor tag click - pj
            $a1 = "<a id='a[$i]' href=\"?Action=Edit$evalkey\" target=_blank>";
            $a2 = "</a>";
         }
         $strList .= "<tr ". NemoControl::renderAttributes($row[html]) ."><td align='right' class='tdCount textColour'>$a1$i$a2</td>"; //20140205 - add ->Data[$i][html] in order to render attributes in the data TR on renderList() - pj

         foreach($this->Columns as $idxCol => $column)
         {
//print_rr($column);
            if($this->eval != "") eval($this->eval); // used in NemoList
            if($column->eval != "") eval($column->eval); // used in NemoList
            //print_rr($column);die;
            $htmlAttr = NemoControl::renderAttributes($column->html);
            $strList .= "<td $htmlAttr >". $row[$idxCol] ."</td>";
         }
         if(count($this->DataKey)>0 && $this->isSelectable == 1) $strList .= "</td><td align=center>$chkSelect";
         $strList .= "</td></tr>";
         $i++;
//break;
      }}
      //print_rr($this);die;
      return $strList;
   }

   public function renderTable($strCaption="", $tableHTML=null)
   {
      global $BR;

      $strList = $this->renderList();

      $htmlAttr = NemoControl::renderAttributes($tableHTML);
      if($strCaption != "")
         $strCaption = "<caption>$strCaption</caption>";

      return "
      <table border='0' cellpadding='2' cellspacing='1' width='100%' $htmlAttr>
         $strCaption
         $strList
      </table>
      $BR";
   }

   public function setEvalTinyint($key, $value)
   {//20110621 - added field-type data-transforms - pj
      $this->arrEvalTinyint[$key] = $value;
   }

}


?>
