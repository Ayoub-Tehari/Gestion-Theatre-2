<?php

	include('entete.php');
	// construction de la requete
	$requete1 = ("SELECT nomS, noSpec FROM theatre.LESSPECTACLES ORDER BY noSpec");
	$requete2 = ("SELECT to_char(daterep,'Day, DD-Month-YYYY HH:MI') as dateRep, noSpec, count(noSerie) as nbr
		FROM theatre.LesTickets 
		group by dateRep, noSpec ORDER BY noSpec");/*
	$requete = ("
		SELECT to_char(daterep,'Day, DD-Month-YYYY HH:MI') as daterep, nomS, nbr from
(SELECT dateRep, noSpec, count(noSerie) as nbr
		FROM theatre.LesTickets 
		group by dateRep, noSpec) natural join theatre.LESSPECTACLES
	");*/

	// analyse de la requete et association au curseur
	$curseur1 = oci_parse ($lien, $requete1) ;
	$ok1 = @oci_execute ($curseur1);
	$res1 = oci_fetch ($curseur1);
	// on affiche la table qui va servir a la mise en page du resultat
			echo "<table><tr><th>no</th><th>Spectacle</th><th>Date Representation</th><th>nombre de places R</th></tr>" ;

	do {
		$nomS = oci_result($curseur1, 1);
		$numS1 = oci_result ($curseur1, 2);

		// analyse de la requete et association au curseur
		$curseur2 = oci_parse ($lien, $requete2) ;
		$ok2 = @oci_execute ($curseur2);
		$res2 = oci_fetch ($curseur2);

		do {
			$DateR = oci_result($curseur2, 1);
			$numS2 = oci_result ($curseur2, 2);
			$nbr = oci_result ($curseur2, 3);
			if ($numS1==$numS2) {
				echo "<tr><td>$numS1</td><td>$nomS</td><td>$DateR</td><td>$nbr</td></tr>";
			}

		}while (oci_fetch ($curseur2));	
		// on libère le curseur
		oci_free_statement($curseur2);

		
	}while (oci_fetch ($curseur1));

			echo "</table>";

	// on libère le curseur
	oci_free_statement($curseur1);

	include('pied.php');

?>
