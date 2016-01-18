<?php

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubMembresaison extends XoopsObject
{

	function ClubMembresaison()
	{
		$this->initVar('membre_licence', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('saison_saison', XOBJ_DTYPE_TXTBOX, '', true, 9);
	}

}

class ClubMembresaisonHandler extends ClubPersistableObjectHandler {

	function ClubMembresaisonHandler(&$db)
	{
		$this->ClubPersistableObjectHandler($db, 'club_membresaison', 'ClubMembresaison', array('membre_licence', 'saison_saison'));

	}
	
	function getSaisons() {
		$sql = "SELECT DISTINCT `saison_saison` FROM `".$this->table."` ORDER BY saison_saison ;";
		$result = $this->db->queryF($sql);
		
		return $this->convertResultSet($result, false, false);
	}

}

?>
