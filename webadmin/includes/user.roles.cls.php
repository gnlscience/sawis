<?php
include_once("_framework/_nemo.list.cls.php");

//ALTER TABLE `sysRoleFunction` drop FOREIGN KEY `sysRoleFunction.strRole`;
//ALTER TABLE `sysRoleFunction` ADD CONSTRAINT `sysRoleFunction.strRole` FOREIGN KEY (`refRoleID`) REFERENCES `sysRole` (`RoleID`) ON UPDATE CASCADE ON DELETE CASCADE;

class SecurityRole extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;
      //Get list of fuctions
      $varFuncList = "";

      $sql = "SELECT vieFunction.FunctionID, vieFunction.strFunction FROM vieFunction";        

      $rstFunc = $xdb->doQuery($sql);
      while($row = $xdb->fetch($rstFunc))
      {

        $varFuncList .= ", '' AS '$row->strFunction'";
      }

      //Generate list with functions
      $this->ListSQL("SELECT sysRole.RoleID, sysRole.strRole AS 'Role', sysRole.txtNotes AS 'Notes'
                     $varFuncList, sysRole.blnActive AS 'Active', sysRole.strLastUser AS 'Last User', sysRole.dtLastEdit AS 'Last Edit'
                     FROM sysRole
                     ORDER BY sysRole.strRole",0,"","Edit");

      //Loop to update $this->Data array
      foreach ($this->Data as $i => $row) {
        //Generate array of role function permissions
        $sql = "SELECT sysRoleFunction.*, vieFunction.strFunction
                FROM sysRoleFunction INNER JOIN vieFunction ON sysRoleFunction.refFunctionID = vieFunction.FunctionID
                WHERE refRoleID = " . $this->Data[$i][RoleID];

        $rstRoleFunc = $xdb->doQuery($sql,0);

        $arrUF = array();
        while($row = $xdb->fetch($rstRoleFunc))
        {
          $arrUF[$row->strFunction]=$row->blnAccess;
        }

        foreach ($arrUF as $key => $row) {
          //$this->Data[$i][$key]=($arrUF[$key] == "1" ? "*" : "");       
          $this->Data[$i][$key]=($arrUF[$key] == "1" ? "<input type='checkbox' checked disabled>" : "<input type='checkbox' disabled>");
          $this->Columns[$key]->html->align="center";

        }
      }

      return $this->renderTable("User Role List");
   }

   public function getRoleEntities($SecurityRoleID)
   {
      global $xdb;

      //print_rr("SecurityRoleID " . $SecurityRoleID);

      $sql = "SELECT
               vieFunction.FunctionID, vieFunction.strFunction, '" . $SecurityRoleID . "' AS refRoleID, '' AS blnAccess
               FROM vieFunction ORDER BY strFunction";        

      $rst = $xdb->doQuery($sql);

      while($row = $xdb->fetch($rst))
      {
         $sql = "SELECT sysRoleFunction.refRoleID, sysRoleFunction.refFunctionID, sysRoleFunction.blnAccess
                 FROM sysRoleFunction WHERE
                  sysRoleFunction.refRoleID = '$SecurityRoleID' AND
                  sysRoleFunction.refFunctionID = '$row->FunctionID' ";


         $rstSecurity = $xdb->doQuery($sql);
         $rowSecurity = $xdb->fetch($rstSecurity);
         $strList .= "
                        <tr>
                           <td nowrap width='5%'>$row->FunctionID</td>
                           <td nowrap>$row->strFunction</td>
                           
                           <td nowrap><input class='controlText' type='hidden' name='refRoleID[$row->FunctionID]' id='refRoleID[$row->FunctionID]' value='$SecurityRoleID'></td>
                           <td nowrap align='center' width='5%'><input type='checkbox' ".($rowSecurity->blnAccess==1?"checked":"")." name='blnAccess[$row->FunctionID]' id='blnAccess[$row->FunctionID]' value='1'></td>
                        </tr>
                     ";
      }
      return "

           <table cellspacing='1' cellpadding='2' border='0' width='100%' class='tblNemoList'>
            <caption>Functions</caption>
               <tbody>
                  <tr>
                     <th nowrap>Function ID</th>
                     <th nowrap>Function Description</th>
                     <th nowrap></th>
                     <th nowrap>Access</th>
                  </tr>
                  $strList
               </tbody>
            </table>
       ";
   }

   public static function Save(&$SecurityRoleID)
   {
      $db = new NemoDatabase("sysRole", $SecurityRoleID, null, 0);
      $db->Fields[strRole] = $_POST['strRole'];
      $db->Fields[txtNotes] = $_POST['txtNotes'];
      $db->Fields[blnActive] = ($_POST['blnActive'] != "" ? "1" : "0");
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;
      $result = $db->save();

      if($SecurityRoleID == 0) $SecurityRoleID = $db->ID[RoleID];

      $FunctionID = $_POST['FunctionID'];
      $strFunctionID = $_POST['strFunction'];
      $refRoleID = $_POST['refRoleID'];
      $blnAccess = $_POST['blnAccess'];

      // /*
      //  * Delete role functions form  sysRoleFunction table for the specified security role and replace with new role functions
      //  */
      $db->doQuery("DELETE FROM sysRoleFunction WHERE refRoleID = ". $SecurityRoleID, 0 );

      if(count($refRoleID) > 0){
        foreach($refRoleID as $key => $value)
        {
            $sysRoleFunctiondb = new NemoDatabase("sysRoleFunction", $SecurityRoleID, null, 0);
            $db->doQuery("INSERT INTO sysRoleFunction ( refRoleID, refFunctionID, blnAccess ) SELECT
                          " . $SecurityRoleID . " AS refRoleID,
                          '" . $key . "' AS refFunctionID,
                          " . ($blnAccess[$key] != "" ? 1 : 0) . " AS blnAccess", 0 );
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
        //Delete from role function table as well as the role table for selected roleid
         $xdb->doQuery("DELETE FROM sysRole WHERE RoleID = ". $xdb->qs($key));

         $xdb->doQuery("DELETE FROM sysRoleFunction WHERE refRoleID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
