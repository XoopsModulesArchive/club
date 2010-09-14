<?php

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_membres.html';
include XOOPS_ROOT_PATH.'/header.php';

// Tooltips include
$xoTheme->addScript('modules/club/include/ToolTips.js');
$xoTheme->addStylesheet('modules/club/include/ToolTips.css');

$membreHandler = xoops_getmodulehandler('membre', 'club');

$limit = 150;
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('membre_actif',1));
$criteria->add(new Criteria('membre_nolicence',0));
$nbMembres = $membreHandler->getCount($criteria);

if($nbMembres < $limit) {

	$membres = $membreHandler->objectToArray($membreHandler->getAllActiveMembres(null, true));

	$xoopsTpl->assign('nbMembres', $nbMembres);
	$xoopsTpl->assign('lettreSelect', '');

} else {

	if(isset($_GET['list']) && strlen($_GET['list']) == 1) {
		if($_GET['list'] >= 'A' && $_GET['list'] <= 'Z') {
			$list = $_GET['list'];
		} else {
			$list = 'A';
		}
	} else {
		$list = 'A';
	}

	$nbMembres = $membreHandler->getAlphaCount();

	$membres = $membreHandler->objectToArray($membreHandler->getAllActiveMembres($list, true));

	$xoopsTpl->assign('nbMembres', $nbMembres['total']);
	unset($nbMembres['total']);
	$xoopsTpl->assign('nbMembresAlpha', $nbMembres);
	$xoopsTpl->assign('lettreSelect', $list);

}

$xoopsTpl->assign('limit', $limit);
$xoopsTpl->assign('membres', $membres);

include(XOOPS_ROOT_PATH."/footer.php");

?>