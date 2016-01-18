<?php

require '../../mainfile.php';

$GLOBALS['xoopsOption']['template_main'] = 'club_detail-tournoi.html';
include XOOPS_ROOT_PATH.'/header.php';
include XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

if(isset($_GET['id'])) {
  $id = intval($_GET['id']);
} else {
  $id = 0;
}

function cmpMembresTournoi($a, $b) {
	if ($a['membre']['membre_nom'] == $b['membre']['membre_nom']) {
		return strnatcmp($a['membre']['membre_prenom'], $a['membre']['membre_prenom']);
	}
	return strnatcmp($a['membre']['membre_nom'], $b['membre']['membre_nom']);
}

$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
$membreHandler = xoops_getmodulehandler('membre', 'club');
$membretournoiHandler = xoops_getmodulehandler('membretournoi', 'club');
$palmaresHandler = xoops_getmodulehandler('palmares', 'club');

$tournoi = $tournoiHandler->objectToArray($tournoiHandler->get($id));
$tournoiHandler->makeDateString($tournoi);
$xoopsTpl->assign('tournoi', $tournoi);

$seriesTournoi = explode('|',$tournoi['tournoi_series']);

$membrestournoi = $membretournoiHandler->objectToArray($membretournoiHandler->getMembresTournoi($id),array('membre_licence','membretournoi_d_partenaire','membretournoi_m_partenaire'));
usort($membrestournoi, "cmpMembresTournoi");
$xoopsTpl->assign('membres', $membrestournoi);

// Si la date du tournoi est dépassée, on supprime le formulaire d'inscription
if($tournoi['tournoi_inscr_date'] > time()) {
	
	$xoopsTpl->assign('pastTournoi', false);
	
	$licence = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
	if(!$xoopsUser) {
		$xoopsTpl->assign('canSubscribe', false);
		$xoopsTpl->assign('canSubscribeMess', "Connectez vous pour pouvoir vous inscrire à ce tournoi");
	} elseif($membreHandler->getCount(new Criteria('membre_licence', $licence)) == 0) {
		$xoopsTpl->assign('canSubscribe', false);
		$xoopsTpl->assign('canSubscribeMess', "Vous ne pouvez pas vous inscrire à ce tournoi, contacter le webmaster");
	} else {

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('tournoi_id', $id));
		$criteria->add(new Criteria('membre_licence', $licence));
		$currentUserTournoi = $membretournoiHandler->objectToArray($membretournoiHandler->getObjects($criteria));

		// Si le membre connecté est inscrit au tournoi
		if(count($currentUserTournoi) == 1) {
			$sSerie = $currentUserTournoi[0]['membretournoi_s_serie'];
			$dSerie = $currentUserTournoi[0]['membretournoi_d_serie'];
			$mSerie = $currentUserTournoi[0]['membretournoi_m_serie'];
			$dPart = $currentUserTournoi[0]['membretournoi_is_double'] ? $currentUserTournoi[0]['membretournoi_d_partenaire'] : '-1';
			$mPart = $currentUserTournoi[0]['membretournoi_is_mixte'] ? $currentUserTournoi[0]['membretournoi_m_partenaire'] : '-1';
		} else {
			$sSerie = '';
			$dSerie = '';
			$mSerie = '';
			$dPart = '-1';
			$mPart = '-1';
		}

		$simpleSelect = new XoopsFormSelect('Simple', 'membretournoi_s_serie', $sSerie);
		$simpleSelect->addOption("","&nbsp;");
		$doubleSelect = new XoopsFormSelect('&nbsp;Double', 'membretournoi_d_serie', $dSerie);
		$doubleSelect->addOption("","&nbsp;");
		$mixteSelect = new XoopsFormSelect('&nbsp;Mixte', 'membretournoi_m_serie', $mSerie);
		$mixteSelect->addOption("","&nbsp;");
		foreach($seriesTournoi as $serie) {
			$simpleSelect->addOption($serie);
			$doubleSelect->addOption($serie);
			$mixteSelect->addOption($serie);
		}

		// Récupération des données du membre connecté
		$clubMembre = $membreHandler->getObjects(new Criteria('membre_licence',$licence));

		// Selection des membres pour les doubles
		$criteriaDouble = new CriteriaCompo();
		$criteriaDouble->add(new Criteria('membre_nolicence',0));
		$criteriaDouble->add(new Criteria('membre_actif',1));
		$criteriaDouble->add(new Criteria('membre_licence',$licence,'!='));
		if($clubMembre[0]->getVar('membre_sexe') == 'H') {
			$criteriaDouble->add(new Criteria('membre_sexe','H'));
		} else {
			$criteriaDouble->add(new Criteria('membre_sexe','F'));
		}
		$criteriaDouble->setSort('membre_nom, membre_prenom');
		$membres = $membreHandler->getObjects($criteriaDouble);
		$partDoubleSelect = new XoopsFormSelect('avec', 'membretournoi_d_partenaire', $dPart);
		$partDoubleSelect->addOption("-1","&nbsp;");
		$partDoubleSelect->addOption("0","X");
		foreach($membres as $membre) {
			$partDoubleSelect->addOption($membre->getVar('membre_licence'),$membre->getVar('membre_nom').' '.$membre->getVar('membre_prenom'));
		}

		// Selection des membres pour le mixte
		$criteriaMixte = new CriteriaCompo();
		$criteriaMixte->add(new Criteria('membre_nolicence',0));
		$criteriaMixte->add(new Criteria('membre_actif',1));
		$criteriaMixte->add(new Criteria('membre_licence',$licence,'!='));
		if($clubMembre[0]->getVar('membre_sexe') == 'H') {
			$criteriaMixte->add(new Criteria('membre_sexe','F'));
		} else {
			$criteriaMixte->add(new Criteria('membre_sexe','H'));
		}
		$criteriaMixte->setSort('membre_nom, membre_prenom');
		$membres = $membreHandler->getObjects($criteriaMixte);
		$partMixteSelect = new XoopsFormSelect('avec', 'membretournoi_m_partenaire', $mPart);
		$partMixteSelect->addOption("-1","&nbsp;");
		$partMixteSelect->addOption("0","X");
		foreach($membres as $membre) {
			$partMixteSelect->addOption($membre->getVar('membre_licence'),$membre->getVar('membre_nom').' '.$membre->getVar('membre_prenom'));
		}

		$form = new XoopsSimpleForm('', 'addMembreTournoi', 'post-tournoi.php', 'post');
		$form->addElement($simpleSelect);
		$form->addElement($doubleSelect);
		$form->addElement($partDoubleSelect);
		$form->addElement($mixteSelect);
		$form->addElement($partMixteSelect);
		$form->addElement(new XoopsFormHidden("tournoi_id", $id));
		$form->addElement(new XoopsFormButton("", "form_submit", "Enregistrer", "submit"));
		$form->assign($xoopsTpl);

		$xoopsTpl->assign('canSubscribe', true);
	}
} else {
	$xoopsTpl->assign('pastTournoi', true);
	
	// Selection du palmares
	$palmares = $palmaresHandler->objectToArray($palmaresHandler->getPalmaresTournoi($id), array('membre_licence'));
	$xoopsTpl->assign('palmares', $palmares);
}

include(XOOPS_ROOT_PATH."/footer.php");

?>