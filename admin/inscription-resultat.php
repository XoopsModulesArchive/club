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

	case 'inscriptions':
		
		switch($step) {
			
			case 'enreg':
				
				$membretournoiHandler = xoops_getmodulehandler('membretournoi', 'club');
				
				foreach($_POST['membre'] as $membre) {
					$data = $_POST[$membre];
					$data['tournoi_id'] = $_POST['tournoi_id'];
					$data['membre_licence'] = $membre;
					$membretournoiHandler->enregInscriptions($data);
				}
				
				$data = $_POST['newUser'];
				$data['tournoi_id'] = $_POST['tournoi_id'];
				$membretournoiHandler->enregInscriptions($data);
				
				redirect_header("inscription-resultat.php", 3, "Mise à jour effectuée");
				
				break;
			
			case 'default':
			default:
				
				xoops_cp_header();
				adminMenu(6);
				
				function cmpMembresTournoi($a, $b) {
				    if ($a['membre']['membre_nom'] == $b['membre']['membre_nom']) {
				        return strnatcmp($a['membre']['membre_prenom'], $a['membre']['membre_prenom']);
				    }
				    return strnatcmp($a['membre']['membre_nom'], $b['membre']['membre_nom']);
				}
				
				$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
				$membreHandler = xoops_getmodulehandler('membre', 'club');
				$membretournoiHandler = xoops_getmodulehandler('membretournoi', 'club');
				
				$tournoi = $tournoiHandler->get($_POST['tournoi_id']);
				$membresTournoi = $membretournoiHandler->objectToArray($membretournoiHandler->getMembresTournoi($_POST['tournoi_id']),array('membre_licence'));
				usort($membresTournoi, "cmpMembresTournoi");
				
				echo '<form action="inscription-resultat.php?op=inscriptions" method="post">';
				echo '<table class="outer" style="width:100%;">';
				echo '<tr>';
				echo '<th colspan="4">'.$tournoi->getVar('tournoi_name').'</th>';
				echo '</tr>';
				echo '<tr style="text-align:center;">';
				echo '<th>Membre</th>';
				echo '<th>Simple</th>';
				echo '<th>Double</th>';
				echo '<th>Mixte</th>';
				echo '</tr>';
				
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('membre_nolicence',0));
				$criteria->add(new Criteria('membre_actif',1));
				$allMembres = $membreHandler->getObjects($criteria);
				
				$seriesTournoi = explode('|',$tournoi->getVar('tournoi_series'));
				
				$i=0;
				foreach($membresTournoi as $membre) {

					$simpleSerie = new XoopsFormSelect('', $membre["membre_licence"].'[membretournoi_s_serie]', $membre['membretournoi_s_serie']);
					$simpleSerie->addOption("","&nbsp;");
					$doubleSerie = new XoopsFormSelect('', $membre["membre_licence"].'[membretournoi_d_serie]', $membre['membretournoi_d_serie']);
					$doubleSerie->addOption("","&nbsp;");
					$mixteSerie = new XoopsFormSelect('', $membre["membre_licence"].'[membretournoi_m_serie]', $membre['membretournoi_m_serie']);
					$mixteSerie->addOption("","&nbsp;");
					
					foreach($seriesTournoi as $serie) {
						$simpleSerie->addOption($serie);
						$doubleSerie->addOption($serie);
						$mixteSerie->addOption($serie);
					}
					
					$dPart = $membre['membretournoi_is_double'] ? $membre['membretournoi_d_partenaire'] : '-1';
					$mPart = $membre['membretournoi_is_mixte'] ? $membre['membretournoi_m_partenaire'] : '-1';
					$doublePart = new XoopsFormSelect('', $membre["membre_licence"].'[membretournoi_d_partenaire]', $dPart);
					$doublePart->addOption("-1","&nbsp;");
					$doublePart->addOption("0","X");
					$mixtePart = new XoopsFormSelect('', $membre["membre_licence"].'[membretournoi_m_partenaire]', $mPart);
					$mixtePart->addOption("-1","&nbsp;");
					$mixtePart->addOption("0","X");
					foreach($allMembres as $allMembre) {
						if($allMembre->getVar('membre_licence') != $membre['membre']['membre_licence']) {
							if($membre['membre']['membre_sexe'] == $allMembre->getVar('membre_sexe')) {
								$doublePart->addOption($allMembre->getVar('membre_licence'), $allMembre->getVar('membre_nom').' '.$allMembre->getVar('membre_prenom'));
							}
							if($membre['membre']['membre_sexe'] != $allMembre->getVar('membre_sexe')) {
								$mixtePart->addOption($allMembre->getVar('membre_licence'), $allMembre->getVar('membre_nom').' '.$allMembre->getVar('membre_prenom'));
							}
						}
					}
					
					$class = ($i++%2 == 0) ? 'even' : 'odd';
					echo '<tr style="text-align:center;" class="'.$class.'">';
					echo '<td>'.$membre['membre']['membre_prenom'].' '.$membre['membre']['membre_nom'].'<input type="hidden" name="membre[]" value="'.$membre['membre_licence'].'" /></td>';
					echo '<td>'.$simpleSerie->render().'</td>';
					echo '<td>'.$doubleSerie->render().' '.$doublePart->render().'</td>';
					echo '<td>'.$mixteSerie->render().' '.$mixtePart->render().'</td>';
					echo '</tr>';
					
				}
				
				$simpleSerie = new XoopsFormSelect('', 'newUser[membretournoi_s_serie]');
				$simpleSerie->addOption("","&nbsp;");
				$doubleSerie = new XoopsFormSelect('', 'newUser[membretournoi_d_serie]');
				$doubleSerie->addOption("","&nbsp;");
				$mixteSerie = new XoopsFormSelect('', 'newUser[membretournoi_m_serie]');
				$mixteSerie->addOption("","&nbsp;");
				
				foreach($seriesTournoi as $serie) {
					$simpleSerie->addOption($serie);
					$doubleSerie->addOption($serie);
					$mixteSerie->addOption($serie);
				}
				
				$membresSelect = new XoopsFormSelect('', 'newUser[membre_licence]');
				$membresSelect->addOption("-1","&nbsp;");
				$doublePart = new XoopsFormSelect('', 'newUser[membretournoi_d_partenaire]');
				$doublePart->addOption("-1","&nbsp;");
				$doublePart->addOption("0","X");
				$mixtePart = new XoopsFormSelect('', 'newUser[membretournoi_m_partenaire]');
				$mixtePart->addOption("-1","&nbsp;");
				$mixtePart->addOption("0","X");
				foreach($allMembres as $allMembre) {
					$membresSelect->addOption($allMembre->getVar('membre_licence'), $allMembre->getVar('membre_nom').' '.$allMembre->getVar('membre_prenom'));
					$doublePart->addOption($allMembre->getVar('membre_licence'), $allMembre->getVar('membre_nom').' '.$allMembre->getVar('membre_prenom'));
					$mixtePart->addOption($allMembre->getVar('membre_licence'), $allMembre->getVar('membre_nom').' '.$allMembre->getVar('membre_prenom'));
				}
				
				echo '<tr><th colspan="4">Inscrire un membre à ce tournoi</th></tr>'."\n";
				
				echo '<tr style="text-align:center;">';
				echo '<td>'.$membresSelect->render().'</td>';
				echo '<td>'.$simpleSerie->render().'</td>';
				echo '<td>'.$doubleSerie->render().' '.$doublePart->render().'</td>';
				echo '<td>'.$mixteSerie->render().' '.$mixtePart->render().'</td>';
				echo '</tr>';
				
				echo '<tr><th colspan="4" style="text-align:center;"><input type="hidden" name="tournoi_id" value="'.$_POST['tournoi_id'].'" /><input type="hidden" name="step" value="enreg" /><input type="submit" name="Valider" value="Valider" /></th></tr>'."\n";
				echo '</table>';
				echo '</form>';
				
				xoops_cp_footer();
				
				break;
			
		}
		
		break;

	case 'addResult':
		
		switch($step) {
			
			case 'enreg':
				
				$membreTournoiHandler = xoops_getmodulehandler('membretournoi', 'club');
				$palmaresHandler = xoops_getmodulehandler('palmares', 'club');
				
				foreach($_POST['membre'] as $membreLicence) {
					
					// Récupération de l'inscription du membre
					$membre = $membreTournoiHandler->get(array($membreLicence, $_POST['tournoi_id']));
					
					// Si le membre a déja un palmares pour ce tournoi
					if($palmaresHandler->isPalmares($_POST['tournoi_id'], $membreLicence)) {
						$palmares = $palmaresHandler->get(array($membreLicence, $_POST['tournoi_id']));
					} else {
						$palmares = $palmaresHandler->create();
						$palmares->setVar('tournoi_id', $_POST['tournoi_id']);
						$palmares->setVar('membre_licence', $membreLicence);
					}
					
					// Si un résultat est renseigné pour le simple, on le renseigne. Sinon, on reset la valeur
					if(isset($_POST['simple'][$membreLicence])) {
						$palmares->setVar('palmares_s',$_POST['simple'][$membreLicence]);
					} else {
						$palmares->setVar('palmares_s',0);
					}
					// Si un résultat est renseigné pour le double, on le renseigne. Sinon, on reset la valeur
					if(isset($_POST['double'][$membreLicence])) {
						$palmares->setVar('palmares_d',$_POST['double'][$membreLicence]);
					} else {
						$palmares->setVar('palmares_d',0);
					}
					// Si un résultat est renseigné pour le mixte, on le renseigne. Sinon, on reset la valeur
					if(isset($_POST['mixte'][$membreLicence])) {
						$palmares->setVar('palmares_m',$_POST['mixte'][$membreLicence]);
					} else {
						$palmares->setVar('palmares_m',0);
					}
					
					// Si le membre est inscrit en double et pas avec X
					if($membre->getVar('membretournoi_is_double') && $membre->getVar('membretournoi_d_partenaire') > 0) {
						
						// Si le partenaire a déja un palmares pour ce tournoi
						if($palmaresHandler->isPalmares($_POST['tournoi_id'], $membre->getVar('membretournoi_d_partenaire'))) {
							$palmaresPDouble = $palmaresHandler->get(array($membre->getVar('membretournoi_d_partenaire'), $_POST['tournoi_id']));
						} else {
							$palmaresPDouble = $palmaresHandler->create();
							$palmaresPDouble->setVar('tournoi_id', $_POST['tournoi_id']);
							$palmaresPDouble->setVar('membre_licence', $membre->getVar('membretournoi_d_partenaire'));
						}
						$palmaresPDouble->setVar('palmares_d',$palmares->getVar('palmares_d'));
						
						// On enregistre si il y a un résultat dans au moins un tableau, sinon on delete
						if($palmaresPDouble->getVar('palmares_s') != 0 || $palmaresPDouble->getVar('palmares_d') != 0 || $palmaresPDouble->getVar('palmares_m') != 0) {
							$palmaresHandler->insert($palmaresPDouble);
						} else {
							$palmaresHandler->delete(array($palmaresPDouble->getVar('membre_licence'),$palmaresPDouble->getVar('tournoi_id')));
						}
					}
					
					// Si le membre est inscrit en mixte et pas avec X
					if($membre->getVar('membretournoi_is_mixte') && $membre->getVar('membretournoi_m_partenaire') > 0) {
						
						// Si le partenaire a déja un palmares pour ce tournoi
						if($palmaresHandler->isPalmares($_POST['tournoi_id'], $membre->getVar('membretournoi_m_partenaire'))) {
							$palmaresPMixte = $palmaresHandler->get(array($membre->getVar('membretournoi_m_partenaire'), $_POST['tournoi_id']));
						} else {
							$palmaresPMixte = $palmaresHandler->create();
							$palmaresPMixte->setVar('tournoi_id', $_POST['tournoi_id']);
							$palmaresPMixte->setVar('membre_licence', $membre->getVar('membretournoi_m_partenaire'));
						}
						$palmaresPMixte->setVar('palmares_m',$palmares->getVar('palmares_m'));
						
						// On enregistre si il y a un résultat dans au moins un tableau, sinon on delete
						if($palmaresPMixte->getVar('palmares_s') != 0 || $palmaresPMixte->getVar('palmares_d') != 0 || $palmaresPMixte->getVar('palmares_m') != 0) {
							$palmaresHandler->insert($palmaresPMixte);
						} else {
							$palmaresHandler->delete(array($palmaresPMixte->getVar('membre_licence'),$palmaresPMixte->getVar('tournoi_id')));
						}
					}
					
					// On enregistre si il y a un résultat dans au moins un tableau, sinon on delete
					if($palmares->getVar('palmares_s') != 0 || $palmares->getVar('palmares_d') != 0 || $palmares->getVar('palmares_m') != 0) {
						$palmaresHandler->insert($palmares);
					} else {
						$palmaresHandler->delete(array($palmares->getVar('membre_licence'),$palmares->getVar('tournoi_id')));
					}
				}
				
				redirect_header("inscription-resultat.php", 3, "Mise à jour effectuée");
				
				break;
			
			case 'default':
			default:
				
				xoops_cp_header();
				adminMenu(6);
				
				function cmpMembresTournoi($a, $b) {
				    if ($a['membre']['membre_nom'] == $b['membre']['membre_nom']) {
				        return strnatcmp($a['membre']['membre_prenom'], $a['membre']['membre_prenom']);
				    }
				    return strnatcmp($a['membre']['membre_nom'], $b['membre']['membre_nom']);
				}
				
				$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
				$membretournoiHandler = xoops_getmodulehandler('membretournoi', 'club');
				$palmaresHandler = xoops_getmodulehandler('palmares', 'club');
				
				$tournoi = $tournoiHandler->get($_POST['tournoi_id']);
				$membresTournoi = $membretournoiHandler->objectToArray($membretournoiHandler->getMembresTournoi($_POST['tournoi_id']),array('membre_licence'));
				usort($membresTournoi, "cmpMembresTournoi");
				
				echo '<form action="inscription-resultat.php?op=addResult" method="post">';
				echo '<table class="outer" style="width:100%;">';
				echo '<tr>';
				echo '<th colspan="4">'.$tournoi->getVar('tournoi_name').'</th>';
				echo '</tr>';
				echo '<tr style="text-align:center;">';
				echo '<th>Membre</th>';
				echo '<th>Simple</th>';
				echo '<th>Double</th>';
				echo '<th>Mixte</th>';
				echo '</tr>';
				
				$resultArray = array(0=>'&nbsp;', -1=>'Vainqueur', '1'=>'Finaliste', '2'=>'1/2 Finale', '4'=>'1/4 de Finale', '8'=>'1/8 de Finale', '16'=>'1/16 de finale');
				
				if(count($membresTournoi) > 0) {
				
					$i=0;
					foreach($membresTournoi as $membre) {
					
						$palmares = $palmaresHandler->get(array($membre["membre_licence"], $_POST['tournoi_id']));
						
						$simpleSelect = new XoopsFormSelect('', 'simple['.$membre["membre_licence"].']', $palmares->getVar('palmares_s'));
						$simpleSelect->addOptionArray($resultArray);
						$doubleSelect = new XoopsFormSelect('', 'double['.$membre["membre_licence"].']', $palmares->getVar('palmares_d'));
						$doubleSelect->addOptionArray($resultArray);
						$mixteSelect = new XoopsFormSelect('', 'mixte['.$membre["membre_licence"].']', $palmares->getVar('palmares_m'));
						$mixteSelect->addOptionArray($resultArray);
					
						$class = ($i++%2 == 0) ? 'even' : 'odd';
						echo '<tr style="text-align:center;" class="'.$class.'">';
						echo '<td>'.$membre['membre']['membre_prenom'].' '.$membre['membre']['membre_nom'].'<input type="hidden" name="membre[]" value="'.$membre['membre_licence'].'" /></td>';
						if($membre['membretournoi_is_simple']) {
							echo '<td>'.$simpleSelect->render().'</td>';
						} else {
							echo '<td>Pas inscrit</td>';
						}
						if($membre['membretournoi_is_double']) {
							echo '<td>'.$doubleSelect->render().'</td>';
						} else {
							echo '<td>Pas inscrit</td>';
						}
						if($membre['membretournoi_is_mixte']) {
							echo '<td>'.$mixteSelect->render().'</td>';
						} else {
							echo '<td>Pas inscrit</td>';
						}
						echo '</tr>';
					}
				} else {
					echo '<tr><td colspan="4">Pas de résultats à enregistrer pour ce tournoi</td></tr>';
				}
				echo '<tr><th colspan="4" style="text-align:center;"><input type="hidden" name="tournoi_id" value="'.$_POST['tournoi_id'].'" /><input type="hidden" name="step" value="enreg" /><input type="submit" name="Valider" value="Valider" /></th></tr>'."\n";
				echo '</table>';
				echo '</form>';
				
				xoops_cp_footer();
				
				break;
			
		}
		
		break;

	case 'default':
	default:

		xoops_cp_header();
		adminMenu(6);
		
		$tournoiHandler = xoops_getmodulehandler('tournoi', 'club');
		
		$tournois = $tournoiHandler->getObjects();
		
		$form = new XoopsThemeForm("Gestion des inscriptions", 'inscr_tournoi', 'inscription-resultat.php?op=inscriptions', 'post', true);
		$tournoiSelect = new XoopsFormSelect('Tournoi', "tournoi_id");
		foreach($tournois as $tournoi) {
			$tournoiSelect->addOption($tournoi->getVar('tournoi_id'), $tournoi->getVar('tournoi_name'));
		}
		$form->addElement($tournoiSelect,true);
		$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
		$form->display();
		echo "<br />";
		
		$form = new XoopsThemeForm("Ajouter des résultats d'un tournoi", 'result_tournoi', 'inscription-resultat.php?op=addResult', 'post', true);
		$tournoiSelect = new XoopsFormSelect('Tournoi', "tournoi_id");
		foreach($tournois as $tournoi) {
			$tournoiSelect->addOption($tournoi->getVar('tournoi_id'), $tournoi->getVar('tournoi_name'));
		}
		$form->addElement($tournoiSelect,true);
		$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
		$form->display();
		
		xoops_cp_footer();

		break;

}

?>
