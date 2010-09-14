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

if (!defined("XOOPS_ROOT_PATH")) {
	die("XOOPS root path not defined");
}

include_once 'ClubPersistableObjectHandler.php';

class ClubStatmembres extends XoopsObject
{

	function ClubStatmembres()
	{
		$this->initVar('statmembres_cat', XOBJ_DTYPE_TXTBOX, '', false);
		$this->initVar('statmembres_poussin', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('statmembres_benjamin', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('statmembres_minime', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('statmembres_cadet', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('statmembres_junior', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('statmembres_senior', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('statmembres_veteran', XOBJ_DTYPE_INT, 0, false);
	}

}

class ClubStatmembresHandler extends ClubPersistableObjectHandler {

	function ClubStatmembresHandler(&$db, $type)
	{
		parent::ClubPersistableObjectHandler($db, 'club_statmembres_'.$type, 'ClubStatmembres'.ucfirst($type), 'statmembres_cat');
	}

}

?>