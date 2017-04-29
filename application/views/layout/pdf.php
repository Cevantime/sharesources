<!DOCTYPE html>
<html lang="en">

	<head>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
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
		<link href="<?php echo base_url('assets/vendor/css/datatable.bootstrap.min.css');?>" rel="stylesheet">

		<link rel="stylesheet" href="<?php echo base_url('assets/local/css/selectize.css'); ?>"/>
		
		<?php echo $css_for_layout ?>
		<?php echo $js_for_layout ?>
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>

	<body data-module='globals'>

		<div id="wrapper">

				<div class="row">
					<div class="col-lg-12">
						<h1 class="page-header"><?php echo $title_for_layout; ?></h1>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<?php echo $content_for_layout; ?>
			<!-- /#page-wrapper -->

		</div>

	</body>

</html>
