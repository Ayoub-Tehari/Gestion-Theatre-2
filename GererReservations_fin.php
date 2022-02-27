<?php

    session_start();
	$titre = 'Les dates du spectacle Les enfoirés';
	include('entete.php');

	// récupération du spectacl + rep
	$nomC = $_COOKIE['categorie'];
	$numS = (int) $_COOKIE['numS']);
	$noPlace= (int)$_COOKIE['noPlace'];
	$noRang = (int)$_COOKIE['noRang'];
	$dateRep = $_COOKIE['dateRep'];
	$noDossier = (int) $_SESSION['noDossier'] ;
reponse =$_POST['feedback'];
if (reponse=='oui') {
	
	// construction des requêtes
	$requete2 = "select max(noSerie) from LesTickets";
// analyse de la requete et association au curseur
	$curseur2 = oci_parse ($lien, $requete2) ;
	// execution de la requete
	oci_execute ($curseur2) ;

	$res2 = oci_fetch ($curseur2);

	if ( !$res2) {
		// le resultat est vide
		echo "<p class=\"erreur\"><b>Aucune relation </b></p>" ;
	}
	else {
		
		$noSerie =  oci_result($curseur2,1) + 1;
	}
oci_free_statement($curseur2);

//-------------------------

	// construction des requêtes
	
	$requete22 = "select prix from LesCategories where nomC = :c";
// analyse de la requete et association au curseur
	$curseur22 = oci_parse ($lien, $requete22) ;
	oci_bind_by_name ($curseur22, ':c', $nomC);
	// execution de la requete
	oci_execute ($curseur22) ;
	echo $nomC;
	$res2 = oci_fetch ($curseur22);

	if ( !$res2) {
		// le resultat est vide
		echo "<p class=\"erreur\"><b>Aucune relationkkkk </b></p>" ;
	}
	else {
		
		$prix =  number (oci_result($curseur22,1) );
	}
oci_free_statement($curseur22);

//-------------------------

// construction des requêtes
	$requete1 = "INSERT INTO LesTickets values ($noSerie, $numS, to_date($dateRep,'DD-MM-YYYY HH:MI'), $noPlace, $noRang, sysdate, $noDossier )";
	$requete3 = "UPDATE LesDossiers set montant=montant+$prix where noDossier=$noDossier";
	$requete2 = "INSERT INTO LesDossiers values ($noDossier, $prix )";
	// analyse de la requete 1 et association au curseur
	$curseur = oci_parse ($lien, $requete1) ;

	// execution de la requete
	$ok = @oci_execute ($curseur, OCI_NO_AUTO_COMMIT) ;

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

		// terminaison de la transaction : annulation
		oci_rollback ($lien) ;

	}
	else {
		// terminaison de la transaction : validation
		oci_commit ($lien) ;

		// analyse de la requete 2 et association au curseur
		$curseur2 = oci_parse ($lien, $requete2) ;

		// execution de la requete
		$ok2 = @oci_execute ($curseur2, OCI_NO_AUTO_COMMIT) ;

		// on teste $ok pour voir si oci_execute s'est bien passé
		if (!$ok) {

			echo LeMessage ("majRejetee")."<br /><br />";
			$e = oci_error($curseur2);
			if ($e['code'] == 1) {
				echo LeMessage ("représentationconnue") ;
			}
			else {
				echo LeMessageOracle ($e['code'], $e['message']) ;
			}

			// terminaison de la transaction : annulation
			oci_rollback ($lien) ;

		}
		else {

			echo LeMessage ("majOK") ;
			// terminaison de la transaction : validation
			oci_commit ($lien) ;

		}
	// on libère le curseur
	oci_free_statement($curseur2);

	}

	// on libère le curseur
	oci_free_statement($curseur);
}else{
	
	echo "<a href= \"https://im2ag-goedel.e.ujf-grenoble.fr/~teharia/JOEtudiants/TheatreEtudiants/GererReservations.php\">acheter un nouveau ticket</a>";
}
unset ($_COOKIE['categorie']);
	unset ( $_COOKIE['numS']);
	unset ( $_COOKIE['noPlace']);
	unset ( $_COOKIE['noRang']);
	unset ( $_COOKIE['dateRep']);
	
	include('pied.php');

?>

