<?php

function userVal($key, $pop) {
	return isset($pop) && isset($pop[$key]) ? $pop[$key] : set_value($key);
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">face</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Mon profil</h4>
				<?php echo form_open_multipart(current_url()); ?>
				<div class="form-group">
					<label for="InputUserName">Prénom<sup>*</sup></label>
					<input type="text" class="form-control" id="InputUserName" name="forname" value="<?php echo userVal('forname', $datas) ?>">
				</div>
				<div class="form-group">
					<label for="InputUserName">Nom<sup>*</sup></label>
					<input type="text" class="form-control" id="InputUserName" name="name" value="<?php echo userVal('name', $datas) ?>">
				</div>
				<div class="form-group">
					<label for="InputUserName">Login<sup>*</sup></label>
					<input type="text" class="form-control" id="InputUserName" name="login" value="<?php echo userVal('login', $datas) ?>">
				</div>

				<div class="form-group">
					<label for="InputEmail">Email<sup>*</sup></label>
					<input type="email" class="form-control" id="InputEmail" name="email" value="<?php echo userVal('email', $datas); ?>">
				</div>
				<div class="form-group">

					<div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="<?php echo base_url(userVal('avatar', $datas) ? userVal('avatar', $datas) : 'assets/local/images/placeholder.jpg'); ?>" alt="...">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
						<div>
							<span class="btn btn-round btn-rose btn-file">
								<span class="fileinput-new">Ajouter une photo</span>
								<span class="fileinput-exists">Changer</span>
								<input type="file" id="tearcher_avatar" name="avatar" class="form-control file">
							</span>
							<br>
							<a href="#" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Enlever</a>
						</div>
					</div>
				</div>
				
				<input type="hidden" name="id" value="<?php echo userVal('id', $datas);
; ?>">
				<input type="hidden" name="save-user" value="1">
				<div class="form-group">
					<label for="InputOldPassword">Ancien mot de passe<sup>*</sup></label>
					<input type="password" class="form-control" id="InputOldPassword" name="oldpassword">
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="InputPassword"><?php if (isset($isEditUser)): ?>Nouveau mot<?php else : ?>Mot<?php endif; ?> de passe<sup>*</sup></label>
							<input type="password" class="form-control" id="InputPassword" name="password">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="InputConfirmPassword">Confirmation du mot de passe<sup>*</sup></label>
							<input type="password" class="form-control" id="InputConfirmPassword" name="passwordconfirm">
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-ar btn-primary pull-right"><i class="fa fa-floppy-o"></i> Enregistrer</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>

