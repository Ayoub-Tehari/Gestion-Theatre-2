<?php
    session_start();
    $login = $_SESSION['login'];
    $motdepasse = $_SESSION['motdepasse'];
    require_once ("utils.php");
    $titre= 'Scénario pour la gestion des representations';
    include('entete.php');


	echo ("
		<form action=\"GererRepresentations-action.php\" method=\"POST\">
			<label for=\"inp_spectacle\">Veuillez saisir le nom spectacle :</label>
			<input type=\"text\" name=\"nomS\" />
			<label for=\"inp_spectacle\">Veuillez saisir le num spectacle :</label>
			<input type=\"text\" name=\"numS\" />
			<br />
			<label for=\"inp_spectacle\">Veuillez saisir la date de la representation sous le fromat 'DD-MM-YYYY HH24:MI':</label>
			<input type=\"text\" name=\"dateRep\" />
			<br /><br />
			<input type=\"submit\" value=\"Valider\" />
			<input type=\"reset\" value=\"Annuler\" />
		</form>
	");
    echo ("
          <p class=\"scenario\"> Pascal est secrétaire de l'organisation, il se connecte à l'application afin de créer une
          nouvelle representation. L'IHM propose un formulaire permettant à ce secrétaire de saisir : le numero spectacle numS et son nom nomS, la
          la date associé à la representation.
          La discipline peut être choisie dans la liste des disciplines existantes, ou si elle n'existe pas,
          l'IHM permet d'en créer un nouvelle.
          </p>");
    include('pied.php');
?>
