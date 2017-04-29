<div class="container" id="top-blogadmin-index" style="display: none;">
	<header class="main-header">
		<div class="container">
			<h2 class="page-title">Ajouter un nouvel article</h1><br/>
		</div>
	</header>
	<?php echo Modules::run('blog/save/bootstrap', null, 'blog/blogpost', 'blog/blogadmin'); ?>
</div>
<div class="container" id="bottom-blogadmin-index" style="display: none;">
	<header class="main-header">
		<div class="container">
			<h2 class="page-title">Tous les articles</h1><br/>
		</div>
	</header>
	<div id="blog-posts" class="container top">
		<?php if (isset($blogposts) && $blogposts): ?>
			<?php $this->load->helper('readabledate'); ?>
			<?php foreach ($blogposts as $blogpost): ?>
				<div class="row padding-bottom">
					<div class="col-md-4">
						<a class="thumb" href=""><img class="img-responsive hidden-xs" src="<?php echo base_url($blogpost->image); ?>"></a>
					</div>
					<div class="col-md-8">
						<div class="post-summary">      
							<h3 style="margin-top: 0px;"><a href=""><?php echo htmlspecialchars($blogpost->title) ?></a></h3>
							<p class="text-sm"><?php echo zero_date($blogpost->creation_time); ?> par <?php echo htmlspecialchars($blogpost->author) ?></p>
							<p>
								<?php echo $blogpost->description; ?>
							</p>
							<p>
								<a href="<?php echo base_url('blog/blogadmin/see/' . $blogpost->id); ?>" class="btn btn-default btn-sm">Lire</a>
								<a href="<?php echo base_url('blog/blogadmin/edit/' . $blogpost->id); ?>" class="btn btn-default btn-sm">Modifier</a>
								<a href="#" class="confirm btn btn-danger btn-sm" 
								   data-url="<?php echo base_url('blog/blogadmin/delete/' . $blogpost->id) ?>" 
								   data-header="Suppression d'un article" 
								   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer un article.</p><p>Continuer?</p> ">
									<i class="fa fa-trash-o"></i> Supprimer
								</a>
							</p>

						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php $model_pagination = implode('-', explode('/', $model)); ?>
		<?php echo pagination('blogadmin_pagination', base_url('blog/blogadmin/index/'.$model_pagination)); ?>
	</div>
</div>
