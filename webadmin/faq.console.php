<?php
   include_once("_framework/_nemo.cls.php");
   include_once("_framework/_nemo.details.translator.cls.php");
   include_once("includes/faq.console.cls.php");


   $page = new Nemo();


//nav
   switch($Action)
   {
		default:
			$page = new FAQConsole();
      	$page->Content = $page->getContent();
   }
   
   $page->ToolBar->Buttons[btnExport]->blnShow = 0;
   $page->Message->Text = $Message;
   $page->Display();


?>