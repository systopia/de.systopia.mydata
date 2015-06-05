# de.systopia.mydata
CiviCRM extension to allow API access to your own data

## This extension offers an API wrapper for these four entities:
 * Contact => MyContact
 * Address => MyAddress
 * Phone => MyPhone
 * Email => MyEmail

They only allow viewing and editing *your own* contact and it's address/email/phone.

The API calls require the ``view my contact`` and ``edit my contact`` respectively.
