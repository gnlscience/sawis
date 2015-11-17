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
   $_TRANSLATION["AF"]["txtFAQ"] = "FAQ";

class FAQ extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->label = "lblSearch"; //20150310 - translating filter labels [note: can code lblSearch or just use the translations for frSearch as a default]
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

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
//tblFAQ.FAQID, tblFAQ.strTitle AS 'Title' , tblFAQ.strTags AS 'Tags', tblFAQ.strLastUser AS 'Last User', tblFAQ.dtLastEdit AS 'Last Edit'
      /*$this->ListSQL("SELECT *
                     FROM ". $_SESSION[USER]->LANGUAGE ."_vieFAQ  
                     WHERE 1=1 $Where
                     ORDER BY 'Order'");*/

      $this->ListSQL("SELECT * FROM vieFAQ_". $_SESSION[USER]->LANGUAGE . " WHERE 1=1 $Where ORDER BY 'Order'");


      return $this->renderTable("FAQ List");
   }

   public static function Save(&$FAQID, $nemo)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $PHP_SELF, $DATABASE_SETTINGS, $SystemSettings;
      $db = new NemoDatabase("tblFAQ", $FAQID, null, 0);

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
         $xdb->doQuery("DELETE FROM tblFAQ WHERE FAQID = ". $xdb->qs($key));
         //$xdb->doQuery("UPDATE tblGLAccountName SET blnActive = 0 WHERE GLAccountNameID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
	
}

?>