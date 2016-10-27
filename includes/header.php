<?php 
	require_once('configuration.php');
?>
<!DOCTYPE html>
<html lang="FR">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $configuration['HeadDescription']; ?>">
	<meta name="keywords" content="<?php echo $configuration['HeadKeywords']; ?>" />
    <title><?php echo $configuration['HeadTitle']; ?></title>
	<link rel="icon" type="image/png" href="favicon.ico" />
	<!--[if IE]><link rel="shortcut icon" type="image/x-icon" href="favicon.ico" /><![endif]-->


    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/blog-home.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	<style>
	.navbar{
	  background-color: <?php echo $configuration['HeadNavBarColor']; ?>; /* fond de la navbar */
	  border-color:transparent !important;
	}
	 
	.navbar-nav li a{
	  color: <?php echo $configuration['HeadNavBarWordColor']; ?> !important; /* Couleur mots */
	}
	 
	.navbar-inverse .navbar-nav>.open>a, .navbar-inverse .navbar-nav>.open>a:focus, .navbar-inverse .navbar-nav>.open>a:hover {
	  background-color: <?php echo $configuration['HeadNavBarSelectWordColor']; ?>; /* Couleur selection menu */
	}
	</style>
	
	
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Ouvrir la barre</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/" id="menu"><?php echo $configuration['TitrePAccueil']; ?></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<?php
			$listePage = $bdd->query('SELECT * FROM pages WHERE Etat = 1');
			while($Page = $listePage->fetch()){
			?>
					
				<ul class="nav navbar-nav">
						
						
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $Page['Titre']; ?><b class="caret"></b> </a>
						<ul class="dropdown-menu">
							<li class="dropdown-submenu">
							
								<?php
								$listeArticle = $bdd->query('SELECT ID, Titre FROM articles WHERE Etat = 1 AND IDPage = '.$Page['ID']);
								$nbArticle=0;
								while($Article = $listeArticle->fetch()){
									$nbArticle=$nbArticle+1;
								?>
									<a href="<?php echo $Article['ID']; ?>"><?php echo $Article['Titre']; ?></a>
								<?php
								}
								$listeArticle->closeCursor();
								if($nbArticle == 0){
									?>
									<a href="#">Aucun article disponible</a>
									<?php
								}
								?>
							</li>
						</ul>
					</li>							
						
				</ul>
					
            <!-- /.navbar-collapse -->
			<?php		
			}
				$listePage->closeCursor();
			?>	
			</div>

					


        </div>
        <!-- /.container -->
    </nav>
	
	
    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <h1 class="page-header">
                    <?php echo $configuration['TitrePrincipal']; ?>
                    <small><?php echo $configuration['SousTitre']; ?></small>
                </h1>	