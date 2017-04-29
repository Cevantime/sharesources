<?php if(is_module_installed('traductions')): ?>
<?php $this->load->helper('form'); ?>
<div class="form-group">
	<label>Langue</label>
	<?php echo form_dropdown(array('class'=>'form-control', 'id'=>'lang', 'name'=>'lang'), array('fr'=>'Français','en'=>'Anglais','ru'=>'Russe'), $lang) ;?>
	<script type="text/javascript">
		$('#lang').change(function(){
			window.location = "<?php echo current_url(); ?>?lang="+$(this).val();
		});
	</script>
</div>
<?php endif; ?>
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
			  name="description" 
			  rows="3" 
			  class="form-control"><?php echo isset($blogpost_add_pop['description']) ? $blogpost_add_pop['description'] : '' ?></textarea>
</div>
<div class="form-group">
	<label>Image</label>
	<span class="input-group">
		<span class="input-group-btn">
			<span class="btn btn-primary btn-file">
				Parcourir…
				<input type="file" id="blog_add_image" name="image" class="form-control file">
			</span>
		</span>
		<input type="text" readonly="" class="form-control">
	</span>
</div>
<div class="form-group">
	<label>Contenu</label>
	<textarea id="blogpost_add_content" 
			  name="content" 
			  rows="10" 
			  class="form-control"><?php echo isset($blogpost_add_pop['content']) ? $blogpost_add_pop['content'] : '' ?></textarea>
</div>

<?php if (isset($blogpost_add_pop['id'])): ?>
	<input type="hidden" value="<?php echo $blogpost_add_pop['id'] ?>" name="id"/>
<?php endif; ?>
<input type="hidden" name="save-<?php echo $model_name ?>" value="1"/>