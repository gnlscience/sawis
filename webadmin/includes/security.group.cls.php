<?php
include_once("_framework/_nemo.list.cls.php");

class SecurityGroup extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      parent::__construct($DataKey);
   }

   public function getList($frClientID)
   {
      $this->ListSQL("SELECT sysSecurityGroup.SecurityGroupID,
                             sysSecurityGroup.strSecurityGroup as 'Security Group',
                             sysSecurityGroup.blnActive as 'Active',
                             sysSecurityGroup.strLastUser as 'Last User',
                             sysSecurityGroup.dtLastEdit as 'Last Edit'
                             FROM sysSecurityGroup
                             GROUP BY sysSecurityGroup.strSecurityGroup,sysSecurityGroup.blnActive,sysSecurityGroup.strLastUser,sysSecurityGroup.dtLastEdit
                             ORDER BY sysSecurityGroup.strSecurityGroup",0,"","Edit");
      return $this->renderTable("User Group List");
   }

   public function getSecurityEntities($SecurityGroupID)
   {
      global $xdb;

      $sql = " SELECT
               sysMenuLevel2.MenuLevel2ID,
               sysMenuLevel1.strMenuLevel1,
               sysMenuLevel2.strMenuLevel2,
               sysMenuLevel2.strEntity,
               sysMenuLevel2.strUrl,
               sysMenuLevel2.strNotes,
               sysMenuLevel2.intOrder,
               sysMenuLevel2.blnMenuItem,
               sysMenuLevel2.blnDivider
               FROM (sysSecurity
                 RIGHT JOIN (sysMenuLevel2
                 LEFT JOIN sysMenuLevel1 ON sysMenuLevel2.MenuLevel1ID = sysMenuLevel1.MenuLevel1ID) ON sysSecurity.refMenulevel2ID = sysMenuLevel2.MenuLevel2ID)
                    GROUP BY sysMenuLevel2.strMenuLevel2
                    ORDER BY sysMenuLevel1.intOrder ASC , sysMenuLevel2.intOrder ASC , sysMenuLevel2.strMenuLevel2
                    ";

      $rst = $xdb->doQuery($sql);
      while($row = $xdb->fetch($rst))
      {
         $sql = "SELECT blnView,blnDelete,blnSave,blnNew,blnSpecial
                 FROM sysSecurity WHERE
                  refSecurityGroupID = '$SecurityGroupID' AND
                  refMenulevel2ID = '$row->MenuLevel2ID' ";

         $rstSecurity = $xdb->doQuery($sql);
         $rowSecurity = $xdb->fetch($rstSecurity);
         $strList .= "
                        <tr>
                           <td nowrap>$row->strMenuLevel1</td>
                           <td nowrap>$row->strMenuLevel2</td>
                           <td nowrap><input class='controlText' type='text' name='strUrl[$row->MenuLevel2ID]' id='strUrl[$row->MenuLevel2ID]' value='$row->strUrl'></td>
                           <td nowrap><input class='controlText' type='text' name='strEntity[$row->MenuLevel2ID]' id='strEntity[$row->MenuLevel2ID]' value='$row->strEntity'></td>
                           <td align='center'><input class='controlText' type='text' name='strNotes[$row->MenuLevel2ID]' id='strNotes[$row->MenuLevel2ID]' value='$row->strNotes'></td>
                           <td nowrap align='center'><input class='controlText' type='text' style='width:60px;text-align:right' name='intOrder[$row->MenuLevel2ID]' id='intOrder[$row->MenuLevel2ID]' value='$row->intOrder'></td>
                           <td nowrap align='center'><input type='checkbox' ".($row->blnMenuItem==1?"checked":"")." name='blnMenuItem[$row->MenuLevel2ID]' id='blnMenuItem[$row->MenuLevel2ID]' value='1'></td>
                           <td nowrap align='center' style='border-right: 1px solid #9B9B9B;'><input type='checkbox' ".($row->blnDivider==1?"checked":"")." name='blnDivider[$row->MenuLevel2ID]' id='blnDivider[$row->MenuLevel2ID]' value='1'></td>
                           <td nowrap align='center' style='border-left: 1px solid #9B9B9B;'><input type='checkbox' ".($rowSecurity->blnView==1?"checked":"")." name='blnView[$row->MenuLevel2ID]' id='blnView[$row->MenuLevel2ID]' value='1'></td>
                           <td nowrap align='center'><input type='checkbox' ".($rowSecurity->blnNew==1?"checked":"")." name='blnNew[$row->MenuLevel2ID]' id='blnNew[$row->MenuLevel2ID]' value='1'></td>
                           <td nowrap align='center'><input type='checkbox' ".($rowSecurity->blnSave==1?"checked":"")." name='blnSave[$row->MenuLevel2ID]' id='blnSave[$row->MenuLevel2ID]' value='1'></td>
                           <td nowrap align='center'><input type='checkbox' ".($rowSecurity->blnDelete==1?"checked":"")." name='blnDelete[$row->MenuLevel2ID]' id='blnDelete[$row->MenuLevel2ID]' value='1'></td>
                           <td nowrap align='center'><input type='checkbox' ".($rowSecurity->blnSpecial==1?"checked":"")." name='blnSpecial[$row->MenuLevel2ID]' id='blnSpecial[$row->MenuLevel2ID]' value='1'></td>
                        </tr>
                     ";
      }
      return "

           <table cellspacing='1' cellpadding='2' border='0' width='100%' class='tblNemoList'>
            <caption>Security Entities</caption>
               <tbody>
                  <tr>
                     <th colspan='8' style='border-right: 1px solid #9B9B9B;'>Entity</th>
                     <th colspan='5' style='border-left: 1px solid #9B9B9B;' nowrap>Access Level</th>
                  </tr>
                  <tr>
                     <th nowrap>Section</th>
                     <th nowrap>Sub Section</th>
                     <th nowrap>Url</th>
                     <th nowrap>Entity</th>
                     <th nowrap>Notes</th>
                     <th nowrap>Order</th>
                     <th nowrap>Menu Item</th>
                     <th nowrap style='border-right: 1px solid #9B9B9B;'>Divider</th>
                     <th style='border-left: 1px solid #9B9B9B;'>View</th>
                     <th>New</th>
                     <th>Save</th>
                     <th>Delete</th>
                     <th>Special</th>
                  </tr>
                  $strList
               </tbody>
            </table>
       ";
   }
   public static function Save(&$SecurityGroupID)
   {

      $db = new NemoDatabase("sysSecurityGroup", $SecurityGroupID, null, 0);
      $db->Fields[strSecurityGroup] = $_POST[strSecurityGroup];
      $db->Fields[blnActive] = ($_POST[blnActive] != "" ? "1" : "0");
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;
      $result = $db->save();
      if($SecurityGroupID == 0) $SecurityGroupID = $db->ID[SecurityGroupID];

      $strNotes = $_POST['strNotes'];
      $strEntity = $_POST['strEntity'];
      $strUrl = $_POST['strUrl'];
      $intOrder = $_POST['intOrder'];
      $blnMenuItem = $_POST['blnMenuItem'];
      $blnDivider = $_POST['blnDivider'];
      /*
       * Access Levels
       */
      $blnView = $_POST['blnView'];
      $blnDelete = $_POST['blnDelete'];
      $blnSave = $_POST['blnSave'];
      $blnNew = $_POST['blnNew'];
      $blnSpecial = $_POST['blnSpecial'];

      /*
       * Delete Item form  Security Table
       */
      $db->doQuery("DELETE FROM sysSecurity WHERE refSecurityGroupID = ". $db->qs($SecurityGroupID) );

      if(count($strNotes) > 0){
      foreach($strNotes as $key => $value)
      {
         /*
          * Items updated for the Menu Level 2 Section
          */
         $sysMenuLevel2db = new NemoDatabase("sysMenuLevel2", $key, null, 0);
            $sysMenuLevel2db->Fields['strEntity'] = $strEntity[$key];
            $sysMenuLevel2db->Fields['strNotes'] = $strNotes[$key];
            $sysMenuLevel2db->Fields['strUrl'] = $strUrl[$key];
            $sysMenuLevel2db->Fields['intOrder'] = $intOrder[$key];
            $sysMenuLevel2db->Fields['blnMenuItem'] = ($blnMenuItem[$key] != "" ? 1 : 0);
            $sysMenuLevel2db->Fields['blnDivider'] = ($blnDivider[$key] != "" ? 1 : 0);
         $sysMenuLevel2db->Save();

         /*
          * Items inserted into the Security Table
          */
         $sysSecuritydb = new NemoDatabase("sysSecurity", 0, null, 0);
            $sysSecuritydb->Fields['refSecurityGroupID'] = $SecurityGroupID;
            $sysSecuritydb->Fields['refMenulevel2ID'] = $key;
            $sysSecuritydb->Fields['blnView'] = ($blnView[$key] != "" ? 1 : 0);
            $sysSecuritydb->Fields['blnDelete'] = ($blnDelete[$key] != "" ? 1 : 0);
            $sysSecuritydb->Fields['blnSave'] = ($blnSave[$key] != "" ? 1 : 0);
            $sysSecuritydb->Fields['blnNew'] = ($blnNew[$key] != "" ? 1 : 0);
            $sysSecuritydb->Fields['blnSpecial'] = ($blnSpecial[$key] != "" ? 1 : 0);
         $sysSecuritydb->Save();
      }
      }

      if($result->Error == 1){
         return $result->Message;
      }
      else{
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
         $xdb->doQuery("DELETE FROM sysSecurityGroup WHERE SecurityGroupID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
