
<?php
$this->load->model('notification');
function notifVal($key, $pop) {
	return isset($pop) && isset($pop[$key]) ? $pop[$key] : set_value($key);
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">message</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Votre notification</h4>
				
				<?php echo form_open(current_url()); ?>
				
				
				<div class="form-group">
					<label for="InputAppTitle">Message de la notification</label>
					<input type="text" class="form-control" id="InputMessageNotif" name="message" value="<?php echo notifVal('message', $pop) ?>">
				</div>
				<div class="form-group">
					<label for="InputAppTitle">Lien de la notification</label>
					<input type="text" class="form-control" id="InputMessageNotif" name="url" value="<?php echo notifVal('url', $pop) ?>">
				</div>
				<div class="form-group">
					<label for="InputAppTitleSmall">Partager</label>
					<?php echo form_dropdown(array('class'=>'form-control', 'name'=> 'visibility'), 
							Notification::$VALUE_LABEL_ASSOC, 
							notifVal('visibility', $pop)); ?>
					
				</div>
				
				<?php if($id = notifVal('id', $pop)): ?>
					<input type="hidden" value="<?php echo $id; ?>" name="id">
				<?php endif; ?>
				<div class="form-group">
					<button type="submit" class="btn btn-ar btn-primary pull-right"><i class="fa fa-floppy-o"></i> Enregistrer</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
