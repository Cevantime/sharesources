
<?php $this->layout->block('css'); ?>
<link href="<?php echo base_url('assets/local/css/wysibb/theme_tutorial.css'); ?>" type="text/css" rel="stylesheet"/>
<?php $this->layout->block(); ?>

<div class="form-group">
	<div class="fileinput fileinput-new text-center" data-provides="fileinput">
		<div class="fileinput-new thumbnail">
			<img src="<?php echo base_url(!empty($blogpost_add_pop['image']) ? $blogpost_add_pop['image'] : 'assets/local/images/image_placeholder.jpg'); ?>" alt="...">
		</div>
		<div class="fileinput-preview fileinput-exists thumbnail"></div>
		<div>
			<span class="btn btn-round btn-rose btn-file">
				<span class="fileinput-new">Sélectionner une bannière</span>
				<span class="fileinput-exists">Changer</span>
				<input type="file" id="blog_add_image" name="image" class="form-control file">
			</span>
			<br>
			<a href="#" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Enlever</a>
		</div>
	</div>
</div>
<div class="form-group">
	<label>Titre</label>
	<input type="text" 
		   id="blog_add_message_title"  
		   name="title" 
		   value="<?php echo isset($blogpost_add_pop['title']) ? $blogpost_add_pop['title'] : '' ?>" 
		   class="form-control">
</div>
<div class="form-group">
	<label>Description</label>
	<textarea id="blogpost_add_description" 
			  name="description_bbcode" 
			  rows="4" 
			  data-module="default_wysibb" 
			  class="form-control"><?php echo isset($blogpost_add_pop['description_bbcode']) ? $blogpost_add_pop['description_bbcode'] : '' ?></textarea>
</div>


<div class="form-group">
	<label for="InputCategory">Catégorie</label>
	<?php $categoriesDropDown = array(); ?>
	<?php $this->load->helper('form'); ?>
	<?php $this->load->model('category'); ?>
	<?php $categories = $this->category->get(); ?>
	<?php if ($categories): ?>
		<?php foreach ($categories as $category): ?>
			<?php $categoriesDropDown[$category->id] = $category->name; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php echo form_dropdown(array('class' => 'form-control', 'id' => 'InputCategory', 'name' => 'category_id'), $categoriesDropDown, isset($blogpost_add_pop['category_id']) ? $blogpost_add_pop['category_id'] : ''); ?>
</div>
<div class="form-group">
	<label>Contenu</label>
	<textarea id="blogpost_add_content" 
			  name="content_bbcode" 
			  rows="10" 
			  class="form-control"><?php echo isset($blogpost_add_pop['content_bbcode']) ? $blogpost_add_pop['content_bbcode'] : '' ?></textarea>
</div>
<div class="form-group">
	<label for="publish-yes">Publier : </label>
	<div class="radio">
		<label>
			<input id="publish-yes" value="1" name="publish" <?php if (isset($blogpost_add_pop['publish']) && $blogpost_add_pop['publish']) { ?> checked<?php } ?> type="radio"><span class="circle"></span><span class="check"></span> Oui
		</label>
	</div>
	<div class="radio">
		<label>
			<input id="publish-no" value="0" name="publish" <?php if (!isset($blogpost_add_pop['publish']) || !$blogpost_add_pop['publish']) { ?> checked<?php } ?> type="radio"><span class="circle"></span><span class="check"></span> Non
		</label>
	</div>

</div>
<div class="form-group">
	<label for="public-yes">Rendre public : </label>
	<div class="radio">
		<label>
			<input id="public-yes" value="1" name="public" <?php if (isset($blogpost_add_pop['public']) && $blogpost_add_pop['public']) { ?> checked<?php } ?> type="radio"><span class="circle"></span><span class="check"></span> Oui
		</label>
	</div>
	<div class="radio">
		<label>
			<input id="public-no" value="0" name="public" <?php if (!isset($blogpost_add_pop['public']) || !$blogpost_add_pop['public']) { ?> checked<?php } ?> type="radio"><span class="circle"></span><span class="check"></span> Non
		</label>
	</div>
</div>
<?php if (user_can('share_to_teacher', 'course', isset($blogpost_add_pop['id']) ? $blogpost_add_pop['id'] : '*')): ?>
	<div class="form-group">
		<label>Ce cours est partagé aux formateurs suivants</label>
		<textarea id="blogpost_shares" 
				  name="teacher_shares" 
				  rows="10" 
				  data-module="select_teachers"
				  class="select-teachers"><?php echo isset($blogpost_add_pop['teacher_shares']) ? $blogpost_add_pop['teacher_shares'] : '' ?></textarea>
	</div>
<?php endif; ?>
<div class="form-group" data-module="add_file_to_post">
	<label>
		Ajouter des fichiers
	</label>
	<?php $this->load->helper('filerender'); ?>
	<a href="#" class="btn btn-success" id="add-files">Ajouter un fichier</a>
	<input name="files[]" value="" type="hidden"> 
	<?php if (isset($blogpost_add_pop['files']) && $blogpost_add_pop['files']): ?>
		<?php foreach ($blogpost_add_pop['files'] as $file): ?>
			<div>
				<input name="files[]" value="<?php echo $file->id; ?>" type="hidden"> 
				<a class="do-close" href="#">
					<i class="fa fa-close"></i>
				</a> 
				<i class="fa <?php echo filefaclass(json_decode($file->infos)); ?>"></i> <?php echo $file->name; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<div class="form-group" data-module="show_input_len">
	<label>Mot clés</label>
	<textarea id="blogpost_add_keywordss" 
			  name="keywords" 
			  rows="4" 
			  data-limit="255" 
			  class="form-control input-tracked"><?php echo isset($blogpost_add_pop['keywords']) ? $blogpost_add_pop['keywords'] : '' ?></textarea>
			  <small class="text-muted input-len"><span class="input-len-num"></span> caractères <span class="input-remaining">restants</span><span class="input-exceeded">à supprimer</span></small><br>
			  <small class="text-muted">Ce champs sert au référencement dans le moteur de recherche <strong>local</strong></small>
</div>
<div class="form-group" data-module="select_tags">

	<label>Tags</label>

	<textarea id="blogpost_add_tags" 
			  name="tags" 
			  data-limit="255" 
			  rows="4" 
			  class="to-selectize input-tracked"><?php echo isset($blogpost_add_pop['tags']) ? $blogpost_add_pop['tags'] : '' ?></textarea>
	<small class="text-muted">Si un tag n'existe pas, il sera automatiquement rajouté</small>

</div>
<br>
<?php if (isset($blogpost_add_pop['id'])): ?>
	<input type="hidden" value="<?php echo $blogpost_add_pop['id'] ?>" name="id"/>
<?php endif; ?>
<input type="hidden" name="save-<?php echo $model_name ?>" value="1"/>
