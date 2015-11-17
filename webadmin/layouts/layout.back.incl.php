<?php

include_once("header.incl.php");

// echo "
// <body>
//    <form name='frmNemo' enctype='multipart/form-data' action='". $this->SystemSettings[FULL_PATH] ."' method='post'>
//    <div id=divPage>";
//       echo $this->Header();
//       echo "<div class='dora-Menu-toolbar'>";
//       echo $this->Menu();
//       echo $this->Message();
//       echo $this->ToolBar();
//       echo "</div>";

//       echo "<div class='Windows'>
//                <div class='SideWindow'> FUCK FUCKITY FUCK </div>
//                <div class='MainWindow'>".$this->Sandbox()."</div>
//             </div>"; 
//       echo $this->Footer();
//       echo"
//    </div>
//    </form>
// </body>
// </html>
// ";

if($_SESSION[sidebar] == "true")
{
 $marginLeft = "margin-left:217px;";
}
else if($_SESSION[sidebar] == "false")
{
 $marginLeft = "margin-left:0px;";
}  
echo "<body>
			".$this->SideBar("white")."
			<div class='content' style='$marginLeft'>
			 <form name='frmNemo' enctype='multipart/form-data' action='". $this->SystemSettings[FULL_PATH] ."' method='post'>
				<div class='dora-Menu-toolbar'>
					<header>".$this->Header()."</header>
					<section>".$this->Menu()."</section>
					<section>".$this->Message()."</section>
					<section>".$this->ToolBar()."</section>
				</div> 
				<section style='padding-bottom:60px;'>".$this->Sandbox()."</section>
				<footer class='footer2'>
					<aside> 
               		</aside>
               		<article>
               			<a href='http://www.xpliquor.com/' target='blank'><img src='images/Xprocure.png' height='40px' /></a>
                  		<a href='http://www.overdrive.co.za/' target='blank'><img src='images/siliconLogo.png' height='40px' /></a>
               		</article>
               		<article style='padding:17px 15px 0px 0px;'>".$this->Footer()."</article>
				</footer>
				</form>
			</div>
		</body>";

 
// echo     ;
// echo "   <div class='content'>";

// echo     ;
// echo     "<div class='dora-Menu-toolbar'>";
// echo     ;
// echo     $this->Message();
// echo     $this->ToolBar();
// echo     "</div>";
// echo     $this->Sandbox();
// echo     $this->Footer(); 

// echo     "</div>
//       </body>";

// echo "
//    <script type='text/javascript'>
   
//       $(window).load(function()
//       {
//          $('[data-toggle]').click(function() 
//          {
//             var toggle_el = $(this).data('toggle');
//             $(toggle_el).toggleClass('open-sidebar');

            
//             // active = $('.open-sidebar').width();
//             // container = $('.container').width(); 
//             // if(active == 1920)
//             // {
//             //    $('.container').animate({
//             //       width: container-240
//             //    }, 400);
//             // }
//             // else
//             // {
//             //    $('.container').animate({
//             //       width: '1920px'
//             //    }, 400);
//             // } 
            
//          });
         
//          $('.swipe-area').swipe(
//          {

//             swipeStatus:function(event, phase, direction, distance, duration, fingers)
//             {
//                if (phase=='move' && direction =='right') 
//                {
//                   $('.container').addClass('open-sidebar');

//                   return false;
//                }

//                if (phase=='move' && direction =='left') 
//                {
//                   $('.container').removeClass('open-sidebar');
//                   return false;
//                }
//             }
//          }); 
//       });
      
//    </script>

//    <body>
//       <div class='container'>

//          <div id='sidebar'>
//             <ul>
//                <li><a href='#'>Home</a></li>
//                <li><a href='#'>Home</a></li>
//             </ul>
//          </div>

//          <div class='main-content'>
//          <a href='#' data-toggle='.container' id='sidebar-toggle'>
//             <div class='swipe-area'></div>
//             </a> 
            
//             <div class='content'>";


// echo $this->Header();
// echo "<div class='dora-Menu-toolbar'>";
// echo $this->Menu();
// echo $this->Message();
// echo $this->ToolBar();
// echo "</div>";
// echo $this->Sandbox();
// echo $this->Footer();


// echo "      </div>
//          </div>

//       </div>
//    </body>";
?>