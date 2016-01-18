<?php

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_tournois.html';
include XOOPS_ROOT_PATH.'/header.php';

$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');

$tournois = $tournoiHandler->objectToArray($tournoiHandler->getObjects());
$tournoiHandler->makeDatesString($tournois);

$xoopsTpl->assign('tournois', $tournois);

include(XOOPS_ROOT_PATH."/footer.php");

?>