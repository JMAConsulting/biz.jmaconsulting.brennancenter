<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */
 */
class CRM_Brennancentre_Page_Captcha {

  /**
   *
   *
   * 
   */
  public static function validateCaptcha() {
    $captcha = filter_input($_POST, 'captchaResponse'); // get the captchaResponse parameter sent from our ajax
 
    /* Check if captcha is filled */
    if (!$captcha) {
      http_response_code(401); // Return error code if there is no captcha
    }
    $recaptchaKey = '6LczZSkTAAAAAHeZCo8a5FN22Vl7SOvYRRx6MZB0';
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaKey}&response={$captcha}");
    if ($response  == false) {
      echo 'SPAM';
      http_response_code(401); // It's SPAM! RETURN SOME KIND OF ERROR
    }
    else {
      // Everything is ok and you can proceed by executing your login, signup, update etc scripts
    }
  }

}