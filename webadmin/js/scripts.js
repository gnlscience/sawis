
function trim(str){
   return str.replace(/^\s*|\s*$/g,"");
}

function ajaxRequest()
{

   var xmlHttp=null;
   try
   {
      // Firefox, Opera 8.0+, Safari
      xmlHttp=new XMLHttpRequest();
   }
   catch (e)
   {
      //Internet Explorer
      try
      {
         xmlHttp=new ActiveXObject('Msxml2.XMLHTTP');
      }
      catch (e)
      {
         xmlHttp=new ActiveXObject('Microsoft.XMLHTTP');
      }
   }
   return xmlHttp;
}

function urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}

//Clears all value fields for controls on a form (text, textarea, select)//Sebastian 19.11.09
//modified by william on 11/25/2009
function clearElements(frm)
{
   try
   {
      formElements = frm.elements;
      for(i=0; i<formElements.length; i++)
      {
         field_type = formElements[i].type.toLowerCase();

         switch(field_type)
         {
            case 'text':
            case 'textarea':
               formElements[i].value = '';
              // formElements[i].disabled = false;
            break;
            case 'select-one':
               formElements[i].selectedIndex = 0;
            break;

            default:
            break;
         }
      }
   }
   catch(err)
   {

   }
}

//Selects Item in Dropdown
function selectDropDown(ddl,value)
{
   for(var i=0; i < ddl.options.length; i++ )
   {
      if(ddl.options[i].value == value)
      {
         ddl.selectedIndex=i;
         return true;
      }
   }
   ddl.selectedIndex=-1;
}

function formatNumber(obj, dec, blnCurrency)
{
   var num='';
   if(obj.type=='text')
           {
              num = obj.value;
           }
           else
           {
              num = obj;
           }

           //num = removeAlpha(num.toString().replace(/,/gi,''));

           if(isNaN(num))
           {
              num = '0';
           }
           if(dec > -1)
           {
              bse = 10;
              sign = (num == (num = Math.abs(num)));
              if(dec > 1)
              {
                 bse = Math.pow(bse,dec);
              }
              num = Math.floor(num*bse+0.50000000001);
              cents = String(num%bse);
              num = Math.floor(num/bse).toString();
              if(cents.length < dec){
                x = dec - cents.length;
                for(i=0;i<x;i++){
                  cents = '0' + cents;
                }
              }
              if(blnCurrency == 1)
              {
                 for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
                 {
                    num = num.substring(0,num.length-(4*i+3))+','+ num.substring(num.length-(4*i+3));
                 }
              }
              if(dec == 0)
              {
                 if(obj.type=='text')
                 {
                    obj.value = (((sign)?'':'-') + num);
                 }
                 else
                 {
                    return (((sign)?'':'-') + num);
                 }
              }
              else
              {
                 if(obj.type=='text')
                 {
                    obj.value = (((sign)?'':'-') + num + '.' + cents);
                 }
                 else
                 {
                    return (((sign)?'':'-') + num + '.' + cents);
                 }
              }
           }
           else
           {
              obj.value = num;
           }
}

function grayOut(vis, options)
{
   // Pass true to gray out screen, false to ungray
   // options are optional. This is a JSON object with the following (optional) properties
   // opacity:0-100 // Lower number = less grayout higher = more of a blackout
   // zindex: # // HTML elements with a higher zindex appear on top of the gray out
   // bgcolor: (#xxxxxx) // Standard RGB Hex color code
   // grayOut(true, {'zindex':'50', 'bgcolor':'#0000FF', 'opacity':'70'});
   // Because options is JSON opacity/zindex/bgcolor are all optional and can appear
   // in any order. Pass only the properties you need to set.
   var options = options || {};
   var zindex = options.zindex || 10;
   var opacity = options.opacity || 70;
   var opaque = (opacity / 100);
   var bgcolor = options.bgcolor || '#000000';
   var dark=document.getElementById('darkenScreenObject');
   if (!dark)
   {
      // The dark layer doesn't exist, it's never been created. So we'll
      // create it here and apply some basic styles.
      // If you are getting errors in IE see: http://support.microsoft.com/default.aspx/kb/927917
      var tbody = document.getElementsByTagName("body")[0];
      var tnode = document.createElement('div'); // Create the layer.
      tnode.style.position='absolute'; // Position absolutely
      tnode.style.top='0px'; // In the top
      tnode.style.left='0px'; // Left corner of the page
      tnode.style.overflow='hidden'; // Try to avoid making scroll bars
      tnode.style.display='none'; // Start out Hidden
      tnode.id='darkenScreenObject'; // Name it so we can find it later
      tbody.appendChild(tnode); // Add it to the web page
      dark=document.getElementById('darkenScreenObject'); // Get the object.
   }
   if (vis) {
   // Calculate the page width and height
   if( document.body && ( document.body.scrollWidth || document.body.scrollHeight ) ) {
   var pageWidth = document.body.scrollWidth+'px';
   var pageHeight = document.body.scrollHeight+'px';
   } else if( document.body.offsetWidth ) {
   var pageWidth = document.body.offsetWidth+'px';
   var pageHeight = document.body.offsetHeight+'px';
   } else {
   var pageWidth='100%';
   var pageHeight='100%';
   }
   //set the shader to cover the entire page and make it visible.
   dark.style.opacity=opaque;
   dark.style.MozOpacity=opaque;
   dark.style.filter='alpha(opacity='+opacity+')';
   dark.style.zIndex=zindex;
   dark.style.backgroundColor=bgcolor;
   dark.style.width= pageWidth;
   dark.style.height= pageHeight;
   dark.style.display='block';
   document.createElement('div');
   } else {
   dark.style.display='none';
   }
}

var jsDDLWidth = 0;
var i=0;
var temp ="";
var biggest=0;



function jsDDLFocus(control)
{//new 20091126 - frikkie

      if(navigator.appName=="Microsoft Internet Explorer")
         {
            jsDDLWidth = control.offsetWidth;
            control.style.width ='90%';
          }
}

function jsDDLBlur(control)
{
   if(navigator.appName=="Microsoft Internet Explorer")
   {
     control.style.width=jsDDLWidth;
   }
}


function d(e)
{//PJ 20100513
   return document.getElementById(e);
}

function validateRad(formObjRad)
{//pj - 20100826 - validates if anything in a rad has been checked
   try{

      for (i= formObjRad.length-1; i > -1; i--)
      {
         if (formObjRad[i].checked)
         {
            return true;
         }
      }
      return false;
   }
   catch(e)
   {
      alert(e);
      return false;
   }
}

function jsGetArrayControlID(obj)
{//20101105 - returns the ID index of a html array of controls, eg. radX[55] returns 55 @auth pj
   var id = 0;
   var idx1 = 0;
   var idx2 = 0;

   //alert(obj.type);
   if(obj.type=='text')
   {
      var s = obj;
   }else{
      var s = obj.id;
   }


   try{
      idx1 = s.indexOf("[", 0);
      idx2 = s.indexOf("]", idx1);
      id = s.substring(idx1+1,idx2);
      //alert(idx1 +' '+ idx2 +' '+ id);
      return id;
   }
   catch(e)
   {
      return 0;
   }
}

function jsShowElement(objID, blnCheck)
{
   jQuery.fx.off = false;
   try{
   obj = d(objID);
   }catch(e){alert(e); return false;}

   if(blnCheck.value == 1)
   {
      $('#'+objID).hide("fast");
      blnCheck.value = 0;
   }else{
      $('#'+objID).show("fast");
      blnCheck.value = 1;
   }
}

var blnListSelect = false;
function jsToggleSelect(chkSel)
{
   blnListSelect = !blnListSelect;
   if(typeof chkSel === "undefined") { chkSel = "chkSelect"}
   $('input[id='+chkSel+']').attr('checked', blnListSelect);
}

