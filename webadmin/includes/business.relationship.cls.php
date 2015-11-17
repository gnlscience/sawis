<?php
include_once("_framework/_nemo.list.cls.php");
include_once("includes/member.cls.php");  // << gets the Farm::sqlInspectorDDL()


//Additional / page specific translations

class BusinessRelationship extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {

      $this->Filters[frMember]->tag = "select";
      $this->Filters[frMember]->html->value = "-1";
      $this->Filters[frMember]->html->class = "controlText";
      $this->Filters[frMember]->sql = Member::sqlMemberDDL();

      $this->Filters[frMemberType]->tag = "select";
      $this->Filters[frMemberType]->html->value = "-1";
      $this->Filters[frMemberType]->html->class = "controlText";
      $this->Filters[frMemberType]->sql = Member::sqlMemberTypeDDL();

      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";


      parent::__construct($DataKey);
   }

   public function getList($MemberID=null, $FarmID=null)
   {
     
      global $xdb;
      //Build where clauses  
      $sqlMember = ", vieMemberRelationship.MemberID, vieMemberRelationship.strMember AS Member";  
      $sqlEntity = ", vieMemberRelationship.EntityID, vieMemberRelationship.strEntity AS Entity";
      $return_url = null;

      if($MemberID != 0)
      {
         $sqlMember = "";
         $Where = " AND vieMemberRelationship.MemberID = '$MemberID'";

         $return_url = "Edit&MemberID=$MemberID&RETURN_URL=".urlencode("member.php?Action=Edit")."&RETURN_VAR=MemberID";
                       
      }elseif($FarmID != 0){

         $sqlEntity = "";
         $Where = " AND vieMemberRelationship.EntityID = '$FarmID' AND strType = 'Farm to Member'";

         $return_url = "Edit&RETURN_URL=". urlencode("farm.php?Action=Edit&FarmID=$FarmID");
      }else{
         //TODO
      
      }

      $this->ListSQL("
         SELECT vieMemberRelationship.ID $sqlMember, vieMemberRelationship.strType AS Type, vieMemberRelationship.strMemberTypeCode AS 'Member Type Code', vieMemberRelationship.strMemberType AS MemberType $sqlEntity, vieMemberRelationship.strApprovedBy AS 'Approved By', vieMemberRelationship.dtApproved AS 'Date'
         FROM vieMemberRelationship
         WHERE 1=1 $Where
         ORDER BY strMember, strMemberTypeCode, strEntity",0,"business.relationship.php",$return_url);



      
      return $this->renderTable("Business Relationships <b class='textGreen'>APPROVED</b>");
   }

   public function getListTMP($MemberID=null, $FarmID=null)
   {
      /*
      NOTE: 
         vieMemberRelationshipTMPx3 = tMember - tMRel - tFarm
         vieMemberRelationshipTMPx2 = Member - tMRel - tFarm
      */
      global $xdb;
      //Build where clauses  
      $sqlMember = ", MemberID, strMember AS Member";  
      $sqlEntity = ", EntityID, strEntity AS Entity";
      $return_url = null;
      $path = "business.relationship.php";

      if($MemberID != 0)
      {
         $sqlMember = "";
         $Where = " AND MemberID = '$MemberID'";

         $return_url = "Edit&MemberID=$MemberID&RETURN_URL=".urlencode("member.pending.php?Action=Edit")."&RETURN_VAR=MemberID";
         $OrderE .= ", Entity";
                       
      }elseif($FarmID != 0){

         $sqlEntity = "";
         $Where = " AND EntityID = '$FarmID' AND strType = 'Farm to Member'";
         $return_url = "Edit&RETURN_URL=". urlencode("farm.pending.php?Action=Edit&FarmID=$FarmID");
         $OrderM .= " Member,";
      }else{
         //TODO
      
      }

      $this->ListSQL("
         SELECT vieMemberRelationshipTMPx3.ID $sqlMember, vieMemberRelationshipTMPx3.strType AS Type, vieMemberRelationshipTMPx3.strMemberTypeCode AS 'Member Type Code', vieMemberRelationshipTMPx3.strMemberType AS MemberType $sqlEntity, vieMemberRelationshipTMPx3.strApprovedBy AS 'Approved By', vieMemberRelationshipTMPx3.dtApproved AS 'Date'
         FROM vieMemberRelationshipTMPx3
         WHERE 1=1 $Where
         UNION ALL
         SELECT vieMemberRelationshipTMPx2.ID $sqlMember, vieMemberRelationshipTMPx2.strType AS Type, vieMemberRelationshipTMPx2.strMemberTypeCode AS 'Member Type Code', vieMemberRelationshipTMPx2.strMemberType AS MemberType $sqlEntity, vieMemberRelationshipTMPx2.strApprovedBy AS 'Approved By', vieMemberRelationshipTMPx2.dtApproved AS 'Date'
         FROM vieMemberRelationshipTMPx2
         WHERE 1=1 $Where
         ORDER BY $OrderM `Member Type Code` $OrderE",0,$path,$return_url);

      
      return $this->renderTable("Business Relationships <b class='textRed'>PENDING</b>");
   }

//REVIEW
   public static function Create()
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblMemberRelationship", $MemberID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Request create. ";
      }
   }

//REVIEW
   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         $xdb->doQuery("DELETE FROM tblMemberRelationship WHERE ID = ". $xdb->qs($key));         
      }
         return "Records Deleted. ";
      }
   }

   public function getFarmRelationship($FarmID,$blnTMP=0)
   {
      global $xdb;
      
      $this->isSelectable = 0;

      //Build where clauses  
      $table = "tblMember";
      $tablehead = "Farm Business Relationship List";
      if($blnTMP){
         $table = "tmpMember";
         $tablehead = "Pending Farm Business Relationship List";
      }

      $this->ListSQL("SELECT MemberID, MemberID AS 'Member ID', strMember, strStatus, refEntityID
         FROM $table INNER JOIN ".$table."Relationship ON ".$table.".MemberID = ".$table."Relationship.refMemberID
         WHERE (((".$table."Relationship.refEntityID)=$FarmID));
      ",1,"member.pending.php"); //w. (tmpMember.strStatus LIKE '1.%')

      return $this->renderTable($tablehead);
   }

   public function getMemberRelationship($MemberID, $blnPending=0){}
}
?>
