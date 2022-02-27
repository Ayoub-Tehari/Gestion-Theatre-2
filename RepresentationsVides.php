<?php

	include('entete.php');
	// construction de la requete
	$requete = ("
		SELECT dateRep, nomS from
(SELECT dateRep, noSpec
		FROM theatre.LesRepresentations
		minus
		SELECT dateRep, noSpec
		FROM theatre.LesTickets) natural join theatre.LESSPECTACLES
	");

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;
	$ok = @oci_execute ($curseur);
	$res = oci_fetch ($curseur);
	// on affiche la table qui va servir a la mise en page du resultat
			echo "<table><tr><th>Date Representation</th><th>Spectacle</th></tr>" ;

	do {
		$DateR = oci_result($curseur, 1);
		$numS = oci_result ($curseur, 2);
		echo "<tr><td>$DateR</td><td>$numS</td></tr>";

	}while (oci_fetch ($curseur));

			echo "</table>";

	// on libère le curseur
	oci_free_statement($curseur);

	include('pied.php');

?>
