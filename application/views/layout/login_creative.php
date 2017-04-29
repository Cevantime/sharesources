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
		<link href="<?php echo base_url("assets/vendor/css/material-dashboard.css"); ?>" rel="stylesheet" />
		<link href="<?php echo base_url("assets/vendor/css/custom.css"); ?>" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo base_url('assets/local/css/selectize.css'); ?>"/>
		<link href="<?php echo base_url("assets/local/css/stylesheet.css"); ?>" rel="stylesheet" />
		<!--     Fonts and icons     -->
		<link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />
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
	</head>

	<body data-module="globals">
		<nav class="navbar navbar-primary navbar-transparent navbar-absolute">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><?php echo config('app_title', translate('Resources')); ?></a>
				</div>
			</div>
		</nav>
		<div class="wrapper wrapper-full-page">
			<div class="full-page login-page" filter-color="black" data-image="<?php echo base_url(config('app_login_image')); ?>">
				<!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->
				<div class="content">
					<div class="container">
						<?php echo $content_for_layout; ?>
					</div>
				</div>
				<footer class="footer">
					<div class="container">
						
						<p class="copyright pull-right">
							&copy;
							<script>
								document.write(new Date().getFullYear())
							</script>
							<a href="http://www.haveasite.fr">HaveASite</a>, made with <i class="fa fa-heart"></i>
						</p>
					</div>
				</footer>
				<?php if(config('app_login_image')): ?>
				<div class="full-page-background" style="background-image: url(<?php echo base_url(config('app_login_image')); ?>) "/>
				<?php endif; ?>
			</div>
		</div>

		<?php echo Modules::run('flashmessages/flashMessages/slidedownstyle'); ?>
		<script src="<?php echo base_url('assets/local/js/app.js'); ?>"></script>
		<?php echo $this->layout->block('scripts'); ?>
		
		<script src="<?php echo base_url('assets/vendor/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/vendor/js/material.min.js'); ?>" type="text/javascript"></script>
		<script src="<?php echo base_url('assets/vendor/js/perfect-scrollbar.jquery.min.js'); ?>" type="text/javascript"></script>
		<!-- TagsInput Plugin -->
		<script src="<?php echo base_url('assets/vendor/js/jquery.tagsinput.js');?>"></script>
		<!-- Material Dashboard javascript methods -->
		<script src="<?php echo base_url('assets/vendor/js/material-dashboard.js'); ?>"></script>
		
		<script type="text/javascript">
			$().ready(function() {

				setTimeout(function() {
					// after 1000 ms we add the class animated to the login/register card
					$('.card').removeClass('card-hidden');
				}, 700)
			});
		</script>
	</body>

</html>