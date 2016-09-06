<?php

require_once 'brennancentre.civix.php';
define('THANKYOU_SINGUP_PROFILE_ID', 13);
define('SINGUP_PROFILE_ID', 18);
/**
 * Implementation of hook_civicrm_config
 */
function brennancentre_civicrm_config(&$config) {
  _brennancentre_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function brennancentre_civicrm_xmlMenu(&$files) {
  _brennancentre_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function brennancentre_civicrm_install() {
  return _brennancentre_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function brennancentre_civicrm_uninstall() {
  return _brennancentre_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function brennancentre_civicrm_enable() {
  return _brennancentre_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function brennancentre_civicrm_disable() {
  return _brennancentre_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 */
function brennancentre_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _brennancentre_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function brennancentre_civicrm_managed(&$entities) {
  return _brennancentre_civix_civicrm_managed($entities);
}

function brennancentre_civicrm_validate($formName, &$fields, &$files, &$form) {
  if ('CRM_Profile_Form_Edit' == $formName && $form->getVar('_gid') == SINGUP_PROFILE_ID) {
    global $user;
    if ($user->uid == 0 && CRM_Utils_Array::value('htmlForm', $fields)) {
      $form->setElementError('g-recaptcha-response', NULL);
      $form->setElementError('recaptcha_challenge_field', NULL);
    }
  }
}

function brennancentre_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Profile_Form_Edit' && $form->getVar('_gid') == THANKYOU_SINGUP_PROFILE_ID) {
    $form->_elements[$form->_elementIndex['buttons']]->_elements[0]->_attributes['value'] = ts('Submit');
    CRM_Core_Resources::singleton()->addStyle('
      div.crm-profile-name-Thank_you_for_signing_up_ div.crm-submit-buttons span.crm-button-type-cancel
        {display:none !important;}

      div.crm-profile-name-Thank_you_for_signing_up_ div.crm-submit-buttons span.crm-button-type-next input{  
        background-color: #df1f0f !important;
        background-image: linear-gradient(to bottom, #df1f0f, #c41a0c) !important;
        background-size: 100% auto !important;
        border: 1px solid #700006  !important;
        color: #fff !important;
        width: 124px !important;
        display: inline-block !important;
        font-size: 1.2em !important;
        font-weight: 700 !important;
        padding: 4px 14px !important;
        text-align: center !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
      }
      div.crm-profile-name-Thank_you_for_signing_up_ div.crm-submit-buttons span.crm-button-type-next {
        border:none !important;
        background : none !important;
      }
      div.crm-profile-name-Thank_you_for_signing_up_ div.crm-submit-buttons {margin-left: auto !important;
       margin-right: auto !important;
       width: 496px;
      }'
    );
  }
}

function brennancentre_civicrm_alterMailParams(&$params, $context) {
  if (!empty($params['html'])) {
    $dochtml = new DOMDocument();
    $dochtml->loadHTML($params['html']);
    $imgTags = $dochtml->getElementsByTagName('img');
    
    foreach($imgTags as $imgTag) {
      $styleAttr = $imgTag->getAttribute('style');
      $heightAttr = $imgTag->getAttribute('height');
      $widthAttr = $imgTag->getAttribute('width');
      if ($styleAttr) {
        $styleArray = explode(';', $styleAttr);
        foreach ($styleArray as $key => $value) {
          if (!$value) {
            unset($styleArray[$key]);
          }
          $val = explode(': ', $value);
          $val[0] = trim($val[0]);
          switch ($val[0]) {
          case 'height':
          case 'width':
            break;
          case 'margin':
          case 'margin-left':
          case 'margin-right':
          case 'margin-top':
          case 'margin-bottom':
            switch ($val[0]) {
            case 'margin':
              $margin = explode(' ', $val[1]);
              $imgTag->setAttribute('hspace', str_replace('px', '', trim($margin[1])));
              $imgTag->setAttribute('vspace', str_replace('px', '', trim($margin[0])));
              unset($styleArray[$key]);
              $val[0] = '';
              break;
            case 'margin-left':
            case 'margin-right':
              $val[0] = 'hspace';
              break;
            case 'margin-top':
            case 'margin-bottom':
              $val[0] = 'vspace';
              break;
            }
            break;
          case 'float':
            $val[0] = 'align';              
            break;
          default:
            $val[0] = '';
          }
          if (empty($val[0])) {
            continue;
          }
          $imgTag->setAttribute($val[0], str_replace('px', '', $val[1]));
          unset($styleArray[$key]);
        }
        if (!empty($styleArray)) {
          $imgTag->setAttribute('style', implode(';', $styleArray));
        }
        else {
          $imgTag->removeAttribute('style');
        }
      }
      if ($heightAttr) {
        $imgTag->setAttribute('height', str_replace('px', '', $heightAttr));
      }
      if ($widthAttr) {
        $imgTag->setAttribute('width', str_replace('px', '', $widthAttr));
      }
    }
    $params['html'] = preg_replace(array("/^\<\!DOCTYPE.*?<html><body>/si",
      "!</body></html>$!si"),
      "",
      $dochtml->saveHTML()
    );
  }
}

function brennancentre_civicrm_unsubscribeGroups($op, $mailingId, $contactId, &$groups, &$baseGroups) {
  if ($op == 'unsubscribe') {
    foreach ($groups as $group) {
      $gid = CRM_Core_DAO::singleValueQuery("SELECT g.id FROM civicrm_group g
      LEFT JOIN civicrm_mailing_group m ON m.entity_id = g.id AND m.entity_table = 'civicrm_group'
      WHERE m.entity_id = {$group} AND m.group_type = 'Base'");
      if ($gid) {
        $params = array(
          'contact_id' => $contactId,
          'group_id' => $gid,
        );
        $result = civicrm_api3('group_contact', 'create', $params);
      }
    }
  }
}
