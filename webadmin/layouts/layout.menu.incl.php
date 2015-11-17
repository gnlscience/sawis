<?php

include_once("header.incl.php");

echo "
<body>
   <form name='frmNemo' enctype='multipart/form-data' action='". $this->SystemSettings[FULL_PATH] ."' method='post'>
   <div id=divPage>";
      echo $this->Header();
      echo $this->Logout();
      echo $this->Menu();
      echo $this->Message();
      //echo $this->ToolBar();
      echo $this->Sandbox();
      echo $this->Footer();
      echo"
   </div>
   </form>
 </body>
</html>
";
 
?>
