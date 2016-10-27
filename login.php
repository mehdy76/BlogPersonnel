<?php 
session_start();
include_once('includes/header.php');
if(isset($_SESSION['KEY'])){
	$connect = $bdd->prepare('SELECT * FROM utilisateurs WHERE Session = ?');
	$connect->execute(array($_SESSION['KEY']));
	$utilisateur = $connect->fetch();
	if($utilisateur['ID'] != NULL){		
		?>
		<h3>Connexion réussie</h3>
		<p><a href="editmod.php"><button type="button" class="btn btn-success">Continuer</button></a></p>	
		<?php
		include_once('includes/footer.php');
		exit();
	}
}
function cryptage($pass, $salt){
	$password1 = sha1(sha1($pass.$salt));
	$password2 = sha1(sha1 ($salt.$password1).$salt);
	$password3 = md5(md5 ($salt.$password2.$salt.$password1.$salt));
	$password4 = sha1(sha1 ($password3.$salt));
	$pass_crypte = sha1(sha1 ($password4.$salt.$salt.$salt));
	return $pass_crypte;
}

if(isset($_POST['connect'])){
	$connect = $bdd->prepare('SELECT ID FROM utilisateurs WHERE Pseudo = :pseudo AND Password = :password');
	$connect->execute(array(
		'pseudo' => $_POST['user'],
		'password' => cryptage(htmlspecialchars($_POST['pwd']), $salt)
	));
	$verifConnect = $connect->fetch();
	if(count($verifConnect['ID']) < 1){
		?>
		<h3>Nom d'utilisateur ou mot de passe incorrect !</h3>
		<p><a href="login.php"><button type="button" class="btn btn-info">Retour</button></a></p>	
		<?php
		include_once('includes/footer.php');
		exit();
	}
	
	
	$key_alea = 0;
	for($i=0;$i < 30;$i++){ 
		$key_alea .= substr("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890",rand()%(strlen("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890")),1); 
	}
	$updateSession = $bdd->prepare("UPDATE utilisateurs SET Session = :session WHERE ID = :id");
	$updateSession->execute(array(
		'session' => $key_alea,
		'id' => $verifConnect['ID']
	));		
	
	$connect->closeCursor();
	$_SESSION['KEY'] = $key_alea;	
		?>
		<h3>Connexion réussie</h3>
		<p><a href="editmod.php"><button type="button" class="btn btn-success">Continuer</button></a></p>	
		<?php
		include_once('includes/footer.php');
	exit();
}
?>
        <!-- Portfolio Item Row -->
        <div class="row">
            <div class="col-xs-12">
                <h3>Veuillez vous connecter pour continuer !</h3>
				<form role="form" action="login.php" method="POST">				
				    <div class="form-group">
						<label for="user">Nom d'utilisateur</label>
						<input type="text" class="form-control" name="user">
				    </div>
				    <div class="form-group">
						<label for="pwd">Password</label>
						<input type="password" class="form-control" name="pwd" placeholder="*******">
						<input type="hidden" name="connect" value="1">
					</div>
					<button type="submit" class="btn btn-default">Me connecter</button>
				</form>
			</div>
        </div>
        <!-- /.row -->
<?php
include_once('includes/footer.php');