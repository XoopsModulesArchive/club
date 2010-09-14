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

		xoops_cp_header();
		adminMenu(3);
	
		$form = new XoopsThemeForm('Ajout d\'un club', 'add_club', 'club.php?op=modify', 'post', true);
		$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
		$form->display();
	
		$form = new XoopsThemeForm('Ajout d\'un club', 'add_club', 'club.php?op=create', 'post', true);
		$form->addElement(new XoopsFormText('Nom du club', 'club_nom', '70', '255'),true);
		$form->addElement(new XoopsFormText('Sigle du club', 'club_sigle', '70', '255'),true);
		$form->addElement(XoopsFormCheckBox('Est-ce le site de ce club ?', 'club_iscurrent'));
		$form->addElement(new XoopsFormButton("", "submit", _SUBMIT, "submit"));
		$form->display();

		xoops_cp_footer();

		break;

}

?>
