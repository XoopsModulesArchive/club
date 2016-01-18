<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubEquipe extends XoopsObject {

	var $externalKey = array();

	function ClubEquipe()
	{
		$this->initVar('equipe_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('equipe_photo', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('equipe_rank_in_club', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('club_id', XOBJ_DTYPE_TXTBOX, '', false);

		$this->externalKey['club_id'] = array('className'=>'club', 'getMethodeName'=>'get', 'keyName'=>'club', 'core'=>false);
	}

}

class ClubEquipeHandler extends ClubPersistableObjectHandler {


	function ClubEquipeHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_equipe', 'ClubEquipe', 'equipe_id');
	}
	
	function getEquipe($id) {

		$criteria = new Criteria('equipe_id', $id);
		return $this->getObjects($criteria);

	}

	function getEquipeWithMembres($id) {

		$membreHandler = xoops_getmodulehandler('membre', 'club');

		$equipe = $this->objectToArray($this->getEquipe($id));

		$membres = $membreHandler->objectToArray($membreHandler->getEquipe($id));

		$ret = array(
					'equipe'=>$equipe[0],
					'membres'=>$membres
				);

		return $ret;
	}

	function getAllEquipes() {

		return $this->getObjects();

	}

}

?>
