<?php $this->layout->block('scripts')?>
<script src="<?php echo base_url('assets/vendor/js/jscolor.min.js'); ?>"></script>
<?php $this->layout->block();?>

<?php

function configVal($key, $pop)
{
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
					<label for="CodeFont">Thème du syntaxe Highlighter</label>
					<select class="form-control" name="font_code" id="FontCode">
						<option value="a11y-dark">A 11 Y Dark</option>

						<option value="a11y-light">A 11 Y Light</option>

						<option value="agate">Agate</option>

						<option value="an-old-hope">An Old Hope</option>

						<option value="androidstudio">Androidstudio</option>

						<option value="arduino-light">Arduino Light</option>

						<option value="arta">Arta</option>

						<option value="ascetic">Ascetic</option>

						<option value="atelier-cave-dark">Atelier Cave Dark</option>

						<option value="atelier-cave-light">Atelier Cave Light</option>

						<option value="atelier-dune-dark">Atelier Dune Dark</option>

						<option value="atelier-dune-light">Atelier Dune Light</option>

						<option value="atelier-estuary-dark">Atelier Estuary Dark</option>

						<option value="atelier-estuary-light">Atelier Estuary Light</option>

						<option value="atelier-forest-dark">Atelier Forest Dark</option>

						<option value="atelier-forest-light">Atelier Forest Light</option>

						<option value="atelier-heath-dark">Atelier Heath Dark</option>

						<option value="atelier-heath-light">Atelier Heath Light</option>

						<option value="atelier-lakeside-dark">Atelier Lakeside Dark</option>

						<option value="atelier-lakeside-light">Atelier Lakeside Light</option>

						<option value="atelier-plateau-dark">Atelier Plateau Dark</option>

						<option value="atelier-plateau-light">Atelier Plateau Light</option>

						<option value="atelier-savanna-dark">Atelier Savanna Dark</option>

						<option value="atelier-savanna-light">Atelier Savanna Light</option>

						<option value="atelier-seaside-dark">Atelier Seaside Dark</option>

						<option value="atelier-seaside-light">Atelier Seaside Light</option>

						<option value="atelier-sulphurpool-dark">Atelier Sulphurpool Dark</option>

						<option value="atelier-sulphurpool-light">Atelier Sulphurpool Light</option>

						<option value="atom-one-dark-reasonable">Atom One Dark Reasonable</option>

						<option value="atom-one-dark">Atom One Dark</option>

						<option value="atom-one-light">Atom One Light</option>

						<option value="brown-paper">Brown Paper</option>

						<option value="codepen-embed">Codepen Embed</option>

						<option value="color-brewer">Color Brewer</option>

						<option value="darcula">Darcula</option>

						<option value="dark">Dark</option>

						<option value="darkula">Darkula</option>

						<option value="docco">Docco</option>

						<option value="dracula">Dracula</option>

						<option value="far">Far</option>

						<option value="foundation">Foundation</option>

						<option value="github-gist">Github Gist</option>

						<option value="github">Github</option>

						<option value="gml">Gml</option>

						<option value="googlecode">Googlecode</option>

						<option value="grayscale">Grayscale</option>

						<option value="gruvbox-dark">Gruvbox Dark</option>

						<option value="gruvbox-light">Gruvbox Light</option>

						<option value="hopscotch">Hopscotch</option>

						<option value="hybrid">Hybrid</option>

						<option value="idea">Idea</option>

						<option value="ir-black">Ir Black</option>

						<option value="isbl-editor-dark">Isbl Editor Dark</option>

						<option value="isbl-editor-light">Isbl Editor Light</option>

						<option value="kimbie.dark">Kimbie Dark</option>

						<option value="kimbie.light">Kimbie Light</option>

						<option value="lightfair">Lightfair</option>

						<option value="magula">Magula</option>

						<option value="mono-blue">Mono Blue</option>

						<option value="monokai-sublime">Monokai Sublime</option>

						<option value="monokai">Monokai</option>

						<option value="nord">Nord</option>

						<option value="obsidian">Obsidian</option>

						<option value="ocean">Ocean</option>

						<option value="paraiso-dark">Paraiso Dark</option>

						<option value="paraiso-light">Paraiso Light</option>

						<option value="pojoaque">Pojoaque</option>

						<option value="purebasic">Purebasic</option>

						<option value="qtcreator_dark">Qtcreator Dark</option>

						<option value="qtcreator_light">Qtcreator Light</option>

						<option value="railscasts">Railscasts</option>

						<option value="rainbow">Rainbow</option>

						<option value="routeros">Routeros</option>

						<option value="school-book">School Book</option>

						<option value="shades-of-purple">Shades Of Purple</option>

						<option value="solarized-dark">Solarized Dark</option>

						<option value="solarized-light">Solarized Light</option>

						<option value="sunburst">Sunburst</option>

						<option value="tomorrow-night-blue">Tomorrow Night Blue</option>

						<option value="tomorrow-night-bright">Tomorrow Night Bright</option>

						<option value="tomorrow-night-eighties">Tomorrow Night Eighties</option>

						<option value="tomorrow-night">Tomorrow Night</option>

						<option value="tomorrow">Tomorrow</option>

						<option value="vs">Vs</option>

						<option value="vs2015">Vs 2015</option>

						<option value="xcode">Xcode</option>

						<option value="xt256">Xt 256</option>

						<option value="zenburn">Zenburn</option>
					</select>
					<script>
						document.querySelector('#FontCode option[value="<?php echo configVal('font_code', $pop) ?>"]').setAttribute('selected' ,true);
					</script>
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