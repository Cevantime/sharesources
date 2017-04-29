Cher <?php echo $teacher->forname.' '.$teacher->name; ?>,<br>
<br/>
Vous avez été ajouté à l'application <?php echo htmlspecialchars(config('app_title', 'Resources')); ?> en tant que formateur.<br/>
<a href="<?php echo base_url(); ?>">Connectez-vous</a> dès à présent à l 'application pour bénéficier des cours ajoutés par notre communauté de formateurs ! <br/>
<br/>
IMPORTANT : Veuillez prendre note de vos identifiants !<br/>
<br/>
Login : <em><?php echo $teacher->login; ?></em><br/>
Mot de passe : <em><?php echo $plainPassword; ?></em><br/>
<br/>
Il vous seront demandés lors de votre connexion. <em>Notez</em> bien votre 
<strong>mot de passe</strong> car celui-ci ne pourra pas vous être restitué en 
cas d'oubli.<br> 
Une fois connecté vous pourrez modifier ces identifiants en accédant à votre <a href="<?php echo base_url('teacher/profile'); ?>">profil.</a>
<br/>
<br/>
Bien cordialement,<br/>
<?php echo htmlspecialchars(config('signature_for_mailing', translate('L\'administration'))); ?>