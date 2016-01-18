<?php

function cmpMembresAnniv($a, $b) {
	if ($a['membre_birth_mois'] == $b['membre_birth_mois']) {
		return ($a['membre_birth_jour'] < $b['membre_birth_jour']) ? -1 : 1;
	} else {
		if($a['membre_birth_annee'] != $b['membre_birth_annee']) {
			return ($a['membre_birth_annee'] != $b['membre_birth_annee']) ? -1 : 1;
		} else {
			return ($a['membre_birth_mois'] < $b['membre_birth_mois']) ? -1 : 1;
		}
	}
}

function clubAnnivShow($options) {
	
	$membreHandler = xoops_getmodulehandler('membre', 'club');
	
	$membres = $membreHandler->objectToArray($membreHandler->getMembreAnnivJour());
	$membreHandler->addMembresAnnivInfo($membres);
	usort($membres, "cmpMembresAnniv");
	
	if(count($membres) > 0) {
		return array('haveAnniv'=>true, 'membres'=>$membres);
	} else {
		return array('haveAnniv'=>false);
	}
}

function clubAnnivEdit($options) {
	
	return;
	
}

function clubNextTournoiShow($options) {
	
	$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
	
	$tournois = $tournoiHandler->objectToArray($tournoiHandler->getNextTournoi());
	$tournoiHandler->makeDatesString($tournois);
	
	return $tournois;
	
}

function clubNextTournoiEdit($options) {
	
	return;
	
}

function clubPalmaresLastTournoiShow($options) {
	
	$palmaresHandler = xoops_getmodulehandler('palmares', 'club');
	return $palmaresHandler->getPalmaresLastTournoi();
	
}

function clubPalmaresLastTournoiEdit($options) {
	
	return;
	
}

?>
