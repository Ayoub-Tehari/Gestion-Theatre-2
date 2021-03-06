<?php

	$titre = 'Liste des places associées au dossier 11 pour une catégorie donnée';
	include('entete.php');
	
	$requete = " select nomC from Theatre.LesCategories";

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;


	// execution de la requete
	$ok = @oci_execute ($curseur) ;

	if(!$ok) {
		// oci_execute a échoué, on affiche l'erreur
		$error_message = oci_error($curseur);
		echo "<p class=\"erreur\">{$error_message['message']}</p>";
	}else{
	$res = oci_fetch ($curseur);
	if (!$res){
		// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b> Spectacle inconnu </b></p>" ;

	}else{
	echo ("
		<form action=\"SpectaclesDossier_v2_action.php\" method=\"POST\">
			<label for=\"inp_categorie\">Veuillez saisir une catégorie :</label>
			<select name=\"categorie\">");
	do {
		$nomC=oci_result($curseur,1);
	// affichage du formulaire
	echo (" <option value=\"$nomC\">$nomC</option> ");
	}while(oci_fetch ($curseur));
	echo ("
</select>
			<br /><br />
			<input type=\"submit\" value=\"Valider\" />
			<input type=\"reset\" value=\"Annuler\" />
		</form>
	");
	}
	}
	// travail à réaliser
	echo ("
		<p class=\"work\">
			Améliorez l'interface utilisateur en proposant, à la place du champ de saisie libre, un choix de catégorie dans une liste contenant toutes les catégories (sous forme de boite de sélection ou de boutons radio).<br />Cette fois-ci, la liste sera extraite de la base de données.
		</p>
	");

	include('pied.php');

?>
