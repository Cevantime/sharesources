<?php echo form_open(current_url(), array('role' => 'form')); ?>
	<div class="card card-login card-hidden">
		<div class="card-header text-center" data-background-color="rose">
			<h4 class="card-title">Connectez-vous</h4>
		</div>

		<div class="card-content">
			<div class="input-group">
				<span class="input-group-addon">
					<i class="material-icons">face</i>
				</span>
				<div class="form-group label-floating">
					<label class="control-label">Nom d'utilisateur</label>
					<input type="text" class="form-control"  name="login" type="text" autofocus>
				</div>
			</div>
			
			<div class="input-group">
				<span class="input-group-addon">
					<i class="material-icons">lock_outline</i>
				</span>
				<div class="form-group label-floating">
					<label class="control-label">Mot de passe</label>
					<input type="password" class="form-control" name="password" type="password" value="">
				</div>
			</div>
			<input type="hidden" name="login-user" value="1"/>
		</div>
		<div class="footer text-center">
			<button type="submit" class="btn btn-rose btn-simple btn-wd btn-lg">C'est parti !</button>
		</div>
	</div>
</form>
