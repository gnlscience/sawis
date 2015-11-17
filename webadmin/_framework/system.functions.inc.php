<?
   //2015-06-18 Add function checkChangePassword() used in user.php for my profile save- christiaan
   //20150708 - popi changes - pj
   include_once("_nemo.database2.cls.php");
   /**
    *
    */

   //register_globals
   if (!empty($_POST)) {
      localize($_POST);
      $_GET = null; //HACK! don't know why but the get vars are not unsetting themselves
   }
   if (!empty($_GET)) {
      localize($_GET);
   }
   if (!empty($_COOKIE)){
      localize($_COOKIE);
   }
   if (!empty($_SERVER))
   {
      $server_vars = array('PHP_SELF');
      foreach ($server_vars as $current) {
         if (isset($_SERVER[$current])) {
            $$current = $_SERVER[$current];
         } elseif (!isset($$current)) {
            $$current = '';
         }
      }
      unset($server_vars, $current);
   }
   $SP = "&nbsp;";
   $HR = "<TR><TD COLSPAN='100%'><HR></TD></TR>";
   $TR = "<TR><TD COLSPAN='100%'>&nbsp;</TD></TR>";
   $BR = "<br />";
	$DT = date("Y-m-d H:i");

   $ServerValue = explode("/" , $_SERVER['HTTP_REFERER']);
   for($i = 0; $i < count($ServerValue) - 1;$i++)
   {
      $SERVERPATH .= $ServerValue[$i]."/";
   }
   $SERVERSCRIPT = $ServerValue[count($ServerValue)];



   //echo "<BR>$SERVERPATH<BR>";
   //phpinfo();

   function gpc_extract($array, &$target, $overrideDebug=0)
	{
		if($overrideDebug == 1)
			print_rr($array);

      $is_magic_quotes = get_magic_quotes_gpc();
      foreach ($array AS $key => $value)
		{
         if($is_magic_quotes){
            $target[$key] = @stripslashes($value);
         } else {
            $target[$key] = $value;
         }
			if($overrideDebug == 1)
				echo "\n\r<BR \>$key => $value";

      }
      return TRUE;
   }
	function localize($obj, $overrideDebug=0)
	{
		gpc_extract($obj, $GLOBALS, $overrideDebug);
	}

   //UTILITY FUNCTIONS
   function print_rr($value, $blnString=false)
   {
      echo "<PRE>";
      print_r($value,$blnString);
      echo "</PRE>";
   }

   function changeSort($sort){
      $arrSwop = array('ASC' => 'DESC','DESC' => 'ASC');
      return $arrSwop[$sort];
   }

   function swapIt($c){
      $arrSwop = array("#E1E0DF" => "#FEFEFE","#FEFEFE" => "#E1E0DF");
      return $arrSwop[$sort];
   }

   function roundUp($dblValue, $div, $mean)
   {
      $dblValue =  round($dblValue / $div / $mean, 2);
      $dblValue =  round($dblValue, 1);
	   $dblValue =  round($dblValue * $mean, 1);
      return $dblValue;
   }

   function windowLocation($strPage, $target="")
   {
      echo "<script>window.". $target ."location.href=\"$strPage\";</script>";
   }
   
   function alert($strMessage)
   {
      return "<script>alert(\"$strMessage\");</script>";
   }

   function js($strCommand)
   {
      return "<script>$strCommand</script>";
   }

	function Basic_show_page_nums($recName, $rec, $total_rec, $dest_page, $filter, $sorting=""){
      global $SystemSettings;

      $TotalPages = ceil($total_rec / $SystemSettings["Rows per Page"]);
//echo " $TotalPages ". $SystemSettings["Rows per Page"];
      $pageLinks = 10;
		$curpage = ceil($rec / $SystemSettings["Rows per Page"]) + 1;
      $ret = "<div align='left' style='line-height:20px;'><strong>total pages [$TotalPages] :: total records [$total_rec]</strong><br>";

      if($curpage - round($pageLinks/2) <= 0)
         $pageStart = 1;

      if($curpage - round($pageLinks/2) > 0)
         $pageStart = $curpage - round($pageLinks/2);

		if($pageStart + $pageLinks > $TotalPages){
			$pageEnd = $TotalPages;
      }else{
			$pageEnd = $pageStart + $pageLinks;
      }
		for($i=$pageStart;$i<=$pageEnd;$i++){
         if($curpage == $i){
				$ret .= "&nbsp;<b>" . $i . "</b>&nbsp;";
         }else{
				$ret .= "&nbsp;<a href=\"" . $dest_page . "?$recName=" . (($i-1) * $SystemSettings["Rows per Page"]) . $filter . $sorting ."\">" . $i . "</a>&nbsp;";
         }
      }
      $ret .= "</div>";
      return $ret;
   }

//DATE FUNCTIONS
	function FormatDate($strDate="", $format="Ymd", $blnNA=0)
	{
		if($strDate!=""){//*optimal input: YYYYmmdd
         //echo "$strDate = '', $format = Ymd ". strtotime(substr($strDate, 0,8));
			return date($format, strtotime(substr($strDate, 0,8)));
      }else{
         if($blnNA)
            return "n/a";
         else
            return date($format);
      }
	}

   function displayDTStamp($strStamp){
      return date("j M Y", strtotime(substr($strStamp,0,8))) ." &nbsp;" . substr($strStamp, 8,2) . ":" . substr($strStamp, 10,2) ;//??. ":" . substr($strStamp, 12,2);
   }

   function displayDT($strStamp){
      return date("j M Y", strtotime(substr($strStamp,0,8))) ." &nbsp;" . substr($strStamp, 8,2);//??. ":" . substr($strStamp, 12,2);
   }

	function DisplayDate($strDate=""){
		if($strDate!=""){//input: YYYYmmdd
         return date("d-m-Y", strtotime($strDate));
      }else{
			return date("d-m-Y");
      }
   }
	function DisplayReportDate($strDate=""){
	  if($strDate!=""){//input: YYYYmmdd
         return date("j M Y", strtotime($strDate));
      }else{
		   return "-";
      }
   }

   function MakeDBDate($strDate=""){
	  if($strDate!=""){//input: dd-mm-YYYY
         return substr($strDate, 6,4) . substr($strDate, 3,2) . substr($strDate, 0,2);
      }else{
	    return date("Ymd");
      }
   }

   function MakeDTStamp(){
      return date("YmdHis");
   }

	function showLastEdit($lastUser = "", $lastEdit = ""){
      if(($lastUser =="")&&($lastEdit=="")){
         return "<i>New Record</i>";
      }else{
         return "Record Last Edited By: <b>$lastUser</b> on: " . displayDTStamp($lastEdit);
      }
   }

   function getTime($intTicks)//??
   {
      $d = $h = $m = $s = 0;
		$s = $intTicks;
		$totalTime += $s;
		while($s > 60)
		{
			$m++;
			$s += -60;
		}
		while($m > 60){
			$h++;
			$m += -60;
		}

		if($s < 10) $s = "0$s";
		if($m < 10) $m = "0$m";
		if($h < 10) $h = "0$h";

      return "$h:$m:$s";
   }

   function ArrayGetKey($array, $match)
   {//new 20090812 - ffs - couldn't find a stupid function to search a array and return -1 for value not found

      foreach($array as $key => $value)
      {
         //echo "<BR>$key:$value ?= $match";
         if($value == $match){
            return $key;
         }
      }

      return -1;
   }

   function PointerGetKey($pointer, $field, $match)
   {//new 20091105 - same as above cept it handles pointerarrays
      //echo "$field, $match";
      //print_rr($pointer);die;
      foreach($pointer as $key => $object)
      {
         if($object->$field == $match)
            return $key;
      }

      return -1;
   }

   function StripString($string, $lastword, $findstring = " ", $replacestring = "...")
   {
      if(strlen($string) > $lastword)
      {
         $idxEnd = strpos($string, $findstring, $lastword);
         if($idxEnd < $lastword)
            return $string;
         else
            return substr($string, 0, $idxEnd) . $replacestring;
      }
      else
      {
         return $string;
      }
   }

   function getJSRemoveAlpha()
   {
      global $SystemSettings;
      return "
         function removeAlpha(obj)
         {
            var arrChars = '".$SystemSettings[NumericChars]."';//pulls data from the sysSettings Table
            var objValue = obj.value;
            var newValue = '';

            for (var i = 0; i < objValue.length; i++)
            {
               if(arrChars.indexOf(objValue.charAt(i)) > -1)
               {
                  newValue += objValue.charAt(i);
               }
            }
            if(newValue == '')
               newValue = '0';

            obj.value = newValue;
            return obj;
         }";
   }

   function mynl2br($text, $blnPreserveCRLF=0)
   {//added 20091126 - pj
      if($blnPreserveCRLF == 1)
         return strtr($text, array("\r\n" => "<br />\r\n", "\r" => "<br />\r", "\n" => "<br />\n"));
      else
         return strtr($text, array("\r\n" => "<br />", "\r" => "<br />", "\n" => "<br />"));
   }

   function mybr2ln($text, $blnPreserveCRLF=0)
   {//added 20091126 - pj
      if($blnPreserveCRLF == 1)
         return strtr($text, array_flip(array("\r\n" => "<br />\r\n", "\r" => "<br />\r", "\n" => "<br />\n")));
      else
         return strtr($text, array_flip(array("\r\n" => "<br />", "\r" => "<br />", "\n" => "<br />")));
   }

   function pointer_sum($pointer, $field)
   {
      $$field = 0;
      foreach($pointer as $key1 => $value1)
      {
         //print_rr($value1);
         if(is_array($value1))
         {
            foreach($value1 as $key2 => $value2)
            {
               //print_rr($value2);
               if(is_array($value2))
               {
                  foreach($value2 as $key3 => $value3)
                  {
                     $$field += $value3->{$field};
                  }
               }else{
                  $$field += $value2->{$field};
               }
            }
         }else{
            $$field += $value1->{$field};
         }
      }

      return $$field;
   }

	function roundTo($number, $to)
	{
		return round($number/$to, 0)* $to;
	}

  //moved to toolbar.cls.php 11/05/2010 -s

   function GLC($t, $pf = "", $l = 6, $bf = 11, $bt = 21)
   {
      $x = strtoupper(substr(base_convert(strrev($t),$bf,$bt),0,$l));
      $y = array("I"=>"N", "J"=>"P");
      $x  = strtr($x , $y);
      return $pf.$x;
   }

   function P($PID)
   {
      global $xdb;
      $row = $xdb->getRowSQL("SELECT * FROM tblProduct WHERE ProductID = $PID");
      return $row->strShortCode;
   }

   function newIP()
   {
      sleep(1);
      return (strtotime("now"));
   }

   function Login($row)
   {//new pj - 20100310
      global $PracticeID;

      $_SESSION['USERNAME'] = $row->strName;
      $_SESSION['PASSWORD'] = $row->strPassword;
      $_SESSION['EMAIL'] = $row->strEmail;
      $_SESSION['USERID'] = $row->UserID;
      $PracticeID = $_SESSION['PRACTICEID'] = $row->refPracticeID;
      $_SESSION['PRACTICENAME'] = $row->strRegisteredName;
      $intCookieTime = 3600;
      setcookie("celusername", $_SESSION['EMAIL'],time() + $intCookieTime);
      setcookie("celpassword", $_SESSION['PASSWORD'],time() + $intCookieTime);

      return true;
   }

   function jsFrameResize()
   {
      return
      "
   <script language='javascript 'type='text/javascript'>
      function resizeFrame() {
	//setIframeHeight();
	//return;
         try {
         var f = document.getElementById('idContentFrame');
         var h = 0;
	 if (f.contentDocument) {
		h = f.contentDocument.getElementById('content').scrollHeight;
		//alert(document.getElementById('idContentFrame').contentDocument.getElementById('content').scrollHeight);
	 }
	else if (f.contentWindow) {
		h = f.contentWindow.document.getElementById('content').scrollHeight;
	}
	if (h !=0) {
		f.style.height = (h+30) + 'px';
	}
         //var intHeight = parseFloat(f.contentWindow.document.body.scrollHeight + 30);
         //alert(intHeight);
         //f.style.height = intHeight +'px';
	//changeHeight(f);
         }
         catch (e) {
            alert(e);
         }

      }


      function changeHeight(iframe)
      {
        try
        {
          var innerDoc = (iframe.contentDocument) ? iframe.contentDocument : iframe.contentWindow.document;
          if (innerDoc.body.offsetHeight) //ns6 syntax
          {
             iframe.height = innerDoc.body.offsetHeight + 32; //Extra height FireFox
          }
          else if (iframe.Document && iframe.Document.body.scrollHeight) //ie5+ syntax
          {
             iframe.height = iframe.Document.body.scrollHeight;
          }
	  iframe.style.height = iframe.height + 'px';
        }
        catch(err)
        {
          alert(err.message);
        }
      }

      function setIframeHeight() {
      var iframeName;
      iframeName = 'idContentFrame';
        //var iframeWin = window.frames[iframeName];
        var iframeEl = document.getElementById? document.getElementById(iframeName): document.all? document.all[iframeName]: null;
        if (iframeEl) {
        //iframeEl.style.height = 'auto'; // helps resize (for some) if new doc shorter than previous
        //var docHt = getDocHeight(iframeWin.document);
        // need to add to height to be sure it will all show
        var h = alertSize();
        var new_h = (h);
        iframeEl.style.height = new_h + 'px';
        //alertSize();
        }
      }

      function alertSize() {
		  var myHeight = 0;
		  if( typeof( window.innerWidth ) == 'number' ) {
		    //Non-IE
		    myHeight = window.innerHeight;
		  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		    //IE 6+ in 'standards compliant mode'
		    myHeight = document.documentElement.clientHeight;
		  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		    //IE 4 compatible
		    myHeight = document.body.clientHeight;
		  }
		  //window.alert( 'Height = ' + myHeight );
		  return myHeight;
		}

   </script>";

   }

   function jsSelectDeselect()
   {
      return
      "function Toggle()
      {
         count = document.frm.elements.length;
         // Get First Item
         var First = document.frm.elements[0].checked;
         for (i=0; i < count; i++)
         {
            if(First)
               document.frm.elements[i].checked = 0;
            else
               document.frm.elements[i].checked = 1;
         }
      }";
   }

   function jsTrim()
   {
      return
      "function trim(str)
      {
         return str.replace(/^\s*|\s*$/g,'');
      }";
   }

   function jsRowColourChange()
   {
      return "
      function ChameleonIn(rec)
      {
         var rec1 = rec+'_1';
         var rec2 = rec+'_2';
         var rec3 = rec+'_3';
         var rec4 = rec+'_4';
         var rec5 = rec+'_5';
         var rec6 = rec+'_6';
         var rec7 = rec+'_7';
         var Colour = '#D0E8E8';

         document.getElementById(rec1).style.backgroundColor=Colour;
         document.getElementById(rec2).style.backgroundColor=Colour;
         document.getElementById(rec3).style.backgroundColor=Colour;
         document.getElementById(rec4).style.backgroundColor=Colour;
         document.getElementById(rec5).style.backgroundColor=Colour;
         document.getElementById(rec6).style.backgroundColor=Colour;
         document.getElementById(rec7).style.backgroundColor=Colour;
         //this.style.backgroundColor='red';
      }

      function ChameleonOut(rec)
      {
         var rec1 = rec+'_1';
         var rec2 = rec+'_2';
         var rec3 = rec+'_3';
         var rec4 = rec+'_4';
         var rec5 = rec+'_5';
         var rec6 = rec+'_6';
         var rec7 = rec+'_7';

         if(rec % 2 == 0){
          var Color = '';
         }else{
          var Color = '';
         }

         document.getElementById(rec1).style.backgroundColor='white';
         document.getElementById(rec2).style.backgroundColor='white';
         document.getElementById(rec3).style.backgroundColor='white';
         document.getElementById(rec4).style.backgroundColor='white';
         document.getElementById(rec5).style.backgroundColor='white';
         document.getElementById(rec6).style.backgroundColor='white';
         document.getElementById(rec7).style.backgroundColor='white';
           // document.getElementById(rec).class.value='row1';
      }";
   }

   //Added by Stephen on the 20100604
   function getPreLoader($left = "400px" , $top = "355px" , $gifName = "loading.gif" , $position = "absolute")
   {
      return "<div id='divPreLoader' name='divPreLoader' style='display:none;position:$position;left:$left;top:$top'><img src='images/layers/$gifName' name='loadingGif' id='loadingGif'></div>";
   }

   function jsPreLoaderDisplay($type = "block")
   {
      return "<script>d('divPreLoader').style.display = '$type';</script>";
   }

   function isValidEmail($email){
      //return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);

      //UPDATED CODE FOR VALIDATE EMAIL - JACQUES 20131121
      return filter_var($email, FILTER_VALIDATE_EMAIL);
   }

   function qs($value){

      $translateTable = array("\""=>"'",
                              "�"=>"'",
                              "`"=>"'",
                              "’"=>"'",
                              '�'=>'"',
                              '�'=>'"',
                              "�"=>"...",
                              "–"=>"-",
                              "�"=>"-");//"funny chars" => "new chars". just add into the array
//echo "<BR>". vd($value);
      $value = strtr($value, $translateTable);//return $value; // strtr: foreach char in $value, if char == A then set char = B
      $value = trim($value);
      if (get_magic_quotes_gpc())
         $value = stripslashes($value);

      $value = str_replace('"',"'",$value);

      return $value;
   }

   function nCopy($obj)
   {
      return unserialize(serialize($obj));
   }

   /*
    * 20110125 - converts excel date to timestamp - pj
   */
   function exceltotime($intDays)
   {
      $intDaysOffsetFromFuckingMSdate = 36526; //ori 36524 days between 1 jan 1900 and 1 jan 2000, but ms dates run from 0 jan 1900... wtf
      $arrDT = explode(".",($intDays - $intDaysOffsetFromFuckingMSdate));//excel date has days.%time

      $intDays = $arrDT[0];
      $dtJan = strtotime("1 Jan 2000");
      $dt = strtotime("+ $intDays Days", $dtJan);

      $strDate = date("Ymd", $dt);

      if($strDate == "19700101")
         return false;
      else
         return $strDate;

   }

   function Obfuscate(){
      return GLC(newIP());
   }

//******************************
//**PROJECT SPECIFIC FU*CTIONS**
//******************************
   /*
    * Login Facillity added by Stephen on the 20101126
    */

   function tblLoginInsert($message)
   {
      global $strUsername , $strPassword;
      $vdb = new NemoDatabase("sysLogin", 0, null, 0);
      $vdb->Fields[strIP] = $_SERVER['REMOTE_ADDR'];
      $vdb->Fields[strResult] = $message;
      $vdb->Fields[strUsername] = $strUsername;
      $vdb->Fields[strPassword] = "******"; //$strPassword; //20150708 - popi changes - pj
      //unset($vdb->FieldList[5]);// = date("YmdHi");
      //print_rr($vdb);
      $vdb->Save();
   }

   function tblSpamInsert($strSpam, $strEmail)
   {
      $sdb = new NemoDatabase("sysSpam", 0, null, 0);
      $sdb->Fields[strSpam] = $strSpam;
      $sdb->Fields[strEmail] = $strEmail;
      $sdb->Fields[strIP] = $_SERVER['REMOTE_ADDR'];
      //unset($vdb->FieldList[5]);// = date("YmdHi");
      //print_rr($vdb);
      $sdb->Save();
   }

   /*
    * SMS Facility Added by Stephen 20 December 2010
    */
   function SendSMS($smsNumber, $smsMessage)
   {
      global $SystemSettings;

      $data= array(
         "Type"=> "sendparam",
         "Username" => $SystemSettings[smsUsername],
         "Password" => $SystemSettings[smsPassword],
         "live" => $SystemSettings[smsLive],
         "numto" => $smsNumber,
         "data1" => $smsMessage); //This contains data that you will send to the server.

      $data = http_build_query($data); //builds the post string ready for posting
      return do_post_request("http://www.mymobileapi.com/api5/http5.aspx", $data);  //Sends the post, and returns the result from the server.
   }

   function do_post_request($url, $data, $optional_headers = null)
   {
      $params = array("http" => array(
               "method" => "POST",
               "content" => $data
            ));
      if ($optional_headers !== null) {
      $params["http"]["header"] = $optional_headers;
      }

      $ctx = stream_context_create($params);
      $fp = @fopen($url, "rb", false, $ctx);

      if (!$fp) {
         echo "Problem with $url: ";
         print_rr(error_get_last());
         die;
         throw new Exception("Problem with $url, ". print_rr(error_get_last()));
      }


      $response = @stream_get_contents($fp);
      if ($response === false) {
         echo "Problem reading data from $url: ";
         print_rr(error_get_last());
         die;
         throw new Exception("Problem reading data from $url, ". error_get_last());
      }
      $response;
      return formatXmlString($response);
   }
   function formatXmlString($xml)
   {
      // add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
      $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);

      // now indent the tags
      $token      = strtok($xml, "\n");
      $result     = ''; // holds formatted version as it is built
      $pad        = 0; // initial indent
      $matches    = array(); // returns from preg_matches()

      // scan each line and adjust indent based on opening/closing tags
      while ($token !== false) :
         // test for the various tag states
         // 1. open and closing tags on same line - no change
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
            $indent=0;
         // 2. closing tag - outdent now
         elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad--;
         // 3. opening tag - don't pad this one, only subsequent tags
         elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent=1;
         // 4. no indentation needed
         else :
            $indent = 0;
         endif;
         // pad the line with the required number of leading spaces
         $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
         $result .= $line . "\n"; // add to the cumulative result, with linefeed
         $token   = strtok("\n"); // get the next token
         $pad    += $indent; // update the pad size for subsequent lines
      endwhile;

      $pos = strpos($result, "False");
      if($pos == "")
      {
         $result = "SMS Sent Successfully.";
      }
      else
      {
         $result = "Error Sending SMS: ". str_replace("False","" , $result);
      }
      return $result;
   }

   function vd($var)
   {
      var_dump($var);
   }

   function CheckSecurityLock($UserID)
   {
      global $BR, $SP, $T, $SystemSettings;
      include_once("_nemo.email.cls.php");

      $xdb = new NemoDatabase("sysUser", $UserID, null, 0);
      //print_rr($xdb->Fields);
      $intFails = 0;
      $intLoginAttempts = 5;

      $rst = $xdb->doQuery("SELECT sysLogin.LoginID, sysLogin.strIP AS IP, sysLogin.strResult AS Result, sysLogin.strUsername AS Username, sysLogin.strPassword AS 'Password', sysLogin.strDateTime AS DT
         FROM sysLogin INNER JOIN sysUser ON sysLogin.strUsername = sysUser.strEmail
         WHERE sysUser.UserID=$UserID AND strResult IN ('Login failed: Incorrect password', 'Login successful')
         ORDER BY sysLogin.LoginID DESC
         LIMIT 0,$intLoginAttempts");
      while($row = $xdb->fetch_object($rst))
      {
         $arrLogin[] = $row;
         if($row->Result == "Login successful"){
            $arrLogin[count($arrLogin)-1]->Password = str_repeat("*", strlen($row->Password));
            break;
         }

         $intFails += 1;
      }
      //print_rr($arrLogin);
      foreach($arrLogin as $i => $row)
      {
         $thLogin = ""; // note clear each time, only use last occ
         $tblLogin .= "<tr bgcolor='white'>";
         foreach($row as $heading => $cell)
         {
            $tblLogin .= "<td>$cell</td>";
            $thLogin .= "<th>$heading</th>";
         }
         $tblLogin .= "</tr>";
      }

      //echo $tblLogin;
      //echo $intFails;
      if($intFails >= $intLoginAttempts)
      {
         $xdb->Fields[blnLogin] = 0;
         $xdb->Fields[strLastUser] = "Security Lockout";
         //$xdb->Save();

         $nemoEmail = new NemoEmail($xdb->Fields[strEmail], $SystemSettings[Title] ." Security Warning", null);

$message = "
Attention ". $xdb->Fields[strUser] ."

Your login account has been suspended due to repeated failed login attempts.

Please contact your site administrator to verify your details and restore your account.

Regards
";

$tblLogin = "
<table cellpadding='2' cellspacing='1' border='0' style='border: 1px solid #343434;' bgcolor='#D5D5D5' >
<caption colspan='100%'><b>Last $intLoginAttempts Login Results</b></caption>
<tr bgcolor='#E6E6E6'>
$thLogin
</tr>
$tblLogin
</table>
";

         $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
         $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
         $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
         $nemoEmail->From = $SystemSettings["SMTP Send As"];
         //$nemoEmail->addStyleSheet("css/colourless.css");
         //$nemoEmail->addStyleSheet("css/nemo.". $SystemSettings[NemoPrimary] .".css");

         $nemoEmail->arrBody[1] = mynl2br($message).$SystemSettings[Brand].$tblLogin;
         $nemoEmail->arrBody[0] = $message.$SystemSettings[Title].$tblLogin;

         $nemoEmail->blnInsert = 0; //inv has no email table

         $nemoEmail->Send();

//print_rr($nemoEmail);
         $T = "error";
         return "$BR Your login account has been suspended. Please contact your site administrator.";
      }elseif($intFails >= 2){
         $T = "error";
         return "$BR You have ". ($intLoginAttempts - $intFails) ." login attempt(s) remaining.";
      }

      return "";
   }

	function my_array_filter($array, $key, $value)
	{
		global $filterarrayobjectkey, $filterarrayobjectvalue;
		$filterarrayobjectkey = $key;
		$filterarrayobjectvalue = $value;

		return array_filter($array, "_array_filter");
	}

	function _array_filter($value)
	{//"$value" in this case is a data row from a rst
		global $filterarrayobjectkey, $filterarrayobjectvalue;

		return($value->$filterarrayobjectkey == $filterarrayobjectvalue);

		//eg if($row->refCourseID == 1) then return true else return false
	}

   function pdfEncrypt ($origFile, $password, $destFile)
   {//new 20120322 - encryping pdfs
      //include the FPDI protection http://www.setasign.de/products/pdf-php-solutions/fpdi-protection-128/
      require_once("fpdi/FPDI_Protection.php");

      if(file_exists($destFile)) unlink($destFile);
      $pdf = new FPDI_Protection();
      // set the format of the destinaton file, in our case 6×9 inch
      $pdf->FPDF("P", "in", array("8.27","11.69"));

      //calculate the number of pages from the original document
      $pagecount = $pdf->setSourceFile($origFile);

      // copy all pages from the old unprotected pdf in the new one
      for ($loop = 1; $loop <= $pagecount; $loop++) {
         $tplidx = $pdf->importPage($loop);
         $pdf->addPage();
         $pdf->useTemplate($tplidx);
      }

      // protect the new pdf file, and allow no printing, copy etc and leave only reading allowed
      $pdf->SetProtection(array('print','copy'), $password);
      $pdf->Output($destFile, "F");

      return $destFile;
   }

   function translateToolbar(&$nemo, $Lang)
   {
      global $_TRANSLATION;

      foreach($nemo->ToolBar->Buttons as $ID => $button)
      {
         if($_TRANSLATION[$Lang][$ID] != ""){ 
            $nemo->ToolBar->Buttons[$ID]->Control->value = $_TRANSLATION[$Lang][$ID]; //vd($nemo->ToolBar->Buttons[$ID]->Control->value);
         }
      }
   }

   function checkChangePassword($strPasswordDB, $strOldPassword, $strNewPassword, $strConfirmPassword)
   {
      if($strNewPassword == $strConfirmPassword && md5($strOldPassword) == $strPasswordDB)
      {
         return true;
      }
      return false;
   }
   
   function parseXmlRow($row, $blnURLEncode=1)
   {
      if($row)
      {
         $xml .= "<row>";
         foreach($row AS $key => $value)
         {
            if($blnURLEncode)
               $xml .= "<$key>".urlencode($value)."</$key>";
            else
               $xml .= "<$key>$value</$key>";
         }
         $xml .= "</row>";
         return $xml;
      }
   }

   function parseXmlColumn($row, $blnURLEncode=1)
   {
      if($row)
      {
         $xml .= "<column>";
         foreach($row AS $key => $value)
         {
            if($blnURLEncode)
               $xml .= "<$key>".urlencode($value)."</$key>";
            else
               $xml .= "<$key>$value</$key>";
         }
         $xml .= "</column>";
         return $xml;
      }
   }
?>