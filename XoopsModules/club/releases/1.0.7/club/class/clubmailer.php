<?php
// $Id: xoopsmailer.php 797 2006-11-08 02:21:38Z skalpa $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
if (!defined("XOOPS_ROOT_PATH")) {
    die("XOOPS root path not defined");
}

include_once(XOOPS_ROOT_PATH."/class/xoopsmailer.php");


/**
 * Class for sending mail.
 *
 * Changed to use the facilities of  {@link XoopsMultiMailer}
 *
 * @deprecated	use {@link XoopsMultiMailer} instead.
 *
 * @package		class
 * @subpackage	mail
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class ClubMailer extends XoopsMailer
{

	function ClubMailer() {
		$this->XoopsMailer();
	}

	// public
	function send($debug = false, $membres)
	{
		global $xoopsConfig;
		if ( $this->body == "" && $this->template == "" ) {
			if ($debug) {
				$this->errors[] = _MAIL_MSGBODY;
			}
			return false;
		} elseif ( $this->template != "" ) {
			$path = ( $this->templatedir != "" ) ? $this->templatedir."".$this->template : (XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/mail_template/".$this->template);
			if ( !($fd = @fopen($path, 'r')) ) {
				if ($debug) {
					$this->errors[] = _MAIL_FAILOPTPL;
				}
            			return false;
        		}
			$this->setBody(fread($fd, filesize($path)));
		}

		// for sending mail only
		if ( $this->isMail  || !empty($this->toEmails) ) {
			if (!empty($this->priority)) {
				$this->headers[] = "X-Priority: " . $this->priority;
			}
			//$this->headers[] = "X-Mailer: PHP/".phpversion();
			//$this->headers[] = "Return-Path: ".$this->fromEmail;
			$headers = join($this->LE, $this->headers);
		}

// TODO: we should have an option of no-reply for private messages and emails
// to which we do not accept replies.  e.g. the site admin doesn't want a
// a lot of message from people trying to unsubscribe.  Just make sure to
// give good instructions in the message.

		// add some standard tags (user-dependent tags are included later)
		global $xoopsConfig;
		$this->assign ('X_ADMINMAIL', $xoopsConfig['adminmail']);
		$this->assign ('X_SITENAME', $xoopsConfig['sitename']);
		$this->assign ('X_SITEURL', XOOPS_URL);
		// TODO: also X_ADMINNAME??
		// TODO: X_SIGNATURE, X_DISCLAIMER ?? - these are probably best
		//  done as includes if mail templates ever get this sophisticated

		// replace tags with actual values
		foreach ( $this->assignedTags as $k => $v ) {
			$this->body = str_replace("{".$k."}", $v, $this->body);
			$this->subject = str_replace("{".$k."}", $v, $this->subject);
		}
		$this->body = str_replace("\r\n", "\n", $this->body);
		$this->body = str_replace("\r", "\n", $this->body);
		$this->body = str_replace("\n", $this->LE, $this->body);

		// send mail to specified mail addresses, if any
		foreach ( $this->toEmails as $mailaddr ) {
			if ( !$this->sendMail($mailaddr, $this->subject, $this->body, $headers) ) {
				if ($debug) {
					$this->errors[] = sprintf(_MAIL_SENDMAILNG, $mailaddr);
				}
			} else {
				if ($debug) {
					$this->success[] = sprintf(_MAIL_MAILGOOD, $mailaddr);
				}
			}
		}

		// send message to specified users, if any

		// NOTE: we don't send to LIST of recipients, because the tags
		// below are dependent on the user identity; i.e. each user
		// receives (potentially) a different message

		foreach ( $this->toUsers as $user ) {
			// set some user specific variables
			$nom = $membres[$user->getVar('uid')]->getVar('membre_nom');
			$prenom = $membres[$user->getVar('uid')]->getVar('membre_prenom');
			$subject = str_replace("{X_UNAME}", $user->getVar("uname"), $this->subject );
			$subject = str_replace("{X_NOM}", $nom, $subject );
			$subject = str_replace("{X_PRENOM}", $prenom, $subject );
			$text = str_replace("{X_UEMAIL}", $user->getVar("email"), $this->body );
			$text = str_replace("{X_UNAME}", $user->getVar("uname"), $text );
			$text = str_replace("{X_NOM}", $nom, $text );
			$text = str_replace("{X_PRENOM}", $prenom, $text );
			// send mail
			if ( $this->isMail ) {
				if ( !$this->sendMail($user->getVar("email"), $subject, $text, $headers) ) {
					if ($debug) {
						$this->errors[] = sprintf(_MAIL_SENDMAILNG, $user->getVar("uname"));
					}
				} else {
					if ($debug) {
						$this->success[] = "Message envoyé à <b>$prenom $nom</b>";
					}
				}
			}
			// send private message
			if ( $this->isPM ) {
				if ( !$this->sendPM($user->getVar("uid"), $subject, $text) ) {
					if ($debug) {
						$this->errors[] = sprintf(_MAIL_SENDPMNG, $user->getVar("uname"));
					}
				} else {
					if ($debug) {
						$this->success[] = sprintf(_MAIL_PMGOOD, $user->getVar("uname"));
					}
				}
			}
			flush();
		}
		if ( count($this->errors) > 0 ) {
			return false;
		}
		return true;
	}

}
?>