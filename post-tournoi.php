<?php

require '../../mainfile.php';

$membretournoiHandler = xoops_getmodulehandler('membretournoi', 'club');
$_POST['membre_licence'] = $xoopsUser->getVar('uid');
$membretournoiHandler->enregInscriptions($_POST);

redirect_header("detail-tournoi.php?id=".$data['tournoi_id'], 3, "Mise à jour effectuée");

?>