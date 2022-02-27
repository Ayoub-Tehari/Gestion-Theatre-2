<?php

	$titre = 'Liste des places associées au dossier donnée';
	include('entete.php');
$requete = " select distinct noDossier from theatre.LesTickets order by noDossier";

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;


	// execution de la requete
	$ok = @oci_execute ($curseur) ;

	if(!$ok) {
		echo"kdkdkdkdk";
	}else{
	$res = oci_fetch ($curseur);
	if (!$res){
		echo "ddddddd";
	}else{
	echo ("
		<form action=\"SpectaclesDossier_v3_action.php\" method=\"POST\">
			<label for=\"inp_dossier\">Veuillez saisir un dossier :</label>
			<select name=\"dossier\">");
	do {
		$noDossier=oci_result($curseur,1);
	// affichage du formulaire
	echo (" <option value=\"$noDossier\">$noDossier</option> ");
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
			Ajoutez une étape à cet enchaînement de scripts de façon à obtenir le fonctionnement suivant :
			<ul>
				<li><b>Etape 1 :</b> un formulaire nous demande de choisir un numéro de dossier dans une liste extraite de la base de données</li>
				<li><b>Etape 2 :</b> pour le numéro de dossier choisi, un formulaire nous demande de sélectionner une catégorie dans une liste qui ne contiendra que les catégories concernées par le numéro de dossier demandé</li>
				<li><b>Etape 3 :</b> affichage de la liste des places correspondant à la catégorie et au numéro de dossier sélectionnés</li>
			</ul>
		</p>
	");

	include('pied.php');

?>
