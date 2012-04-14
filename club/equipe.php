<?php

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_equipe.html';
include XOOPS_ROOT_PATH.'/header.php';

// Tooltips include
$xoTheme->addScript('modules/club/include/ToolTips.js');
$xoTheme->addStylesheet('modules/club/include/ToolTips.css');

$equipeHandler = xoops_getmodulehandler('equipe', 'club');

$equipeData = $equipeHandler->getEquipeWithMembres(intval($_GET['id']));

$xoopsTpl->assign('equipeData', $equipeData);

include(XOOPS_ROOT_PATH."/footer.php");

?>