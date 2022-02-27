<?php
    session_start();
    $login = $_SESSION['login'];
    $motdepasse = $_SESSION['motdepasse'];

require_once ("utils.php");

    $titre = "Scénario pour la gestion des ventes";
    include('entete.php');


$requete = " select nomS from LesSpectacles";

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;


	// execution de la requete
	$ok = oci_execute ($curseur) ;

	
	$res = oci_fetch ($curseur);
	if (!$res){

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b> Spectacle inconnu </b></p>" ;

	}else{
	
	echo ("
		<form action=\"GererReservations_action.php\" method=\"POST\">
			<label for=\"inp_spectacle\">Veuillez selectioner un spectacle :</label>
			<select name=\"spectacle\">");
	do {
		$nomS = oci_result($curseur,1);
		// affichage du formulaire
		echo (" <option value=\"$nomS\"> $nomS </option> ");
	}while(oci_fetch ($curseur));
	echo ("
</select>
			<br /><br />
			<input type=\"submit\" value=\"Valider\" />
			<input type=\"reset\" value=\"Annuler\" />
		</form>
	");
	}
	
if (!isset ($_SESSION['noDossier'])){
	$requete2 = " select max(noDossier) from LesDossiers";

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete2) ;


	// execution de la requete
	$ok = oci_execute ($curseur) ;

	
	$res = oci_fetch ($curseur);
	if (!$res){

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b> Spectacle inconnu </b></p>" ;

	}else{
	
		$noDossier = oci_result($curseur,1);
		$_SESSION['noDossier'] = $noDossier + 1;
		
	}}







    echo ("
          <p class=\"scenario\"> Vincent est un utilisateur de l'application. Il y
          accède afin d'acheter des places à des épreuves à venir. Il peut supprimer des places déjà achetées au cours de la transaction (ou session). Il peut aussi en ajouter pour une épreuve pour laquelle il a déjà des places. Il peut aussi en acheter pour 
          d'autres épreuves.
          Il constitue ainsi son panier.
          
          Lorsque Vincent valide son panier, terminant ainsi la transaction, l'application demande une adresse où envoyer les tickets correspondants. Si Vincent quitte l'application, où si un autre utilisateur se connecte, avant que Vincent ne valide son panier, celui-ci est perdu (il n'est pas sauvegardé).</p>
     ");
    include('pied.php');
 ?>
    
