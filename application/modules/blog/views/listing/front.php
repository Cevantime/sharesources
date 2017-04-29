<div id="blog-posts" class="container top">
	<?php if(isset($blogposts) && $blogposts): ?>
	<?php $this->load->helper('readabledate'); ?>
	<?php foreach($blogposts as $blogpost): ?>
	<div class="row padding-bottom">
		<div class="col-md-4">
			<a class="thumb" href=""><img class="img-responsive hidden-xs" src="<?php echo base_url($blogpost->image); ?>"></a>
		</div>
		<div class="col-md-8">
			<div class="post-summary">      
				<h3 style="margin-top: 0px;"><a href=""><?php echo htmlentities($blogpost->title) ?></a></h3>
				<p class="text-sm"><?php echo zero_date($blogpost->creation_time); ?> par <?php echo htmlentities($blogpost->author); ?></p>
				<p>
					<?php echo htmlentities($blogpost->description); ?>
				</p>
				<p>
					<a href="<?php echo base_url('blog/see/basic/'.$blogpost->id); ?>" class="btn btn-default btn-sm">Lire</a>
					<a href="#" class="confirm btn btn-danger btn-sm" 
					   data-url="<?php echo base_url('blog/see/delete/'.$blogpost->id) ?>" 
					   data-header="Suppression d'un post" 
					   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer un post.</p><p>Continuer?</p> ">
						<i class="fa fa-trash-o"></i> Supprimer
					</a>
				</p>
				
			</div>
		</div>
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
	<?php $model_pagination = implode('-', explode('/', $model)); ?>
	<?php echo pagination_ajax('pagination-blogposts-front-list','#blog-posts',base_url('blog/listing/front/'.$model_pagination)); ?>
</div>