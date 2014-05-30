<?php

require_once 'brennancentre.civix.php';

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
          if (in_array($val[0], array('height', 'width'))) {
            $imgTag->setAttribute($val[0], str_replace('px', '', $val[1]));
            unset($styleArray[$key]);
          }
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