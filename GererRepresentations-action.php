<?php

	$titre = 'Les dates du spectacle Les enfoirés';
	include('entete.php');
	$dateRep = $_POST['dateRep'];
	$nomS = $_POST['nomS'];
	$numS = $_POST['numS'];
	// construction des requêtes
	$requete1 = "INSERT INTO LesSpectacles values (:n, :b)";
	$requete2 = "INSERT INTO LesRepresentations values (:n, to_date(:d, 'DD-MM-YYYY HH24:MI'))";

	// analyse de la requete 1 et association au curseur
	$curseur = oci_parse ($lien, $requete1) ;

	// affectation de la variable
	oci_bind_by_name ($curseur, ':n', $numS);
	oci_bind_by_name ($curseur, ':b', $nomS);

	// execution de la requete
	$ok = @oci_execute ($curseur, OCI_NO_AUTO_COMMIT) ;
	$flag1=0;
	// on teste $ok pour voir si oci_execute s'est bien passé
	if (!$ok) {

		echo LeMessage ("majRejetee")."<br /><br />";
		$e = oci_error($curseur);
		if ($e['code'] == 1) {
			echo LeMessage ("spectacleconnu") ;$flag1=1;
		}
		else {
			echo LeMessageOracle ($e['code'], $e['message']) ;
		}

		// terminaison de la transaction : annulation
		oci_rollback ($lien) ;

	}
	if ($flag1==1 or $ok) {

		// analyse de la requete 2 et association au curseur
		$curseur = oci_parse ($lien, $requete2) ;

		// affectation de la variable
		oci_bind_by_name ($curseur, ':n', $numS);
		oci_bind_by_name ($curseur, ':d', $dateRep);
		
		// execution de la requete
		$ok = @oci_execute ($curseur, OCI_NO_AUTO_COMMIT) ;

		// on teste $ok pour voir si oci_execute s'est bien passé
		if (!$ok) {

			echo LeMessage ("majRejetee")."<br /><br />";
			$e = oci_error($curseur);
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

	}

	// on libère le curseur
	oci_free_statement($curseur);

	include('pied.php');

?>
