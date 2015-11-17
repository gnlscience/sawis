<?php

   include_once("_framework/_nemo.cls.php");
   

//echo session_name();
   $page = new Nemo();
   echo $_SESSION[S2REGISTRATION]->FarmID;

//events

//page start
   //vd($_SESSION[USER]->LANGUAGE);
   //$_SESSION[USER]->SECURITYGROUPID = 11;

   $page->Content = "
   	<div style='padding-top: 10px'>
   		". $SystemSettings["VISION_". $_SESSION[USER]->LANGUAGE] ."
   	</div>
   	<div style='padding-top: 10px'>
   		". $SystemSettings["MISSION_". $_SESSION[USER]->LANGUAGE] ."
   	</div>
   	<div style='padding-top: 10px'>
   		". $SystemSettings["PRINCIPLES_". $_SESSION[USER]->LANGUAGE] ."
   	</div>
   	<div style='padding-top: 10px'>
   		". $SystemSettings["STRATEGY_". $_SESSION[USER]->LANGUAGE] ."
   	</div>";
   $page->Display();


//print_rr($page);

?>