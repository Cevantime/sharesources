<?php $this->layout->block('scripts') ?>
<script src="<?php echo base_url('assets/vendor/js/jscolor.min.js'); ?>"></script>
<?php $this->layout->block(); ?>

<?php

function configVal($key, $pop) {
	return isset($pop) && isset($pop[$key]) ? $pop[$key] : set_value($key);
}

?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">settings</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">La configuration</h4>
				
				<?php echo form_open_multipart(current_url()); ?>
				<div class="form-group">

					<div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail">
							<img src="<?php echo base_url(configVal('app_login_image', $pop) ? configVal('app_login_image', $pop) : 'assets/local/images/image_placeholder.jpg'); ?>" alt="...">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail"></div>
						<div>
							<span class="btn btn-round btn-rose btn-file">
								<span class="fileinput-new">Ajouter une image de fond pour la page de connexion</span>
								<span class="fileinput-exists">Changer</span>
								<input type="file" id="InputAppBigImage" name="app_login_image" class="form-control file">
							</span>
							<br>
							<a href="#" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Enlever</a>
						</div>
					</div>
				</div>
				<div class="form-group">

					<div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="<?php echo base_url(configVal('app_sidebar_image', $pop) ? configVal('app_sidebar_image', $pop) : 'assets/local/images/placeholder.jpg'); ?>" alt="...">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
						<div>
							<span class="btn btn-round btn-rose btn-file">
								<span class="fileinput-new">Ajouter une image de fond pour la sidebar</span>
								<span class="fileinput-exists">Changer</span>
								<input type="file" id="InputAppSmallImage" name="app_sidebar_image" class="form-control file">
							</span>
							<br>
							<a href="#" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Enlever</a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="InputAppTitle">Titre de l'application</label>
					<input type="text" class="form-control" id="InputAppTitle" name="app_title" value="<?php echo configVal('app_title', $pop) ?>">
				</div>
				<div class="form-group">
					<label for="InputAppTitleSmall">Titre raccourci (2 lettres max.)</label>
					<input type="text" class="form-control" id="InputAppTitleSmall" name="app_title_small" value="<?php echo configVal('app_title_small', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputColor1">Couleur principale</label>
					<input type="text" class="form-control jscolor" id="InputColor1" name="color1" value="<?php echo configVal('color1', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputColor2">Couleur secondaire</label>
					<input type="text" class="form-control jscolor" id="InputColor2" name="color2" value="<?php echo configVal('color2', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputSignatureForEmail">Signature des emails automatiques</label>
					<input type="text" class="form-control" id="InputSignatureForEmail" name="signature_for_mailing" value="<?php echo configVal('signature_for_mailing', $pop) ?>">
				</div>
				<div class="form-group">
					<label for="InputEmailForMailing">Email de provenance des emails automatiques</label>
					<input type="email" class="form-control" id="InputEmailForMailing" name="email_for_mailing" value="<?php echo configVal('email_for_mailing', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputNameForMailing">Nom de provenance des emails automatiques</label>
					<input type="text" class="form-control" id="InputNameForMailing" name="name_for_mailing" value="<?php echo configVal('name_for_mailing', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputUserSmtp">Email smtp</label>
					<input type="email" class="form-control" id="InputUserSmtp" name="email_smtp" value="<?php echo configVal('email_smtp', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputPasswordSmtp">Mot de passe smtp</label>
					<input type="password" class="form-control" id="InputPasswordSmtp" name="password_smtp" value="<?php echo configVal('password_smtp', $pop); ?>">
				</div>
				<div class="form-group">
					<label for="InputHostSmtp">Hôte smtp</label>
					<input type="text" class="form-control" id="InputUserSmtp" name="host_smtp" placeholder="smtp.googlemail.com" value="<?php echo configVal('host_smtp', $pop); ?>">
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-ar btn-primary pull-right"><i class="fa fa-floppy-o"></i> Enregistrer</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>