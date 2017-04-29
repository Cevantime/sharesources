<?php if($sessions): ?>
<select data-module="select_session" class="form-control input-sm session-select">
	<?php foreach($sessions as $session): ?>
		<option value="<?php echo $session->id; ?>"<?php if(teachsession('id') == $session->id){ echo ' selected="selected"' ; } ?>>
			<?php echo $session->name; ?>
		</option>
	<?php endforeach; ?>
</select>
<?php endif; ?>
