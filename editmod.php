<?php
session_start();
define ('SESSION_TIMEOUT', "1800"); // définition du timeout de la session (ici , apres 1800 sec -> deco ! )
include_once('includes/header.php');
?>
	<h2>Bienvenue sur l'EditMod</h2>
<?php
if(isset($_SESSION['KEY'])){
	$connect = $bdd->prepare('SELECT * FROM utilisateurs WHERE Session = ?');
	$connect->execute(array($_SESSION['KEY']));
	$utilisateur = $connect->fetch();
	if(count($utilisateur['ID']) != 1){		
		?>
		<h3>Accès refusé</h3>
		<p>Connectez vous pour continuer</p>
		<p><a href="login.php"><button type="button" class="btn btn-default">Connexion</button></a></p>
		<?php
		include_once('includes/footer.php');
		exit();
	}else{
		
		
		
	
		if(isset($_GET['article'])){ //Partie article
			if($_GET['article']){ //Création d'un article ou edition
			$editmod = FALSE;
				if(is_numeric($_GET['article'])){
					$article = $bdd->prepare('SELECT * FROM articles WHERE ID = ?');
					$article->execute(array($_GET['article']));
					$articleShow = $article->fetch();
					if($articleShow['ID'] != NULL){  //Si l'article existe, les modifications dessus sont possibles
						$editmod = TRUE;
						
						$recupPageArticle = $bdd->prepare("SELECT * FROM pages WHERE ID = ?");
						$recupPageArticle->execute(array($articleShow['IDPage']));
						$recupPageArticleDonnees = $recupPageArticle->fetch();
						$recupPageArticle->closeCursor();
						if(isset($_GET['view'])){ // Si l'utilisateur souhaite visualiser la page
							?>
							<h2><?php echo $articleShow['Titre']; ?></h2>
							<article><?php echo $articleShow['Contenu']; ?></article>
							
							<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
							<p><a href="editmod.php?article=<?php echo $_GET['article']; ?>"><button type="button" class="btn btn-warning">Modifier</button></a></p>
							<?php
							include_once('includes/footer.php');
							exit();
						}
						if(isset($_GET['etat'])){ //Activation ou désactivation d'un article
							if($_GET['article'] == 1){
								?>
								<h4>Impossible de désactiver la page d'accueil !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>	
								<?php
								include_once('includes/footer.php');
								exit();			
							}
							if($articleShow['Etat'] == 0 AND $recupPageArticleDonnees['Etat'] == 1){
								$activationArticle = 1;
								?>
								<h4>Article activé !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<?php
							}
							else{
								$activationArticle = 0;
								?>
								<h4>Article désactivé !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<p>nb : Un article peut être activé si sa page associée est aussi activée</p>
								<?php
							}
							$updateEtat = $bdd->prepare("UPDATE articles SET Etat = ? WHERE ID = ?");
							$updateEtat->execute(array(
								$activationArticle,
								$articleShow['ID']
							));
							
							include_once('includes/footer.php');
							exit();
						}
						
						if(isset($_GET['epingle'])){ //epingler page à l'accueil
							if($_GET['article'] == 1){
								?>
								<h4>Page déjà épinglée</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>	
								<?php
								include_once('includes/footer.php');
								exit();			
							}
							if($articleShow['EpingleAccueil'] == 0 AND $recupPageArticleDonnees['Etat'] == 1){
								$EpingleAccueil = 1;
								?>
								<h4>Article épinglée !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<?php
							}
							else{
								$EpingleAccueil = 0;
								?>
								<h4>Article enlevé de l'accueil !</h4>
								<p>nb : Un article peut être ép si sa page associée est aussi activée</p>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<?php
							}
							$updateEtat = $bdd->prepare("UPDATE articles SET EpingleAccueil = ? WHERE ID = ?");
							$updateEtat->execute(array(
								$EpingleAccueil,
								$articleShow['ID']
							));
							
							include_once('includes/footer.php');
							exit();
						}						
						
						
						
						
						
						
						
						
						
						
						if(isset($_GET['delete'])){ //Suppression d'un article
							if($_GET['article'] == 1){
								?>
								<h4>Impossible de supprimer la page d'accueil !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>	
								<?php
								include_once('includes/footer.php');
								exit();			
							}
							$deleteArticle = $bdd->prepare("DELETE FROM articles WHERE ID = ?");
							$deleteArticle->execute(array($articleShow['ID']));
							?>
								<h4>Article supprimé !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>						
							<?php
							include_once('includes/footer.php');
							exit();
						}
						
						if(isset($_POST['hello'])){ //Modification d'un article existant
						
						
						
							if($_GET['article'] == 1){ //Modification de la page d'accueil
								$updateEtat = $bdd->prepare("UPDATE articles SET Titre = :titre, Contenu = :contenu, DerniereEdition = CURRENT_TIMESTAMP WHERE ID = 1");
								$updateEtat->execute(array(
									'titre' => $_POST['titre'],
									'contenu' => $_POST['contenu']
								));		
								?>
								<h4>Page d'accueil modifiée</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<?php
								include_once('includes/footer.php');
								exit();
							}
							
							

							$updateEtat = $bdd->prepare("UPDATE articles SET Titre = :titre, Contenu = :contenu, IDPage = :page, DerniereEdition = CURRENT_TIMESTAMP WHERE ID = :id");
							$updateEtat->execute(array(
								'titre' => $_POST['titre'],
								'contenu' => $_POST['contenu'],
								'page' => $_POST['page'],
								'id' => $articleShow['ID']
							));		
							?>
							<h4>Article modifié</h4>
							<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
							<p><a href="editmod.php?article=<?php echo $_GET['article']; ?>&view"><button type="button" class="btn btn-default">Visualiser</button></a></p>
							<p>nb : Un article peut être activé si sa page associée est aussi activée</p>
							<?php
							
							include_once('includes/footer.php');
							exit();
						}
						
					}
					$article->closeCursor();
				}
				
				
				if(isset($_POST['hello'])){ //Création d'un nouvel article 
					$newArticle = $bdd->prepare("INSERT INTO articles(IDPage, IDUser, Titre, Contenu, DateHeure, Etat) VALUES(:idpage, :iduser, :titre, :contenu, CURRENT_TIMESTAMP, 0)");
					$newArticle->execute(array(
						'idpage' => $_POST['page'],
						'iduser' => $utilisateur['ID'],
						'titre' => $_POST['titre'],
						'contenu' => $_POST['contenu']
					));
					?>
					<h4>Article créé</h4>
					<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
					<p>nb : Activation manuelle requis</p>
					<?php
				
				
					include_once('includes/footer.php');
					exit();
				}
				
				
				
			}




			
				?>
				
				
				<?php if($editmod == TRUE){?><h3>Edition d'un article</h3><?php }else{ ?> <h3>Création d'un article</h3> <?php } ?>
				<form role="form" action="editmod.php?article=<?php echo $_GET['article']; ?>" method="POST">
					<div class="form-group">
						<label for="titre">Titre</label>
						<input type="text" class="form-control" name="titre" <?php if($editmod == TRUE){?>value="<?php echo $articleShow['Titre']; ?>"<?php } ?>>
					</div>
					<div class="form-group">
						<label for="titre">Contenu en HTML</label>
						<textarea class="form-control" rows="10" name="contenu"><?php if($editmod == TRUE){ echo $articleShow['Contenu']; }else{ ?><p> </p><?php } ?></textarea>
					</div>
					<div class="form-group">
						<label for="sel1">Selectionnez une page :</label>
						<select class="form-control" id="page" name="page">
						<?php 
						$LSTPage = $bdd->query("SELECT * FROM pages");
						while($ReqLSTPage = $LSTPage->fetch()){
						?>
							<option value="<?php echo $ReqLSTPage['ID']; ?>">#<?php echo $ReqLSTPage['ID']; ?> | <?php echo $ReqLSTPage['Titre']; ?></option>
						<?php
						}
						$LSTPage->closeCursor();
						?>
						</select>
					</div>
					<input type="hidden" name="hello" value="1">
					<button type="submit" class="btn btn-warning">Enregistrer</button>
					<a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a>
				</form>			
				
				
				
				
				<?php

			
			
			
			include_once('includes/footer.php');
			exit();
		}
		
		
		
		if(isset($_GET['page'])){ //Partie page
			if($_GET['page']){ //Création d'une page ou edition
			$editmod = FALSE;
				if(is_numeric($_GET['page'])){
					$page = $bdd->prepare('SELECT * FROM pages WHERE ID = ?');
					$page->execute(array($_GET['page']));
					$pageShow = $page->fetch();
					if($pageShow['ID'] != NULL){  //Si la page existe, les modifications dessus sont possibles
						$editmod = TRUE;		
		
						if(isset($_GET['etat'])){ //Activation ou désactivation d'une page
							if($pageShow['Etat'] == 0){
								$activationPage = 1;
								?>
								<h4>Page activée !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<?php
							}
							else{
								$activationPage = 0;
								?>
								<h4>Article désactivé !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
								<p>nb : Les articles associés ont été désactivés.</p>
								<?php
							}
							$updateEtat = $bdd->prepare("UPDATE pages SET Etat = ? WHERE ID = ?");
							$updateEtat->execute(array(
								$activationPage,
								$pageShow['ID']
							));
							if($activationPage == 0){
								$updateEtatArticle = $bdd->prepare("UPDATE articles SET Etat = 0 WHERE IDPage = ?");
								$updateEtatArticle->execute(array($pageShow['ID']));									
							}
						
							
							include_once('includes/footer.php');
							exit();
						}
						
						
						if(isset($_GET['delete'])){ //Suppression d'une page
							$deletePage = $bdd->prepare("DELETE FROM pages WHERE ID = ?");
							$deletePage->execute(array($pageShow['ID']));
							$updateEtatArticle = $bdd->prepare("UPDATE articles SET Etat = 0 WHERE IDPage = ?");
							$updateEtatArticle->execute(array($pageShow['ID']));								
							?>
								<h4>Page supprimée !</h4>
								<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>	
								<p>nb : Les articles associés ont été désactivés.</p>								
							<?php
							include_once('includes/footer.php');
							exit();
						}
						if(isset($_POST['hello'])){ //Modification d'une page existant


							$updateEtat = $bdd->prepare("UPDATE pages SET Titre = :titre WHERE ID = :id");
							$updateEtat->execute(array(
								'titre' => $_POST['titre'],
								'id' => $pageShow['ID']
							));		
							?>
							<h4>Page modifiée</h4>
							<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
							<?php
							
							include_once('includes/footer.php');
							exit();
						}
						
					}
					$page->closeCursor();						
				}
				
				if(isset($_POST['hello'])){ //Création d'une nouvelle page

					$newPage = $bdd->prepare("INSERT INTO pages(Etat, Titre) VALUES(0, ?)");
					$newPage->execute(array($_POST['titre']));					
					?>
					<h4>Page créée</h4>
					<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>
					<p>nb : Activation manuelle requis</p>
					<?php
				
				
					include_once('includes/footer.php');
					exit();
				}
				
			}
			?>
			
			
				
				<?php if($editmod == TRUE){?><h3>Edition d'une page</h3><?php }else{ ?> <h3>Nouvelle page</h3> <?php } ?>
				<form role="form" action="editmod.php?page=<?php echo $_GET['page']; ?>" method="POST">
					<div class="form-group">
						<label for="titre">Titre</label>
						<input type="text" class="form-control" name="titre" <?php if($editmod == TRUE){?>value="<?php echo $pageShow['Titre']; ?>"<?php } ?>>
					</div>
					<input type="hidden" name="hello" value="1">
					<button type="submit" class="btn btn-warning">Enregistrer</button>
					<a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a>
				</form>		


				
			
			<?php
			include_once('includes/footer.php');
			exit();	
		}
		
		
		
		if(isset($_GET['configuration'])){ //Partie configuration du site
		
		
			if(isset($_POST['hello'])){ //mise à jour de la configuration
			
			
				$majConfig = $bdd->prepare('UPDATE configuration SET HeadTitle = :HeadTitle, HeadDescription = :HeadDescription, HeadKeywords = :HeadKeywords, HeadNavBarColor = :HeadNavBarColor, HeadNavBarWordColor = :HeadNavBarWordColor, HeadNavBarSelectWordColor = :HeadNavBarSelectWordColor, TitrePAccueil = :TitrePAccueil, TitrePrincipal = :TitrePrincipal, SousTitre = :SousTitre, CopyFooter = :CopyFooter WHERE ID = 1');
				$majConfig->execute(array(
					'HeadTitle' => $_POST['HeadTitle'],
					'HeadDescription' => $_POST['HeadDescription'],
					'HeadKeywords' => $_POST['HeadKeywords'],
					'HeadNavBarColor' => $_POST['HeadNavBarColor'],
					'HeadNavBarWordColor' => $_POST['HeadNavBarWordColor'],
					'HeadNavBarSelectWordColor' => $_POST['HeadNavBarSelectWordColor'],
					'TitrePAccueil' => $_POST['TitrePAccueil'],
					'TitrePrincipal' => $_POST['TitrePrincipal'],
					'SousTitre' => $_POST['SousTitre'],
					'CopyFooter' => $_POST['CopyFooter']
				));
			
			
			
			
			
				?>
				<h4>Mise à jour effectué !</h4>
				<p><a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a></p>									
				<?php

				include_once('includes/footer.php');
				exit();
			}
		
		?>		
				
				<h3>Edition de la configuration du site</h3>
				<form role="form" action="editmod.php?configuration" method="POST">
					<div class="form-group">
						<label for="HeadTitle">Titre du site (balise : title)</label>
						<input type="text" class="form-control" name="HeadTitle" value="<?php echo $configuration['HeadTitle']; ?>">
					</div>
					
					<div class="form-group">
						<label for="HeadDescription">Description du site (balise : description)</label>
						<input type="text" class="form-control" name="HeadDescription" value="<?php echo $configuration['HeadDescription']; ?>">
					</div>	

					<div class="form-group">
						<label for="HeadKeywords">Mots-clés du site (balise : keywords)</label>
						<input type="text" class="form-control" name="HeadKeywords" value="<?php echo $configuration['HeadKeywords']; ?>">
					</div>	

					<div class="form-group">
						<label for="HeadNavBarColor">Couleur de la Navbar</label>
						<input type="text" class="form-control" name="HeadNavBarColor" value="<?php echo $configuration['HeadNavBarColor']; ?>">
					</div>	

					<div class="form-group">
						<label for="HeadNavBarWordColor">Couleur des mots de la navbar</label>
						<input type="text" class="form-control" name="HeadNavBarWordColor" value="<?php echo $configuration['HeadNavBarWordColor']; ?>">
					</div>	

					<div class="form-group">
						<label for="HeadNavBarSelectWordColor">Couleur lors de la sélection</label>
						<input type="text" class="form-control" name="HeadNavBarSelectWordColor" value="<?php echo $configuration['HeadNavBarSelectWordColor']; ?>">
					</div>						
					
					<div class="form-group">
						<label for="TitrePAccueil">Titre d'accueil dans la navbar</label>
						<input type="text" class="form-control" name="TitrePAccueil" value="<?php echo $configuration['TitrePAccueil']; ?>">
					</div>						
					
					<div class="form-group">
						<label for="TitrePrincipal">Titre principal</label>
						<input type="text" class="form-control" name="TitrePrincipal" value="<?php echo $configuration['TitrePrincipal']; ?>">
					</div>						
					
					<div class="form-group">
						<label for="SousTitre">Sous-titre</label>
						<input type="text" class="form-control" name="SousTitre" value="<?php echo $configuration['SousTitre']; ?>">
					</div>

					<div class="form-group">
						<label for="CopyFooter">Footer</label>
						<code><?php echo $configuration['CopyFooter']; ?></code>
						<input type="text" class="form-control" name="CopyFooter" value="<?php echo $configuration['CopyFooter']; ?>">
					</div>			
					
					
					<input type="hidden" name="hello" value="1">
					<button type="submit" class="btn btn-warning">Enregistrer</button>
					<a href="editmod.php"><button type="button" class="btn btn-default">Retour</button></a>
				</form>		


				
			
			<?php
			include_once('includes/footer.php');
			exit();			

		}
		
		?>
	
	    <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-xs-12">
                <h3>Que faire ?</h3>
				<a href="editmod.php?article=nouveau"><button type="button" class="btn btn-default">Rédiger un nouvel article</button></a>
				<a href="editmod.php?page=nouveau"><button type="button" class="btn btn-default">Créer une nouvelle page</button></a>
				<a href="editmod.php?configuration"><button type="button" class="btn btn-default">Modifier configuration du site</button></a>
				<a href='logout.php'><button type="button" class="btn btn-default">Déconnexion</button></a>
			</div>
        </div>
        <!-- /.row -->
	
		<hr />
        <!-- Portfolio Item Row -->
        <div class="row">
			<div class="col-xs-12">
				<h3>Liste des articles</h3>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="row">#ID</th>
								<th>#Page</th>
								<th>Date | Heure publication</th>
								<th>Titre</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
						
						<?php
						$article = $bdd->query("SELECT * FROM articles");
						while($AFFARTICLE = $article->fetch()){
						if($AFFARTICLE['Etat'] == 1){ $class = "success"; }else{ $class = "danger"; }
						if($AFFARTICLE['EpingleAccueil'] == 1){ $class = "info"; }
						?>
							<tr class="<?php echo $class; ?>">
								<td><?php echo $AFFARTICLE['ID']; ?></td>
								<td>
									#<?php 
									echo $AFFARTICLE['IDPage']; 
									?>
									-
									<?php
									$articlePage = $bdd->query("SELECT Titre FROM pages WHERE ID = ".$AFFARTICLE['IDPage']);
									$AFFArticlePage= $articlePage->fetch();
									$articlePage->closeCursor();
									echo $AFFArticlePage['Titre'];
									?>
								</td>
								<td><?php echo $AFFARTICLE['DateHeure']; ?></td>
								<td><?php echo $AFFARTICLE['Titre']; ?></td> 
								<td>
									<p><a href="editmod.php?article=<?php echo $AFFARTICLE['ID']; ?>&etat"><?php if($AFFARTICLE['Etat'] == 1 ){ ?><button type="button" class="btn btn-danger">Désactiver</button><?php }else{ ?><button type="button" class="btn btn-success">Activer</button><?php } ?></a></p>
									<p><a href="editmod.php?article=<?php echo $AFFARTICLE['ID']; ?>&epingle"><?php if($AFFARTICLE['EpingleAccueil'] == 1 ){ ?><button type="button" class="btn btn-danger">Enlever de l'accueil</button><?php }else{ ?><button type="button" class="btn btn-success">Epingler</button><?php } ?></a></p>
									<p><a href="editmod.php?article=<?php echo $AFFARTICLE['ID']; ?>"><button type="button" class="btn btn-warning">Modifier</button></a></p>
									<p><a href="editmod.php?article=<?php echo $AFFARTICLE['ID']; ?>&view"><button type="button" class="btn btn-primary">Visualiser</button></a></p>
									<p><a class="btn btn-danger" href="#" onclick="confirmationdeleteaction('article', '<?php echo $AFFARTICLE['ID']; ?>');">Supprimer</a></p>
								</td>
							</tr>
						<?php
						}
						$article->closeCursor();
						?>
						
						</tbody>
					</table>
				</div>
			
			</div>
        </div>
        <!-- /.row -->
	
		<hr />
        <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-xs-12">
                <h3>Liste des pages</h3>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th scope="row">#ID</th>
								<th>Titre</th>
								<th>Actions</th>
								</tr>
						</thead>
						<tbody>		
						<?php
						$page = $bdd->query("SELECT * FROM pages");
						while($AFFPAGE = $page->fetch()){
						if($AFFPAGE['Etat'] == 1){ $class = "success"; }else{ $class = "danger"; }
						?>
							<tr class="<?php echo $class; ?>">
								<td><?php echo $AFFPAGE['ID']; ?></td>
								<td><?php echo $AFFPAGE['Titre']; ?></td>
								<td>
									<p><a href="editmod.php?page=<?php echo $AFFPAGE['ID']; ?>&etat=<?php echo $AFFPAGE['ID']; ?>"><?php if($AFFPAGE['Etat'] == 1 ){ ?><button type="button" class="btn btn-danger">Désactiver</button><?php }else{ ?><button type="button" class="btn btn-success">Activer</button><?php } ?></a></p>
									<p><a href="editmod.php?page=<?php echo $AFFPAGE['ID']; ?>"><button type="button" class="btn btn-warning">Modifier</button></a></p>
									<p><a class="btn btn-danger" href="#" onclick="confirmationdeleteaction('page', '<?php echo $AFFPAGE['ID']; ?>');">Supprimer</a></p>
								</td>
							</tr>
						<?php
						}
						$page->closeCursor();
						?>
						
						</tbody>
					</table>
				</div>	
			</div>
        </div>
        <!-- /.row -->	

		<?php
		include_once('includes/footer.php');
		exit();		
	}

	
}
else{
	?>
	<h3>Accès refusé</h3>
	<p>Connectez vous pour continuer</p>
	<p><a href="login.php"><button type="button" class="btn btn-default">Connexion</button></a></p>	
	<?php
	include_once('includes/footer.php');
	exit();
}