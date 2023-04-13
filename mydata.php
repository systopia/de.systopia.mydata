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
  $permissions['my_contact']['get']               = array('view my contact');
  $permissions['my_contact']['getsingle']         = array('view my contact');
  $permissions['my_contact']['create']            = array('edit my contact');
  $permissions['my_contact']['update']            = array('edit my contact');
  $permissions['my_address']['get']               = array('view my contact');
  $permissions['my_address']['getsingle']         = array('view my contact');
  $permissions['my_address']['create']            = array('edit my contact');
  $permissions['my_address']['update']            = array('edit my contact');
  $permissions['my_phone']['get']                 = array('view my contact');
  $permissions['my_phone']['getsingle']           = array('view my contact');
  $permissions['my_phone']['create']              = array('edit my contact');
  $permissions['my_phone']['update']              = array('edit my contact');
  $permissions['my_email']['get']                 = array('view my contact');
  $permissions['my_email']['getsingle']           = array('view my contact');
  $permissions['my_email']['create']              = array('edit my contact');
  $permissions['my_email']['update']              = array('edit my contact');
  $permissions['my_contribution']['get']          = array('view my contact');
  $permissions['my_contribution']['getsingle']    = array('view my contact');
  $permissions['my_contribution']['create']       = array('edit my contact');
  $permissions['my_membership']['get']            = array('view my contact');
  $permissions['my_membership']['getsingle']      = array('view my contact');
  $permissions['my_membership']['create']         = array('edit my contact');
  $permissions['my_custom_value']['get']          = array('view my contact');
  $permissions['my_membership_payment']['create'] = array('edit my contact');
}

/**
 * Implementation of hook_civicrm_config
 */
function mydata_civicrm_config(&$config) {
  _mydata_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_install
 */
function mydata_civicrm_install() {
  return _mydata_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_enable
 */
function mydata_civicrm_enable() {
  return _mydata_civix_civicrm_enable();
}
