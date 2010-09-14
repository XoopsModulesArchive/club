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

	case 'sendLogin':
		
		xoops_cp_header();
		adminMenu(3);
		
		if(file_exists(XOOPS_ROOT_PATH.'/language/french/user.php')) {
			include XOOPS_ROOT_PATH.'/language/french/user.php';
		} else {
			include XOOPS_ROOT_PATH.'/language/english/user.php';
		}
		
		$member_handler =& xoops_gethandler('member');
		$getuser =& $member_handler->getUsers(new Criteria('uid', $_GET['id']));
		$email = $getuser[0]->getVar('email');
		$areyou = substr($getuser[0]->getVar("pass"), 0, 5);
	
		$xoopsMailer =& getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setTemplate("lostpass1.tpl");
        $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
        $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
        $xoopsMailer->assign("SITEURL", XOOPS_URL."/");
        $xoopsMailer->assign("IP", $_SERVER['REMOTE_ADDR']);
        $xoopsMailer->assign("NEWPWD_LINK", XOOPS_URL."/lostpass.php?email=".$email."&code=".$areyou);
        $xoopsMailer->setToUsers($getuser[0]);
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->setSubject(sprintf(_US_NEWPWDREQ, $xoopsConfig['sitename']));
		
        if ( !$xoopsMailer->send() ) {
            echo $xoopsMailer->getErrors();
        }
        echo "<h4>";
        printf(_US_CONFMAIL, $getuser[0]->getVar("uname"));
        echo "</h4>";
		
		xoops_cp_footer();
	
		break;

	case 'default':
	default:

		switch($step) {

			case 'enreg':

				$membreHandler = xoops_getmodulehandler('membre', 'club');

				foreach($_POST['xoops_upload_file'] as $licence) {

					$havePhoto = 0;
					if($_FILES[$licence]['error'] == 0) {
						move_uploaded_file($_FILES[$licence]['tmp_name'],XOOPS_ROOT_PATH.'/uploads/club/joueurs/'.$licence.'.gif');
						$havePhoto = 1;
					}

					$sql = "UPDATE ".$membreHandler->table." SET `fonction_id` = '".$_POST['fonction'][$licence].
							"', `membre_equipe` = '".$_POST['equipe'][$licence];
					if($havePhoto == 1) {
						$sql .= "', `membre_photo` = '".$havePhoto;
					}
					$sql .= "' WHERE `membre_licence` = ".$licence.";";
					$membreHandler->db->query($sql);
				}

				redirect_header("membre.php", 3, "Mise à jour effectuée");

				break;

			case 'default':
			default:

				xoops_cp_header();
				adminMenu(3);

				$membreHandler = xoops_getmodulehandler('membre', 'club');
				$fonctionHandler = xoops_getmodulehandler('fonction', 'club');
				$equipeHandler = xoops_getmodulehandler('equipe', 'club');

				$membres = $membreHandler->getAllActiveMembres();
				$fonctions = $fonctionHandler->getFonctions();
				$equipes = $equipeHandler->getAllEquipes();

				echo '<form method="post" action="membre.php" enctype="multipart/form-data">'."\n";
				echo '<table class="outer" style="text-align:center; width:100%;">'."\n";
				echo '<tr>'."\n";
				echo '<th>PHOTO</th>'."\n";
				echo '<th>NOM</th>'."\n";
				echo '<th>SEXE</th>'."\n";
				echo '<th>EQUIPE</th>'."\n";
				echo '<th>BUREAU</th>'."\n";
				echo '<th>Envoi Infos</th>'."\n";
				echo '</tr>'."\n";
				$i = 0;
				foreach($membres as $membre) {
					$photo = $membre->getVar('membre_photo') ? $membre->getVar('membre_licence') : '00000000' ;
					$formFile = new XoopsFormFile('', $membre->getVar('membre_licence'), 200000);
					$prenom = $membre->getVar('membre_prenom');
					$nom = $membre->getVar('membre_nom');
					$sexe = $membre->getVar('membre_sexe');


					$equipeForm = '<select name="equipe['.$membre->getVar('membre_licence').']">'."\n";
					if($membre->getVar('membre_equipe') == 0) {
						$equipeForm .= '<option selected="selected" value="0">&nbsp;</option>'."\n";
					} else {
						$equipeForm .= '<option value="0">&nbsp;</option>'."\n";
					}
					foreach($equipes as $equipe) {
						if($membre->getVar('membre_equipe') == $equipe->getVar('equipe_id')) {
							$equipeForm .= '<option selected="selected" value="'.$equipe->getVar('equipe_id').'">Equipe '.$equipe->getVar('equipe_id').'</option>'."\n";
						} else {
							$equipeForm .= '<option value="'.$equipe->getVar('equipe_id').'">Equipe '.$equipe->getVar('equipe_id').'</option>'."\n";
						}
					}
					$equipeForm .= '</select>';


					$fonctionForm = '<select name="fonction['.$membre->getVar('membre_licence').']">'."\n";
					if($membre->getVar('fonction_id') == 0) {
						$fonctionForm .= '<option selected="selected" value="0">&nbsp;</option>'."\n";
					} else {
						$fonctionForm .= '<option value="0">&nbsp;</option>'."\n";
					}
					foreach($fonctions as $fonction) {
						if($membre->getVar('fonction_id') == $fonction->getVar('fonction_id')) {
							$fonctionForm .= '<option selected="selected" value="'.$fonction->getVar('fonction_id').'">'.$fonction->getVar('fonction_nom').'</option>'."\n";
						} else {
							$fonctionForm .= '<option value="'.$fonction->getVar('fonction_id').'">'.$fonction->getVar('fonction_nom').'</option>'."\n";
						}
					}
					$fonctionForm .= '</select>';

					echo '<tr class="'.(($i++)%2 == 0 ? "even" : "odd").'">'."\n";
					echo '<td><img src="'.XOOPS_URL.'/uploads/club/joueurs/'.$photo.'.gif" alt="'.$prenom.' '.$nom.'" title="'.$prenom.' '.$nom.'" /><br /><br />'.$formFile->render().'</td>'."\n";
					echo '<td style="vertical-align:middle;">'.$prenom.' '.$nom.'</td>'."\n";
					echo '<td style="vertical-align:middle;"><img src="'.XOOPS_URL.'/modules/club/images/'.$sexe.'.gif" alt="Genre : Masculin ou Féminin" /></td>'."\n";
					echo '<td>'.$equipeForm.'</td>'."\n";
					echo '<td>'.$fonctionForm.'</td>'."\n";
					echo '<td><a href="membre.php?op=sendLogin&id='.$membre->getVar("membre_licence").'"><img src="../images/email.gif" title="Renvoyer les informations de connection à '.$prenom.' '.$nom.'" alt="Renvoyer les informations de connection à '.$prenom.' '.$nom.'" /></a></td>'."\n";
					echo '</tr>'."\n";
				}
				echo '<tr><th colspan="6"><input type="hidden" name="step" value="enreg" /><input type="submit" name="Valider" /></th></tr>'."\n";
				echo '</table>'."\n";
				echo '</form>'."\n";

				xoops_cp_footer();

				break;
			}

		break;

}

?>
