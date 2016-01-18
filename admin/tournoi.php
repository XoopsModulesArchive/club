<?php

include '../../../include/cp_header.php';
include '../../../class/xoopsformloader.php';
include 'function.php';

if(isset($_GET['op'])) {
	$op = $_GET['op'];
} else {
	$op = 'default';
}

if(isset($_POST['step'])) {
	$step = $_POST['step'];
} else {
	$step = 'default';
}

switch($op) {

	case 'modify':
		
		switch($step) {
			
			case 'enreg':
				
				$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
				
				list($year,$month,$day) = explode("-",$_POST['tournoi_inscr_date']);
				$_POST['tournoi_inscr_date'] = mktime(0,0,0,$month,$day,$year);
				list($year,$month,$day) = explode("-",$_POST['tournoi_start_date']);
				$_POST['tournoi_start_date'] = mktime(0,0,0,$month,$day,$year);
				list($year,$month,$day) = explode("-",$_POST['tournoi_end_date']);
				$_POST['tournoi_end_date'] = mktime(0,0,0,$month,$day,$year);
				
				$tournoi = $tournoiHandler->create(false);
				$tournoi->setVars($_POST);
				if($tournoiHandler->checkSeries($tournoi)) {
					$tournoiHandler->insert($tournoi);
				}

				redirect_header("tournoi.php", 3, "Mise à jour effectuée");
				
				break;
			
			case 'default':
			default:
				
				xoops_cp_header();
				adminMenu(5);
				
				$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
				$tournoi = $tournoiHandler->get($_POST['tournoi_id']);

				$form = new XoopsThemeForm("Modifier un tournoi", 'modify_tournoi', 'tournoi.php?op=modify', 'post', true);
				$form->addElement(new XoopsFormText("Nom", 'tournoi_name', '30', '255', $tournoi->getVar('tournoi_name')),true);
				$form->addElement(new XoopsFormDhtmlTextArea("Description", 'tournoi_desc', $tournoi->getVar('tournoi_desc')),true);
				$seriesTBox = new XoopsFormText("Séries concernées", 'tournoi_series', '30', '255', $tournoi->getVar('tournoi_series'));
				$seriesTBox->setDescription("Séries séparées par un <b>|</b>.<br />Par exemple : C|D|NC");
				$form->addElement($seriesTBox,true);
				$form->addElement(new XoopsFormTextDateSelect('Limite d\'inscription', 'tournoi_inscr_date', 15, $tournoi->getVar('tournoi_inscr_date')), true);
				$form->addElement(new XoopsFormTextDateSelect('Début', 'tournoi_start_date', 15, $tournoi->getVar('tournoi_start_date')), true);
				$form->addElement(new XoopsFormTextDateSelect('Fin', 'tournoi_end_date', 15, $tournoi->getVar('tournoi_end_date')), true);
				$form->addElement(new XoopsFormHidden("tournoi_id", $_POST['tournoi_id']));
				$form->addElement(new XoopsFormHidden("step", 'enreg'));
				$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
				$form->display();
				
				xoops_cp_footer();
				
				break;
			
		}
		
		break;

	case 'default':
	default:

		switch($step) {

			case 'enreg':

				$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
				
				list($year,$month,$day) = explode("-",$_POST['tournoi_inscr_date']);
				$_POST['tournoi_inscr_date'] = mktime(0,0,0,$month,$day,$year);
				list($year,$month,$day) = explode("-",$_POST['tournoi_start_date']);
				$_POST['tournoi_start_date'] = mktime(0,0,0,$month,$day,$year);
				list($year,$month,$day) = explode("-",$_POST['tournoi_end_date']);
				$_POST['tournoi_end_date'] = mktime(0,0,0,$month,$day,$year);
				
				$tournoi = $tournoiHandler->create();
				$tournoi->setVars($_POST);
				if($tournoiHandler->checkSeries($tournoi)) {
					$tournoiHandler->insert($tournoi);
				}

				redirect_header("tournoi.php", 3, "Mise à jour effectuée");

				break;

			case 'default':
			default:

				xoops_cp_header();
				adminMenu(5);
				
				$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
				
				$tournois = $tournoiHandler->getObjects();
				
				$form = new XoopsThemeForm("Modifier un tournoi", 'modify_tournoi', 'tournoi.php?op=modify', 'post', true);
				$tournoiSelect = new XoopsFormSelect('Tournoi', "tournoi_id");
				foreach($tournois as $tournoi) {
					$tournoiSelect->addOption($tournoi->getVar('tournoi_id'), $tournoi->getVar('tournoi_name'));
				}
				$form->addElement($tournoiSelect,true);
				$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
				$form->display();
				
				echo "<br />";
				
				$form = new XoopsThemeForm("Ajouter un tournoi", 'create_tournoi', 'tournoi.php', 'post', true);
				$form->addElement(new XoopsFormText("Nom", 'tournoi_name', '30', '255'),true);
				$form->addElement(new XoopsFormDhtmlTextArea("Description", 'tournoi_desc', ''),true);
				$seriesTBox = new XoopsFormText("Séries concernées", 'tournoi_series', '30', '255');
				$seriesTBox->setDescription("Séries séparées par un <b>|</b>.<br />Par exemple : C|D|NC");
				$form->addElement($seriesTBox,true);
				$form->addElement(new XoopsFormTextDateSelect('Limite d\'inscription', 'tournoi_inscr_date', 15, time()), true);
				$form->addElement(new XoopsFormTextDateSelect('Début', 'tournoi_start_date', 15, time()), true);
				$form->addElement(new XoopsFormTextDateSelect('Fin', 'tournoi_end_date', 15, time()), true);
				$form->addElement(new XoopsFormHidden("step", 'enreg'));
				$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
				$form->display();

				xoops_cp_footer();

				break;
			}

		break;

}

?>
