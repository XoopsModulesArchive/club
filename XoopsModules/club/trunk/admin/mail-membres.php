<?php

include '../../../include/cp_header.php';
include '../../../class/xoopsformloader.php';
include '../class/clubmailer.php';
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

	case 'default':
	default:

		switch($step) {

			case 'send':

				xoops_cp_header();
				adminMenu(4);

				set_time_limit(120);

				if(isset($_POST['mail_to_users'])) {

					$myts =& MyTextSanitizer::getInstance();
					$userHandler = xoops_gethandler('user');
					$membreHandler = xoops_getmodulehandler('membre', 'club');

					$footer = "Sincères salutations,\r".$xoopsConfig['sitename']."\r(".XOOPS_URL.")\r{ADMINMAIL}";

					$xoopsMailer = new ClubMailer();
					$xoopsMailer->useMail();
					$membres = array();
					foreach($_POST['mail_to_users'] as $userId) {
						$xoopsMailer->setToUsers($userHandler->get($userId));
						$membres[$userId] = $membreHandler->getMembre($userId);
					}
					if($_POST['mail_from'] == 'site') {
						$fromMail = $GLOBALS['xoopsConfig']['adminmail'];
					} else {
						$fromMail = $xoopsUser->getVar('email');
					}
					$xoopsMailer->setFromEmail($fromMail);

					$footer = "\rSincères salutations,\r".$xoopsConfig['sitename']."\r(".XOOPS_URL.")\r".$fromMail;

					$xoopsMailer->setFromName($GLOBALS['xoopsConfig']['sitename']);
					$xoopsMailer->setSubject($myts->oopsStripSlashesGPC($_POST['mail_subject']));
					$xoopsMailer->setBody($myts->oopsStripSlashesGPC($_POST['mail_body']));

					$xoopsMailer->send(true, $membres);
					echo $xoopsMailer->getSuccess();
					echo $xoopsMailer->getErrors();

				}

				xoops_cp_footer();

				break;

			case 'default':
			default:

				xoops_cp_header();

				adminMenu(4);

				$equipeHandler = xoops_getmodulehandler('equipe', 'club');
				$membresaisonHandler = xoops_getmodulehandler('membresaison', 'club');

				define("_MD_AM_DBUPDATED","Database Updated Successfully");
				if ( file_exists(XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/mailusers.php") ) {
					include XOOPS_ROOT_PATH."/modules/system/language/".$xoopsConfig['language']."/admin/mailusers.php";
				} else {
					include XOOPS_ROOT_PATH."/modules/system/language/english/admin/mailusers.php";
				}


				$form = new XoopsThemeForm(_AM_SENDMTOUSERS, "mailusers", "mail-membres.php", 'post', true);

				$radio = "<input type='radio' name='mail_from' id='mail_from_site' value='site' checked='checked' /><b>".$GLOBALS['xoopsConfig']['sitename']."</b> (".$GLOBALS['xoopsConfig']['adminmail'].")<br />";
				$radio .= "<input type='radio' name='mail_from' id='mail_from_user' value='user' /><b>Vous</b> (".$xoopsUser->getVar('email').")";
				$fromRadio = new XoopsFormElementTray($radio);
				$from = new XoopsFormElementTray('De');
				$from->addElement($fromRadio);
				$form->addElement($from);

				$equipes = $equipeHandler->getAllEquipes();
		    $toGroup = new XoopsFormSelect('', "mail_to_group[]", 0, 5, true);
		    $toGroup->addOption(0, 'Tous les membres');
		    $toGroup->addOption(-1, 'Le bureau');
		    foreach ($equipes as $equipe) {
		    	$toGroup->addOption($equipe->getVar('equipe_id'), 'Equipe '.$equipe->getVar('equipe_id'));
		    }

				$year = date('Y');
				$month = date('n');
				if($month < 9) {
					$selectedSaison = ($year - 1).'-'.($year);
				} else {
					$selectedSaison = ($year).'-'.($year + 1);
				}
				$saisons = $membresaisonHandler->getSaisons();
				$toSaison = new XoopsFormSelect('', "mail_to_saison[]", $selectedSaison, 5, true);
		    $toSaison->addOption(0, 'Toutes les saisons');
		    foreach ($saisons as $saison) {
		    	$toSaison->addOption($saison['saison_saison']);
		    }

				$toCat = new XoopsFormSelect('', "mail_to_cat[]", 0, 5, true);
				$toCat->addOption(0, 'Toutes les catégories');
				$toCat->addOption(1, 'Poussin');
				$toCat->addOption(2, 'Benjamin');
				$toCat->addOption(3, 'Minime');
				$toCat->addOption(4, 'Cadet');
				$toCat->addOption(5, 'Junior');
				$toCat->addOption(6, 'Senior');
				$toCat->addOption(7, 'Vétéran');

				$to = new XoopsFormElementTray('Critères de selection des membres', ' et ');
				$to->addElement($toGroup);
				$to->addElement($toSaison);
				$to->addElement($toCat);
			    $form->addElement($to);

				$toUsersForm = new XoopsFormElementTray("<div id=\"membreSelect\"><select name=\"mail_to_users[]\" id=\"mail_to_users[]\"></select></div>");
				$toUser = new XoopsFormElementTray('Pour');
				$toUser->addElement($toUsersForm);
				$form->addElement($toUser);

				$subject_caption = "Sujet<br /><br /><span style='font-size:x-small;font-weight:bold;'>Tags utils :</span><br /><span style='font-size:x-small;font-weight:normal;'>{X_NOM} affichera le nom<br />{X_PRENOM} affichera le prénom</span>";
				$subject_text = new XoopsFormText($subject_caption, "mail_subject", 50, 255);
				$form->addElement($subject_text, true);

				$footer	= "\r\r\rSincères salutations,\r".$xoopsConfig['sitename']."\r(".XOOPS_URL.")\r".$GLOBALS['xoopsConfig']['adminmail'];
				$footerSite	= "\\r\\r\\rSincères salutations,\\r".$xoopsConfig['sitename']."\\r(".XOOPS_URL.")\\r".$GLOBALS['xoopsConfig']['adminmail'];
				$footerUser	= "\\r\\r\\rSincères salutations,\\r".$xoopsConfig['sitename']."\\r(".XOOPS_URL.")\\r".$xoopsUser->getVar('email');
				$body_caption = "Corps<br /><br /><span style='font-size:x-small;font-weight:bold;'>Tags utils :</span><br /><span style='font-size:x-small;font-weight:normal;'>{X_NOM} affichera le nom<br />{X_PRENOM} affichera le prénom<br />{X_UNAME} affichera le login<br />{X_UEMAIL} affichera l'email du membre</span>";
				$form->addElement(new XoopsFormTextArea('', "mail_body", "", 10), true);

				$op_hidden = new XoopsFormHidden("step", "send");
				$form->addElement($op_hidden);

				$submit_button = new XoopsFormButton("", "mail_submit", _SEND, "submit");
				$form->addElement($submit_button);

				$form->addElement($start_hidden);

				$form->display();

				echo '<script type="text/javascript" src="'.XOOPS_URL.'/modules/club/include/mootools-release-1.11.js"></script>';
				?>
				<script type="text/javascript">

						// Chargement initial de la liste des membres
						var url = '<?php echo XOOPS_URL; ?>/modules/club/admin/_getUsers.php?groups=0&saisons=<?php echo $selectedSaison;?>&cats=0&PHPSESSID=<?php echo $_COOKIE['PHPSESSID']; ?>';
						new Ajax(url, {
							method: 'get',
							update: $('membreSelect')
						}).request();

						function sendRequest() {

							// Groups séléctionnés
							var groups = "";
							var num = 0;
							for (i=0; i<$('mail_to_group[]').options.length; i++) {
							  if ($('mail_to_group[]').options[i].selected ) {
								if(num != 0) {
									groups = groups+"|";
								}
								groups = groups+$('mail_to_group[]').options[i].value;
								num++;
							  }
							}

							// Saisons séléctionnés
							var saisons = "";
							num = 0;
							for (i=0; i<$('mail_to_saison[]').options.length; i++) {
							  if ($('mail_to_saison[]').options[i].selected ) {
								if(num != 0) {
									saisons = saisons+"|";
								}
								saisons = saisons+$('mail_to_saison[]').options[i].value;
								num++;
							  }
							}

							// Catégories séléctionnés
							var cats = "";
							num = 0;
							for (i=0; i<$('mail_to_cat[]').options.length; i++) {
							  if ($('mail_to_cat[]').options[i].selected ) {
								if(num != 0) {
									cats = cats+"|";
								}
								cats = cats+$('mail_to_cat[]').options[i].value;
								num++;
							  }
							}

							var url = '<?php echo XOOPS_URL; ?>/modules/club/admin/_getUsers.php?groups='+groups+'&saisons='+saisons+'&cats='+cats+'&PHPSESSID=<?php echo $_COOKIE['PHPSESSID']; ?>';

							// Envoi de la requette Ajax
							new Ajax(url, {
								method: 'get',
								update: $('membreSelect')
							}).request();
						}

						window.addEvent('load', function(){
							$('mail_to_group[]').addEvent('change', function(e) {
								e = new Event(e).stop();
								sendRequest();
							});

							$('mail_to_saison[]').addEvent('change', function(e) {
								e = new Event(e).stop();
								sendRequest();
							});

							$('mail_to_cat[]').addEvent('change', function(e) {
								e = new Event(e).stop();
								sendRequest();
							});
						});


					</script>
					<?php

				xoops_cp_footer();

				break;
			}

		break;

}

?>
