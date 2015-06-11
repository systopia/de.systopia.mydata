# SYSTOPIA MyData extension
CiviCRM extension to allow API access to your own data

**This extension offers API wrappers (get/getsingle/create) for these five entities:**
 * Contact => MyContact
 * Address => MyAddress
 * Phone => MyPhone
 * Email => MyEmail
 * Contribution => MyContribution (no updates)
 * Membership => MyMembership (no updates)
 * CustomValue => MyCustomValue (only get, only a Contact custom fields)
 * MembershipPayment => MyMembershipPayment (only create)

They only allow viewing and editing *your own* contact and its associated address/email/phone/contribution/membership.

The API calls require the ``view my contact`` and ``edit my contact`` respectively.
