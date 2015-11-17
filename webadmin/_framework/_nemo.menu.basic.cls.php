<?php
include_once("system.php");

class NemoMenu
{
   public $Control;
   public $Tier1 = array();
   public $Tier2 = array();

   public $sqlTier1;
   public $sqlTier2;

   public $SystemSettings = array();
   //private $db;
   
   public function __construct()
   {
      global $SystemSettings;
      $this->SystemSettings = $SystemSettings;
   }

   private function __autoload()
   {
   }

   public function setSecurityGroup($SGID)
   {
      $this->SystemSettings[USER]->SECURITYGROUPID = $SGID;
   }

   public function getControl()
   {
      return $this->Control;
   }


   public function BuildMenuArray()
   {
      foreach($this->Tier1 as $row1)
      {
         if(file_exists($row1->strUrl) || count($this->Tier2[$row1->ID]) > 0)
         {
            $strMenu .= "<LI class=''>";
            if(file_exists($row1->URL))
               $strMenu .= "<a href='$row1->URL'>$row1->Label</a>";
            else
               $strMenu .= "$row1->Label";

            $strMenu .= "<UL class=''>";
            foreach($this->Tier2[$row1->ID] as $row2)
            {
               $this->Tier2[$row2->ID] = $row2;

               $strMenu .= "<LI ". ($row2->blnDivider == 1 ? "class='divider'" : "class=''") .">";

               if(file_exists($row2->URL))
                  $strMenu .= "<a href='$row2->URL'>$row2->Label</a>";
               else
                  $strMenu .= "$row2->Label";

               $strMenu .= "</LI class=''>";
            }
            $strMenu .= "</UL class=''></LI class=''>";
         }
      }
   }

   public function BuildMenuSQL()
   {
      $xdb = new NemoDatabase("",null,null,0);

      $rst1 = $xdb->doQuery("
         SELECT tblMenuLevel1.MenuLevel1ID AS ID, tblMenuLevel1.strMenuLevel1 AS Label, tblMenuLevel1.strUrl AS URL, Count(tblMenuLevel2.MenuLevel2ID) as Children, tblMenuLevel1.intOrder AS 'Order'
         FROM tblMenuLevel2 RIGHT JOIN tblMenuLevel1 ON tblMenuLevel2.MenuLevel1ID = tblMenuLevel1.MenuLevel1ID
         GROUP BY tblMenuLevel1.MenuLevel1ID, tblMenuLevel1.strMenuLevel1, tblMenuLevel1.strUrl, tblMenuLevel1.intOrder
         ORDER BY tblMenuLevel1.intOrder");
      $strMenu = "<UL>";
      while($row1 = $xdb->fetch($rst1))
      {
         if(1)
         {
            $strMenu .= "<LI class=''>";
            if(file_exists($row1->URL))
               $strMenu .= "<a href='$row1->URL'>$row1->Label</a>";
            else
               $strMenu .= "$row1->Label";

            $this->Tier1[$row1->ID] = $row1;
            if($row1->Children > 0){
               $xdb->doQuery("
                  SELECT tblMenuLevel2.MenuLevel1ID as Tier1ID, tblMenuLevel2.MenuLevel2ID AS ID, tblMenuLevel2.strMenuLevel2 AS Label, tblMenuLevel2.strEntity AS Entity, tblMenuLevel2.strUrl AS URL, tblMenuLevel2.blnDivider, tblMenuLevel2.intOrder AS 'Order'
                  FROM tblMenuLevel2
                  WHERE (((tblMenuLevel2.MenuLevel1ID)=$row1->ID) AND ((tblMenuLevel2.blnMenuItem)=1))
                  GROUP BY tblMenuLevel2.MenuLevel1ID, tblMenuLevel2.MenuLevel2ID, tblMenuLevel2.intOrder, tblMenuLevel2.strMenuLevel2, tblMenuLevel2.strEntity, tblMenuLevel2.strUrl, tblMenuLevel2.blnDivider

                  ORDER BY tblMenuLevel2.intOrder, tblMenuLevel2.strMenuLevel2");

               $strMenu .= "<UL class=''>";
               while($row2 = $xdb->fetch())
               {
                  $this->Tier2[$row1->ID][$row2->ID] = $row2;

                  $strMenu .= "<LI ". ($row2->blnDivider == 1 ? "class='divider'" : "class=''") .">";

                  if(file_exists($row2->URL))
                     $strMenu .= "<a href='$row2->URL'>$row2->Label</a>";
                  else
                     $strMenu .= "$row2->Label";

                  $strMenu .= "</LI class=''>";
               }
               $strMenu .= "</UL class=''>";
            }
            $strMenu .= "</LI class=''>";
         }
      }
      $strMenu .= "</UL>";
      //echo $strMenu;

      $this->Control = $strMenu;
   }
}




?>
