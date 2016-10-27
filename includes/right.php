
            <!-- Blog Sidebar Widgets Column -->
            <div class="col-md-4">

                <!-- Blog Search Well -->
                <div class="well">
                    <h4>Recherche d'article</h4>
                    <div class="input-group">
						<form method='POST' action='/'>
							<span class="input-group-btn">
								<input type="text" name='recherche' class="form-control">
								<button class="btn btn-default" type="submit">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</span>
						</form>
                    </div>
                    <!-- /.input-group -->
                </div>

                <!-- Blog Categories Well -->
                <div class="well">
                    <h4>Derniers articles</h4>
                    <div class="row">
                        <div class="col-lg-9">
                            <ul class="list-unstyled">
								<?php
								$listeArticleR = $bdd->query('SELECT ID, Titre, DAY(DateHeure) AS jour, MONTH(DateHeure) AS mois, YEAR(DateHeure) AS annee FROM articles WHERE Etat = 1 AND ID != 1 ORDER by DateHeure DESC LIMIT 6');
								while($Article = $listeArticleR->fetch()){
									?>
									<li><a href="<?php echo $Article['ID']; ?>"><?php echo $Article['Titre']; ?> du <?php echo $Article['jour'].'/'.$Article['mois'].'/'.$Article['annee']; ?></a></li>
									<?php
								}
								$listeArticleR->closeCursor();
								?>
                            </ul>
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                    <!-- /.row -->
                </div>


            </div>