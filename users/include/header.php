
<header class="main-header">
	<div class="d-flex align-items-center logo-box justify-content-start d-md-none d-block">	
		<!-- Logo -->
		<a href="index.php" class="logo">
		  <!-- logo-->
		  <div class="logo-mini w-100">
			  <span class="light-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
			  <span class="dark-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
		  </div>
		  
		</a>	
	</div>   
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
	  <div class="app-menu">
		<ul class="header-megamenu nav">
			<li class="btn-group nav-item">
				<a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
					<i class="icon-Menu"><span class="path1"></span><span class="path2"></span></i>
			    </a>
			</li>
			
		</ul> 
	  </div>
		
   
    </nav>
  </header>
  
  <aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
		<div class="d-flex align-items-center logo-box justify-content-start d-md-block d-none">	
			<!-- Logo -->
			<a href="index.php" class="logo">
			  <!-- logo-->
			  <div class="logo-mini">
				  <span class="light-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
			  </div>
			  
			</a>	
		</div> 
		<div class="user-profile my-15 px-20 py-10 b-1 rounded10 mx-15">
			<div class="d-flex align-items-center justify-content-between">			
				<div class="image d-flex align-items-center">
				    <img src="images/avatar/avatar-13.png" class="rounded-0 me-10" alt="User Image">
					<div>
						<h6 class="mb-0 fw-600"><?php echo $_SESSION['user_id'];?></h6>
						<p class="mb-0">Editor</p>
					</div>
				</div>
				<div class="info">
					<a class="dropdown-toggle p-15 d-grid" data-bs-toggle="dropdown" href="#"></a>
					<div class="dropdown-menu dropdown-menu-end">
					  
					  <a class="dropdown-item" href="logout.php"><i class="ti-lock"></i> Logout</a>
					</div>
				</div>
			</div>
	    </div>
	  	<div class="multinav">
		  <div class="multinav-scroll" style="height: 97%;">	
			  <!-- sidebar menu-->
			  <ul class="sidebar-menu" data-widget="tree">	
				<li class="header">Main Menu</li>
				<li>
				  <a href="index.php">
					<i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
					<span>Dashboard</span>
				  </a>
				</li>
				<li class="treeview">
				  <a href="#">
					<i class="fa-solid fa-question fa-xs"></i>
					<span>Question</span>
					<span class="pull-right-container">
					  <i class="fa fa-angle-right pull-right"></i>
					</span>
				  </a>
				  <ul class="treeview-menu">
					<li><a href="view-mcqquestions.php"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>MCQ</a></li>
					<li><a href="view-arquestions.php"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>AR Based</a></li>
					<li><a href="view-fillupquestions.php"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Fill In The Blanks </a></li>
					<li><a href="view-truefalsequestions.php"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>True/False</a></li>
					<li><a href="view-descriptivequestions.php"><i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>Descriptive</a></li>
					
				</ul>
				</li>
				<!--<li>-->
				<!--  <a href="question-papers.php">-->
				<!--	<i class="fa-solid fa-file-circle-question"></i>-->
				<!--	<span>Question Paper</span>-->
					
				<!--  </a>-->
				<!--</li>-->
				<li>
				  <a href="upload-images.php">
					<i class="fa-solid fa-upload"></i>
					<span>Upload Images</span>
					
				  </a>
				  
				</li> 
				<li>
				  <a href="view-dictionary.php">
					<i class="fa-solid fa-spell-check"></i>
					<span>Dictionary</span>
					
				  </a>
				  
				</li> 	 
				<li>
				  <a href="view-book.php">
					<i class="fa-solid fa-book"></i>
					<span>Books</span>
					
				  </a>
				  
				</li> 	     
			  </ul>
			  
			  <div class="sidebar-widgets">
				 <!-- <div class="mx-25 mb-30 pb-20 side-bx bg-primary-light rounded20">-->
					<!--<div class="text-center">-->
					<!--	<img src="https://crm-admin-dashboard-template.multipurposethemes.com/images/svg-icon/color-svg/custom-17.svg" class="sideimg p-5" alt="">-->
					<!--	<h4 class="title-bx text-primary">View Full Report</h4>-->
					<!--	<a href="#" class="py-10 fs-14 mb-0 text-primary">-->
					<!--		Best CRM App here <i class="mdi mdi-arrow-right"></i>-->
					<!--	</a>-->
					<!--</div>-->
				 <!-- </div>-->
				<div class="copyright text-center m-25">
					<p><strong class="d-block">Study Sphere</strong> © <script>document.write(new Date().getFullYear())</script> All Rights Reserved</p>
				</div>
			  </div>
		  </div>
		</div>
    </section>
  </aside>