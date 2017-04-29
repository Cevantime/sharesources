<?php if (is_module_installed('traductions')): ?>
	<?php $this->load->helper('form'); ?>
	<p>
		<?php echo form_dropdown(array('id' => 'lang', 'name' => 'lang'), array('fr' => 'Français', 'en' => 'Anglais', 'ru' => 'Russe'), $lang); ?>
		<script type="text/javascript">
			$('#lang').change(function () {
				window.location = "<?php echo current_url(); ?>?lang=" + $(this).val();
			});
		</script>
	</p>
<?php endif; ?>
<p><label>Titre :</label> <input type="text" id="blog_add_message_title" name="title" value="<?php echo isset($blogpost_add_pop['title']) ? $blogpost_add_pop['title'] : '' ?>"/></p>
<p><label>Description :</label><textarea id="blogpost_add_description" name="description"><?php echo isset($blogpost_add_pop['description']) ? $blogpost_add_pop['description'] : '' ?></textarea></p>
<p><label>Image :</label><input type="file" id="blog_add_image" name="image"/></p>
<p><label>Contenu :</label><textarea id="blogpost_add_content" name="content"><?php echo isset($blogpost_add_pop['content']) ? $blogpost_add_pop['content'] : '' ?></textarea></p>
	<?php if (isset($blogpost_add_pop['id'])): ?>
	<input type="hidden" value="<?php echo $blogpost_add_pop['id'] ?>" name="id"/>
<?php endif; ?>
<input type="hidden" value="save-<?php echo $model_name ?>" value="1" name="blogpost_id"/>

