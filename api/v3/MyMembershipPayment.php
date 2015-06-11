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

require_once 'api/v3/MembershipPayment.php';

/**
 * Wrapper for MembershipPayment.create API call
 * requires the user to have 'edit my contact' permissions
 *
 * MembershipPayments can NOT be edited, only created
 *
 * @access public
 */
function civicrm_api3_my_membership_payment_create($params) {
  // get user's CiviCRM ID
  $user_id = mydata_civicrm_get_civicrm_user_id();
  if (empty($user_id)) {
    return civicrm_api3_create_error("The CiviCRM id of the caller could not be determined.");
  } else {
    // first, verify the membership specified by membership_id belongs to the user
    if (empty($params['membership_id']) && (int) $params['membership_id']) {
      return civicrm_api3_create_error("You need to provide a membership_id.");
    }
    $params['membership_id'] = (int) $params['membership_id'];
    $membership = civicrm_api3('Membership', 'getsingle', array('id' => $params['membership_id']));
    if ($membership['contact_id'] != $user_id) {
      return civicrm_api3_create_error("Membership [{$params['membership_id']}] does not belong to you!");
    }

    // then, verify the contribution specified by contribution_id also belongs to the user
    if (empty($params['contribution_id']) && (int) $params['contribution_id']) {
      return civicrm_api3_create_error("You need to provide a contribution_id.");
    }
    $params['contribution_id'] = (int) $params['contribution_id'];
    $membership = civicrm_api3('Contribution', 'getsingle', array('id' => $params['contribution_id']));
    if ($membership['contact_id'] != $user_id) {
      return civicrm_api3_create_error("Contribution [{$params['contribution_id']}] does not belong to you!");
    }

    $params['check_permissions'] = 0;
    return civicrm_api3_membership_payment_create($params);
  }
}

/**
 * Adjust Metadata for MyMembershipPayment.create action
 */
function _civicrm_api3_my_membership_payment_create_spec(&$params) {
  if (version_compare(CRM_Utils_System::version(), '4.6', '>=')) {
    // these only exist in 4.6
    _civicrm_api3_membership_payment_create_spec($params);
  }
}
