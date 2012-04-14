<?php

// Fonction pour trier le bureau
function sortBureauMembre($a, $b)
{
	if($a['fonction_ordre'] == $b['fonction_ordre']) {
		return 0;
	}
	return ($a['fonction_ordre'] > $b['fonction_ordre']) ? 1 : -1 ;
}

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_bureau.html';
include XOOPS_ROOT_PATH.'/header.php';

$membreHandler = xoops_getmodulehandler('membre', 'club');
$fonctionHandler = xoops_getmodulehandler('fonction', 'club');

$membres = $membreHandler->objectToArray($membreHandler->getBureau());
$fonctions = $fonctionHandler->getFonctions();


foreach($membres as $k=>$v) {
	$membres[$k]['fonction_nom'] = $fonctions[$v['fonction_id']]->getVar('fonction_nom');
	$membres[$k]['fonction_ordre'] = $fonctions[$v['fonction_id']]->getVar('fonction_ordre');
}

usort($membres, "sortBureauMembre");

$xoopsTpl->assign('membres', $membres);

include(XOOPS_ROOT_PATH."/footer.php");

?>