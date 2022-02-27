
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<head>
   <title>Gestion du Théâtre : Menu </title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="style.css" rel="stylesheet" media="all" type="text/css">
</head>

<!-- procedures et fonctions pour les mises a jour du Théâtre
	 MC Fauvet, Mars 2015 -->
<body>

<form method="post" action="DetailsRepresentation-action.php" >
<select name="choix">
    <option value="La flute enchantee">La flute enchantee</option>
    <option value="Coldplay">Coldplay</option>
    <option value="Lac des cygnes">Lac des cygnes </option> 
</select>
<input type="submit" value"valider" />
</form>
	$titre = 'Les dates du spectacle Coldplay';
	include('entete.php');


	include('pied.php');

</body>
</html>
