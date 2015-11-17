<?php
include_once("_framework/_nemo.list.cls.php");
//include_once("includes/faq.cls.php");
include_once("_framework/_nemo.faq.cls.php");

class FAQConsole extends NemoList
{
	private $ID = 0;

	public function __construct($DataKey)
	{
		//construct 
		parent::__construct($DataKey);
	}

	/*public function getTreeList()
   {
      global $xdb;
      $topic1;
      $topic2;
      $i = 1;

     $rst = $xdb->doQuery("SELECT * FROM sysFAQ");

     $treeList .= "<li>";

      while($topic = $rst->fetch_object())
      {
         $topic2 = $topic1;
         $topic1 = $topic->EN_lstTopic;
         
         if($topic2 != $topic1){
            $treeList .= "<label for='mylist-node$i'>$topic->EN_lstTopic</label>
            <input type='checkbox' id='mylist-node$i' /><ul>";

            $i++;
         }
         $treeList .= "<li id ='$topic->FAQID'>$topic->EN_strTitle</li>";
      }


      return "<ul class='collapsibleList'>$treeList</li></ul>"; 
   }*/

   /*public function getTreeList()
   {
      global $xdb;
      $topic1;
      $topic2;
      $i = 1;
      $Language = $_SESSION[USER]->LANGUAGE;

      $Where = "";

      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE (". $xdb->qs("%". $this->Filters[frSearch]->html->value . "%") . ")";
         $Where .= " AND (sysFAQ.".$Language."_lstTopic $like
                        OR sysFAQ.".$Language."_strTitle $like
                        OR sysFAQ.".$Language."_txtFAQ $like
                        ) ";
      }

      if($this->Filters[frTopic]->html->value != -1)
         $Where .= " AND ". $Language."_lstTopic = '" .$this->Filters[frTopic]->html->value . "'";

      if($this->Filters[frRegistration]->html->checked == "checked")
      {
         $like = "LIKE (".$xdb->qs("%". $this->Filters[frRegistration]->html->value . "%") . ")";
         $Where .= " AND (sysFAQ.strTags $like) ";
      }

      if($this->Filters[frSawis3]->html->checked == "checked")
      {
         $like = "LIKE (".$xdb->qs("%". "3" . "%") . ")";
         $Where .= " AND (sysFAQ.strTags $like) ";
      }

      if($this->Filters[frSawis2]->html->checked == "checked")
      {
         $like = "LIKE (".$xdb->qs("%". "2" . "%") . ")";
         $Where .= " AND (sysFAQ.strTags $like) ";
      }

      $rst = $xdb->doQuery("SELECT * FROM sysFAQ WHERE 1=1 $Where ORDER BY dtLastEdit DESC ");

      $treeList .= "<li>";

      while($topic = $rst->fetch_object())
      {
         $topic2 = $topic1;
         $topic1 = $topic->{$Language."_lstTopic"};
         
         if($topic2 != $topic1){
            $treeList .= "<label for='mylist-node$i'>".$topic->{$Language."_lstTopic"}."</label>
            <input type='checkbox' checked id='mylist-node$i'/><ul>";

            $i++;
         }
         $dtDate = explode(" ", $topic->dtLastEdit);
         $treeList .= "
         <li id ='$topic->FAQID'><a href='faq.php?Action=NavTopic&&FAQID=$topic->FAQID'>".$topic->{$Language."_strTitle"}."</a>, ". $dtDate[0]. "</li>";
         //$treeList .= "</ul>";
      }

      return " <ul class='collapsibleList'>$treeList</li></ul><input type='hidden' id='blnChecked'>"; 
   }*/

	public function getContent()
	{
		global $xdb;

		$Language = $_SESSION[USER]->LANGUAGE;

		$FAQID = $_GET['FAQID'];
		if($FAQID == "")
			$FAQID = 0;

      //print_rr($_POST);

      if($_POST["Action"] == "Clear" || $_GET["Action"] == "View"){
         $_POST["frSearch"] = "";
         $_POST["frTopic"] = -2;
         $_POST["frRegistration"] = "";
         $_POST["frSawis3"] = "";
         $_POST["frSawis2"] = "";
      }

      //print_rr($_POST);

      if($_POST["frSearch"] != ""){
         $like = "LIKE(". $this->db->qs("%".$_POST["frSearch"]."%").")";
         $Where .= " AND (lstTopic $like
                  OR strTitle $like
                  OR txtFAQ $like)";
         $frSearchValue = $_POST["frSearch"];
      }else
         $Where = "";

      /*switch ($_POST["frTopic"]) {
         case "-2":
            $Where .= "";
            break;
         default:
            $Where .= " AND lstTopic = '".$_POST['frTopic']. "'";
            break;
      }*/

      if($_POST["frTopic"])
      {
         if($_POST["frTopic"] != "-2"){
            $Where .= " AND lstTopic = '".$_POST["frTopic"]."'";
            } 
      }

      if($_POST["frRegistration"] == "registration"){
         $like = "LIKE(". $this->db->qs("%".$_POST["frRegistration"]."%").")";
         $Where .= " AND (strTags $like)";
         $frRegistration = "checked";
      }

      if($_POST["frSawis2"] == "sawis2"){
         $like = "LIKE(". $this->db->qs("%".$_POST["frSawis2"]."%").")";
         $Where .= " AND (strTags $like)";
         $frSawis2 = "checked";
      }

      if($_POST["frSawis3"] == "sawis3"){
         $like = "LIKE(". $this->db->qs("%".$_POST["frSawis3"]."%").")";
         $Where .= " AND (strTags $like)";
         $frSawis3 = "checked";
      }

      $FaqPage = new FAQ();

      if($_GET[Action] == "View")
      {
         //IF FAQID != 0 - DISPLAY SPECIFIC FAQ
         $rst = $xdb->doQuery("SELECT * FROM vieFAQ_$Language WHERE FAQID = ". $_GET[FAQID], 0);

         $rowContent = $rst->fetch_object();

         $dtDate = explode(" ", $rowContent->dtLastEdit);
         $dtDate = $dtDate[0];

         $strHMTLContent = "
            <table xclass='tblNemoList' width='100%' border=1 cellpadding=2 cellspacing=1>
               <caption style='text-align:left'>$rowContent->strTitle - $dtDate</caption>
               <tr><td>$rowContent->txtFAQ</td></tr>
            </table>";
         //--   
      }
      else
      {
   		$rst = $xdb->doQuery("SELECT * FROM vieFAQ_$Language WHERE 1 = 1 $Where ORDER BY strTitle", 0);

         while($rowContent = $rst->fetch_object()){

         //IF FAQID = 0 -- DISPLAY ALL FAQ
            $dtDate = explode(" ", $rowContent->dtLastEdit);
            $dtDate = $dtDate[0];

            $txtFAQ = $rowContent->txtFAQ;
            $truncated = (strlen($txtFAQ) > 500) ? substr($txtFAQ, 0, 260) . "... <a href='faq.console.php?Action=View&FAQID=$rowContent->FAQID'>read more</a>" : $txtFAQ;
            //echo $truncated;
            //$truncated = $txtFAQ;
            $strHMTLContent .= "
               <table xclass='tblNemoList' width='100%' border=1 cellpadding=2 cellspacing=1>
                  <caption style='text-align:left'>$rowContent->strTitle - $dtDate</caption>
                  <tr><td>$truncated</td></tr>
               </table>
            ";
         }
      }

      $html = "
         <table xclass='tblNemoList' width='100%' cellpadding=2 cellspacing=1>
            <tr>
               <td colspan=100%>
               ". $FaqPage->getFilters() ."
               </td>
            </tr>
            <tr>
               <td width='85%' valign='top'>
               $strHMTLContent
               </td>
   			<td valign='top'>". $FaqPage->getTreeList() ."</td>
   			</tr>
   			<tr>
   				<td colspan=100% >". $FaqPage->renderPager().
               "</td>
   				</tr>
   			</table>"; 

		return $html;

	}
}

?>