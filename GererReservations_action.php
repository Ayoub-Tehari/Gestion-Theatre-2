<?php
    session_start();
    $login = $_SESSION['login'];
	$titre = 'Relations appartenant au compte ' . $login;
	include('entete.php');
	
	$nomS = $_POST['spectacle'];
	setcookie('spectacle',  $nomS, time() + 365*24*3600, null, null, false, true);
	$datesRep = array();
	$swap1 = array();
	$swap2 = array();
	// construction des requ?tes
	$requete1 = "select count(*) from LesPlaces";
	$requete2 = "(select to_char (dateRep, 'DD') || '-' || to_char(dateRep, 'MM') || '-' || to_char (dateRep,'YYYY') || ' ' || to_char (dateRep,'HH:MI') as dateRep from LesRepresentations natural join LesSpectacles where lower(nomS)=lower(:s))
 minus ( select to_char (dateRep, 'DD') || '-' || to_char(dateRep, 'MM') || '-' || to_char (dateRep,'YYYY') || ' ' || to_char (dateRep,'HH:MI') as dateRep from LesTickets natural join LesSpectacles where (lower(nomS)= lower(:s)))";
	$requete3 = "select to_char (dateRep, 'DD') || '-' || to_char(dateRep, 'MM') || '-' || to_char (dateRep,'YYYY') || ' ' || to_char (dateRep,'HH:MI') as dateRep, count(*) as nbr from LesTickets natural join LesSpectacles where (nomS= :s) group by dateRep ";

	// analyse de la requete et association au curseur
	$curseur1 = oci_parse ($lien, $requete1) ;

	// execution de la requete
	oci_execute ($curseur1) ;

	$res1 = oci_fetch ($curseur1);

	if ( !$res1) {
		// le resultat est vide
		echo "<p class=\"erreur\"><b>Aucune relation </b></p>" ;
	}
	else {
		// la table qui va servir a la mise en page du resultat
		// on affiche un r?sultat et on passe au suivant s'il existe
			$resultat=oci_result($curseur1,1);
			echo $resultat ;

					

					echo ("<form action=\"GererReservations_action2.php\" method=\"POST\">
			<label for=\"inp_spectacle\">Veuillez saisir une representation du spectacle $nomS : </label>
			<select name=\"dateRep\">");
					// analyse de la requete 2 et association au curseur
					$curseur3 = oci_parse ($lien, $requete3) ;
					// affectation de la variable
					oci_bind_by_name ($curseur3, ':s', $nomS);


					// execution de la requete
					$ok3 = @oci_execute ($curseur3) ;

					// on teste $ok pour voir si oci_execute s'est bien pass?
					if (!$ok3) {
	
					// oci_execute a ?chou?, on affiche l'erreur
					$error_message = oci_error($curseur3);
					echo "<p class=\"erreur\">{$error_message['message']}</p>";
	
					}else {
	
						// oci_execute a r?ussi, on fetch sur le premier r?sultat
						$res3 = oci_fetch ($curseur3);
		
						if (!$res3) {
	
							// il n'y a aucun r?sultat
							echo "<p class=\"erreur\"><b>Aucune place associ?e ? cette cat?gorie ou cat?gorie inconnue</b></p>" ;

						}else { 
							do {
								$dateRep =  oci_result($curseur3,1);
								$palces_reserv =  oci_result($curseur3,2);

								$PlacesQuiReste= $resultat - $palces_reserv; 
								if ($PlacesQuiReste>70) {
								// affichage du formulaire
//$heure=substr ($dateRep , strlen($dateRep)-6, strlen($dateRep)-1);
//$jour = strlen($dateRep);
								echo (" <option value=\"$dateRep\"> $dateRep </option> ");
								}
						
							}while(oci_fetch ($curseur3));
			
						}
					}
						
					oci_free_statement($curseur3);
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
		do {
		$dateRep =  oci_result($curseur2,1);
echo (" <option value=\"$dateRep\"> $dateRep </option> ");
								  
 		}while(oci_fetch ($curseur2));
	}
oci_free_statement($curseur2);
					echo ("</select><br /><br /><input type=\"submit\" value=\"Valider\" /><input type=\"reset\" value=\"Annuler\" /></form>");

					}
				
oci_free_statement($curseur1);
				
					


	
	

	include('pied.php');
?>
