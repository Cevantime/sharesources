
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-icon" data-background-color="normal">
				<i class="material-icons">apps</i>
			</div>
			<div class="card-content">
				<h4 class="card-title">Les catégories</h4>
				<div class="toolbar">
					<a class="btn btn-primary" href="<?php echo base_url('categories/add'); ?>"><i class="fa fa-plus"></i> Ajouter une catégorie</a>

				</div>
				<div class="responsive-table">
					<table id="tableCategories" class="table table-responsive table-striped datatabled" data-module="datatable">  
						<thead>  
							<tr>  
								<th>#</th>  
								<th>Image</th>  
								<th>Nom</th>  
								<th>Description</th>  
								<th>Action</th>  
							</tr>  
						</thead>  
						<tbody>  
							<?php if ($categories): ?>
								<?php foreach ($categories as $category): ?>
									<tr>  
										<td><?php echo $category->id; ?></td>  
										<td><img src="<?php echo imageresize($category->image, 30,30,true); ?>" 
												 alt="Image de la catégorie <?php echo strip_tags($category->name); ?>"/></td>  
										<td><?php echo htmlentities($category->name); ?></td>  
										<td><?php echo htmlentities($category->description); ?></td>  
										<td class="td-actions">
											<?php if(user_can('edit','category',$category->id)): ?>
											<a class="btn btn-success" href="<?php echo base_url('categories/edit/' . $category->id) ?>"><i class="fa fa-pencil"></i></a>
											<?php endif; ?>
											<?php if(user_can('delete','category',$category->id)): ?>
											<a class="btn btn-danger confirm" href="#" 
											   data-url="<?php echo base_url('categories/delete/' . $category->id) ?>" 
											   data-header="Suppression d'une Catégorie" 
											   data-body="<p>Attention!</p><p>Vous êtes sur le point de supprimer une categorie.</p><p>Continuer?</p> ">
												<i class="fa fa-trash-o"></i>
											</a>
											<?php endif; ?>
										</td>  
									</tr>  
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>