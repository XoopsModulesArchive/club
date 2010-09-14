<?php
// $Id: index.php,v 1.19 2005/09/02 07:05:47 zoullou Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

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

	case 'default':
	default:

		switch($step) {

			case 'enreg':



				break;

			case 'default':
			default:

				xoops_cp_header();
				adminMenu(3);

				echo '<script type="text/javascript" src="../include/prototype.js"></script>';
				echo '<script type="text/javascript" src="../include/rico.js"></script>';
				echo '<script type="text/javascript">';
				echo 'ajaxEngine.registerRequest("addClub", "addClub.php");';
				echo 'ajaxEngine.registerAjaxElement("club_nom");';
				echo '</script>';

				$clubHandler = xoops_getmodulehandler('club', 'club');

				$clubs = $clubHandler->getAllClubs();

				$form = new XoopsThemeForm('Ajout d\'une équipe', 'add_equipe', 'equipe.php', 'post', true);
				$elementTray = new XoopsFormElementTray("Selection du club");
				$clubSelect = new XoopsFormSelect("", 'club_nom');
				//$clubSelect
				$elementTray->addElement(new XoopsFormText("", 'ajout_club_nom', '70', '255'));
				$elementTray->addElement(new XoopsFormLabel("", '<a href="#" onClick="ajaxEngine.sendRequest("addClub","club_nom=test");">Ajouter ce club</a>'));
				$form->addElement($elementTray);
				$form->display();


				xoops_cp_footer();

				break;
			}

		break;

}

?>
