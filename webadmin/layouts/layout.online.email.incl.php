<?php
include_once "header.incl.php";

// echo "

// </head>
// <body class='background'>";
      
 





// echo  "   <div class='HeaderBackground'>
//                         <div class='full_wrapper'>
                        
//                         <div class='Header'>
                           
//                            <div class='logo'><h1><a></a></h1></div>
//                            <div class='header_navigation_wrapper'>
//                               <div class='header_navigation'>
//                               </div>
//                            <div style='clear:both'></div>
//                         </div>
//                      </div>
//                   </div>
//                </div>";






// echo " <div class='main_wrapper'>";
//    echo "      <div class='full_wrapper'>
//                   <div id='main' style='width: 1085px !important; padding:none !important;'>
//                      <div style='margin:0px !important;' class='content_page_wrapper'>

                        
//                         <div style='width:100%; !important;' class='left_content_main_content_wrapper'>
//                            <div class='content_block'>
//                               <div class='contentSpace' id='centercontent'>
//                                  ". $this->Content ."
//                               </div>
//                            </div>
//                         </div>";

//    echo "               
                        

//                      </div>
//                      <div style='clear:both'></div>
//                   </div>
//                </div>   
//             </div>";














//    //    echo "<div id='main'>";
//    //    echo $this->Menu();
//    //    echo "

//    //    <div id='backcontent'>
//    //       <div id='content'>
//    //          <table border='0' width='100%'>
//    //             <tr>";
//    //          echo "<td valign='top' id='centercontent' class='justify' width='80%'>". $this->Content ."</td>";
//    //          echo "<td valign='top' id='rightcontent'>". $this->Learner_Right_Content() ."</td>";
//    //          echo "
//    //             </tr>
//    //          </table>
//    //       </div>
//    //    </div class=backcontent>
//    // </div id='main'>";

//    echo $this->Footer();

//    //echo $this->GoogleAnalytics();
// echo "</body>
// </form>
// </html>";



echo "<body style=margin-left:0px;>
         <div class='overallWrapper'>
         <form name='frmNemo' enctype='multipart/form-data' action='". $this->SystemSettings[FULL_PATH] ."' method='post'>
            <header>".$this->Header()."</header>
            <section>".$this->Message()."</section>
           
            <div style='width:100%; !important;' class='left_content_main_content_wrapper'>
               <div class='content_block'>
                  <div class='emailReadOnlineBox' id='centercontent'>
                     ". $this->Content ."
                  </div>
               </div>
            </div>
            <footer class='footer1'>
               <aside>
                  <a href='https://www.mozilla.org/en-US/firefox/new/' target='blank'><img src='images/firefox-icon.png' height='40px' /></a>
                  <a href='http://www.google.com/chrome/index.html?hl=en&brand=CHMA&utm_campaign=en&utm_source=en-ha-na-us-bk&utm_medium=ha' target='blank'><img src='images/chrome-icon.png' height='40px' /></a>
                  <a href='https://support.apple.com/kb/DL1531?locale=en_US' target='blank'><img src='images/safari-icon.png' height='40px' /></a>
               </aside>
               <article>
                  <a href='http://www.xpliquor.com/' target='blank'><img src='images/Xprocure.png' height='40px' /></a>
                  <a href='http://www.overdrive.co.za/' target='blank'><img src='images/siliconLogo.png' height='40px' /></a>
               </article>
               <article style='padding:35px 15px 0px 0px;'>".$this->Footer()."</article>

               
            </footer>
         </form>
         </div>
      </body>";


?>
