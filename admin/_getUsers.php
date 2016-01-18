<?php

$_COOKIE['PHPSESSID'] = $_GET['PHPSESSID'];

header('Content-Type: text/html; charset=ISO-8859-15');

include '../../../include/cp_header.php';
include '../../../class/xoopsformloader.php';

function _sortUsers(&$u1, &$u2) {
	$u1String = explode(" ", $u1->getVar('name'));
	$u2String = explode(" ", $u2->getVar('name'));
	
	$u1Prenom = $u1String[0];
	array_shift($u1String);
	$u1Nom = implode(" ", $u1String);
	
	$u2Prenom = $u2String[0];
	array_shift($u2String);
	$u2Nom = implode(" ", $u2String);
	
	$comp = strnatcmp($u1Nom, $u2Nom);
	
	if($comp != 0) {
		return $comp;
	}
	
	return strnatcmp($u1Prenom, $u2Prenom);
}

function getUsers($groups, $saisons, $cats) {

	$membreHandler = xoops_getmodulehandler('membre', 'club');
	$membresaisonHandler = xoops_getmodulehandler('membresaison', 'club');
	$userHandler = xoops_gethandler('user');

	$criteria = new CriteriaCompo();
	
	if(!in_array(0, $groups)) {
		// Bureau
		if(in_array(-1, $groups)) {
			$criteriaBureau = new CriteriaCompo();
			$criteriaBureau->add(new Criteria('fonction_id',0,'!='));
			unset($groups[array_search(-1, $groups)]);
			$criteria->add($criteriaBureau, 'OR');
		}
		
		// Tout le monde
		if(in_array(0, $groups)) {
			$criteriaAll = new CriteriaCompo();
			unset($groups[array_search(0, $groups)]);
			$criteria->add($criteriaAll, 'OR');
		}
		
		// Equipe
		$haveEquipe = false;
		$criteriaEquipe = new CriteriaCompo();
		$criteriaEquipeTmp = new CriteriaCompo();
		foreach ($groups as $equipe) {
			$criteriaEquipeTmp->add(new criteria('membre_equipe', $equipe), 'OR');
			$haveEquipe = true;
		}
		if($haveEquipe) {
			$criteriaEquipe->add($criteriaEquipeTmp);
			$criteria->add($criteriaEquipe, 'OR');
		}
	}
	
	// Saison
	if(!in_array(0, $saisons)) {
		$criteriaSaisonTmp = new CriteriaCompo();
		foreach ($saisons as $saison) {
			$criteriaSaisonTmp->add(new criteria('saison_saison', $saison), 'OR');
		}
		$membres = $membresaisonHandler->getObjects($criteriaSaisonTmp);
		$in = '(';
		foreach($membres as $membre) {
			$in .= $membre->getVar('membre_licence').', ';
		}
		$in = substr($in, 0, -2);
		$in .= ')';
		$criteriaSaison = new Criteria('membre_licence', $in, 'IN');
		$criteria->add($criteriaSaison);
	}
	
	// Catégories
	if(!in_array(0, $cats)) {
	
		$catArray = array(1=>'Poussin', 2=>'Benjamin', 3=>'Minime', 4=>'Cadet', 5=>'Junior', 6=>'Senior', 7=>'Vétéran');
	
		$criteriaCatTmp = new CriteriaCompo();
		foreach ($cats as $cat) {
			$criteriaCatTmp->add(new criteria('membre_categorie', $catArray[$cat]), 'OR');
		}
		$membres = $membreHandler->getObjects($criteriaCatTmp);
		$in = '(';
		foreach($membres as $membre) {
			$in .= $membre->getVar('membre_licence').', ';
		}
		$in = substr($in, 0, -2);
		$in .= ')';
		$criteriaCat = new Criteria('membre_licence', $in, 'IN');
		$criteria->add($criteriaCat);
	}
	
	
	$criteria->setSort('membre_nom,membre_prenom');
	$membres = $membreHandler->getObjects($criteria, true);

	$count = count($membres);
	if($count > 0) {
		$in = '(';
		foreach($membres as $membre) {
			$in .= $membre->getVar('membre_licence').', ';
		}
		$in = substr($in, 0, -2);
		$in .= ')';
		$criteria = new Criteria('uid', $in, 'IN');
	} else {
		$criteria = new Criteria('uid', '(0)', 'IN');
	}

	return $userHandler->getObjects($criteria);
}

$groups = explode("|", $_GET['groups']);
$saisons = explode("|", $_GET['saisons']);
$cats = explode("|", $_GET['cats']);

$users = getUsers($groups, $saisons, $cats);
usort($users, "_sortUsers");
$toUsers = new XoopsFormSelect('', "mail_to_users", -1, 10, true);
$selected = array();
foreach($users as $user) {
	$selected[] = $user->getVar('uid');
	$toUsers->addOption($user->getVar('uid'), $user->getVar('name'));
}
$toUsers->setValue($selected);

echo count($users)." utilisateurs trouvés :<br />";
echo $toUsers->render();

?>
