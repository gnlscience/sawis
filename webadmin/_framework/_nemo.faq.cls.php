<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblGLAccountName` drop FOREIGN KEY `tblGLAccountName.strCompany`;
ALTER TABLE `tblGLAccountName` ADD CONSTRAINT `tblGLAccountName.strCompany` FOREIGN KEY (`refCompanyID`) REFERENCES `tblCompany` (`CompanyID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations
$_TRANSLATION["EN"]["strTitle"] = "Title";
$_TRANSLATION["AF"]["strTitle"] = "Titel";
$_TRANSLATION["EN"]["txtFAQ"] = "FAQ";
$_TRANSLATION["AF"]["txtFAQ"] = "AF: FAQ";

class FAQ extends NemoList
{
   private $ID = 0;

   /*public function __construct($DataKey)
   {
      $this->Filters[frSearch]->label = "lblSearch"; //20150310 - translating filter labels [note: can code lblSearch or just use the translations for frSearch as a default]
      $this->Filters[frTopic]->tag = "select";
      $this->Filters[frTopic]->html->value = "";
      $this->Filters[frTopic]->html->class = "comboBox";
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

      parent::__construct($DataKey);

   } */

   public function __construct(){

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;

      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")";
         $Where .= " AND (Tags $like OR FAQ $like)";
      } 
//sysFAQ.FAQID, sysFAQ.strTitle AS 'Title' , sysFAQ.strTags AS 'Tags', sysFAQ.strLastUser AS 'Last User', sysFAQ.dtLastEdit AS 'Last Edit'
      $this->ListSQL("
         SELECT FAQID, EN_lstTopic AS Topic, EN_strTitle AS Title, AF_strTitle AS Titel, strTags AS Tags, intOrder AS 'Order', blnActive AS Active, strLastUser AS 'Last User', dtLastEdit AS 'Last Edit'
         FROM sysFAQ
         WHERE 1=1 $Where
         ORDER BY EN_lstTopic, intOrder",0);


      return $this->renderTable("FAQ List");

      die;
   }

   public static function Save(&$FAQID, $nemo)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $PHP_SELF, $DATABASE_SETTINGS, $SystemSettings;
      $db = new NemoDatabase("sysFAQ", $FAQID, null, 0);

      $db->SetValues($_POST);
    
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      $result = $db->Save();
      
      if($FAQID == 0) 
      {
         $FAQID = $db->ID[FAQID];
      }
      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details Saved.";
      }
   }

   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         $xdb->doQuery("DELETE FROM sysFAQ WHERE FAQID = ". $xdb->qs($key));
         //$xdb->doQuery("UPDATE tblGLAccountName SET blnActive = 0 WHERE GLAccountNameID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }

   public function getTreeList()
   {
      global $xdb;
      $topic1;
      $topic2;
      $i = 1;
      $Language = $_SESSION[USER]->LANGUAGE;

     $rst = $xdb->doQuery("SELECT * FROM vieFAQ_$Language");

     $treeList .= "<li>";

      while($topic = $rst->fetch_object())
      {
         $topic2 = $topic1;
         $topic1 = $topic->lstTopic;
         
         if($topic2 != $topic1){
            $treeList .= "<label for='mylist-node$i'>$topic->lstTopic</label>
            <input type='checkbox' checked id='mylist-node$i' /><ul>";

            $i++;
         }
         $treeList .= "<li id ='$topic->FAQID'><a href='faq.console.php?Action=View&FAQID=$topic->FAQID'>$topic->strTitle</a></li>";
      }


      return "<ul class='collapsibleList'>$treeList</li></ul>"; 
   }

   public function getFilters(){

      global $xdb;

      $Language = $_SESSION[USER]->LANGUAGE;

      $rstTopic = $xdb->doQuery("SELECT -2 AS ControlValue, '- All -' AS ControlText
                        UNION ALL 
                           SELECT lstTopic AS 'ControlValue', lstTopic AS 'ControlText'
                           FROM vieFAQ_$Language
                           GROUP BY lstTopic
                        ORDER BY ControlText");

      while($rowTopic = $xdb->fetch_object($rstTopic)){
         if($_POST["frTopic"] == $rowTopic->ControlValue)
            $selected = "selected";   
         else
            $selected = "";
         $ddlTopic .= "<option value='$rowTopic->ControlValue' $selected>$rowTopic->ControlText</option>";            
      }

      return "<table xclass='tblNemoList' width='45%' cellpadding=2 cellspacing=1>
               <tr>
                  <td nowrap='' align='right'><label for='frSearch'>Search:</label></td>
                  <td colspan='3' align='left'><input id='frSearch' class='controlText' name='frSearch' value='$frSearchValue'></td>
                  <td nowrap='' align='right'><label for='frTopic'>Topic:</label></td>
                  <td align='left'>
                  <select id='frTopic' class='controlText' name='frTopic' value='-2'>
                     $ddlTopic
                  </select>
                  </td>
                  <td align='right'><input id='btnSearch' class='controlButton' type='submit' value='Search' name='Action' style=''></td>
                  <td align='left'><input id='btnClear' class='controlButton' type='submit' value='Clear' name='Action' style=''></td>
               </tr>
               <tr>
                  <td align='right'><label>Tags:</label></td>
                  <td><input type='checkbox' name='frRegistration' value='registration' $frRegistration>Registration</td> 
                  <td><input type='checkbox' name='frSawis2' value='sawis2' $frSawis2>Sawis 2</td>   
                  <td><input type='checkbox' name='frSawis3' value='sawis3' $frSawis3>Sawis 3</td>
               </tr>
               </table>";
   }


   
}
?>