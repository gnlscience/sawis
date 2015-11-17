<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `sysUser` drop FOREIGN KEY `sysUser.strSecurityGroup`;
ALTER TABLE `sysUser` ADD CONSTRAINT `sysUser.strSecurityGroup` FOREIGN KEY (`refSecurityGroupID`) REFERENCES `sysSecurityGroup` (`SecurityGroupID`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `sysUserFunction` drop FOREIGN KEY `sysUserFunction.strUser`;
ALTER TABLE `sysUserFunction` ADD CONSTRAINT `sysUserFunction.strUser` FOREIGN KEY (`refUserID`) REFERENCES `sysUser` (`UserID`) ON UPDATE CASCADE ON DELETE CASCADE;

*/
class User extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "-1";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = "SELECT -1 AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT 1 AS ControlValue, 'Active' AS ControlText
                        UNION ALL
                        SELECT 0 AS ControlValue, 'Inactive' AS ControlText

                        ORDER BY ControlText ASC";

      parent::__construct($DataKey);
   }

   public function getList()
   {
      $MemberID = $_SESSION['USER']->MEMBERID;

      global $xdb;
      //Get list of fuctions

      $varFuncList = "";
      $rstFunc = $xdb->doQuery("SELECT vieFunction.FunctionID, vieFunction.strFunction FROM vieFunction ORDER BY intOrder1, intOrder2");
      while($row = $xdb->fetch($rstFunc))
      {
         $varFuncList .= ", '' AS '$row->strFunction'";
      }

      //Search filters
      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")";
         $Where .= " AND (sysUser.UserID $like OR sysUser.strUser $like OR sysUser.strEmail $like)";
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
         $Where .= " AND sysUser.blnActive = ". $this->Filters[frStatus]->html->value;
      }

      $this->ListSQL("
            SELECT sysUser.refMemberID as MemberID, 0 as tmpMemberID, sysUser.UserID, sysUser.strUser AS User, sysSecurityGroup.strSecurityGroup AS 'Security Group', sysUser.strEmail AS Email $varFuncList, sysUser.blnActive AS Active, sysUser.strLastUser AS 'Last User', sysUser.dtLastEdit AS 'Last Edit'
            FROM ((sysUserFunction RIGHT JOIN sysUser ON sysUserFunction.refUserID = sysUser.UserID) INNER JOIN sysSecurityGroup ON sysUser.refSecurityGroupID = sysSecurityGroup.SecurityGroupID) INNER JOIN tblMember ON sysUser.refMemberID = tblMember.MemberID
            WHERE refSecurityGroupID != 13 AND sysUser.refMemberID = $MemberID $Where
            GROUP BY sysUser.UserID
         UNION ALL
            SELECT 0 as MemberID, sysUser.refMemberID as tmpMemberID,  sysUser.UserID, sysUser.strUser AS User, sysSecurityGroup.strSecurityGroup AS 'Security Group', sysUser.strEmail AS Email $varFuncList, sysUser.blnActive AS Active, sysUser.strLastUser AS 'Last User', sysUser.dtLastEdit AS 'Last Edit'
            FROM ((sysUserFunction RIGHT JOIN sysUser ON sysUserFunction.refUserID = sysUser.UserID) INNER JOIN sysSecurityGroup ON sysUser.refSecurityGroupID = sysSecurityGroup.SecurityGroupID) INNER JOIN tmpMember ON sysUser.refMemberID = tmpMember.MemberID
            WHERE refSecurityGroupID = 13 AND sysUser.refMemberID = $MemberID $Where
            GROUP BY sysUser.UserID
         ORDER BY User",1); //sysUser.UserID != ".$_SESSION['USER']->ID."

//print_rr($this->Data);
      //Loop to update $this->Data array
      foreach ($this->Data as $i => $row) {
        //Generate array of role function permissions
        $sql = "SELECT sysUserFunction.*, vieFunction.strFunction
                FROM sysUserFunction RIGHT JOIN vieFunction ON sysUserFunction.refFunctionID = vieFunction.FunctionID
                WHERE (refMemberID = ". $MemberID .") AND (refUserID = ". $this->Data[$i][UserID] .")";

        $rstRoleFunc = $xdb->doQuery($sql,1);

        $arrUF = array();
        while($row = $xdb->fetch($rstRoleFunc))
        {
            $arrUF[$row->strFunction]=$row->blnAccess;
        }

         foreach ($arrUF as $key => $row) {
            $this->Data[$i][$key]=($arrUF[$key] == "1" ? "<input type='checkbox' checked disabled>" : "<input type='checkbox' disabled>");
            $this->Columns[$key]->html->align="center";
         }
      }

      unset($this->Columns["MemberID"]);
      unset($this->Columns["tmpMemberID"]);

      return $this->renderTable("User List");
   }

   public function getRoleEntities($UserID)
   {
      global $xdb;
      $MemberID = $_SESSION['USER']->MEMBERID;

      //Get function and roles
      $sql = "SELECT
               vieFunction.FunctionID, vieFunction.strFunction, '" . $UserID . "' AS refUserID, '' AS blnAccess
               FROM vieFunction ORDER BY strFunction";        

      $rst = $xdb->doQuery($sql);

      while($row = $xdb->fetch($rst))
      {
         $sql = "SELECT sysUserFunction.refUserID, sysUserFunction.refFunctionID, sysUserFunction.blnAccess
                 FROM sysUserFunction WHERE
                  sysUserFunction.refUserID = '$UserID' AND
                  sysUserFunction.refMemberID = '$MemberID' AND
                  sysUserFunction.refFunctionID = '$row->FunctionID' ";

         $rstSecurity = $xdb->doQuery($sql);
         $rowSecurity = $xdb->fetch($rstSecurity);
         $strList .= "
                        <tr>
                           <td nowrap width='5%'>$row->FunctionID</td>
                           <td nowrap>$row->strFunction</td>
                           
                           <td nowrap><input class='controlText' type='hidden' name='refUserID[$row->FunctionID]' id='refUserID[$row->FunctionID]' value='$UserID'></td>
                           <td nowrap align='center' width='5%'><input type='checkbox' ".($rowSecurity->blnAccess==1?"checked":"")." name='blnAccess[$row->FunctionID]' id='blnAccess[$row->FunctionID]' value='1'></td>
                        </tr>
                     ";
      }
      if ($UserID != "") {
         //Get other companies
         $sql = "SELECT sysUserFunction.refUserID, sysUserFunction.refMemberID, tblMember.strMember
                  FROM sysUserFunction LEFT JOIN tblMember ON sysUserFunction.refMemberID = tblMember.MemberID
                  GROUP BY sysUserFunction.refUserID, sysUserFunction.refMemberID, tblMember.strMember
                  HAVING (sysUserFunction.refUserID=" . $UserID . ") AND (sysUserFunction.refMemberID<>" . $MemberID . ")";        

         $rst = $xdb->doQuery($sql);

         while($row = $xdb->fetch($rst))
         {
            $strListOther .= "
                           <tr>
                              <td nowrap width='5%'>$row->refMemberID</td>
                              <td nowrap>$row->strMember</td>
                           </tr>
                        ";
         }
      }
      else {
         $strListOther .= "";
      }

      if ($UserID != "") {
         //Get pending requests
         $sql = "SELECT sysUserFunction.refUserID, sysUserFunction.refMemberID, tblMember.strMember
                  FROM sysUserFunction LEFT JOIN tblMember ON sysUserFunction.refMemberID = tblMember.MemberID
                  GROUP BY sysUserFunction.refUserID, sysUserFunction.refMemberID, tblMember.strMember
                  HAVING (sysUserFunction.refUserID=" . $UserID . ") AND (sysUserFunction.refMemberID<>" . $MemberID . ")";        
         $rst = $xdb->doQuery($sql);

         while($row = $xdb->fetch($rst))
         {
            $strListPending .= "
                           <tr>
                              <td nowrap width='5%'>$row->refMemberID</td>
                              <td nowrap>$row->strMember</td>
                           </tr>
                        ";
         }
      }
      else {
         $strListPending .= "";
      }
      //Return tables
      return "
           <table cellspacing='1' cellpadding='2' border='0' width='100%' class='tblNemoList'>
            <caption>Functions : My Member</caption>
               <tbody>
                  <tr>
                     <th nowrap>Function ID</th>
                     <th nowrap>Function</th>
                     <th nowrap></th>
                     <th nowrap>Access</th>
                  </tr>
                  $strList
               </tbody>
            </table>

           <table cellspacing='1' cellpadding='2' border='0' width='100%' class='tblNemoList'>
            <caption>Other Members</caption>
               <tbody>
                  <tr>
                     <th nowrap>Member ID</th>
                     <th nowrap>Member Name</th>
                  </tr>
                  $strListOther
               </tbody>
            </table>            

           <table cellspacing='1' cellpadding='2' border='0' width='100%' class='tblNemoList'>
            <caption>(Still needs to be completed) Pending requests</caption>
               <tbody>
                  <tr>
                     <th nowrap>Member ID</th>
                     <th nowrap>Member Name</th>
                  </tr>
                  $strListPending
               </tbody>
            </table>                        
       ";
   }

   public static function Save(&$UserID)
   {
      $MemberID = $_SESSION['USER']->MEMBERID;
      $DefRoleID = $_POST['refRoleID'];

      global $xdb, $arrSys, $TR, $SP, $HR, $PHP_SELF, $DATABASE_SETTINGS, $SystemSettings;
      $db = new NemoDatabase("sysUser", $UserID, null, 0);

      $db->Fields[refMemberID] = $MemberID;

      $db->SetValues($_POST);

      if(isset($_SESSION['USER']->MEMBERID))
      {
         $db->Fields[refMemberID] = $_SESSION['USER']->MEMBERID;
      }

      $db->Fields[strPasswordMD5] = md5($_POST[strPassword]);

      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      if($UserID == 0){
         $db->Fields[strFirstUser] = $_SESSION['USER']->USERNAME;
         $db->Fields[dtFirstEdit] = date("Y-m-d H:i:s");
      }

      $result = $db->Save();
      
      if($UserID == 0) $UserID = $db->ID[UserID];

      if($result->Error != 1){
         if ($DefRoleID==0) {
            //Add function for saving sysUserFunction
            $FunctionID = $_POST['FunctionID'];
            $strFunction = $_POST['strFunction'];
            $refUserID = $_POST['refUserID'];
            $blnAccess = $_POST['blnAccess'];

            //Delete role functions form  sysRoleFunction table for the specified security role and replace with new role functions
            $db->doQuery("DELETE FROM sysUserFunction WHERE (refUserID = ". $UserID . ") AND (refMemberID = ". $MemberID . ")", 0 );

            if(count($refUserID) > 0){
              foreach($refUserID as $key => $value)
              {
                  $sysUserFunctiondb = new NemoDatabase("sysUserFunction", $UserID, null, 0);

                  $db->doQuery("INSERT INTO sysUserFunction ( refMemberID, refUserID, refFunctionID, blnAccess ) SELECT
                                 " . $MemberID . " AS refMemberID,
                                 " . $UserID . " AS refUserID,
                                 '" . $key . "' AS refFunctionID,
                                 " . ($blnAccess[$key] != "" ? 1 : 0) . " AS blnAccess", 0 );
              }
            } 
         }
         else {
            //Delete role functions form  sysRoleFunction table for the specified security role and replace with new role functions
            $db->doQuery("DELETE FROM sysUserFunction WHERE (refUserID = ". $UserID . ") AND (refMemberID = ". $MemberID . ")", 0 );

            $sysUserFunctiondb = new NemoDatabase("sysUserFunction", $UserID, null, 0);

            $db->doQuery("INSERT INTO sysUserFunction ( refMemberID, refUserID, refFunctionID, blnAccess ) SELECT
                           " . $MemberID . " AS refMemberID,
                           " . $UserID . " AS refUserID,
                           sysRoleFunction.refFunctionID,
                           sysRoleFunction.blnAccess
                           FROM sysRoleFunction
                           WHERE (sysRoleFunction.refRoleID=" . $DefRoleID . ")", 0 );
         }
      }
      //

      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details Saved.";
      }
   }

   public static function SaveMyProfile(&$UserID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $PHP_SELF, $DATABASE_SETTINGS, $SystemSettings;
      $db = new NemoDatabase("sysUser", $UserID, null, 0);

      if($db->Fields[strPassword] == qs($_POST[strOldPassword]))
      {
         $db->Fields[strPassword] = $_POST[strPassword];
         $db->Fields[strPasswordMD5] = md5($_POST[strPassword]);

         $db->Fields[strLastUser] = $_SESSION[USER]->USERNAME;
     
         $result = $db->Save(0,0);     
      }
      else
      {
         $result->Error = 1;
         $result->Message = "The old password does not match the current password in the database.";
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
         $UserID=$_SESSION['USER']->ID;

         if ($key != $UserID) {
            //Delete from user function table as well as the user table for selected userid
            $xdb->doQuery("DELETE FROM sysUserFunction WHERE (refUserID = ". $xdb->qs($key) . ") AND (refCompanyID = ". $_SESSION['USER']->COMPANYID . ")");         

            //$xdb->doQuery("DELETE FROM sysUser WHERE UserID = ". $xdb->qs($key));
            $xdb->doQuery("UPDATE sysUser SET blnActive = 0 WHERE UserID = ". $xdb->qs($key));
         }
         else
         {
            //print_rr("Not delete " . $xdb->qs($key));
         }
      }
         return "Records Deleted.";
      }
   }

}
?>
