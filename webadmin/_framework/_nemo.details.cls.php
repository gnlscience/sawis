<?php
include_once("_framework/_nemo.cls.php");
include_once("_framework/_nemo.database2.cls.php");

/* notes:
  setting up foreign key contraints:
   ADD CONSTRAINT `tblBooking.strDriver` FOREIGN KEY (`refDriverID`) REFERENCES `tblDriver` (`DriverID`) ON DELETE CASCADE ON UPDATE CASCADE,
   ADD CONSTRAINT `tblBooking.strClient` FOREIGN KEY (`refClientID`) REFERENCES `tblClient` (`ClientID`) ON DELETE CASCADE ON UPDATE CASCADE;

* 20150112 - v2 added tab controller - jj
//20150317 - added datalist control type- pj/jj
//20140717 - added RETURN_URL - maanie

*/
class NemoDetails extends Nemo
{
   //public $SystemSettings = array();
   public $ContentLeft;
   public $ContentRight;

   public $Fields = array();
   //public $Layout = "layout.back.details.incl.php";
   private $dbInfo;

   private $jsValidate = "";

   public function __construct()
   {
      parent::__construct();

      $this->ToolBar->Buttons[btnSave]->blnShow = $this->Security->blnSave;
      $this->ToolBar->Buttons[btnClose]->blnShow = 1;
      $this->ToolBar->Buttons[btnReload]->blnShow = 1;
      $this->ToolBar->Label = $this->ToolBar->Label ." Details";
      
      if($_REQUEST[RETURN_URL] != "")
      {//20140717 - added RETURN_URL - maanie
         $this->Fields[RETURN_URL]->Control->id =
         $this->Fields[RETURN_URL]->Control->name = "RETURN_URL";
         $this->Fields[RETURN_URL]->Control->tag = "input";
         $this->Fields[RETURN_URL]->Control->type = "hidden";
         $this->Fields[RETURN_URL]->VALUE =
         $this->Fields[RETURN_URL]->Control->value = $_REQUEST[RETURN_URL];
         
         $this->Fields[RETURN_VAR]->Control->id =
         $this->Fields[RETURN_VAR]->Control->name = "RETURN_VAR";
         $this->Fields[RETURN_VAR]->Control->tag = "input";
         $this->Fields[RETURN_VAR]->Control->type = "hidden";
         $this->Fields[RETURN_VAR]->VALUE =
         $this->Fields[RETURN_VAR]->Control->value = $_REQUEST[RETURN_VAR];
      }
   }

   private function __autoload()
   {// only applies to zend

   }

   public function AssimulateTable($table, $ID, $altEntityHeadingField="")
   {
      global $db, $DATABASE_SETTINGS; //from system.php
      //ini
      $currentDB = $DATABASE_SETTINGS[$this->SystemSettings[SERVER_NAME]]->database;
      $xdb = new NemoDatabase($table, $ID, null, 0);

      if($ID != 0)
         $this->ToolBar->Label .=": <span class='textColour'>$ID</span>";
      else
         $this->ToolBar->Label .=": <span class='textColour'>New Record</span>";
      //print_rr($xdb->Fields);

      mysqli_select_db($db, "information_schema");

      $this->dbInfo = new NemoDatabase("");

      $rst = $this->dbInfo->doQuery("SELECT COLUMNS.COLUMN_NAME, COLUMNS.ORDINAL_POSITION, COLUMNS.COLUMN_DEFAULT, COLUMNS.IS_NULLABLE, COLUMNS.DATA_TYPE, COLUMNS.COLUMN_TYPE, COLUMNS.CHARACTER_MAXIMUM_LENGTH, COLUMNS.EXTRA, COLUMNS.COLUMN_COMMENT, COLUMNS.COLUMN_KEY, KEY_COLUMN_USAGE.CONSTRAINT_NAME, KEY_COLUMN_USAGE.REFERENCED_TABLE_NAME, KEY_COLUMN_USAGE.REFERENCED_COLUMN_NAME
         FROM KEY_COLUMN_USAGE RIGHT JOIN COLUMNS ON (KEY_COLUMN_USAGE.TABLE_NAME = COLUMNS.TABLE_NAME) AND (KEY_COLUMN_USAGE.TABLE_SCHEMA = COLUMNS.TABLE_SCHEMA) AND (KEY_COLUMN_USAGE.COLUMN_NAME = COLUMNS.COLUMN_NAME)
         WHERE (((COLUMNS.TABLE_SCHEMA)='$currentDB') AND ((COLUMNS.TABLE_NAME)='$table'))
         ORDER BY COLUMNS.ORDINAL_POSITION ASC",0);
      while($row = $this->dbInfo->fetch())
      {
         //INI
         $blnContinue = 0;
         $this->Fields[$row->COLUMN_NAME] = $row;
         $this->Fields[$row->COLUMN_NAME]->VALUE = $xdb->Fields[$row->COLUMN_NAME];
         //default html control def
         $this->Fields[$row->COLUMN_NAME]->Control->id =
         $this->Fields[$row->COLUMN_NAME]->Control->name = $row->COLUMN_NAME;
         $this->Fields[$row->COLUMN_NAME]->Label = self::cleanColumnName($row->COLUMN_NAME);
         $this->Fields[$row->COLUMN_NAME]->Control->comment = $row->COLUMN_COMMENT;
         $this->Fields[$row->COLUMN_NAME]->Control->IS_NULLABLE = $row->IS_NULLABLE;

         if($xdb->Fields[$row->COLUMN_NAME] != "")
            $this->Fields[$row->COLUMN_NAME]->Control->value = $xdb->Fields[$row->COLUMN_NAME];
         else
            $this->Fields[$row->COLUMN_NAME]->Control->value = $row->COLUMN_DEFAULT;

         //PK
         if($row->COLUMN_KEY == "PRI")
         {
            $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
            $this->Fields[$row->COLUMN_NAME]->Control->type = "text"; //"hidden"
            $this->Fields[$row->COLUMN_NAME]->Control->readonly = "readonly";
            $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlLabel";
            //$this->Fields[$row->COLUMN_NAME]->Control->value = "PRIMARY ID";
            continue;// next record
         }

         //FK
         if($row->COLUMN_KEY == "MUL" && $row->REFERENCED_TABLE_NAME != "")
         {//print_rr($row);
            if($row->IS_NULLABLE == "NO"){
               $text = "-  Select  -";

            }
            else{
               $text = "-  None  -";
            }

            $controlText = str_replace($table .".", $row->REFERENCED_TABLE_NAME .".", $row->CONSTRAINT_NAME);
            $sqlDDL = "SELECT 0 AS ControlValue, '$text' AS ControlText
                        UNION ALL
                        SELECT `$row->REFERENCED_COLUMN_NAME` AS ControlValue, $controlText AS ControlText
                        FROM `$row->REFERENCED_TABLE_NAME`
                        WHERE blnActive = 1 OR `$row->REFERENCED_COLUMN_NAME` = '". $xdb->Fields[$row->COLUMN_NAME] ."'
                        ORDER BY ControlText ASC";
            $this->Fields[$row->COLUMN_NAME]->Control->tag = "select";
            $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText";
            $this->Fields[$row->COLUMN_NAME]->sql = $sqlDDL;

            $this->Fields[$row->COLUMN_NAME]->jsValidate = "
               if(d('$row->COLUMN_NAME').value == '' || d('$row->COLUMN_NAME').value == 0)
                  msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
               ";

            continue;// next record
         }

         //named columns 1
         switch($row->COLUMN_NAME)
         {
            case "dtLastEdit":
            case "dtFirstEdit":
               if($xdb->Fields[$row->COLUMN_NAME] == "") $this->Fields[$row->COLUMN_NAME]->Control->value = date("Y-m-d h:i:s");
               break;
            case "strLastUser":
            case "strFirstUser":
               if($xdb->Fields[$row->COLUMN_NAME] == "") $this->Fields[$row->COLUMN_NAME]->Control->value = $this->SystemSettings[USER]->USERNAME;
               break;
         }
         //named columns 2
         switch($row->COLUMN_NAME)
         {
            case "dtFirstEdit":
            case "dtLastEdit":
            case "strFirstUser":
            case "strLastUser":

               $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
               $this->Fields[$row->COLUMN_NAME]->Control->type = "text"; //"hidden"
               $this->Fields[$row->COLUMN_NAME]->Control->readonly = "readonly";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlLabel";
               //$this->Fields[$row->COLUMN_NAME]->Control->value = "ajlfbasf";
               //do not add to jsValidate
               $this->Fields[$row->COLUMN_NAME]->Control->IS_NULLABLE = "YES";
               $blnContinue = 1;


         }//note: using continue inside a switch does not effect the outer loop/while

         if($blnContinue) continue;

         if(strpos($row->COLUMN_NAME,'lst') === 0) // || (strpos($row->COLUMN_NAME,'_lst') !== false
         {//20150317 - added datalist control type- pj/jj
            //print_rr($row);
            $this->Fields[$row->COLUMN_NAME]->Control->tag = "datalist";
            $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlList";
            $this->Fields[$row->COLUMN_NAME]->sql = "SELECT $row->COLUMN_NAME AS ControlValue, '' AS ControlText FROM $table GROUP BY $row->COLUMN_NAME ORDER BY ControlText"; // note: No "- Select/None -" option
            $this->Fields[$row->COLUMN_NAME]->Control->autoconplete = "off";
            //print_rr($this->Fields[$row->COLUMN_NAME]);
            continue;
         }


         //all other
         switch($row->DATA_TYPE)
         {
            case "varchar":
               //textboxswitch($row->COLUMN_NAME)
               $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
               $this->Fields[$row->COLUMN_NAME]->Control->type = "text";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText";
               $this->Fields[$row->COLUMN_NAME]->Control->maxlength = $row->CHARACTER_MAXIMUM_LENGTH;
               $this->Fields[$row->COLUMN_NAME]->jsValidate = "
               if(trim(d('$row->COLUMN_NAME').value) == ''){
                  msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
                  d('$row->COLUMN_NAME').value = '';
               }
               ";
               //print_rr($this->Fields[$row->COLUMN_NAME]);
               break;

            case "text":
               //textarea
               $this->Fields[$row->COLUMN_NAME]->Control->tag = "textarea";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlWideMax";
               $this->Fields[$row->COLUMN_NAME]->Control->innerHTML = $this->Fields[$row->COLUMN_NAME]->Control->value;
               $this->Fields[$row->COLUMN_NAME]->jsValidate = "
                  if(trim(d('$row->COLUMN_NAME').value) == ''){
                     msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
                     d('$row->COLUMN_NAME').value = '';
                  }
                  ";
               break;

            case "double":
            case "int":
               $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
               $this->Fields[$row->COLUMN_NAME]->Control->type = "text";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlNumeric";
               $this->Fields[$row->COLUMN_NAME]->Control->onblur = "removeAlpha(this);";
               $this->Fields[$row->COLUMN_NAME]->jsValidate = "
                  if( isNaN(d('$row->COLUMN_NAME').value) )
                  {
                     msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
                  }
                  ";
               break;

            case "tinyint":
               //chk
               $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
               $this->Fields[$row->COLUMN_NAME]->Control->type = "checkbox";
               $this->Fields[$row->COLUMN_NAME]->Control->value = "checked";
               $this->Fields[$row->COLUMN_NAME]->Control->IS_NULLABLE = "YES";

            //var_dump($xdb->Fields[$row->COLUMN_NAME]);
               if($xdb->Fields[$row->COLUMN_NAME] == 1)
               {
                  $this->Fields[$row->COLUMN_NAME]->Control->checked = "checked";
               }
               elseif($xdb->Fields[$row->COLUMN_NAME] === "0")
               {//$xdb->Fields[] > string(1) "0" if no record is loaded, but blnX = 0
                  unset($this->Fields[$row->COLUMN_NAME]->Control->checked);
               }
               elseif($row->COLUMN_DEFAULT == 1)
               {//$xdb->Fields[] > int(0) if no record is loaded
                  $this->Fields[$row->COLUMN_NAME]->Control->checked = "checked";
               }
               $this->Fields[$row->COLUMN_NAME]->jsValidate = "
                  if( isNaN(d('$row->COLUMN_NAME').value) )
                  {
                     msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
                  }
                  ";
               break;

            case "enum":
               //ddl values = $row->COLUMN_TYPE, eg. COLUMN_TYPE: enum('Test Value1','Test Value 2')
               eval("\$arrValues = ". str_replace("enum", "array", $row->COLUMN_TYPE).";");
               //print_rr($arrValues);die;
               if($row->IS_NULLABLE == "NO"){
                  $text = "-  Select  -";
               }
               else{
                  $text = "- None -";
               }
               $sqlDDL = "";
               foreach($arrValues as $value){
                  $sqlDDL .= " UNION ALL SELECT '$value' AS ControlValue, '$value' AS ControlText ";
               }

               $sqlDDL = "SELECT 0 AS ControlValue, '$text' AS ControlText
                        $sqlDDL
                        ORDER BY ControlText ASC";

               $this->Fields[$row->COLUMN_NAME]->jsValidate = "
                  if(d('$row->COLUMN_NAME').value == '' || d('$row->COLUMN_NAME').value == 0)
                     msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
                  ";

               $this->Fields[$row->COLUMN_NAME]->Control->tag = "select";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText";
               $this->Fields[$row->COLUMN_NAME]->sql = $sqlDDL;

               break;

            case "date":
               //date box
               $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
               $this->Fields[$row->COLUMN_NAME]->Control->type = "text";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlNumeric controlDateTime datePicker";
               $this->Fields[$row->COLUMN_NAME]->Control->readonly = "readonly";
               if($row->IS_NULLABLE == "NO"){
                  if($this->Fields[$row->COLUMN_NAME]->Control->value == "" || $this->Fields[$row->COLUMN_NAME]->Control->value == "0000-00-00")
                     $this->Fields[$row->COLUMN_NAME]->Control->value = date("Y-m-d");
               }elseif($this->Fields[$row->COLUMN_NAME]->Control->value == "0000-00-00")
                  $this->Fields[$row->COLUMN_NAME]->Control->value = "";

               $this->Fields[$row->COLUMN_NAME]->jsValidate = "
                  if(d('$row->COLUMN_NAME').value == '' || d('$row->COLUMN_NAME').value == '0000-00-00')
                     msg += '\\n". $this->Fields[$row->COLUMN_NAME]->Label ."';
                  ";

               break;
            case "datetime":
            case "timestamp":
               //label
               $this->Fields[$row->COLUMN_NAME]->Control->tag = "input";
               $this->Fields[$row->COLUMN_NAME]->Control->type = "text"; //"hidden"
               $this->Fields[$row->COLUMN_NAME]->Control->readonly = "readonly";
               $this->Fields[$row->COLUMN_NAME]->Control->class = "controlText controlLabel controlDateTime";
               //$this->Fields[$row->COLUMN_NAME]->Control->value = "ajlfbasf";
               break;
         }//eoSwitch

      }
      //post
      //print_rr($this->Fields); 
      //die;

      mysqli_select_db($db, $currentDB);
      $this->renderControls();
         //vd($ID);
      if($ID == null){
         $this->ToolBar->Label .=": <span class='textColour'>New Record</span>";
      }else{
         if($altEntityHeadingField == ""){
            $altEntityHeadingField = $ID;
         }
         else{
            $altEntityHeadingField = $this->Fields[$altEntityHeadingField]->VALUE;
         }
         $this->ToolBar->Label .=": <span class='textColour'>$altEntityHeadingField</span>";
      }
   }

   public function getDetails($ID)
   {

   }

   public function renderControls()
   {

      foreach($this->Fields as $idxCol => $column)
      {//print_rr($column);
         if($column->Control->tag == "select"){
            
            $column->Control->innerHTML = $this->db->ListOptions($column->sql, $column->Control->value);
         } 

         if($column->Control->tag == "datalist" || $column->Control->datalist != "")
         {//20150317 - added datalist control type- pj/jj
            //print_rr($column); //die;
            $column->Control->datalist = "<datalist id='dl_". $column->Control->id ."'>". $this->db->ListOptions($column->sql, null) ."</datalist>";
            $column->Control->list = "dl_". $column->Control->id; //note input.list property must match the datalist.id
            $column->Control->tag = "input";
         }

         $this->Fields[$idxCol]->ControlHTML = NemoControl::renderControl($column->Control->tag, $column->Control);

      }
   }

   public function renderDetails()
   {

      $strDetail = "";
      foreach($this->Fields as $idxCol => $column)
      {
         $arrPosition[$idxCol] = $column->ORDINAL_POSITION;
      }
      array_multisort($arrPosition);

      //print_rr($arrPosition);

      foreach($arrPosition as $idxCol => $intOrd)
      {
//print_rr($column);
//vd($this->jsValidate );
         $column = $this->Fields[$idxCol];
         if($column->Control->IS_NULLABLE == "NO")
         {
            $required = $this->SystemSettings[imgRequired];
            $this->jsValidate .= $column->jsValidate;
         }else{
            $required = "";
         }
         
         $control = NemoControl::renderControl($column->Control->tag, $column->Control);

         if($column->Control->type == "hidden")
         {
            $strDetail .= $control;
         }else{
            
            if(isset($column->NewGroup))
            {
               $Test = "<tr><td class='dora-detailsGroup' colspan='100%'>$column->NewGroup</td></tr>";
            }
            else
            {
               $Test = "";
            }

            $strDetail .= "

            $Test
            <tr>
               <td align='right' class='tdDetailsLeft'><label for='$column->COLUMN_NAME'>$column->Label: </label><span class='fr'>$required</span></td>
               <td align='left' class='tdDetailsRight'>$control</td></tr>";
         }

      }

      return $strDetail;
   }

   public function renderTable($strCaption="", $tableHTML=null)
   {
      global $BR;

      $strDetail = $this->renderDetails();

      $htmlAttr = NemoControl::renderAttributes($tableHTML);
      if($strCaption != "")
         $strCaption = "<caption>$strCaption</caption>";
      return "
      <table class='dora-DetailsTable' border='0' cellpadding='2' cellspacing='1' width='100%' $htmlAttr>
         $strCaption
         $strDetail
      </table>
      ";
   }

   public function getJsNemoValidateSave($jsExtra="", $jsFuctionName="jsNemoValidateSave")
   {//vd($this->jsValidate);

      return "<script>
            function $jsFuctionName()
            {
               var msg = '';
               $this->jsValidate

               $jsExtra

               if(msg == '')
               {
                  return true;
               }else
               {
                  alert('Please complete all the required fields: \\n'+ msg);
                  return false;
               }
            }
            </script>";
   }

   public function getJsNemoValidateSaveTabs($jsExtra="", $jsFuctionName="jsNemoValidateSave")
   {//vd($this->jsValidate);

      return " 
            function $jsFuctionName()
            {
               var msg = '';
               $this->jsValidate

               $jsExtra

               if(msg == '')
               {
                  return true;
               }else
               {
                  alert('Please complete all the required fields: \\n'+ msg);
                  return false;
               }
            }
             ";
   }

   protected function Sandbox()
   {
      return "
      <table border='0' cellpadding='2' cellspacing='1' width='100%' id='tblContent'>
         <tr>
            <td align='left' valign='top' id='tdContentLeft'>
               <div id='divContentx'>
               ". $this->ContentLeft ."
               </div>
            </td>
            <td align='left' valign='top' id='tdContentRight'>
               <div id='divContentx'>
               ". $this->ContentRight ."
               </div>

            </td>
         </tr>
         <tr><td colspan='100%' style='background: transparent; padding: 0px;'>". $this->Content ."</td></tr>
      </table>
      ";
   }

   public static function cleanColumnName($ColumnName)
   {//remove the "str" and add spaces for every "word"
      //$translateTable = array("["=>"`",
      //                        "]"=>"`");//"funny chars" => "new chars". just add into the array
      //$sql = strtr($sql, $translateTable);
      //ini_set('display_errors', '0');
      $count = strlen($ColumnName);
      $i = 0;
      $ii = 0;
      $boolCap = false;
      $intCap = 0;
      $Output = "";

      //loop through characters
      while($i < $count)
      {
         $char = $ColumnName{$i};
         if($intCap > 1 && preg_match("/[a-z]/", $char)){
            $strings[$ii] .= "*".$char;
            $boolCap=false;
            $intCap=0;
         }
         //check if character is a capital letter and capital letter not done twice
         elseif(preg_match("/[A-Z]/", $char) && $boolCap == false ){
            $ii++;
            $strings[$ii] .= $char;
            $boolCap=true;
         }
         //otherwise not capital letter
         elseif(preg_match("/[a-z, 1-9\:]/", $char) ){//[a-z, 1-9\:] include numbers
            $strings[$ii] .= $char;
            $boolCap=false;
         }
         //check if capital letter was done twice
         elseif($boolCap == true){
            $strings[$ii] .= $char;
            $intCap++;
         }
         $i++;
      }//print_rr($strings);

      foreach($strings as $index => $value)
      {
         $string = $strings[$index];

         //if(ereg("[A-Z]", $string[0]))
         if(preg_match("/[A-Z]/", $string[0]))
         {
            $pos = strpos($string, "*");
            if($pos!="")
            {
               $cleanedstr = substr_replace($string, "", $pos-1);
               $cleanedstr2 = substr_replace($string, "",0, $pos-1 );
               $cleanedstr2=str_replace("*", "", $cleanedstr2);
               $Output .= $cleanedstr." ".$cleanedstr2;
            }
            else
            {
               $Output .= $string." ";
            }
         }
      }

      //echo "<BR>$Output: ". strpos($Output, " ID") ." _ ". (strlen($Output)-4);
      if(strpos($Output, " ID") === (strlen($Output)-4))
      {//20111031 - removed extra " ID" if last 3 chars of the lable, eg. "Region ID" => "Region"
         $Output = substr($Output, 0, strlen($Output)-4);
      }
      //ini_set('display_errors', '1');
      return $Output;
   }

}

?>
