<?php


function xoops_module_update_club(&$xoopsModule, $oldVersion = null) {

	if($oldVersion < 101) {

		$db =& Database::getInstance();
		$sql = "ALTER TABLE `".$db->prefix('club_equipe')."` ADD `equipe_class_url` VARCHAR( 255 ) NOT NULL AFTER `equipe_rank_in_club` ;";
		$db->query($sql);
		$sql = "ALTER TABLE `".$db->prefix('club_equipe')."` ADD `equipe_resultat_url` VARCHAR( 255 ) NOT NULL AFTER `equipe_class_url` ;";
		$db->query($sql);

	}
	
	if($oldVersion < 103) {
		
		$db =& Database::getInstance();
		$sql = "ALTER TABLE `".$db->prefix('club_membre')."` ADD `membre_moyd_s` FLOAT NOT NULL AFTER `membre_moy_m` ;";
		$db->query($sql);
		$sql = "ALTER TABLE `".$db->prefix('club_membre')."` ADD `membre_moyd_d` FLOAT NOT NULL AFTER `membre_moyd_s` ;";
		$db->query($sql);
		$sql = "ALTER TABLE `".$db->prefix('club_membre')."` ADD `membre_moyd_m` FLOAT NOT NULL AFTER `membre_moyd_d` ;";
		$db->query($sql);
		$sql = "ALTER TABLE `".$db->prefix('club_membre')."` ADD `membre_nolicence` BOOL NOT NULL AFTER `membre_actif` ;";
		$db->query($sql);
		$sql = "CREATE TABLE `".$db->prefix('club_membresaison')."` (`membre_licence` int(11) NOT NULL, `saison_saison` varchar(9) NOT NULL, PRIMARY KEY  (`membre_licence`,`saison_saison`)) ;";
		$db->query($sql);
		
	}
	
	if($oldVersion < 104) {
		
		$db =& Database::getInstance();
		$sql = "CREATE TABLE `".$db->prefix('club_membretournoi')."` (`membre_licence` int(11) NOT NULL,`tournoi_id` int(11) NOT NULL,`membretournoi_is_simple` tinyint(1) NOT NULL default '0',  `membretournoi_s_serie` enum('NC','D','C','B','A') NOT NULL,`membretournoi_is_double` tinyint(1) NOT NULL default '0',`membretournoi_d_partenaire` int(11) NOT NULL default '0',`membretournoi_d_serie` enum('NC','D','C','B','A') NOT NULL,`membretournoi_is_mixte` tinyint(1) NOT NULL default '0',`membretournoi_m_partenaire` int(11) NOT NULL default '0',`membretournoi_m_serie` enum('NC','D','C','B','A') NOT NULL,PRIMARY KEY  (`membre_licence`,`tournoi_id`),UNIQUE KEY `unique_double` (`membre_licence`,`tournoi_id`,`membretournoi_is_double`),UNIQUE KEY `unique_simple` (`membre_licence`,`tournoi_id`,`membretournoi_is_simple`),UNIQUE KEY `unique_mixte` (`membre_licence`,`tournoi_id`,`membretournoi_is_mixte`)) ;";
		$db->query($sql);
		$sql = "CREATE TABLE `".$db->prefix('club_palmares')."` (`membre_licence` int(11) NOT NULL,`tournoi_id` int(11) NOT NULL,`palmares_s` tinyint(4) NOT NULL default '0',`palmares_d` tinyint(4) NOT NULL default '0',`palmares_m` tinyint(4) NOT NULL default '0',PRIMARY KEY  (`membre_licence`,`tournoi_id`)) ;";
		$db->query($sql);
		$sql = "CREATE TABLE `".$db->prefix('club_tournoi')."` (`tournoi_id` int(11) NOT NULL auto_increment,`tournoi_name` varchar(255) NOT NULL,`tournoi_desc` text NOT NULL,`tournoi_series` varchar(255) NOT NULL,`tournoi_start_date` int(11) NOT NULL,`tournoi_end_date` int(11) NOT NULL,PRIMARY KEY  (`tournoi_id`)) ;";
		$db->query($sql);
		
	}

    return true;
}


?>