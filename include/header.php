<?php
require_once 'vendor/autoload.php'; // Adjust the path if necessary

session_start();

$client = new Google_Client();
$client->setClientId('811088445179-nl11nrnkom33dme9njclst83rm8b0ios.apps.googleusercontent.com'); // Replace with your Client ID
$client->setClientSecret('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'); // Replace with your Client Secret
$client->setRedirectUri('http://localhost/easy-padhai/signup.php'); // Update with your callback URL
$client->addScope('email');
$client->addScope('profile');

$loginUrl = $client->createAuthUrl();
?>


    <!-- Bootstrap  -->
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/bootstrap.css" >

    <!-- Theme Style -->
    <!--<link rel="stylesheet" type="text/css" href="assets/stylesheets/shortcodes.css">-->
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/style.css">

    <!-- REVOLUTION LAYERS STYLES -->
    <link rel="stylesheet" type="text/css" href="assets/revolution/css/layers.css">
    <link rel="stylesheet" type="text/css" href="assets/revolution/css/settings.css">
    
    <!-- Responsive -->
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/responsive.css">

    <!-- Colors -->
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/colors/color1.css" id="colors">
	
	<!-- Animation Style -->
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/animate.css">
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
    <!-- Favicon and touch icons  -->
    <link href="assets\images\mylogo\favicon.png" rel="apple-touch-icon-precomposed" sizes="96x96">
    
    <link href="assets\images\mylogo\favicon.png" rel="shortcut icon">

   <style>
       @media only screen and (max-width: 600px) {
  .mobbot {
    background-color: #222222 !important;
  }
  .font1{
      font-size:10px;
  }
}
   </style>
</head> 
<body class="header-sticky page-template-front-page">
    <div class="boxed">
        <div class="windows8">
            <div class="preload-inner">
                <div class="wBall" id="wBall_1">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_2">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_3">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_4">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_5">
                    <div class="wInnerBall"></div>
                </div>
            </div>
        </div>
    	<!--<div class="header-inner-pages">-->
    	<!--	<div class="top">-->
    	<!--		<div class="container">-->
    	<!--			<div class="row">-->
    	<!--				<div class="col-md-12">-->
    	<!--					<div class="text-information">-->
    	<!--						<p>Welcome to Easy Padhai</p>-->
    	<!--					</div>-->
     <!--                       <div class="right-bar">-->
     <!--   						<ul class="flat-information">-->
     <!--   							<li class="phone">-->
     <!--   								<a href="tel:+919990793355" title="Phone number">+91-9990793355</a>-->
     <!--   							</li>-->
     <!--   							<li class="email">-->
     <!--   								<a href="mailto:Chauhansir.Easypadhai@gmail.com" title="Email address">Chauhansir.Easypadhai@gmail.com</a>-->
     <!--   							</li>-->
     <!--                           </ul>-->
     <!--                           <ul class="flat-socials">-->
     <!--   							<li class="facebook">-->
     <!--   								<a href="#">-->
     <!--   									<i class="fa fa-facebook"></i>-->
     <!--   								</a>-->
     <!--   							</li>-->
     <!--   							<li class="twitter">-->
     <!--   								<a href="#">-->
     <!--   									<i class="fa fa-twitter"></i>-->
     <!--   								</a>-->
     <!--   							</li>-->
     <!--   							<li class="linkedin">-->
     <!--   								<a href="#">-->
     <!--   									<i class="fa fa-linkedin"></i>-->
     <!--   								</a>-->
     <!--   							</li>-->
     <!--   							<li class="youtube">-->
     <!--   								<a href="#">-->
     <!--   									<i class="fa fa-youtube-play"></i>-->
     <!--   								</a>-->
     <!--   							</li>-->
     <!--   						</ul>-->
     <!--                       </div>-->
    	<!--				</div>-->
    	<!--			</div>-->
    	<!--		</div>-->
    	<!--	</div>      -->
    	<!--</div><!-- /.header-inner-pages -->

    	<!-- Header --> 
    	<header id="header" class="header clearfix"> 
        	<div class="container">
                <div class="header-wrap clearfix">
                    <div id="logo" class="logo">
                        <a href="index.php" rel="home">                            
                            <img src="assets\images\mylogo\image (1).png" alt="Easy Padhai">
                        </a>
                    </div><!-- /.logo -->            
                    <div class="nav-wrap">
                        <div class="btn-menu">
                            <span></span>
                        </div><!-- //mobile menu button -->
                        <nav id="mainnav" class="mainnav">
                            <ul class="menu"> 
                                <li>
                                    <a class="active" href="#rev_slider_1078_1_wrapper">Home</a>
                                </li>
                                <li><a href="#about">About Us</a>
                                </li>
                                <li><a href="#services">Services</a>
                                </li> 
                                

                                <li><a href="#contact">Contact</a>
                                </li>
                                <li><button type="button" class="mobbot"  data-toggle="modal" data-target="#myModal">Start Learning</button>  
                                </li>
                                <!-- Button trigger modal -->




                                
                            </ul><!-- /.menu -->
                        </nav><!-- /.mainnav -->    
                    </div><!-- /.nav-wrap -->
                    
                   
                </div><!-- /.header-inner --> 
            </div>
        </header><!-- /.header -->
        
        
        
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title" id="myModalLabel">Start Learning</h4>
      </div>
      <div class="modal-body">
        <button class="font1" style="width: 100%;margin-bottom:4px;" onclick="window.location.href='<?php echo htmlspecialchars($loginUrl); ?>'">Continue With Google</button>
        <button class="font1" style="width: 100%;" onclick="window.location.href='users/index.php'">Login</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>