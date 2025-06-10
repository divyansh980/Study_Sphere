  <!-- Footer -->
        <footer class="footer">  
            <div class="footer-widgets">   
                <div class="container">
                    <div class="row">
                        <div class="col-md-3">  
                            <div class="widget widget-text">
                                
                                <div id="logo" class="logo mb-5">
                                                    <a href="index.php" rel="home">
                                                    <img src="assets/images/mylogo/image.png" alt="Easy Padhai">
                                                  </a>
                                  </div>
                                <!--<img src="assets/images/Easy Padhai 8.png" width="35%" alt="EasyLogo">-->
                                <p>
                                    As a teacher, you want to help the students, to achieve their goals… Great… You are welcome to join our team and help millions of students in their studies.
                                </p>
                            </div><!-- /.widget -->      
                        </div>

                        <div class="col-md-3">
                            <div class="widget widget_tweets clearfix">
                                <h5 class="widget-title">User Links</h5>
                                <ul class="link-left">
                                    
                                <li><a href="#about">About Us</a>
                                </li>
                                <li><a href="#services">Services</a>
                                </li> 
                                

                                <li><a href="#contact">Contact</a>
                                </li>
                                <li><a href="<?php echo htmlspecialchars($loginUrl); ?>">Start Learning</a>
                                </li>
                                </ul>
                                
                            </div><!-- /.widget-recent-tweets -->
                        </div><!-- /.col-md-2 -->

                        <div class="col-md-3">
                            <div class="widget widget_tweets">
                                <h5 class="widget-title">Subject</h5>
                                <ul>
                                    <li><a href="#services">LANGUAGES</a></li>
                                    <li><a href="#services">MATH</a></li>
                                    <li><a href="#services">ACCOUNTANCY</a></li>
                                    <li><a href="#services">BUSINESS STUDIES</a></li>
                                    <li><a href="#services">HISTORY</a></li>
                                    <li><a href="#services">GEOGRAPHY</a></li>
                                    <li><a href="#services">POLITICAL SCIENCE</a></li>
                                    
                                </ul> 
                            </div>
                        </div><!-- /.col-md-4-->

                        <div class="col-md-3">
                            <div class="widget widget-text">
                                <h5 class="widget-title">Quick Contact</h5>
                                <ul>
                                    <li class="address">Dilshad Garden delhi 95</li>
                                    <li class="phone"><a href="tel:+919794783967 ">+91-9794783967 </a></li>
                                    <li class="email"><a href="mailto:divyansh_213042@saitm.org">divyansh_213042saitm.org</a></li>  
                                </ul> 
                            </div><!-- /.widget .widget-instagram -->
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.footer-widgets -->
        </footer>
        
        <div class="social-button" title="expand social media options">
          <i class="fas fa-comment-alt"id="social-icon"></i>
          <a hidden class="social-point" href="https://wa.me/+919794783967/?text= Hello,i want to know more about your services" target="_blank" rel="nofollow" title="Whatsapp"><i class="fab fa-whatsapp"></i></a>
          <a hidden class="social-point" href="#" target="_blank" rel="nofollow" title="Twitter"><i class="fab fa-twitter"></i></a>
          <a hidden class="social-point" href="#" target="_blank" rel="nofollow" title="youtube"><i class="fab fa-youtube" style="color: #ff3d3d;"></i></a>
          <a hidden class="social-point" href="#" target="_blank" rel="nofollow" title="Facebook"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
        </div>


        <a class="go-top">
            <i class="fa fa-chevron-up fs-1"></i>
        </a>

        <!-- Bottom -->
        <div class="bottom">
            <div class="container">
                <ul class="flat-socials-v1">
                    <li class="facebook">
                        <a href="https://wa.me/+919794783967/?text= Hello,i want to know more about your services"><i class="fab fa-whatsapp"></i></a>
                    </li>
                    <li class="twitter">
                        <a href="#"><i class="fab fa-twitter"></i></i></a>
                    </li>
                    <li class="vimeo">
                        <a href="#"><i class="fab fa-youtube" style="color: #ff3d3d;"></i></a>
                    </li>
                    <li class="rss">
                        <a href="#"><i class="fa fa-rss"></i></a>
                    </li>
                </ul>    
                <div class="row">
                    <div class="container-bottom">
                        <div class="copyright"> 
                            <p>Copyrights © All Rights Reserved 2024 | <span><a href="index.php">Study Sphere</a></span> . Designed by <span><a href="#">Divyansh Gupta</a></span></p>
                        </div>
                    </div><!-- /.container-bottom -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div>
    </div><!-- /. boxed -->


        <!-- Javascript -->
    <script type="text/javascript" src="assets/javascript/jquery.min.js"></script>
    <script type="text/javascript" src="assets/javascript/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/javascript/jquery.easing.js"></script> 
    <script type="text/javascript" src="assets/javascript/owl.carousel.js"></script> 
    <script type="text/javascript" src="assets/javascript/jquery-waypoints.js"></script>
    <script type="text/javascript" src="assets/javascript/jquery-countTo.js"></script>    
    <!--<script type="text/javascript" src="assets/javascript/parallax.js"></script>-->
    <script type="text/javascript" src="assets/javascript/jquery.cookie.js"></script>
    <script type="text/javascript" src="assets/javascript/jquery-validate.js"></script>     
    <script type="text/javascript" src="assets/javascript/main.js"></script>

	<!-- Revolution Slider -->
    <script type="text/javascript" src="assets/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/jquery.themepunch.revolution.min.js"></script>
    <script type="text/javascript" src="assets/revolution/js/slider.js"></script>

    
    
    <script>var social_open = false
window.addEventListener('load', () => {
  const menu = document.querySelector('.social-button');

  menu.addEventListener('click', () => {
    const icon = document.querySelector('#social-icon');
    if(social_open == true) {
      social_open = false;

      menu.title = menu.title.replace(/hide/,"expand")
      menu.classList.remove('social-button-open')
      icon.classList.remove('fa-times')
      icon.classList.add('fa-comment-alt')

      var menu_point = document.querySelectorAll(".social-point");
      for (let i = 0; i < menu_point.length; i++) {
          menu_point[i].classList.remove('social-point-open');
        setTimeout(function() {
          menu_point[i].hidden = true;
        }, 800)
      }
    } else {
      social_open = true;

      menu.title = menu.title.replace(/expand/,"hide")
      menu.classList.add('social-button-open');
      icon.classList.remove('fa-share-alt')
      icon.classList.add('fa-times')

      var menu_point = document.querySelectorAll(".social-point");
      for (let i = 0; i < menu_point.length; i++) {
        menu_point[i].hidden = false;
        setTimeout(function() {
          menu_point[i].classList.add('social-point-open');
        }, 200)
      }
    }
  });
})
</script>

    
</body>
</html>