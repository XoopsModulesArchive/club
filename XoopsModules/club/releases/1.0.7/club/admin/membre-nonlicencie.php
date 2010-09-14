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
			
				$userHandler = xoops_gethandler('user');
				$membreHandler = xoops_getmodulehandler('membre', 'club');
				
				$nom = strtoupper($_POST['membre_nom']);
				$prenom = ucwords(strtolower($_POST['membre_prenom']));
				$licence = isset($_POST['membre_licence']) ? $_POST['membre_licence'] : 0;
				
				if(!checkEmail($_POST['emailPerso'])) {
					redirect_header("membre-nonlicencie.php", 5, "L'adresse email n'a pas un format valide");
				}
				
				if(!preg_match('`[0123][0-9]-[01][0-9]-[0-9]{4}`', $_POST['membre_birth'])) {
					redirect_header("membre-nonlicencie.php", 5, "La date de naissance n'est pas au bon format (JJ-MM-AAAA)");
				}
				// Formattage de la date en date SQL
				$date = explode('-',$_POST['membre_birth']);
				$birth = $date[2].'-'.$date[1].'-'.$date[0];
				
				$membre = $membreHandler->create(false);
				$membre->setVars(array('membre_licence'=>$licence, 'membre_nom'=>$nom, 'membre_prenom'=>$prenom, 'membre_licence'=>$licence, 'membre_birth'=>$birth, 'membre_sexe'=>$_POST['membre_sexe'], 'membre_nolicence'=>1, 'membre_actif'=>$_POST['membre_actif']));
				$membreHandler->insert($membre, true);
				
				$user = $userHandler->get($licence);
				$user->setVars(array('uname'=>$prenom.' '.$nom, 'email'=>$_POST['emailPerso']));
				$userHandler->insert($user);
				
				redirect_header("membre-nonlicencie.php", 3, "Utilisateur modifié");
			
				break;
			
			case 'default':
			default:
				
				xoops_cp_header();
				adminMenu(2);

				$userHandler = xoops_gethandler('user');
				$membreHandler = xoops_getmodulehandler('membre', 'club');
				
				$criteria = new Criteria('membre_licence',intval($_POST['membre_licence']));
				$membre = $membreHandler->getObjects($criteria);
				
				$user = $userHandler->get(intval($_POST['membre_licence']));
				$email = $user ? $user->getVar('email') : '';
				
				// Formattage de la date en date SQL
				$date = explode('-',$membre[0]->getVar('membre_birth'));
				$birth = $date[2].'-'.$date[1].'-'.$date[0];
				
				$form = new XoopsThemeForm("Modification d'un membre non licencié", 'create_membre', 'membre-nonlicencie.php?op=modify', 'post', true);
				$form->addElement(new XoopsFormText("Nom", 'membre_nom', '30', '255', $membre[0]->getVar('membre_nom')),true);
				$form->addElement(new XoopsFormText("Prénom", 'membre_prenom', '30', '255', $membre[0]->getVar('membre_prenom')),true);
				$form->addElement(new XoopsFormText("Date de naissance (JJ-MM-AAAA)", 'membre_birth', '10', '10', $birth),true);
				$form->addElement(new XoopsFormText("Email", 'emailPerso', '30', '255', $email),true);
				$hfSelect = new XoopsFormSelect('Sexe', "membre_sexe", $membre[0]->getVar('membre_sexe'));
				$hfSelect->addOption('H', 'Homme');
				$hfSelect->addOption('F', 'Femme');
				$form->addElement($hfSelect,true);
				$form->addElement(new XoopsFormRadioYN("Membre actif", 'membre_actif', $membre[0]->getVar('membre_actif')));
				$form->addElement(new XoopsFormHidden("membre_licence", intval($_POST['membre_licence'])));
				$form->addElement(new XoopsFormHidden("step", 'enreg'));
				$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
				$form->display();
				
				xoops_cp_footer();
				
				break;
		
			break;
		}
		
		break;
		
	case 'default':
	default:

		switch($step) {

			case 'enreg':

				$membreHandler = xoops_getmodulehandler('membre', 'club');
				
				$nom = strtoupper($_POST['membre_nom']);
				$prenom = ucwords(strtolower($_POST['membre_prenom']));
				$licence = isset($_POST['membre_licence']) ? $_POST['membre_licence'] : 0;
				
				if(!checkEmail($_POST['emailPerso'])) {
					redirect_header("membre-nonlicencie.php", 5, "L'adresse email n'a pas un format valide");
				}
				
				if(!preg_match('`[0123][0-9]-[01][0-9]-[0-9]{4}`', $_POST['membre_birth'])) {
					redirect_header("membre-nonlicencie.php", 5, "La date de naissance n'est pas au bon format (JJ-MM-AAAA)");
				}
				// Formattage de la date en date SQL
				$date = explode('-',$_POST['membre_birth']);
				$birth = $date[2].'-'.$date[1].'-'.$date[0];
				
				$membre = $membreHandler->create();
				$membre->setVars(array('membre_nom'=>$nom, 'membre_prenom'=>$prenom, 'membre_licence'=>$licence, 'membre_birth'=>$birth, 'membre_sexe'=>$_POST['membre_sexe'], 'membre_nolicence'=>1, 'membre_actif'=>1));

				$membreHandler->insert($membre, true);
				$membreHandler->_addXoopsMembre(array('licence'=>$licence, 'prenom'=>$prenom, 'nom'=>$nom, 'emailPerso'=>$_POST['emailPerso']));
				redirect_header("membre-nonlicencie.php", 3, "Utilisateur créé");
				
				break;

			case 'default':
			default:

				xoops_cp_header();
				adminMenu(2);

				$membreHandler = xoops_getmodulehandler('membre', 'club');
				$membres = $membreHandler->getAllNolicenceMembres();
				
				$form = new XoopsThemeForm("Modification d'un membre non licencié", 'modify_nolicence', 'membre-nonlicencie.php?op=modify', 'post', true);
				$membreSelect = new XoopsFormSelect('Membre', "membre_licence");
				$membreSelect->addOption(0, '&nbsp;');
				foreach($membres as $membre) {
					$membreSelect->addOption($membre->getVar('membre_licence'), $membre->getVar('membre_prenom').' '.$membre->getVar('membre_nom'));
				}
				$form->addElement($membreSelect,true);
				$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
				$form->display();
				
				$form = new XoopsThemeForm("Ajouter un membre non licencié", 'create_membre', 'membre-nonlicencie.php', 'post', true);
				$form->addElement(new XoopsFormText("Nom", 'membre_nom', '30', '255'),true);
				$form->addElement(new XoopsFormText("Prénom", 'membre_prenom', '30', '255'),true);
				$form->addElement(new XoopsFormText("Licence", 'membre_licence', '8', '8'),false);
				$form->addElement(new XoopsFormText("Date de naissance (JJ-MM-AAAA)", 'membre_birth', '10', '10'),true);
				$form->addElement(new XoopsFormText("Email", 'emailPerso', '30', '255'),true);
				$hfSelect = new XoopsFormSelect('Sexe', "membre_sexe", 'H');
				$hfSelect->addOption('H', 'Homme');
				$hfSelect->addOption('F', 'Femme');
				$form->addElement($hfSelect,true);
				$form->addElement(new XoopsFormHidden("step", 'enreg'));
				$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
				$form->display();
				
				xoops_cp_footer();

				break;
			}

		break;

}

?>
