<?php

//20150317 - added datalist control type- pj/jj

class NemoControl
{
   var $html;

   public static function createControl($type="text", $name, $id=null, $value, $attr=null)
   {
      if(isset($attr)){
         $attr->type = $type;
         $attr->name = "";
         $attr->id = "";
         $attr->value = "";
         $htmlControl = $attr;
      }

      $htmlControl->type = $type;
      $htmlControl->name = $name;
      if($id == null) {$id = $name;}
      $htmlControl->id = $id;
      $htmlControl->value = $value;

      switch($type)
      {
         case "label":
            break;
         case "date":
            break;
         case "select":
         case "ddl":
            break;
         case "textarea":
            break;
         default: //handles text/password/rad/chk/btn/submit

            return self::renderInputControl($htmlControl);
            break;
      }
   }

   public static function renderInputControl($htmlControl = null)
   {
      if(!isset($htmlControl)){
         $htmlControl = $this->html;
      }

      foreach($htmlControl as $attr => $value)
      {
         //echo $control;
         $control .= self::renderAttribute($attr, $value);
      }


      return "<input $control >$htmlControl->innerHTML</input>";
   }

   public static function renderControl($tag = "input", $htmlControl = null)
   {
      //print_rr($htmlControl);
      $control = self::renderAttributes($htmlControl); 
      if($htmlControl->comment != ""){
         $comment = "<span class='textComment'>$htmlControl->comment</span>";         
      }
      if($htmlControl->datalist != ""){ //20150317 - added datalist control type- pj/jj
         $datalist = $htmlControl->datalist;         
      }
      if($tag == "label") $htmlControl->innerHTML = $htmlControl->value;
      if($tag == "a") $htmlControl->innerHTML = $htmlControl->value;

      return "<$tag $control >$htmlControl->innerHTML</$tag>$datalist$comment";
   }

   public static function renderControlToolbar($tag = "input", $htmlControl = null, $class)
   {
      $control = self::renderAttributes($htmlControl);

      if($htmlControl->comment != "") $comment = "<span class='textComment'>$htmlControl->comment</span>";
      if($tag == "label") $htmlControl->innerHTML = $htmlControl->value;
      if($tag == "a") $htmlControl->innerHTML = $htmlControl->value;

      return "<$tag $control  >$htmlControl->innerHTML</$tag>$comment";
   }

   public static function renderAttribute($attr, $value)
   {
      return " $attr = \"". qs($value) ."\"";
   }

   public static function renderAttributes($html)
   {
      $htmlAttr = "";
      if(count($html) > 0){
      foreach($html as $attr => $value)
      {
         switch($attr)
         {
            case "innerHTML":
               break;
            default:
               $htmlAttr .= self::renderAttribute($attr, $value);
         }
      }}
      return $htmlAttr;
   }
}




?>
