<!doctype html>
<html lang="en">

	<head>
		<meta charset="utf-8" />
		<link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
		<link rel="icon" type="image/png" href="../assets/img/favicon.png" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title><?php echo $title_for_layout; ?></title>
		<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
		<meta name="viewport" content="width=device-width" />

		<!-- Bootstrap core CSS     -->
		<link href="<?php echo base_url("assets/vendor/css/bootstrap/bootstrap.min.css"); ?>" rel="stylesheet" />
		<!--  Material Dashboard CSS    -->
		<link href="<?php echo base_url("assets/vendor/css/custom.css"); ?>" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo base_url('assets/local/css/selectize.css'); ?>"/>
		<link href="<?php echo base_url("assets/local/css/chat.css"); ?>" rel="stylesheet" />
		<link href="<?php echo base_url("assets/local/css/stylesheet.css"); ?>" rel="stylesheet" />
		<!--     Fonts and icons     -->
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
		<script>
			var baseURL = '<?php echo base_url(); ?>';
			var currentURL = '<?php echo current_url(); ?>'
			var locale = '<?php echo locale(); ?>';
		</script>
		<style>
			
		</style>

		<?php echo $css_for_layout ?>
		<?php echo $js_for_layout ?>
		<?php echo $this->layout->block('css'); ?>
		<?php echo $this->layout->block(); ?>

		<?php echo $this->layout->block('js'); ?>
		<?php echo $this->layout->block(); ?>
	</head>

	<body data-module="globals" <?php if(pref_is('sidebar_collapsed', '1')): ?>class="sidebar-mini"<?php endif; ?>>
		<div class="wrapper">
			<div class="sidebar" data-active-color="rose" data-background-color="white" data-image="<?php echo base_url(config('app_sidebar_image') ? config('app_sidebar_image') : "assets/local/images/sidebar-1.jpg"); ?>">

				<div class="logo">
					<a href="<?php echo base_url('home'); ?>" class="simple-text">
						<?php echo config('app_title', translate('Resources')); ?>
					</a>
				</div>
				<div class="logo logo-mini">
					<a href="<?php echo base_url('home'); ?>" class="simple-text">
						<?php echo config('app_title_small', 'R'); ?>
					</a>
				</div>
				<div class="sidebar-wrapper" data-module="perfect_scrollbar">
					<div class="user">
						<div class="photo">
							<img src="<?php echo imageresize(user('avatar') ? user('avatar') : config('default_avatar', 'assets/local/images/default-avatar.png'), 80, 80, true); ?>" />
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" class="collapsed">
								<?php if (user_is('webforceteacher')): ?>
									<?php echo user('forname'); ?> <?php echo user('name'); ?>
								<?php else : ?>
									<?php echo user('login'); ?>
								<?php endif; ?>
								<b class="caret"></b>
							</a>
							<div class="collapse" id="collapseExample">
								<ul class="nav">
									<?php if (user_is('teacher')): ?>
										<li>
											<a href="<?php echo base_url('teacher/profile'); ?>">Mon Profil</a>
										</li>
									<?php endif; ?>
									<?php if (user_is('teacher')): ?>
										<li>
											<?php echo Modules::run('sessionsController/mySessions'); ?>
										</li>
									<?php endif; ?>
									<li>
										<?php echo Modules::run('memberspace/disconnection/bootstrap'); ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<ul class="nav">
						<li>
							<a data-toggle="collapse" <?php if( pref_is('sidebar_collapsed', '0') && route_is('courses')): ?> aria-expanded="true" <?php else: ?> class="collapsed"<?php endif; ?> href="#pagesExamples">
								<i class="material-icons">library_books</i>
								<p>Les cours
									<b class="caret"></b>
								</p>
							</a>
							<div  class="collapse<?php if( pref_is('sidebar_collapsed', '0') && route_is('courses')): ?> in<?php endif; ?>" id="pagesExamples">
									
								<ul class="nav">
									<li>
										<a href="<?php echo base_url('courses/calendar'); ?>">Mon calendrier</a>
									</li>
									<?php if(user_is('teacher') || user_is('root')) : ?>
									<li>
										<a href="<?php echo base_url('courses/mines'); ?>">Mes cours</a>
									</li>
									<?php endif; ?>
									<li>
										<a href="<?php echo base_url('courses/all'); ?>">Tous les cours</a>
									</li>
									<?php if (user_can('add', 'course')): ?>
										<li>
											<a href="<?php echo base_url('courses/bootstrap'); ?>">Ajouter un cours</a>
										</li>
									<?php endif; ?>
								</ul>
							</div>
						</li>
						<?php if (user_can('*', 'category')): ?>
							<li>
								<a data-toggle="collapse" <?php if( pref_is('sidebar_collapsed', '0') && route_is('categorie')): ?> aria-expanded="true" <?php else: ?> class="collapsed"<?php endif; ?> href="#componentsExamples">
									<i class="material-icons">apps</i>
									<p>Les catégories
										<b class="caret"></b>
									</p>
								</a>
								<div class="collapse<?php if( pref_is('sidebar_collapsed', '0') && route_is('categorie')): ?> in<?php endif; ?>" id="componentsExamples">
									<ul class="nav">
										<li>
											<a href="<?php echo base_url('categories/all'); ?>">Toutes les catégories</a>
										</li>
										<li>
											<a href="<?php echo base_url('categories/add'); ?>">Ajouter une catégorie</a>
										</li>
									</ul>
								</div>
							</li>
						<?php endif; ?>
						<?php if (user_can('see', 'teachsession')): ?>
							<li>
								<a data-toggle="collapse" <?php if( pref_is('sidebar_collapsed', '0') && route_is('sessions')): ?> aria-expanded="true" <?php else: ?> class="collapsed"<?php endif; ?> href="#formsExamples">
									<i class="material-icons">date_range</i>
									<p>Les sessions
										<b class="caret"></b>
									</p>
								</a>
								<div class="collapse<?php if( pref_is('sidebar_collapsed', '0') && route_is('sessions')): ?> in<?php endif; ?>" id="formsExamples">
									<ul class="nav">
										<li>
											<a href="<?php echo base_url('sessions/mines'); ?>">Mes sessions</a>
										</li>
										<?php if (user_can('see', 'teachsession')): ?>
											<li>
												<a href="<?php echo base_url('sessions/all'); ?>">Toutes les sessions</a>
											</li>
										<?php endif; ?>
										<?php if (user_can('add', 'teachsession')): ?>
											<li>
												<a href="<?php echo base_url('sessions/add'); ?>">Ajouter une session</a>
											</li>
										<?php endif; ?>

									</ul>
								</div>
							</li>
						<?php endif; ?>
						<?php if (user_can('see', 'webforceteacher')): ?>
							<li>
								<a data-toggle="collapse" <?php if( pref_is('sidebar_collapsed', '0') && route_is('teacher')): ?> aria-expanded="true" <?php else: ?> class="collapsed"<?php endif; ?> href="#tablesExamples">
									<i class="material-icons">account_box</i>
									<p>Les formateurs
										<b class="caret"></b>
									</p>
								</a>
								<div class="collapse<?php if( pref_is('sidebar_collapsed', '0') && route_is('teacher')): ?> in<?php endif; ?>" id="tablesExamples">
									<ul class="nav">
										<li>
											<a href="<?php echo base_url('teachers/all'); ?>">Tous les formateurs</a>
										</li>
										<?php if(user_can('add', 'webforceteacher')) : ?>
										<li>
											<a href="<?php echo base_url('teacher/add'); ?>">Ajouter un formateur</a>
										</li>
										<?php endif; ?>
									</ul>
								</div>
							</li>
						<?php endif; ?>
						<?php if (user_can('see', 'webforceadmin')): ?>
							<li>
								<a data-toggle="collapse" <?php if( pref_is('sidebar_collapsed', '0') && route_is('administrator')): ?> aria-expanded="true" <?php else: ?> class="collapsed"<?php endif; ?> href="#adminExamples">
									<i class="material-icons">account_circle</i>
									<p>Les administrateurs
										<b class="caret"></b>
									</p>
								</a>
								<div class="collapse<?php if( pref_is('sidebar_collapsed', '0') && route_is('administrator')): ?> in<?php endif; ?>" id="adminExamples">
									<ul class="nav">
										<li>
											<a href="<?php echo base_url('administrators'); ?>">Tous les administrateurs</a>
										</li>
										<?php if (user_can('add', 'webforceadmin')): ?>
										<li>
											<a href="<?php echo base_url('administrator/add'); ?>">Ajouter un administrateur</a>
										</li>
										<?php endif; ?>
									</ul>
								</div>
							</li>
							
						<?php endif; ?>
						<?php if(user_can('*','configuration')): ?>
							<li>
								<a data-toggle="collapse" <?php if( pref_is('sidebar_collapsed', '0') && route_is('admin/')): ?> aria-expanded="true" <?php else: ?> class="collapsed"<?php endif; ?> href="#configuration">
									<i class="material-icons">settings</i>
									<p>Administration
										<b class="caret"></b>
									</p>
								</a>
								<div class="collapse<?php if( pref_is('sidebar_collapsed', '0') && route_is('admin/')): ?> in<?php endif; ?>" id="configuration">
									<ul class="nav">
										<li>
											<a href="<?php echo base_url('admin/configuration'); ?>">Configuration</a>
										</li>
										<li>
											<a href="<?php echo base_url('admin/notifications'); ?>">Notifications</a>
										</li>
									</ul>
								</div>
							</li>
						 <?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="main-panel">
				<nav class="navbar navbar-transparent navbar-absolute">
					<div class="container-fluid">
						<div class="navbar-minimize">
							<button id="minimizeSidebar" class="btn btn-round btn-white btn-fill btn-just-icon">
								<i class="material-icons visible-on-sidebar-regular">more_vert</i>
								<i class="material-icons visible-on-sidebar-mini">view_list</i>
							</button>
						</div>
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="#" title="<?php echo $title_for_layout; ?>"> <?php echo ellipsize( $title_for_layout, 45, 0.5 ); ?> </a>
						</div>
						<div class="collapse navbar-collapse">
							<ul class="nav navbar-nav navbar-right">
								<?php echo Modules::run('NotificationsController/index'); ?>
									
								
								<?php if (user_is('teacher')): ?>
									<li>
										<a href="<?php echo base_url('teacher/profile'); ?>">
											<i class="material-icons">person</i>
											<p class="hidden-lg hidden-md">Profil</p>
										</a>
									</li>
								<?php endif; ?>
								<?php if (teachsession()): ?>
									<li>
										<?php echo Modules::run('sessionsController/mySessions'); ?>
									</li>
								<?php endif; ?>
								<li class="separator hidden-lg hidden-md"></li>
							</ul>
							<form class="navbar-form navbar-right" role="search" action="<?php echo base_url('search'); ?>">
								<div class="form-group form-search is-empty">
									<input type="text" class="form-control" name="search" placeholder="Votre recherche">
									<span class="material-input"></span>
								</div>
								<button type="submit" class="btn btn-white btn-round btn-just-icon">
									<i class="material-icons">search</i>
									<div class="ripple-container"></div>
								</button>
							</form>
						</div>
					</div>
				</nav>
				<div class="content">
					<div class="container-fluid">
						<?php echo $content_for_layout; ?>
					</div>
				</div>
				<footer class="footer">
					<div class="container-fluid">

						<p class="copyright pull-right">
							&copy;
							<script>
								document.write(new Date().getFullYear())
							</script>
							<a href="http://www.haveasite.fr">HaveASite</a>, made with <i class="fa fa-heart"></i>
						</p>
					</div>
				</footer>
				
			</div>
		</div>
		<!-- /#wrapper -->
		<div id="modal-from-dom" class="modal small fade" data-module="modules/bo/parseModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<a href="#" class="close" data-dismiss="modal">&times;</a>
						<h3></h3>
					</div>
					<div class="modal-body">

					</div>
					<div class="modal-footer">
						<a href="" class="btn btn-confirm btn-danger">OK</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
					</div>
				</div>
			</div>
		</div>
		<?php echo Modules::run('flashmessages/flashMessages/slidedownstyle'); ?>
		<script src="<?php echo base_url('assets/local/js/app.js'); ?>"></script>
		<?php echo $this->layout->block('scripts'); ?>
		<?php echo $this->layout->block(); ?>
		<script src="<?php echo base_url('assets/vendor/js/material.min.js'); ?>" type="text/javascript"></script>
		<!-- Forms Validations Plugin -->
		<script src="<?php echo base_url('assets/vendor/js/jquery.validate.min.js'); ?>"></script>
		<!--  Plugin for the Wizard -->
        <!-- Perfect Scrollbar Jquery -->
		<script src="<?php echo base_url('assets/vendor/js/perfect-scrollbar.jquery.min.js'); ?>"></script>
        
		<script src="<?php echo base_url('assets/vendor/js/jquery.bootstrap-wizard.js'); ?>"></script>
		<!-- Vector Map plugin -->
		<script src="<?php echo base_url('assets/vendor/js/jquery-jvectormap.js'); ?>"></script>
		<!-- Sliders Plugin -->
		<script src="<?php echo base_url('assets/vendor/js/nouislider.min.js'); ?>"></script>

		<!-- Sweet Alert 2 plugin -->
		<script src="<?php echo base_url('assets/vendor/js/sweetalert2.js'); ?>"></script>
		<!--	Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
		<script src="<?php echo base_url('assets/vendor/js/jasny-bootstrap.min.js'); ?>"></script>
		<!-- TagsInput Plugin -->
		<script src="<?php echo base_url('assets/vendor/js/jquery.tagsinput.js'); ?>"></script>
		<!-- Material Dashboard javascript methods -->
		<script src="<?php echo base_url('assets/vendor/js/material-dashboard.js'); ?>"></script>
	</body>

</html>