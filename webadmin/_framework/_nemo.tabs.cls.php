<?php
include_once("_framework/_nemo.cls.php");
include_once("_framework/_nemo.database2.cls.php");
 
class NemoTabs extends NemoDetails
{ 
   public $Tabs = array();

   private $arrTabDEFAULT;

         //$this->tabs["Details"]->html
         //$this->tabs["Details"]->default = 1/0;
         //$this->tabs["Details"]->ToolbarItems = array('btnSave', 'btnClose');

   public function __construct($RecordLabel, $arrTabs)
   {
      parent::__construct();

      $this->ToolBar->Buttons[btnReload]->blnShow = 1;
      $this->ToolBar->Buttons[btnSave]->blnShow = $this->Security->blnSave;
      $this->ToolBar->Buttons[btnClose]->blnShow = 1;
      $this->ToolBar->Label = $this->ToolBar->Label .": <span class=textColour>". $RecordLabel ."</span>";


      $this->arrTabDEFAULT->ToolbarItems = array('btnReload','btnSave', 'btnClose');
      $this->arrTabDEFAULT->jsValidate = ""; //js OnSave validation for this tab
      $this->arrTabDEFAULT->jsValidateFucntion = "jsNemoValidateSave";  
      $this->arrTabDEFAULT->default = 0;

      foreach($arrTabs as $strTab)
      {
         $this->Tabs[$strTab] = nCopy($this->arrTabDEFAULT);
      }
      //print_rr($_POST);
      $this->ACTIVE_TAB = $_POST["ACTIVE_TAB"];
   }

   private function __autoload()
   { 

   }

   // $GROUPING USED FOR WHEN MULTIPLE TABS SECTIONS IS ON ONE PAGE
   public function renderTabs($Grouping="default")
   {
      print_rr($this->disableControlsOnBlur);
      ## VARS
      $TabMenu = "";
      $TabView = "";
      $counter = "0";
      
      ## LOOP THROUGHT TABS
      foreach($this->Tabs AS $TabHeading => $TabDetails)
      {  
         //ini 
          
          
         $ValidateFunction = $TabDetails->jsValidateFucntion;
          
         ## TOOLBAR ITEM
         $JsArray = "[";
         $JsArrayCounter = 0;
         foreach($TabDetails->ToolbarItems AS $ToolbarItem)
         {  
            if($JsArrayCounter == 0)
            {
               $comma = "";
            }
            else
            {
               $comma = ", ";
            }
            $JsArray .= $comma.$ToolbarItem;
            $JsArrayCounter++; 
         }
         $JsArray .= "]"; 

         ## ADD COUNTER
         $counter++;

         if($this->ACTIVE_TAB != ""){
            if($this->ACTIVE_TAB == $TabHeading){
               $TabDetails->default = 1;
               //print_rr($TabDetails->default);
            }else{ 
            }
         }
         else if($this->Tab_Default == $TabHeading)
         {  
            $TabDetails->default = 1;
         }

         ## SET DEFAULT TAB
         if($TabDetails->default == 1)
         {

            $ActiveTab = "ActiveTab";
            $TabDisplay = "";
            $ActiveClass = "ActiveClass";
            $this->ACTIVE_TAB = $TabHeading;
         }else{
            $ActiveTab = "InactiveTab";
            $TabDisplay = "display:none;";
            $ActiveClass = " ";
           
         }

         ## TAB AND CONTECT HTML
         $TabMenu .= "<div id='".$Grouping."_btn_$counter' onclick='jsSwitchView(\"$TabHeading\", $JsArray, \"$Grouping\", this, \"".$Grouping."_Data_$counter\", \"$ValidateFunction\");' class='dora-TabsMenuItem ".$Grouping."TabsMenuItem $ActiveTab'>".$TabHeading."</div>";
         $TabView .= "<div id='".$Grouping."_Data_$counter' $checkHidden class='$ActiveClass dora-Views ".$Grouping."Views' style='$TabDisplay'>".$TabDetails->html."</div>";
      }

      $TabMenu .= "<div style='clear:both;'></div>";

      ## COMPLETE HTML
      $View = "<div class='dora-TabsContainer'>
                  <div class='dora-TabsMenu'>$TabMenu</div>
                  <div class='dora-TabsWindow'>$TabView</div>
                  <input type=hidden name='ACTIVE_TAB' id='ACTIVE_TAB' value='$this->ACTIVE_TAB' />
               </div>";

      ## TABS BEHAVIOUR JS
      $TabJS = "  <script>
                     $( document ).ready(function() { 
                        $('.ActiveTab').click();
                     });

                     function jsSwitchView(tabHeading, JsArray, grouping, tab, ViewID, ValidateFunction)
                     {    
                         

                        $('#ACTIVE_TAB').val(tabHeading); 
                        $('.toolbar').fadeOut();
                        window.setTimeout
                        (
                           function (){
                              $('.linkbutton').hide();
                              $('.linkbutton').parent().hide();

                              $.each(JsArray, function(key, value) {
                                 $(value).show(); 
                                 $(value).parent().show(); 

                              });
                              $('.toolbar').fadeIn();
                           }, 
                           500);
                       

                        // CHANGE MENU CLASSES ///////////////////////////////////////////////

                        $('.'+grouping+'TabsMenuItem').removeClass('ActiveTab');
                        $('.'+grouping+'TabsMenuItem').addClass('InactiveTab');
                        $('#'+tab.id).removeClass('InactiveTab');
                        $('#'+tab.id).addClass('ActiveTab'); 

                        // CHANGE VIEW ///////////////////////////////////////////////////////
                        $('.'+grouping+'Views').slideUp('slow'); 
                        $('.'+grouping+'Views').removeClass('ActiveClass'); 
                        $('#'+ViewID).slideDown('slow');
                        $('#'+ViewID).addClass('ActiveClass'); 

                        if($this->disableControlsOnBlur == 1)
                        {
                           $('.dora-Views').each(function() 
                           {
                              GetClasses = $(this).attr('class');
                              getActive = GetClasses.indexOf('ActiveClass')
                              
                              if(getActive > 0)
                              {
                                 $('#'+this.id + ' :input').removeAttr('disabled');
                              }
                              else
                              {
                                 $('#'+this.id + ' :input').attr('disabled', 'disabled');
                              }
                           });

                        }
                        else
                        {

                        }

                        // change btnSave.onclick
                           
                        jQuery('#btnSave').unbind('click');
                        jQuery('#btnSave').click(function()
                        { 
                          return eval(ValidateFunction + '()');
                        }); 

                     }

                  </script>";    /* set btnSave.onclick = $this->Tabs["Other Details"]->jsValidate */

      ## RETURN
      return $View . $TabJS;
   }




}

?>
