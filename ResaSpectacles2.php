<?php

	include('entete.php');
	// construction de la requete
	$requete = ("
		SELECT to_char(daterep,'Day, DD-Month-YYYY HH:MI') as daterep, nomS, nbr from
(SELECT dateRep, noSpec, count(noSerie) as nbr
		FROM theatre.LesTickets 
		group by dateRep, noSpec) natural join theatre.LESSPECTACLES
	");

	// analyse de la requete et association au curseur
	$curseur = oci_parse ($lien, $requete) ;
	$ok = @oci_execute ($curseur);
	$res = oci_fetch ($curseur);
	// on affiche la table qui va servir a la mise en page du resultat
			echo "<table><tr><th>Date Representation</th><th>Spectacle</th><th>nombre de places R</th></tr>" ;

	do {
		$DateR = oci_result($curseur, 1);
		$numS = oci_result ($curseur, 2);
		$nbr = oci_result ($curseur, 3);
		echo "<tr><td>$DateR</td><td>$numS</td><td>$nbr</td></tr>";

	}while (oci_fetch ($curseur));

			echo "</table>";

	// on libère le curseur
	oci_free_statement($curseur);

	include('pied.php');

?>
