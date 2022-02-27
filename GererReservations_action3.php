<?php

    session_start();
	$titre = 'Les dates du spectacle Les enfoirés';
	include('entete.php');

	// récupération du spectacl + rep
	$nomC = $_POST['categorie'];
	setcookie('categorie',  $categorie, time() + 3600, null, null, false, true);
	$nomS = $_COOKIE['spectacle'];
	$dateRep = $_COOKIE['dateRep'];
	$noDossier = $_SESSION['noDossier'] ;

$jour=substr ($dateRep , 0, 1);$mois=substr ($dateRep , 2, 3);$annee=substr ($dateRep , 5, 9);$heure=substr ($dateRep , 11, 15);

	
	// construction des requêtes
	$requete1 = "(select noPlace, noRang from LesPlaces natural join LesZones where nomC= :c ) minus ( select noPlace, noRang
from LesTickets where numS=:s and :j =to_char(daterep,'DD') and :m =to_char(daterep,'MM') and :y =to_char(daterep,'YYYY') and :h =to_char(daterep,'HH:MI'))";
	$requete2 = "select numS from LesSpectacles where lower(nomS)= lower(:s)";
// analyse de la requete et association au curseur
	$curseur2 = oci_parse ($lien, $requete2) ;
// affectation de la variable
	oci_bind_by_name ($curseur2, ':s', $nomS);
	// execution de la requete
	oci_execute ($curseur2) ;

	$res2 = oci_fetch ($curseur2);

	if ( !$res2) {
		// le resultat est vide
		echo "<p class=\"erreur\"><b>Aucune relation </b></p>" ;
	}
	else {
		
		$numS =  oci_result($curseur2,1);
		setcookie('numS',  $numS, time() + 3600, null, null, false, true);
	}
oci_free_statement($curseur2);

//-------------------------

	// analyse de la requete 1 et association au curseur
	$curseur = oci_parse ($lien, $requete1) ;


	// affectation de la variable

	oci_bind_by_name ($curseur, ':c', $nomC);
	oci_bind_by_name ($curseur, ':s', $numS);
	oci_bind_by_name ($curseur, ':j', $jour);
	oci_bind_by_name ($curseur, ':m', $mois);
	oci_bind_by_name ($curseur, ':y', $annee);

	oci_bind_by_name ($curseur, ':h', $heure);

	// execution de la requete
	$ok = @oci_execute ($curseur) ;

	// on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {

		echo LeMessage ("majRejetee")."<br /><br />";
		$e = oci_error($curseur);
		if ($e['code'] == 1) {
			echo LeMessage ("spectacleconnu") ;
		}
		else {
			echo LeMessageOracle ($e['code'], $e['message']) ;
		}


	}else {


					


		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch ($curseur);

		if (!$res) {

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b>Aucune place associée à cette catégorie ou catégorie inconnue</b></p>" ;

		}else {

			
		
		$noPlace =  oci_result($curseur,1); 
		setcookie('noPlace',  $noPlace, time() + 3600, null, null, false, true);
		$noRang =  oci_result($curseur,2); 
		setcookie('noRang',  $noRang, time() + 3600, null, null, false, true);			
		}
		

	}

	// on libère le curseur
	oci_free_statement($curseur);



	// on affiche la table qui va servir a la mise en page du resultat
	echo "<table><tr><th>nomSpec</th><th>noSpec</th><th>dateRep</th><th>noPlace</th><th>noRang</th><th>Categorie</th><th>noDossier</th></tr>" ;

	// on affiche un résultat et on passe au suivant s'il existe
	echo "<tr><td>$nomS</td><td>$numS</td><td>$dateRep</td><td>$noPlace</td><td>$noRang</td><td>$nomC</td><td>$noDossier</td></tr>";		
	echo "</table>";$oui='oui';$non='non';
echo ("<form action=\"GererReservations_fin.php\" method=\"POST\">
			<label for=\"inp_confirmation\">Veuillez confirmer ou non ce tickets : </label>
		<select name=\"feedback\"><option value=\"$oui\"> $oui </option>
<option value=\"$non\"> $non </option></select><br /><br />
	<input type=\"submit\" value=\"Valider\" /><input type=\"reset\" value=\"Annuler\" /></form>");

	include('pied.php');

?>

