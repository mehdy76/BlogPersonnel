<?php
include_once('includes/header.php');


if(isset($_POST['recherche'])){
	?>
	<h2>Votre recherche :</h2>
	<?php
	if($_POST['recherche'] AND strlen($_POST['recherche']) >= 3){
		$viewArticle = $bdd->prepare('SELECT *, DAY(DateHeure) AS jour, MONTH(DateHeure) AS mois, YEAR(DateHeure) AS annee FROM articles WHERE Contenu LIKE ? AND Etat = 1 AND ID != 1');
		$viewArticle->execute(array('%'.$_POST['recherche'].'%'));
		$nbRechercheBoucle=0;
		while($donnesArticle = $viewArticle->fetch()){
			$nbRechercheBoucle=$nbRechercheBoucle+1;
			?>
				<hr>
				<h2>
					<a href="<?php echo $donnesArticle['ID']; ?>"><?php echo $donnesArticle['Titre']; ?></a>
				</h2>
				<p class="lead">
					<?php 
					$whoWrite = $bdd->query('SELECT Pseudo FROM utilisateurs WHERE ID ='.$donnesArticle['IDUser']);
					$user = $whoWrite->fetch();
					$whoWrite->closeCursor();
					?>
				   Ecrit par <?php echo $user['Pseudo']; ?>
				</p>
				<p><span class="glyphicon glyphicon-time"></span> Posté le <?php echo $donnesArticle['jour'].'/'.$donnesArticle['mois'].'/'.$donnesArticle['annee']; ?></p>
				<hr>
				<article><?php echo $donnesArticle['Contenu']; ?></article>
				<a class="btn btn-primary" href="<?php echo $donnesArticle['ID']; ?>">Lire la suite <span class="glyphicon glyphicon-chevron-right"></span></a>
		
			
			
			<?php
		}
		$viewArticle->closeCursor();
		if($nbRechercheBoucle==0){
			?>
			<p>Aucun article correspondant à votre recherche n'a été trouvé !</p>
			<?php
		}
	}else{
		?>
			<p>Aucun article correspondant à votre recherche n'a été trouvé !</p>
		<?php
	}
	include_once('includes/footer.php');
	exit();	
}


if(isset($_GET['article'])){
	if(is_numeric($_GET['article'])){
		$viewArticle = $bdd->prepare('SELECT *, DAY(DateHeure) AS jour, MONTH(DateHeure) AS mois, YEAR(DateHeure) AS annee, HOUR(DateHeure) AS heure, MINUTE(DateHeure) AS minute, DAY(DerniereEdition) AS jourLastEdit, MONTH(DerniereEdition) AS moisLastEdit, YEAR(DerniereEdition) AS anneeLastEdit, HOUR(DerniereEdition) AS heureLastEdit, MINUTE(DerniereEdition) AS minuteLastEdit FROM articles WHERE ID = ? AND Etat = 1');
		$viewArticle->execute(array($_GET['article']));
		$donnesArticle = $viewArticle->fetch();
		$viewArticle->closeCursor();
		
		$viewPage = $bdd->prepare('SELECT * FROM pages WHERE ID = ? AND Etat = 1');
		$viewPage->execute(array($donnesArticle['IDPage']));
		$donnesPage = $viewPage->fetch();
		$viewPage->closeCursor();
		if($donnesPage['Etat'] == 0 OR $donnesArticle['Etat'] == 0){
			?>
				<h2>Oups</h2>
				<p>404 : La page demandée est introuvable.</p>
			<?php
			include_once('includes/footer.php');
			exit();
		}
		?>
				<h2><?php echo $donnesArticle['Titre']; ?></h2>
				<p class="lead">
					<?php 
					$whoWrite = $bdd->query('SELECT Pseudo FROM utilisateurs WHERE ID ='.$donnesArticle['IDUser']);
					$user = $whoWrite->fetch();
					$whoWrite->closeCursor();
					?>
				   Ecrit par <?php echo $user['Pseudo']; ?>
				</p>
				<p><span class="glyphicon glyphicon-time"></span> Posté le <?php echo $donnesArticle['jour'].'/'.$donnesArticle['mois'].'/'.$donnesArticle['annee']; ?> à <?php echo $donnesArticle['heure'].'h'.$donnesArticle['minute']; ?></p>
				<hr>
				<article><?php echo $donnesArticle['Contenu']; ?></article>
		<?php
		if($donnesArticle['DerniereEdition'] != NULL ){
			?>
				<hr>
				<p><span class="glyphicon glyphicon-time"></span> Dernière edition le <?php echo $donnesArticle['jourLastEdit'].'/'.$donnesArticle['moisLastEdit'].'/'.$donnesArticle['anneeLastEdit']; ?> à <?php echo $donnesArticle['heureLastEdit'].'h'.$donnesArticle['minuteLastEdit']; ?></p>
			<?php
		}
		
	}else{
		?>
			<h2>Oups</h2>
			<p>404 : La page demandée est introuvable.</p>
		<?php
	}
	
	include_once('includes/footer.php');
	exit();
}

?>


                <!-- First Blog Post -->
				<?php
				$Presentation = $bdd->query('SELECT ID, IDUser, Titre, DAY(DateHeure) AS jour, MONTH(DateHeure) AS mois, YEAR(DateHeure) AS annee, Contenu FROM articles WHERE ID = 1');
				$Prez = $Presentation->fetch();
				$Presentation->closeCursor();
				?>
                <h2><?php echo $Prez['Titre']; ?></h2>
                <p class="lead">
					<?php 
					$whoWrite = $bdd->query('SELECT Pseudo FROM utilisateurs WHERE ID ='.$Prez['IDUser']);
					$user = $whoWrite->fetch();
					$whoWrite->closeCursor();
					?>
                   Ecrit par <?php echo $user['Pseudo']; ?>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> Posté le <?php echo $Prez['jour'].'/'.$Prez['mois'].'/'.$Prez['annee']; ?></p>
                <hr>
				<article>
				<?php echo $Prez['Contenu']; ?>
				</article>
				
				<!-- Other Blog Column -->
				
				<?php
				$listeArticleF = $bdd->query('SELECT ID, IDUser, Titre, Contenu, DAY(DateHeure) AS jour, MONTH(DateHeure) AS mois, YEAR(DateHeure) AS annee FROM articles WHERE EpingleAccueil = 1 AND Etat = 1 AND ID != 1 ORDER by DateHeure');
				while($Article = $listeArticleF->fetch()){
				?>
				<hr>
					
					
				<h2>
					<a href="<?php echo $Article['ID']; ?>"><?php echo $Article['Titre']; ?></a>
				</h2>
				<p class="lead">
					<?php 
					$whoWrite = $bdd->query('SELECT Pseudo FROM utilisateurs WHERE ID ='.$Article['IDUser']);
					$user = $whoWrite->fetch();
					$whoWrite->closeCursor();
					?>
				   Ecrit par <?php echo $user['Pseudo']; ?>
				</p>
				<p><span class="glyphicon glyphicon-time"></span> Posté le <?php echo $Article['jour'].'/'.$Article['mois'].'/'.$Article['annee']; ?></p>
				<hr>
				<article><?php echo substr($Article['Contenu'], 0, 255).'...'; ?></article>
				<a class="btn btn-primary" href="<?php echo $Article['ID']; ?>">Lire la suite <span class="glyphicon glyphicon-chevron-right"></span></a>

				<?php
				}
				$listeArticleF->closeCursor();
				?>		

<?php
include_once('includes/footer.php');
?>