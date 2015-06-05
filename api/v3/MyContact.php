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

require_once 'api/v3/Contact.php';

/**
 * Wrapper for Contact.get API call
 * requires the user to have 'view my contact' permissions
 * and will ONLY return data of the user him/herself
 *
 * @access public
 */
function civicrm_api3_my_contact_get($params) {
  // get user's CiviCRM ID
  $user_id = mydata_civicrm_get_civicrm_user_id();
  if (empty($user_id)) {
    return civicrm_api3_create_error("The CiviCRM id of the caller could not be determined.");
  } else {
    $params['id'] = $user_id;
    if (isset($params['contact_id'])) unset($params['contact_id']);
    $params['check_permissions'] = 0;
    return civicrm_api3_contact_get($params);
  }
}

/**
 * Adjust Metadata for MyContact.get action
 */
function _civicrm_api3_my_contact_get_spec(&$params) {
  return _civicrm_api3_contact_get_spec($params);
}




/**
 * Wrapper for Contact.getsingle API call
 * requires the user to have 'view my contact' permissions
 * and will ONLY return data of the user him/herself
 *
 * @access public
 */
function civicrm_api3_my_contact_getsingle($params) {
  // just pass it on to MyContact.get...
  $result = civicrm_api3_my_contact_get($params);
  if ($result['is_error'] !== 0) {
    return $result;
  } elseif ($result['count'] === 1) {
    return $result['values'][0];
  } elseif ($result['count'] !== 1) {
    return civicrm_api3_create_error("Expected one " . $params['entity'] . " but found " . $result['count'], array('count' => $result['count']));
  } else {
    return civicrm_api3_create_error("Undefined behavior");
  }
}

/**
 * Adjust Metadata for MyContact.getsingle action
 */
function _civicrm_api3_my_contact_getsingle_spec(&$params) {
  return _civicrm_api3_contact_get_spec($params);
}




/**
 * Wrapper for Contact.create API call
 * requires the user to have 'view my contact' permissions
 * and will ONLY return data of the user him/herself
 *
 * @access public
 */
function civicrm_api3_my_contact_create($params) {
  // get user's CiviCRM ID
  $user_id = mydata_civicrm_get_civicrm_user_id();
  if (empty($user_id)) {
    return civicrm_api3_create_error("The CiviCRM id of the caller could not be determined.");
  } else {
    $params['id'] = $user_id;
    if (isset($params['contact_id'])) unset($params['contact_id']);
    $params['check_permissions'] = 0;
    return civicrm_api3_contact_create($params);
  }
}

/**
 * Adjust Metadata for MyContact.create action
 */
function _civicrm_api3_my_contact_create_spec(&$params) {
  _civicrm_api3_contact_create_spec($params);
  // we don't need 'contact_type', we're only editing contacts
  $params['contact_type']['api.required'] = 0;
}
