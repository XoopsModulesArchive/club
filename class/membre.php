<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubMembre extends XoopsObject
{

	function ClubMembre()
	{
		$this->initVar('membre_licence', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membre_nom', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('membre_prenom', XOBJ_DTYPE_TXTBOX, '', true, 255);
		$this->initVar('membre_birth', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('membre_categorie', XOBJ_DTYPE_TXTBOX, 'Senior', false, 255);
		$this->initVar('membre_sexe', XOBJ_DTYPE_TXTBOX, 'H', false, 255);
		$this->initVar('membre_class_s', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_class_d', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_class_m', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_moy_s', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_moy_d', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_moy_m', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_moyd_s', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_moyd_d', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_moyd_m', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_mclass_s', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_mclass_d', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_mclass_m', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_mmoy_s', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_mmoy_d', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_mmoy_m', XOBJ_DTYPE_TXTBOX, '0.0', false, 255);
		$this->initVar('membre_vclass_s', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_vclass_d', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('membre_vclass_m', XOBJ_DTYPE_TXTBOX, 'NC', false, 255);
		$this->initVar('fonction_id', XOBJ_DTYPE_INT, 0, false, 255);
		$this->initVar('membre_equipe', XOBJ_DTYPE_INT, 0, false, 255);
		$this->initVar('membre_actif', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membre_nolicence', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('membre_photo', XOBJ_DTYPE_INT, 0, false);
	}

}

class ClubMembreHandler extends ClubPersistableObjectHandler {

	// Couleur des camenbert
	var $_camenbertColor = array('#0000FF','#FF0000','#0099FF','#99FF00','#00FF00','#FF9900','#9900FF');

	function ClubMembreHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_membre', 'ClubMembre', 'membre_licence');

	}

	function getAllActiveMembres($list = null, $excludeNL = false) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		if($excludeNL) {
			$criteria->add(new Criteria('membre_nolicence',0));
		}
		if($list != null) {
			$criteria->add(new Criteria('membre_nom',$list.'%','LIKE'));
		}
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getAllNolicenceMembres() {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_nolicence',1));
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getAllMembres($list = null) {
		$criteria = new CriteriaCompo();
		if($list != null) {
			$criteria->add(new Criteria('membre_nom',$list.'%','LIKE'));
		}
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getEquipe($id) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		$criteria->add(new Criteria('membre_equipe',$id));
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getMembreParClassementSimple($classement, $categorie = null) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		$criteria->add(new Criteria('membre_class_s',$classement));
		if(!is_null($categorie)) {
			$criteria->add(new Criteria('membre_categorie',$categorie.'%', 'LIKE'));
		}
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getMembreParClassementDouble($classement, $categorie = null) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		$criteria->add(new Criteria('membre_class_d',$classement));
		if(!is_null($categorie)) {
			$criteria->add(new Criteria('membre_categorie',$categorie.'%', 'LIKE'));
		}
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getMembreParClassementMixte($classement, $categorie = null) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		$criteria->add(new Criteria('membre_class_m',$classement));
		if(!is_null($categorie)) {
			$criteria->add(new Criteria('membre_categorie',$categorie.'%', 'LIKE'));
		}
		$criteria->setSort('membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getBureau() {
		$criteria = new Criteria('fonction_id',0,'!=');
		$criteria->setSort('fonction_id, membre_nom, membre_prenom');

		return $this->getObjects($criteria);
	}

	function getMembre($id) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		$criteria->add(new Criteria('membre_licence',$id));

		$membre = $this->getObjects($criteria);

		if(isset($membre[0])) {
			return $membre[0];
		} else {
			return false;
		}
	}

	function getMembreAnnivJour($nbJourAVenir = 7) {
		$now = mktime();

		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('membre_actif',1));
		$criteriaBirth = new CriteriaCompo();
		for($i=0 ; $i<$nbJourAVenir ; $i++) {
			$criteriaBirth->add(new Criteria('membre_birth',date("%-m-d", $now + ($i * 86400)),'LIKE'), 'OR');
		}
		$criteria->add($criteriaBirth);
		return $this->getObjects($criteria);
	}

	function addMembreAnnivInfo(&$membre) {

		$today['mois'] = date('n');
		$today['jour'] = date('j');
		$today['annee'] = date('Y');

		list($annee, $mois, $jour) = split('[-.]', $membre['membre_birth']);
		$membre['membre_birth_annee'] = $annee;
		$membre['membre_birth_mois'] = $mois;
		$membre['membre_birth_jour'] = $jour;
		$annees = $today['annee'] - $annee;
		if ($today['mois'] <= $mois) {
			if ($mois == $today['mois']) {
				if ($jour >= $today['jour'])
				$annees--;
			} else {
				$annees--;
			}
		}
		$membre['membre_age'] = $annees + 1;

	}

	function addMembresAnnivInfo(&$membres) {
		for($i=0;$i<count($membres);$i++) {
			$this->addMembreAnnivInfo($membres[$i]);
		}
	}

	function _getXMLMembresData() {

		include_once 'pear/HTTP/Request.php';

		$req = &new HTTP_Request('http://poona.ffba.org/page.php?P=fo/menu/public/accueil/index');
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addPostData('Action', 'connect_user');
		$req->addPostData('login', $GLOBALS['xoopsModuleConfig']['poona_login']);
		$req->addPostData('password', $GLOBALS['xoopsModuleConfig']['poona_pass']);
		$rep = $req->sendRequest();
		$ssid = $req->getResponseCookies();
		$ssid = $ssid[0]['value'];
		$code = $req->getResponseCode();

		// On sort si il y a un probleme de connection avec poona.
		if($code != 200 && $code != 302) {
			return false;
		}

		$req = &new HTTP_Request('http://poona.ffba.org/page.php?P=bo/acteurs_du_sport/joueurs/liste_avancee/liste');
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addHeader("Cookie", "PHPSESSID=".$ssid);
		$req->addPostData('Action', 'load_requete');
		$req->addPostData('requete_select_idRequete', $GLOBALS['xoopsModuleConfig']['poona_req_id']);
		$req->sendRequest();

		$req = &new HTTP_Request('http://poona.ffba.org/page.php?P=bo/acteurs_du_sport/joueurs/export/format');
		$req->setMethod(HTTP_REQUEST_METHOD_POST);
		$req->addHeader("Cookie", "PHPSESSID=".$ssid);
		$req->addPostData('Action', 'exporter_avance');
		$req->addPostData('page_hidden_idJoueur', '');
		$req->addPostData('page_pagination_pageActuelle', '1');
		$req->addPostData('affich_select_tri1', 'nom');
		$req->addPostData('affich_select_ordre1', 'ASC');
		$req->addPostData('affich_select_nbAffichage', '3');
		$req->sendRequest();

		$req = &new HTTP_Request('http://poona.ffba.org/includer.php?inc=ajax/generation/xml_joueur_export&target=chargementInfo&ieCache='.substr(microtime(),2,3).'&_=');
		$req->setMethod(HTTP_REQUEST_METHOD_GET);
		$req->addHeader("Cookie", "PHPSESSID=".$ssid);
		$req->sendRequest();

		$reponse = $req->getResponseBody();
		unset($req);

		$filePatern = '`http://poona.ffba.org/public/telechargements/temp/.*?xml`';

		preg_match($filePatern, $reponse, $file);
		return $this->_parseXMLFile($file[0]);

		//return $this->_parseXMLFile(XOOPS_ROOT_PATH."/cregybad.xml");

	}

	function _parseXMLFile($filename) {

		$data = implode("",file($filename));
		$parser = xml_parser_create('ISO-8859-1');
		xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
		xml_parser_set_option($parser,XML_OPTION_SKIP_WHITE,1);
		xml_parse_into_struct($parser,$data,$values,$tags);
		xml_parser_free($parser);

		$joueurs = array();

		// boucle à travers les structures
		foreach ($tags as $key=>$val) {
			if ($key == "joueur") {
				$joueurRanges = $val;
				// each contiguous pair of array entries are the
				// lower and upper range for each joueur definition
				for ($i=0; $i < count($joueurRanges); $i+=2) {
					$offset = $joueurRanges[$i] + 1;
					$len = $joueurRanges[$i + 1] - $offset;
					$joueurValues = array_slice($values, $offset, $len);
					for ($j=0; $j < count($joueurValues); $j++) {
						$value = isset($joueurValues[$j]["value"]) ? $joueurValues[$j]["value"] : "" ;
						$joueur[$joueurValues[$j]["tag"]] = $value;
					}
					$joueurs[] = $joueur;
				}
			} else {
				continue;
			}
		}
		if(count($joueurs) > 0) {
			return $joueurs;
		} else {
			return false;
		}

	}

	function _ajoutMembresHistorique($membreId, $membreXML) {

		$resultmembreHandler = xoops_getmodulehandler('resultmembre', 'club');

		$date = date('Y-m-d');
		//$date = "2007-09-15";

		// Sauvegarde des résultats du membre
		$resultmembre = $resultmembreHandler->create();
		$data = array(
		'membre_licence'=>$membreId,
		'resultmembre_date'=>$date,
		'resultmembre_class_simple'=>$membreXML['classSimple'],
		'resultmembre_class_double'=>$membreXML['classDouble'],
		'resultmembre_class_mixte'=>$membreXML['classMixte'],
		'resultmembre_moy_simple'=>$membreXML['moyMonteeSimple'],
		'resultmembre_moy_double'=>$membreXML['moyMonteeDouble'],
		'resultmembre_moy_mixte'=>$membreXML['moyMonteeMixte']
		);
		$resultmembre->setVars($data);
		$resultmembreHandler->insert($resultmembre,true);

	}

	function _miseAJourStatmembreSimple($tableauxStatSimple) {

		$statmembreSimpleHandler = xoops_getmodulehandler('statmembresSimple', 'club');
		foreach($tableauxStatSimple as $k=>$v) {
			$data = array(
			'statmembres_cat'=>$k,
			'statmembres_poussin'=>$v['p'],
			'statmembres_benjamin'=>$v['b'],
			'statmembres_minime'=>$v['m'],
			'statmembres_cadet'=>$v['c'],
			'statmembres_junior'=>$v['j'],
			'statmembres_senior'=>$v['s'],
			'statmembres_veteran'=>$v['v']
			);
			$serieData = $statmembreSimpleHandler->create(false);
			$serieData->setVars($data);
			$statmembreSimpleHandler->insert($serieData,true);
		}

	}

	function _miseAJourStatmembreDouble($tableauxStatDouble) {

		$statmembreDoubleHandler = xoops_getmodulehandler('statmembresDouble', 'club');
		foreach($tableauxStatDouble as $k=>$v) {
			$data = array(
			'statmembres_cat'=>$k,
			'statmembres_poussin'=>$v['p'],
			'statmembres_benjamin'=>$v['b'],
			'statmembres_minime'=>$v['m'],
			'statmembres_cadet'=>$v['c'],
			'statmembres_junior'=>$v['j'],
			'statmembres_senior'=>$v['s'],
			'statmembres_veteran'=>$v['v']
			);
			$serieData = $statmembreDoubleHandler->create(false);
			$serieData->setVars($data);
			$statmembreDoubleHandler->insert($serieData,true);
		}

	}

	function _miseAJourStatmembreMixte($tableauxStatMixte) {

		$statmembreMixteHandler = xoops_getmodulehandler('statmembresMixte', 'club');
		foreach($tableauxStatMixte as $k=>$v) {
			$data = array(
			'statmembres_cat'=>$k,
			'statmembres_poussin'=>$v['p'],
			'statmembres_benjamin'=>$v['b'],
			'statmembres_minime'=>$v['m'],
			'statmembres_cadet'=>$v['c'],
			'statmembres_junior'=>$v['j'],
			'statmembres_senior'=>$v['s'],
			'statmembres_veteran'=>$v['v']
			);
			$serieData = $statmembreMixteHandler->create(false);
			$serieData->setVars($data);
			$statmembreMixteHandler->insert($serieData,true);
		}

	}

	function _miseAJourMembre($membreXML, &$membreDB) {

		// Calcul du classement virtuel
		$classVirtuels = $this->_getClassementVirtuel($membreXML);

		// Traitement du cas des catégories d'ages spéciales (Cadet 1, 2...)
		if(substr($membreXML['categorie'],0,7) == 'Veteran') {
			$membreXML['categorie'] = substr($membreXML['categorie'],0,7);
		}

		// Si c'est un nouveau membre
		if(!$this->_membreExist($membreXML['licence'])) {

			// Formattage de la date en date SQL
			$date = explode('-',$membreXML['dateNaissance']);
			$naissance = $date[2].'-'.$date[1].'-'.$date[0];

			$data = array(
			'membre_licence'=>$membreXML['licence'],
			'membre_nom'=>$membreXML['nom'],
			'membre_prenom'=>$membreXML['prenom'],
			'membre_birth'=>$naissance,
			'membre_categorie'=>$membreXML['categorie'],
			'membre_sexe'=>$membreXML['sexe'],
			'membre_class_s'=>$membreXML['classSimple'],
			'membre_class_d'=>$membreXML['classDouble'],
			'membre_class_m'=>$membreXML['classMixte'],
			'membre_moy_s'=>$membreXML['moyMonteeSimple'],
			'membre_moy_d'=>$membreXML['moyMonteeDouble'],
			'membre_moy_m'=>$membreXML['moyMonteeMixte'],
			'membre_moyd_s'=>$membreXML['moyDescenteSimple'],
			'membre_moyd_d'=>$membreXML['moyDescenteDouble'],
			'membre_moyd_m'=>$membreXML['moyDescenteMixte'],
			'membre_mclass_s'=>$membreXML['classSimple'],
			'membre_mclass_d'=>$membreXML['classDouble'],
			'membre_mclass_m'=>$membreXML['classMixte'],
			'membre_mmoy_s'=>$membreXML['moyMonteeSimple'],
			'membre_mmoy_d'=>$membreXML['moyMonteeDouble'],
			'membre_mmoy_m'=>$membreXML['moyMonteeMixte'],
			'membre_vclass_s'=>$classVirtuels['classVSimple'],
			'membre_vclass_d'=>$classVirtuels['classVDouble'],
			'membre_vclass_m'=>$classVirtuels['classVMixte'],
			'membre_actif'=>1
			);

			// Ajout du joueur dans le module club
			$newJoueur = $this->create();
			$newJoueur->setVars($data);

			// Ajout du joueur dans la base XOOPS uniquement si son Email est renseigné
			if($membreXML['emailPerso'] && $GLOBALS['xoopsModuleConfig']['create_XoUser']) {
				$this->_addXoopsMembre($membreXML);
			}

			// Si le membre n'éxistait pas, on sort de la methode aprés l'avoir créer, pas besoin de mise à jour
			return $this->insert($newJoueur, true);

		}

		$member_handler =& xoops_gethandler('member');
		$user = $member_handler->getUser($membreXML['licence']);

		// On agit sur les membres XOOPS uniquement si un email est renseigné et si les users Xoops doivent etre créés
		if($membreXML['emailPerso'] && $GLOBALS['xoopsModuleConfig']['create_XoUser']) {
			// Si le membre XOOPS n'existait pas, on le crée
			if(!$user) {
				$this->_addXoopsMembre($membreXML);
			} else {
				$this->_updateXoopsMembre($user, $membreXML);
			}
		}

		$meilleursClassements = $this->_getMeilleursClassements($membreXML, $membreDB);
		$meilleursMoyennes = $this->_getMeilleuresMoyennes($membreXML, $membreDB);

		// On met à jour les classements et moyennes et categorie
		$sql = "UPDATE ".$this->table." SET
					`membre_categorie` = '".$membreXML['categorie'].
					"', `membre_class_s` = '".$membreXML['classSimple'].
					"', `membre_class_d` = '".$membreXML['classDouble'].
					"', `membre_class_m` = '".$membreXML['classMixte'].
					"', `membre_moy_s` = '".$membreXML['moyMonteeSimple'].
					"', `membre_moy_d` = '".$membreXML['moyMonteeDouble'].
					"', `membre_moy_m` = '".$membreXML['moyMonteeMixte'].
					"', `membre_mclass_s` = '".$meilleursClassements['meilleurClassSimple'].
					"', `membre_mclass_d` = '".$meilleursClassements['meilleurClassDouble'].
					"', `membre_mclass_m` = '".$meilleursClassements['meilleurClassMixte'].
					"', `membre_moyd_s` = '".$membreXML['moyDescenteSimple'].
					"', `membre_moyd_d` = '".$membreXML['moyDescenteDouble'].
					"', `membre_moyd_m` = '".$membreXML['moyDescenteMixte'].
					"', `membre_mmoy_s` = '".$meilleursMoyennes['meilleurMoySimple'].
					"', `membre_mmoy_d` = '".$meilleursMoyennes['meilleurMoyDouble'].
					"', `membre_mmoy_m` = '".$meilleursMoyennes['meilleurMoyMixte'].
					"', `membre_vclass_s` = '".$classVirtuels['classVSimple'].
					"', `membre_vclass_d` = '".$classVirtuels['classVDouble'].
					"', `membre_vclass_m` = '".$classVirtuels['classVMixte'].
					"', `membre_actif` = '1'
					 WHERE `membre_licence` = '".$membreDB['membre_licence']."';";

		$this->db->queryF($sql);

		// Mise à jour de la table membresaison
		$year = date('Y');
		$month = date('n');
		if($month < 9) {
			$saison = ($year - 1).'-'.($year);
		} else {
			$saison = ($year).'-'.($year + 1);
		}
		$sql = "INSERT INTO ".$this->db->prefix('club_membresaison')." VALUES (".$membreDB['membre_licence'].", '".$saison."')";
		$this->db->queryF($sql);

	}

	function _updateXoopsMembre(&$user, $membreXML) {

		$member_handler =& xoops_gethandler('member');

		$user->setVar('name', $membreXML['prenom']." ".$membreXML['nom']);
		$user->setVar('email', $membreXML['emailPerso']);
		$member_handler->insertUser($user, true);
	}

	function _addXoopsMembre($membreXML) {

		include_once XOOPS_ROOT_PATH.'/language/'.$GLOBALS['xoopsConfig']['language'].'/user.php';

		$member_handler =& xoops_gethandler('member');
		$newuser =& $member_handler->createUser();

		$tmpName = $membreXML['prenom']." ".$membreXML['nom'];
		$tmpUname = $this->_makeXoopsUserName($membreXML);
		$tmpEmail = $membreXML['emailPerso'];
		$tmpPass = xoops_makepass();

		$newuser->setVar('user_viewemail', 0, true);
		$newuser->setVar('uid', $membreXML['licence'], true);
		$newuser->setVar('name', $tmpName, true);
		$newuser->setVar('uname', $tmpUname, true);
		$newuser->setVar('email', $tmpEmail, true);
		$newuser->setVar('user_avatar','blank.gif', true);
		$actkey = substr(md5(uniqid(mt_rand(), 1)), 0, 8);
		$newuser->setVar('actkey', $actkey, true);
		$newuser->setVar('pass', md5($tmpPass), true);
		$newuser->setVar('timezone_offset', 1.0, true);
		$newuser->setVar('user_regdate', time(), true);
		$newuser->setVar('uorder',$GLOBALS['xoopsConfig']['com_order'], true);
		$newuser->setVar('umode',$GLOBALS['xoopsConfig']['com_mode'], true);
		$newuser->setVar('user_mailok',0, true);
		$newuser->setVar('level',1, true);

		$newuser->cleanVars();
		$userData = $newuser->cleanVars;

		foreach ($userData as $k => $v) {
			${$k} = $v;
		}
		$sql = sprintf("INSERT INTO %s (uid, uname, name, email, url, user_avatar, user_regdate, user_icq, user_from, user_sig, user_viewemail, actkey, user_aim, user_yim, user_msnm, pass, posts, attachsig, rank, level, theme, timezone_offset, last_login, umode, uorder, notify_method, notify_mode, user_occ, bio, user_intrest, user_mailok) VALUES (%u, %s, %s, %s, %s, %s, %u, %s, %s, %s, %u, %s, %s, %s, %s, %s, %u, %u, %u, %u, %s, %.2f, %u, %s, %u, %u, %u, %s, %s, %s, %u)", $this->db->prefix('users'), $uid, $this->db->quoteString($uname), $this->db->quoteString($name), $this->db->quoteString($email), $this->db->quoteString($url), $this->db->quoteString($user_avatar), time(), $this->db->quoteString($user_icq), $this->db->quoteString($user_from), $this->db->quoteString($user_sig), $user_viewemail, $this->db->quoteString($actkey), $this->db->quoteString($user_aim), $this->db->quoteString($user_yim), $this->db->quoteString($user_msnm), $this->db->quoteString($pass), $posts, $attachsig, $rank, $level, $this->db->quoteString($theme), $timezone_offset, 0, $this->db->quoteString($umode), $uorder, $notify_method, $notify_mode, $this->db->quoteString($user_occ), $this->db->quoteString($bio), $this->db->quoteString($user_intrest), $user_mailok);
		$this->db->queryF($sql);
		$this->db->queryF("INSERT INTO xoops_groups_users_link (linkid, groupid, uid) VALUES (0, ".XOOPS_GROUP_USERS.", ".$newuser->getVar('uid').");");

		$this->_sendLoginInfo($membreXML['prenom'], $uid, $uname, $tmpPass);
		echo "Message envoyé à : $uname\n";

	}

	function _sendLoginInfo($prenom, $uid, $uname, $pass) {

		$xoopsMailer =& getMailer();
		$xoopsMailer->useMail();
		$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH.'/modules/club/language/english/mail_template/');
		$xoopsMailer->setTemplate('register.tpl');
		$xoopsMailer->assign('SITENAME', $GLOBALS['xoopsConfig']['sitename']);
		$xoopsMailer->assign('ADMINMAIL', $GLOBALS['xoopsConfig']['adminmail']);
		$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
		$xoopsMailer->assign('PRENOM', $prenom);
		$xoopsMailer->assign('LOGIN', $uname);
		$xoopsMailer->assign('PASS', $pass);
		$xoopsMailer->setToUsers(new XoopsUser($uid));
		$xoopsMailer->setFromEmail($GLOBALS['xoopsConfig']['adminmail']);
		$xoopsMailer->setFromName($GLOBALS['xoopsConfig']['sitename']);
		$xoopsMailer->setSubject($GLOBALS['xoopsConfig']['sitename'].' : Création de compte');
		if(!$xoopsMailer->send(true)) {
			echo $xoopsMailer->getErrors();
		}

	}

	function resendLoginInfo() {

		$member_handler =& xoops_gethandler('member');
		$membreHandler = xoops_getmodulehandler('membre', 'club');

		$membres = $member_handler->getUsers(new Criteria('last_login', 0));

		foreach($membres as $membre) {
			$clubMembre = $membreHandler->get($membre->getVar('uid'));
			$pass = xoops_makepass();
			$this->_sendLoginInfo($clubMembre->getVar('membre_prenom'), $membre->getVar('uid'), $membre->getVar('uname'), $pass);

			// Mise à jour du pass en base pour cet user
			$sql = "UPDATE ".$this->db->prefix('users')." SET `pass` = '".md5($pass)."' WHERE `uid` = ".$membre->getVar('uid')." LIMIT 1 ;";
			$this->db->queryF($sql);
		}

	}

	function updateMembrePhotoStatus() {

		$membres = $this->getAllMembres();

		for($i=0;$i<count($membres);$i++) {
			$licence = $membres[$i]->getVar('membre_licence');

			if(file_exists(XOOPS_UPLOAD_PATH."/club/joueurs/$licence.gif")) {

				$sql = "UPDATE ".$this->db->prefix('club_membre')." SET `membre_photo` = '1' WHERE `membre_licence` = '$licence' LIMIT 1 ;";
				$this->db->queryF($sql);
			}
		}

	}

	function updateXoopsName() {

		$userHandler = xoops_gethandler('user');

		$membres = $this->getAllMembres();

		foreach($membres as $membre) {

			if($user = $userHandler->get($membre->getVar('membre_licence'))) {
				$name = $membre->getVar('membre_prenom')." ".$membre->getVar('membre_nom');
				$user->setVar('name', $name);
				$userHandler->insert($user, true);
			}

		}
	}

	function _makeXoopsUserName(&$membreXML) {

		$member_handler =& xoops_gethandler('member');

		// Suppression des accents et mise en minuscule
		$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
		$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";

		$nom = strtolower(strtr(str_replace(' ','',$membreXML['nom']),$tofind,$replac));
		$prenom = substr(strtolower(strtr(str_replace(' ','',$membreXML['prenom']),$tofind,$replac)),0,1);

		// Creation du login a partir du nom est du prenom
		$i = 1;
		$uname = $prenom.$nom;

		while($member_handler->getUserCount(new Criteria('uname', $uname)) > 0) {
			$uname = $prenom.$nom.$i++;
		}

		return $uname;

	}

	function _getMeilleursClassements($membreXML, &$membre) {

		$ret = array('meilleurClassSimple'=>'NC', 'meilleurClassDouble'=>'NC', 'meilleurClassMixte'=>'NC');

		$classOrdre = array('T5'=>1,'T10'=>2,'T20'=>3,'T50'=>4,'A1'=>5,'A2'=>6,'A3'=>7,'A4'=>8,'B1'=>9,'B2'=>10,'B3'=>11,'B4'=>12,'C1'=>13,'C2'=>14,'C3'=>15,'C4'=>16,'D1'=>17,'D2'=>18,'D3'=>19,'D4'=>20,'NC'=>21);
		// Simple
		if($classOrdre[$membreXML['classSimple']] < $classOrdre[$membre['membre_mclass_s']]) {
			$ret['meilleurClassSimple'] = $membreXML['classSimple'];
		} else {
			$ret['meilleurClassSimple'] = $membre['membre_mclass_s'];
		}
		// Double
		if($classOrdre[$membreXML['classDouble']] < $classOrdre[$membre['membre_mclass_d']]) {
			$ret['meilleurClassDouble'] = $membreXML['classDouble'];
		} else {
			$ret['meilleurClassDouble'] = $membre['membre_mclass_d'];
		}
		// Mixte
		if($classOrdre[$membreXML['classMixte']] < $classOrdre[$membre['membre_mclass_m']]) {
			$ret['meilleurClassMixte'] = $membreXML['classMixte'];
		} else {
			$ret['meilleurClassMixte'] = $membre['membre_mclass_m'];
		}

		return $ret;
	}

	function _getMeilleuresMoyennes($membreXML, &$membre) {

		$ret = array('meilleurMoySimple'=>0, 'meilleurMoyDouble'=>0, 'meilleurMoyMixte'=>0);

		// Simple
		if($membreXML['moyMonteeSimple'] > $membre['membre_mmoy_s']) {
			$ret['meilleurMoySimple'] = $membreXML['moyMonteeSimple'];
		} else {
			$ret['meilleurMoySimple'] = $membre['membre_mmoy_s'];
		}
		// Double
		if($membreXML['moyMonteeDouble'] > $membre['membre_mmoy_d']) {
			$ret['meilleurMoyDouble'] = $membreXML['moyMonteeDouble'];
		} else {
			$ret['meilleurMoyDouble'] = $membre['membre_mmoy_d'];
		}
		// Mixte
		if($membreXML['moyMonteeMixte'] > $membre['membre_mmoy_m']) {
			$ret['meilleurMoyMixte'] = $membreXML['moyMonteeMixte'];
		} else {
			$ret['meilleurMoyMixte'] = $membre['membre_mmoy_m'];
		}

		return $ret;
	}

	function _getClassementVirtuel($joueur) {

		$moyenneClass = array('T5'=>162,'T10'=>162,'T20'=>162,'T50'=>162,'A1'=>135,'A2'=>108,'A3'=>81,'A4'=>54,'B1'=>45,'B2'=>36,'B3'=>27,'B4'=>18,'C1'=>15,'C2'=>12,'C3'=>9,'C4'=>6,'D1'=>5,'D2'=>4,'D3'=>3,'D4'=>2,'NC'=>0);
		$class = array('T50','A1','A2','A3','A4','B1','B2','B3','B4','C1','C2','C3','C4','D1','D2','D3','D4','NC');

		// ### Traitement du classement virtuel ###

		$ret = array('classVSimple'=>'NC', 'classVDouble'=>'NC', 'classVMixte'=>'NC');

		// ### Simple ###
		// Si le joueur descend
		if($joueur['moyDescenteSimple'] < $moyenneClass[$joueur['classSimple']]) {
			if($joueur['classSimple'] == 'NC') {
				$ret['classVSimple'] = 'NC';
			} elseif($joueur['classSimple'] == 'D4') {
				$ret['classVSimple'] = 'D4';
			} else {
				$key = array_search($joueur['classSimple'],$class) + 1;
				$ret['classVSimple'] = $class[$key];
			}
			// Si le joueur monte
		} elseif($joueur['moyMonteeSimple'] > $moyenneClass[$joueur['classSimple']]) {
			foreach($moyenneClass as $k=>$v) {
				if($joueur['moyMonteeSimple'] >= $v) {
					$ret['classVSimple'] = $k;
					break;
				}
			}
			// Si le joueur ne bouge pas
		} else {
			$ret['classVSimple'] = $joueur['classSimple'];
		}

		// ### Double ###
		// Si le joueur descend
		if($joueur['moyDescenteDouble'] < $moyenneClass[$joueur['classDouble']]) {
			if($joueur['classDouble'] == 'NC') {
				$ret['classVDouble'] = 'NC';
			} elseif($joueur['classDouble'] == 'D4') {
				$ret['classVDouble'] = 'D4';
			} else {
				$key = array_search($joueur['classDouble'],$class) + 1;
				$ret['classVDouble'] = $class[$key];
			}
			// Si le joueur monte
		} elseif($joueur['moyMonteeDouble'] > $moyenneClass[$joueur['classDouble']]) {
			foreach($moyenneClass as $k=>$v) {
				if($joueur['moyMonteeDouble'] >= $v) {
					$ret['classVDouble'] = $k;
					break;
				}
			}
			// Si le joueur ne bouge pas
		} else {
			$ret['classVDouble'] = $joueur['classDouble'];
		}

		// ### Mixte ###
		// Si le joueur descend
		if($joueur['moyDescenteMixte'] < $moyenneClass[$joueur['classMixte']]) {
			if($joueur['classMixte'] == 'NC') {
				$ret['classVMixte'] = 'NC';
			} elseif($joueur['classMixte'] == 'D4') {
				$ret['classVMixte'] = 'D4';
			} else {
				$key = array_search($joueur['classMixte'],$class) + 1;
				$ret['classVMixte'] = $class[$key];
			}
			// Si le joueur monte
		} elseif($joueur['moyMonteeMixte'] > $moyenneClass[$joueur['classMixte']]) {
			foreach($moyenneClass as $k=>$v) {
				if($joueur['moyMonteeMixte'] >= $v) {
					$ret['classVMixte'] = $k;
					break;
				}
			}
			// Si le joueur ne bouge pas
		} else {
			$ret['classVMixte'] = $joueur['classMixte'];
		}

		// ### Ajustement des classements ###
		// Si classé dans un tableau, classé dans tous
		if($ret['classVSimple'] != 'NC' || $ret['classVDouble'] != 'NC' || $ret['classVMixte'] != 'NC') {
			if($ret['classVSimple'] == 'NC') {
				$ret['classVSimple'] = 'D4';
			}
			if($ret['classVDouble'] == 'NC') {
				$ret['classVDouble'] = 'D4';
			}
			if($ret['classVMixte'] == 'NC') {
				$ret['classVMixte'] = 'D4';
			}
		}

		// Ajustement pour ne pas avoir plus de 4 séries d'écart
		$maxClass = $ret['classVSimple'];
		if(array_search($maxClass, $class) > array_search($ret['classVDouble'], $class)) {
			$maxClass = $ret['classVDouble'];
		}
		if(array_search($maxClass, $class) > array_search($ret['classVMixte'], $class)) {
			$maxClass = $ret['classVMixte'];
		}

		$diff = array_search($maxClass, $class) - array_search($ret['classVSimple'], $class);
		if($diff < -4) {echo "Ajustement Simple => ";
			$ret['classVSimple'] = $class[array_search($ret['classVSimple'], $class) + $diff + 4];
		}

		$diff = array_search($maxClass, $class) - array_search($ret['classVDouble'], $class);
		if($diff < -4) {echo "Ajustement Double => ";
			$ret['classVDouble'] = $class[array_search($ret['classVDouble'], $class) + $diff + 4];
		}

		$diff = array_search($maxClass, $class) - array_search($ret['classVMixte'], $class);
		if($diff < -4) {echo "Ajustement Mixte => ";
			$ret['classVMixte'] = $class[array_search($ret['classVMixte'], $class) + $diff + 4];
		}

		return $ret;

	}

	function _membreExist($licence) {
		$criteria = new Criteria('membre_licence',intval($licence));
		return $this->getCount($criteria) > 0;
	}

	function miseAJourMembres($membresXML = null) {

		include_once 'clubgraph.php';

		$membres = $this->getAllMembres();

		if($membresXML == null) {
			$membresXML = $this->_getXMLMembresData();
		}

		// Si il y a eu un probleme de dialogue avec poona ou qu'il n'y a pas de membre encors licenciés, on n'effectue aucun traitement
		if(!$membresXML) {
			return false;
		}

		// Tout les membres sont mis comme non actif. Les membres contenu
		// dans le fichier XML (licenciés en cours) seront remis comme actif
		// durant la mise à jour de leurs infos.
		$criteria = new Criteria('membre_nolicence', 0);
		$this->updateAll('membre_actif', 0, null, true);

		// Construction d'un tableau avec le numéro de licence comme clé pour les membres déja présents en base
		$membresDB = array();
		foreach($membres as $membre) {
			$tmp = $membre->getValues();
			$membresDB[$tmp['membre_licence']] = $tmp;
		}

		unset($membres);

		// Initialisation des variables stockant les données de stats sur les membres
		$nbHomme = 0;
		$nbFemme = 0;
		$ageCat = array('p'=>0,'b'=>0,'m'=>0,'c'=>0,'j'=>0,'s'=>0,'v'=>0);
		$nivCat = array('T5'=>$ageCat,'T10'=>$ageCat,'T20'=>$ageCat,'T50'=>$ageCat,'A1'=>$ageCat,'A2'=>$ageCat,'A3'=>$ageCat,'A4'=>$ageCat,'B1'=>$ageCat,'B2'=>$ageCat,'B3'=>$ageCat,'B4'=>$ageCat,'C1'=>$ageCat,'C2'=>$ageCat,'C3'=>$ageCat,'C4'=>$ageCat,'D1'=>$ageCat,'D2'=>$ageCat,'D3'=>$ageCat,'D4'=>$ageCat,'NC'=>$ageCat);
		$tableauxStatSimple = $nivCat;
		$tableauxStatDouble = $nivCat;
		$tableauxStatMixte = $nivCat;
		$nbMembreByCatAge = array('Poussin'=>0, 'Benjamin'=>0, 'Minime'=>0, 'Cadet'=>0, 'Junior'=>0, 'Senior'=>0, 'Vétéran'=>0);

		for($i=0;$i<count($membresXML);$i++) {

			// Traitements des stats
			if(substr($membresXML[$i]['categorie'], 0, 7) == 'Veteran') {
				$membresXML[$i]['categorie'] = 'Veteran';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['v']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['v']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['v']++;
				$nbMembreByCatAge['Vétéran']++;
			} elseif($membresXML[$i]['categorie'] == 'Senior') {
				$membresXML[$i]['categorie'] = 'Senior';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['s']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['s']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['s']++;
				$nbMembreByCatAge['Senior']++;
			} elseif (substr($membresXML[$i]['categorie'], 0, 6) == 'Junior') {
				$membresXML[$i]['categorie'] = 'Junior';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['j']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['j']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['j']++;
				$nbMembreByCatAge['Junior']++;
			} elseif (substr($membresXML[$i]['categorie'], 0, 5) == 'Cadet') {
				$membresXML[$i]['categorie'] = 'Cadet';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['c']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['c']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['c']++;
				$nbMembreByCatAge['Cadet']++;
			} elseif (substr($membresXML[$i]['categorie'], 0, 6) == 'Minime') {
				$membresXML[$i]['categorie'] = 'Minime';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['m']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['m']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['m']++;
				$nbMembreByCatAge['Minime']++;
			} elseif (substr($membresXML[$i]['categorie'], 0, 8) == 'Benjamin') {
				$membresXML[$i]['categorie'] = 'Benjamin';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['b']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['b']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['b']++;
				$nbMembreByCatAge['Benjamin']++;
			} elseif(substr($membresXML[$i]['categorie'], 0, 7) == 'Poussin') {
				$membresXML[$i]['categorie'] = 'Poussin';
				$tableauxStatSimple[$membresXML[$i]['classSimple']]['p']++;
				$tableauxStatDouble[$membresXML[$i]['classDouble']]['p']++;
				$tableauxStatMixte[$membresXML[$i]['classMixte']]['p']++;
				$nbMembreByCatAge['Poussin']++;
			}

			$membreDB = isset($membresDB[intval($membresXML[$i]['licence'])]) ? $membresDB[intval($membresXML[$i]['licence'])] : null;

			$this->_miseAJourMembre($membresXML[$i], $membreDB);

			// Stat homme femme
			if($membresXML[$i]['sexe'] == 'H') {
				$nbHomme++;
			} else {
				$nbFemme++;
			}

		}

		// Mise à jour des stat sur les membres en base
		$this->_miseAJourStatmembreSimple($tableauxStatSimple);
		$this->_miseAJourStatmembreDouble($tableauxStatDouble);
		$this->_miseAJourStatmembreMixte($tableauxStatMixte);

		// Création du graphique des stat homme femme
		ClubGraph::graphStatHommeFemme($nbHomme, $nbFemme);

		// Création du graphique des stat par categorie d'age
		ClubGraph::graphStatCategorieAge($nbMembreByCatAge);

	}

	function miseAJourMembresAvecHisto() {

		$this->updateXoopsName();

		$membresXML = $this->_getXMLMembresData();

		// Si il y a eu un probleme de dialogue avec poona, on n'effectue aucun traitement
		if(!$membresXML) {
			echo "Probleme de connection avec POONA\r";
			return false;
		}

		$this->miseAJourMembres($membresXML);

		foreach($membresXML as $membreXML) {

			// Récuperation du membre à partir de son numéro de licence
			$criteria = new Criteria('membre_licence',$membreXML['licence']);
			$membre = $this->getObjects($criteria);
			$membreId = $membre[0]->getVar('membre_licence');

			// Sauvegardes des ses classements et moyennes
			$this->_ajoutMembresHistorique($membreId, $membreXML);
		}

		// Actualisation des graphiques de stat pour chaque joueur actif
		$this->_buildGraphJoueurs();

	}

	function getAlphaCount() {

		$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

		$ret = array();
		$total = 0;

		foreach($alpha as $digit) {
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('membre_actif',1));
			$criteria->add(new Criteria('membre_nom',$digit.'%','LIKE'));

			$ret[$digit] = $this->getCount($criteria);
			$total += $ret[$digit];
			unset($criteria);
		}
		$ret['total'] = $total;

		return $ret;
	}

	function _buildGraphJoueurs() {

		include_once 'clubgraph.php';
		$resultHandler = xoops_getmodulehandler('resultmembre', 'club');

		$mois = date("n");
		$annee = date("Y");

		// Parametre de la periode des graph suivi
		$nbAnnee = 3;
		$saisonAnneeFinSuivi = $annee;
		if($mois > 8) {
			$saisonAnneeFinSuivi++;
		}
		$saisonAnneeDebSuivi = $saisonAnneeFinSuivi - $nbAnnee;

		// Parametre de la periode des graph saison
		if($mois < 9) {
			$saisonAnneeDebSaison = $annee - 1;
			$saisonAnneeFinSaison = $annee;
		} else {
			$saisonAnneeDebSaison = $annee;
			$saisonAnneeFinSaison = $annee + 1;
		}

		$membres = $this->getAllActiveMembres();

		foreach($membres as $membre) {

			// Si il s'agit d'un non licencié au club, on ne construit pas ses graphs
			if($membre->getVar('membre_nolicence') == 1) {
				continue;
			}
			// Récupération des données pour la saison
			$resultSaison = $resultHandler->getResultmembreSaison($membre, $saisonAnneeDebSaison, $saisonAnneeFinSaison);

			// Récupération des données pour le suivi
			$resultSuivi = $resultHandler->getResultmembre($membre, $saisonAnneeDebSuivi, $saisonAnneeFinSuivi);

			// Création des graph pour la saison
			//$this->_graphClassementSaisonJoueur($membre, $saisonAnneeDebSaison, $saisonAnneeFinSaison);
			ClubGraph::graphMoyenneSaisonJoueur($membre, $resultSaison, $saisonAnneeDebSaison, $saisonAnneeFinSaison);

			// Création des graph de suivi
			//ClubGraph::graphClassementSuiviJoueur($membre, $saisonAnneeDebSuivi, $saisonAnneeFinSuivi);
			ClubGraph::graphMoyenneSuiviJoueur($membre, $resultSuivi, $saisonAnneeDebSuivi, $saisonAnneeFinSuivi);

		}

	}

}

?>
