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

require_once 'api/v3/CustomValue.php';

/**
 * Wrapper for Contribution.get API call
 * requires the user to have 'view my contact' permissions
 * and will ONLY return data of the user him/herself
 *
 * @access public
 */
function civicrm_api3_my_custom_value_get($params) {
  // get user's CiviCRM ID
  $user_id = mydata_civicrm_get_civicrm_user_id();
  if (empty($user_id)) {
    return civicrm_api3_create_error("The CiviCRM id of the caller could not be determined.");
  } else {
    $params['entity_id'] = $user_id;

    // ONLY allow access to custom group associated with 'Contact' entities
    $params['entity_table'] = 'civicrm_contact';

    $params['check_permissions'] = 0;
    return civicrm_api3_custom_value_get($params);
  }
}

/**
 * Adjust Metadata for MyContribution.get action
 */
function _civicrm_api3_my_custom_value_get_spec(&$params) {
  if (version_compare(CRM_Utils_System::version(), '4.6', '>=')) {
    // these only exist in 4.6
    _civicrm_api3_custom_value_get_spec($params);
  }
}

