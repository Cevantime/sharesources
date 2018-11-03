<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
        

		<title><?php echo $title_for_layout; ?></title>

		<!-- Bootstrap Core CSS -->
		<link href="<?php echo base_url('assets/vendor/css/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet">

		<!-- MetisMenu CSS -->
		<link href="<?php echo base_url('assets/vendor/css/metisMenu/metisMenu.min.css'); ?>" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="<?php echo base_url("assets/local/css/sb-admin-2.css"); ?>" rel="stylesheet">

		<!-- Morris Charts CSS -->
		<link href="<?php echo base_url('assets/vendor/css/morrisjs/morris.css'); ?>" rel="stylesheet">

		<!-- Custom Fonts -->
		<link href="<?php echo base_url('assets/vendor/css/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css">

		<!-- DataTables CSS -->
		<link href="<?php echo base_url('assets/vendor/css/datatable.min.css'); ?>" rel="stylesheet">

		<!-- DataTables Responsive CSS -->
		<link href="<?php echo base_url('assets/vendor/css/datatable.bootstrap.min.css'); ?>" rel="stylesheet">

		<link rel="stylesheet" href="<?php echo base_url('assets/local/css/selectize.css'); ?>"/>
		
		<!-- custom stylesheet -->
		<link rel="stylesheet" href="<?php echo base_url('assets/local/css/stylesheet.css'); ?>"/>

		<script>
			var baseURL = '<?php echo base_url(); ?>';
			var currentURL = '<?php echo current_url(); ?>'
			var locale = '<?php echo locale(); ?>';
		</script>
		<?php echo $css_for_layout ?>
		<?php echo $js_for_layout ?>
		<?php echo $this->layout->block('css'); ?>
		<?php echo $this->layout->block(); ?>

		<?php echo $this->layout->block('js'); ?>
		<?php echo $this->layout->block(); ?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>

	<body data-module='globals'>

		<div id="wrapper">
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0"">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?php echo base_url(); ?>">WebForce resources</a>
					</div>
					<script type="text/javascript" data-module="modules/bo/fileselect"></script>
					<script type="text/javascript" data-module="modules/bo/parseModal"></script>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user fa-fw"></i><span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li>
										<?php if(user_is('teacher')): ?>
										<a>
											Bienvenue, <?php echo htmlspecialchars($this->webforceteacher->forname.' '.$this->webforceteacher->name); ?>
										</a>
										<?php else : ?>
										<a href="#">
											Session <?php echo htmlspecialchars(user('login')); ?>
										</a>
										<?php endif; ?>
									</li>
									<?php if(user_is('teacher')): ?>
									<li role="separator" class="divider"></li>
									<div style="padding: 3px 20px; width: 230px;">
										<?php echo Modules::run('sessionsController/mySessions'); ?>
									</div>
									<?php endif; ?>
									<li role="separator" class="divider"></li>
									<li><?php echo Modules::run('memberspace/disconnection/bootstrap'); ?></li>
								</ul>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</div><!-- /.container-fluid -->
			</nav>


				<div class="navbar-default sidebar" role="navigation">
					<div class="sidebar-nav">
						<ul class="nav" id="side-menu">
							<!--							<li class="sidebar-search">
															<div class="input-group custom-search-form">
																<input type="text" class="form-control" placeholder="Search...">
																<span class="input-group-btn">
																	<button class="btn btn-default" type="button">
																		<i class="fa fa-search"></i>
																	</button>
																</span>
															</div>
															 /input-group 
														</li>-->
							<!--							<li>
															<a href="index.html"><i class="fa fa-dashboard fa-fw"></i> Les cours</a>
														</li>-->
							<!--							<li>
															<a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
															<ul class="nav nav-second-level">
																<li>
																	<a href="flot.html">Flot Charts</a>
																</li>
																<li>
																	<a href="morris.html">Morris.js Charts</a>
																</li>
															</ul>
															 /.nav-second-level 
														</li>-->
							<!--							<li>
															<a href="tables.html"><i class="fa fa-table fa-fw"></i> Calendrier</a>
														</li>-->
							<!--							<li>
															<a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
														</li>-->
							<li>
								<a href="#"><i class="fa fa-book fa-fw"></i> Les cours<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<?php if (user_can('add', 'course')): ?>
										<li>
											<a href="<?php echo base_url('courses/bootstrap'); ?>">Ajouter un cours</a>
										</li>
										<li>
											<a href="<?php echo base_url('courses/mines'); ?>">Mes cours</a>
										</li>
									<?php endif; ?>
									<li>
										<a href="<?php echo base_url('courses/all'); ?>">Tous les cours</a>
									</li>
									<li>
										<a href="<?php echo base_url('courses/calendar'); ?>">Mon calendrier</a>
									</li>
								</ul>
							</li>
							<?php if (user_can('*', 'category')): ?>
								<li>
									<a href="#"><i class="fa fa-wrench fa-fw"></i> Les catégories<span class="fa arrow"></span></a>
									<ul class="nav nav-second-level">
										<li>
											<a href="<?php echo base_url('categories/all'); ?>">Toutes les catégories</a>
										</li>
										<li>
											<a href="<?php echo base_url('categories/add'); ?>"> Ajouter une catégorie</a>
										</li>

									</ul>
								</li>
							<?php endif; ?>
							<?php if(user_can('see', 'teachsession')): ?>
							<li>
								<a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Les sessions<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<?php if(user_can('see', 'teachsession')): ?>
									<li>
										<a href="<?php echo base_url('sessions/all'); ?>">Toutes les sessions</a>
									</li>
									<?php endif; ?>
									<li>
										<a href="<?php echo base_url('sessions/mines'); ?>">Mes sessions</a>
									</li>
									<?php if(user_can('add', 'teachsession')): ?>
									<li>
										<a href="<?php echo base_url('sessions/add'); ?>"> Ajouter une session</a>
									</li>
									<?php endif; ?>

								</ul>
							</li>
							
							<?php endif; ?>
							
							<?php if(user_is('teacher')): ?>
							<li>
								<a href="<?php echo base_url('teacher/profile'); ?>"><i class="fa fa-user fa-fw"></i> Mon profil formateur</a>
							</li>
							
							<?php endif; ?>
							<?php if(user_can('*', 'webforceteacher')): ?>
							<li>
								<a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Gestions des formateurs<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="<?php echo base_url('teacher/add'); ?>">Ajouter un formateur</a>
									</li>
									<li>
										<a href="<?php echo base_url('teachers'); ?>">Tous les formateurs</a>
									</li>
									
								</ul>
							</li>
							
							<?php endif; ?>
							<?php if(user_can('*', 'bo/admin')): ?>
							<li>
								<a href="#"><i class="fa fa-graduation-cap fa-fw"></i> Gestions des administrateurs<span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<li>
										<a href="<?php echo base_url('administrator/add'); ?>">Ajouter un administrateur</a>
									</li>
									<li>
										<a href="<?php echo base_url('administrators'); ?>">Tous les administrateurs</a>
									</li>
									
								</ul>
							</li>
							
							<?php endif; ?>
							<!--							<li>
															<a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
															<ul class="nav nav-second-level">
																<li>
																	<a href="#">Second Level Item</a>
																</li>
																<li>
																	<a href="#">Second Level Item</a>
																</li>
																<li>
																	<a href="#">Third Level <span class="fa arrow"></span></a>
																	<ul class="nav nav-third-level">
																		<li>
																			<a href="#">Third Level Item</a>
																		</li>
																		<li>
																			<a href="#">Third Level Item</a>
																		</li>
																		<li>
																			<a href="#">Third Level Item</a>
																		</li>
																		<li>
																			<a href="#">Third Level Item</a>
																		</li>
																	</ul>
																	 /.nav-third-level 
																</li>
															</ul>
														</li>
														<li>
															<a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
															<ul class="nav nav-second-level">
																<li>
																	<a href="blank.html">Blank Page</a>
																</li>
																<li>
																	<a href="login.html">Login Page</a>
																</li>
															</ul>
														</li>-->
						</ul>
					</div>
					<!-- /.sidebar-collapse -->
				</div>
				<!-- /.navbar-static-side -->
			</nav>

			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header"><?php echo $title_for_layout; ?></h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<?php echo $content_for_layout; ?>
			</div>
			<!-- /#page-wrapper -->

		</div>
		<!-- /#wrapper -->
		<div id="modal-from-dom" class="modal small fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<a href="#" class="close" data-dismiss="modal">&times;</a>
						<h3></h3>
					</div>
					<div class="modal-body">

					</div>
					<div class="modal-footer">
						<a href="" class="btn btn-danger">OK</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Custom Theme JavaScript -->
		<?php echo Modules::run('flashmessages/flashMessages/slidedownstyle'); ?>
		<?php echo $this->layout->block('scripts'); ?>
		<script src="<?php echo base_url('assets/local/js/app.js'); ?>"></script>

	</body>

</html>
