<?php

include_once("header.incl.php");
// echo "
// <body>
//    <form name='frmNemo' enctype='multipart/form-data' action='". $this->SystemSettings[FULL_PATH] ."' method='post'>
//    <div id=divPage>";
//       echo $this->Header();
//       echo $this->Message();
//       echo $this->Sandbox();
//       echo $this->Footer();
//    echo"
//    </div class=Page>
//    </form>
//    </body>
// </html>
// ";

echo "<body style=margin-left:0px;>
         <div class='overallWrapper'>
         <form name='frmNemo' enctype='multipart/form-data' action='". $this->SystemSettings[FULL_PATH] ."' method='post'>
            <header>".$this->Header()."</header>
            <section>".$this->Message()."</section>
            <section class='ContentContainer'>".$this->Sandbox()."</section> 
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
