<?php
// $Id$
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

function xoops_module_install_club(&$xoopsModule) {

	$dir = XOOPS_ROOT_PATH."/uploads/club";
	if(!is_dir($dir))
		mkdir($dir);

	$dir = XOOPS_ROOT_PATH."/uploads/club/joueurs";
	if(!is_dir($dir))
		mkdir($dir);

	$dir = XOOPS_ROOT_PATH."/uploads/club/equipes";
	if(!is_dir($dir))
		mkdir($dir);

	$indexFile = XOOPS_ROOT_PATH."/modules/club/include/index.html";
	copy($indexFile, XOOPS_ROOT_PATH."/uploads/club/index.html");
	copy($indexFile, XOOPS_ROOT_PATH."/uploads/club/joueurs/index.html");
	copy($indexFile, XOOPS_ROOT_PATH."/uploads/club/equipes/index.html");

	copy(XOOPS_ROOT_PATH."/modules/club/images/00000000.gif", XOOPS_ROOT_PATH."/uploads/club/joueurs/00000000.gif");

	return true;
}

?>