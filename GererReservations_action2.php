<?php

	$titre = 'Les dates du spectacle Les enfoirés';
	include('entete.php');

	// récupération du spectacl + rep
	$nomS = $_COOKIE['spectacle'];
	$dateRep = $_POST['dateRep'];
	setcookie('dateRep',  $dateRep, time() + 3600, null, null, false, true);

$jour=substr ($dateRep , 0, 1);$mois=substr ($dateRep , 2, 3);$annee=substr ($dateRep , 5, 9);$heure=substr ($dateRep , 11, 15);

	
	// construction des requêtes
	$requete1 = "select A.nomC, A.nbr1-B.nbr2 as nbrP, prix from (select nomC, count(*) as nbr1 from LesPlaces natural join LesZones group by nomC) A
 left join 
( select nomC, count(*) as nbr2 from LesTickets natural join LesSpectacles natural join LesPlaces natural join LesZones
 where nomS='Coldplay' and :j =to_char(daterep,'DD') and :m =to_char(daterep,'MM') and :y =to_char(daterep,'YYYY') and :h =to_char(daterep,'HH:MI') group by nomC ) B 
on (A.nomC=B.nomC)
join LesCategories on LesCategories.nomC=A.nomC";
	

	// analyse de la requete 1 et association au curseur
	$curseur = oci_parse ($lien, $requete1) ;


	// affectation de la variable
	oci_bind_by_name ($curseur, ':s', $nomS);
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


					echo ("<form action=\"GererReservations_action3.php\" method=\"POST\">
			<label for=\"inp_spectacle\">Veuillez saisir une categorie : </label>
			<select name=\"categorie\">");


		// oci_execute a réussi, on fetch sur le premier résultat
		$res = oci_fetch ($curseur);

		if (!$res) {

			// il n'y a aucun résultat
			echo "<p class=\"erreur\"><b>Aucune place associée à cette catégorie ou catégorie inconnue</b></p>" ;

		}else {

			
		do {
		$categorie =  oci_result($curseur,1);
		$nbr =  oci_result($curseur,2);
		$prix =  oci_result($curseur,3);
			if ($nbr>0 or is_null($nbr)){
				echo (" <option value=\"$categorie\"> $categorie : $prix euros </option> ");
			}						  
 		}while(oci_fetch ($curseur));

		}
	echo ("</select><br /><br /><input type=\"submit\" value=\"Valider\" /><input type=\"reset\" value=\"Annuler\" /></form>");
		

	}

	// on libère le curseur
	oci_free_statement($curseur);

	include('pied.php');

?>

