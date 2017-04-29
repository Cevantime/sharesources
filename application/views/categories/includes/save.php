
<?php $this->layout->block('scripts') ?>
<script src="<?php echo base_url('assets/vendor/js/jscolor.min.js'); ?>"></script>
<?php $this->layout->block(); ?>
<?php

function categoryVal($datas, $key) {
	if (!isset($datas) || !isset($datas[$key]) || !$datas[$key])
		return '';
	return $datas[$key];
}
?>
<?php $this->load->helper('form'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">apps</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">La catégorie</h4>
				<div class="toolbar">
					<a href="<?php echo base_url('categories/all'); ?>" class="btn btn-primary">Revenir à la liste des categories</a>
				</div>
				<?php echo form_open_multipart(current_url()); ?>
				<?php if (is_module_installed('traductions')): ?>
					<div  class="form-group">
						<?php echo form_dropdown(array('class' => 'form-control', 'id' => 'lang', 'name' => 'lang'), array('fr' => 'Français', 'en' => 'Anglais', 'ru' => 'Russe'), $lang); ?>
						<script type="text/javascript">
							$('#lang').change(function () {
								window.location = "<?php echo current_url(); ?>?lang=" + $(this).val();
							});
						</script>
					</div>
				<?php endif; ?>
				<div class="form-group">
					<div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail">
							<img src="<?php echo base_url(categoryVal($datas, 'image') ? categoryVal($datas, 'image') : 'assets/local/images/image_placeholder.jpg'); ?>" alt="...">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail"></div>
						<div>
							<span class="btn btn-round btn-rose btn-file">
								<span class="fileinput-new">Sélectionner une bannière</span>
								<span class="fileinput-exists">Changer</span>
								<input type="file" id="InputImage" name="image" class="form-control file">
							</span>
							<br>
							<a href="#" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Enlever</a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="InputName">Nom</label>
					<input class="form-control" id="InputName" type="text" name="name" value="<?php echo categoryVal($datas, 'name') ?>" />
				</div>
				<div class="form-group">
					<label for="InputDescription">Description</label>
					<input class="form-control" id="InputDescription" type="text" name="description" value="<?php echo categoryVal($datas, 'description') ?>" />
				</div>
				<div class="form-group">
					<label for="InputColor">Couleur</label>
					<input class="form-control jscolor" id="InputColor" type="text" name="color" value="<?php echo categoryVal($datas, 'color') ?>" />
				</div>


				<input type="hidden" name="save-category" value="1"/>
				<?php if (categoryVal($datas, 'id')): ?>
					<input type="hidden" name="id" value="<?php echo categoryVal($datas, 'id'); ?>"/>
				<?php endif; ?>

				<button class="btn btn-primary"><i class="fa fa-save"></i> Enregistrer</button> <br/><br/>

				</form>
			</div>
		</div>
	</div>
</div>

