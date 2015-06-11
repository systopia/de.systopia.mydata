<?php
/*-------------------------------------------------------+
| MyData - CiviCRM extension to access own data via API  |
| Copyright (C) 2015 SYSTOPIA                            |
| Author: B. Endres (endres -at- systopia.de)            |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

require_once 'mydata.civix.php';

/**
 * helper function to extract the API user's CiviCRM ID
 */
function mydata_civicrm_get_civicrm_user_id() {
  $config = CRM_Core_Config::singleton();
  $ufID = $config->userSystem->getLoggedInUfID();
  if (empty($ufID)) {
    return NULL;
  } else {
    return (int) CRM_Core_BAO_UFMatch::getContactId($ufID);
  }
}

/**
 * helper function to verify an about-to-be-edited entity actually belongs to the user
 *
 * @param $entity_type  the entity type (e.g. 'Address')
 * @param $entity_id    the entity's ID
 * @param $user_id      the ID expected to find in the entity's contact_id field
 *
 * @return TRUE         iff the entity exists, and its contact_id field is set to $user_id
 */
function mydata_civicrm_verify_entity_belongs_to_user($entity_type, $entity_id, $user_id) {
  if (empty($user_id)) return FALSE;

  try {
    $entity = civicrm_api3($entity_type, 'getsingle', array('id' => $entity_id));
    return $entity['contact_id'] == $user_id;
  } catch (Exception $e) {
    // doesn't exist or not specified => all the same to us: FAILED
  }
  return FALSE;
}


/**
 * Set permission to the API calls
 */
function mydata_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  $permissions['my_contact']['get']              = array('view my contact');
  $permissions['my_contact']['getsingle']        = array('view my contact');
  $permissions['my_contact']['create']           = array('edit my contact');
  $permissions['my_contact']['update']           = array('edit my contact');
  $permissions['my_address']['get']              = array('view my contact');
  $permissions['my_address']['getsingle']        = array('view my contact');
  $permissions['my_address']['create']           = array('edit my contact');
  $permissions['my_address']['update']           = array('edit my contact');
  $permissions['my_phone']['get']                = array('view my contact');
  $permissions['my_phone']['getsingle']          = array('view my contact');
  $permissions['my_phone']['create']             = array('edit my contact');
  $permissions['my_phone']['update']             = array('edit my contact');
  $permissions['my_email']['get']                = array('view my contact');
  $permissions['my_email']['getsingle']          = array('view my contact');
  $permissions['my_email']['create']             = array('edit my contact');
  $permissions['my_email']['update']             = array('edit my contact');
  $permissions['my_contribution']['get']         = array('view my contact');
  $permissions['my_contribution']['getsingle']   = array('view my contact');
  $permissions['my_contribution']['create']      = array('edit my contact');
  $permissions['my_membership']['get']           = array('view my contact');
  $permissions['my_membership']['getsingle']     = array('view my contact');
  $permissions['my_membership']['create']        = array('edit my contact');
}

/**
 * Implementation of hook_civicrm_config
 */
function mydata_civicrm_config(&$config) {
  _mydata_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function mydata_civicrm_xmlMenu(&$files) {
  _mydata_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 */
function mydata_civicrm_install() {
  return _mydata_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function mydata_civicrm_uninstall() {
  return _mydata_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 */
function mydata_civicrm_enable() {
  return _mydata_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 */
function mydata_civicrm_disable() {
  return _mydata_civix_civicrm_disable();
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
function mydata_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _mydata_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 */
function mydata_civicrm_managed(&$entities) {
  return _mydata_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 */
function mydata_civicrm_caseTypes(&$caseTypes) {
  _mydata_civix_civicrm_caseTypes($caseTypes);
}
