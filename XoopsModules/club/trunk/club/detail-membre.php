<?php

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_detail-membre.html';
include XOOPS_ROOT_PATH.'/header.php';

if(isset($_GET['id'])) {
  $id = $_GET['id'];
} else {
  $id = 0;
}

$mois = date("n");
$annee = date("Y");

// Parametre de la periode des graph suivi
$nbAnnee = 3;
$saisonAnneeFinSuivi = $annee;
$saisonAnneeDebSuivi = $saisonAnneeFinSuivi - $nbAnnee;
if($mois > 9) {
  $saisonAnneeFinSuivi++;
}

// Parametre de la periode des graph saison
if($mois < 10) {
  $saisonAnneeDebSaison = $annee - 1;
  $saisonAnneeFinSaison = $annee;
} else {
  $saisonAnneeDebSaison = $annee;
  $saisonAnneeFinSaison = $annee + 1;
}

$membreHandler = xoops_getmodulehandler('membre', 'club');
$membreObj = $membreHandler->getMembre($id);
if($membreObj) {
	$membre = $membreHandler->objectToArray($membreObj);

	$xoopsTpl->assign('membre', $membre);
	$xoopsTpl->assign('debutSaison', $saisonAnneeDebSaison);
	$xoopsTpl->assign('finSaison', $saisonAnneeFinSaison);
} else {
	$xoopsTpl->assign('membre', false);
}

// Selection du palmares
$palmaresHandler = xoops_getmodulehandler('palmares', 'club');
$palmares = $palmaresHandler->getPalmaresJoueur($id);
$xoopsTpl->assign('palmares', $palmares);

include(XOOPS_ROOT_PATH."/footer.php");

?>