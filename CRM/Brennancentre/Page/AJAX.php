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

class CRM_Brennancentre_Page_AJAX {

  /**
   * Validate captcha.
   *
   * @return bool
   */
  public static function validateCaptcha() {
    // get the captchaResponse parameter sent from our ajax
    $captcha = filter_input(INPUT_POST, 'captchaResponse');

    /* Check if captcha is filled */
    if (!$captcha) {
      // Return error code if there is no captcha
      http_response_code(401); 
    }
    $recaptchaKey = '6LdNfSkTAAAAANCwZTg3MMMDLaEaEGNlUPU0az6v';
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaKey}&response={$captcha}");
    $response = json_decode($response);
    $output = array('isError' => FALSE);
    if ($response->success  == FALSE) {
      $field = 'error-codes';
      $output =  array(
        'isError' => TRUE,
        'error_message' => reset($response->$field),
      );
    }
    self::output($output);
  }

  public static function output($input) {
    echo json_encode($input);
    CRM_Utils_System::civiExit();
  }

}
