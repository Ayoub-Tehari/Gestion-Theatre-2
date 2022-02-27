<?php

	// récupération de la catégorie
	// récupération du dossier
	$dossier = $_POST['dossier'];
	setcookie('dossier',  $_POST['dossier'], time() + 365*24*3600, null, null, false, true); 
	//
	$titre = "Liste des places associées au dossier $dossier";
	include('entete.php');

	// construction de la requete
	$requete = ("
		SELECT distinct nomC
		FROM theatre.LesSieges natural join theatre.LesZones natural join theatre.LesCategories natural join theatre.LesTickets natural join theatre.LesSpectacles
		WHERE noDossier = :b
	");

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;

	// affectation de la variable
	oci_bind_by_name ($curseur, ':b', $dossier);

	// execution de la requete
	$ok = @oci_execute ($curseur) ;

	// on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {

		// oci_execute a échoué, on affiche l'erreur
		$error_message = oci_error($curseur);
		echo "<p class=\"erreur\">{$error_message['message']}</p>";

	}
	else {

		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch ($curseur);

		if (!$res) {

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b>Aucune place associée à cette catégorie ou catégorie inconnue</b></p>" ;

		}
		else {

			echo ("
		<form action=\"SpectaclesDossier_v3_action2.php\" method=\"POST\">
			<label for=\"inp_categoris\">Veuillez saisir une categorie :</label>
			<select name=\"categorie\">");
			// on affiche un résultat et on passe au suivant s'il existe
			do {
		$categorie=oci_result($curseur,1);
	// affichage du formulaire
	echo (" <option value=\"$categorie\">$categorie</option> ");
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

	// on libère le curseur
	oci_free_statement($curseur);

	include('pied.php');

?>
