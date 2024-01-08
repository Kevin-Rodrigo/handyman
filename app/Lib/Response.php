<?php

//default responses
const DEFAULT_200 = [
    'response_code' => 'default_200',
    'message' => 'successfully loaded'
];

const DEFAULT_SENT_OTP_200 = [
    'response_code' => 'default_200',
    'message' => 'successfully sent OTP'
];

const DEFAULT_SENT_OTP_FAILED_200 = [
    'response_code' => 'default_200',
    'message' => 'Failed to sent OTP'
];

const OTP_VERIFICATION_SUCCESS_200 = [
    'response_code' => 'default_200',
    'message' => 'Successfully verified'
];
const OTP_VERIFICATION_FAIL_403 = [
    'response_code' => 'default_403',
    'message' => 'Verification failed'
];

const DEFAULT_VERIFIED_200 = [
    'response_code' => 'default_verified_200',
    'message' => 'successfully verified'
];

const DEFAULT_PASSWORD_RESET_200 = [
    'response_code' => 'default_password_reset_200',
    'message' => 'password reset successful'
];

const NO_CHANGES_FOUND = [
    'response_code' => 'no_changes_found_200',
    'message' => 'no changes found'
];

const DEFAULT_204 = [
    'response_code' => 'default_204',
    'message' => 'information not found'
];

const DEFAULT_400 = [
    'response_code' => 'default_400',
    'message' => 'invalid or missing information'
];

const DEFAULT_401 = [
    'response_code' => 'default_401',
    'message' => 'credential does not match'
];

const DEFAULT_USER_REMOVED_401 = [
    'response_code' => 'default_user_removed_401',
    'message' => 'user has been removed, please talk to the authority'
];

const DEFAULT_USER_DISABLED_401 = [
    'response_code' => 'default_user_disabled_401',
    'message' => 'user has been disabled, please talk to the authority'
];

const DEFAULT_403 = [
    'response_code' => 'default_403',
    'message' => 'your access has been denied'
];
const DEFAULT_404 = [
    'response_code' => 'default_404',
    'message' => 'resource not found'
];

const DEFAULT_DELETE_200 = [
    'response_code' => 'default_delete_200',
    'message' => 'successfully deleted information'
];

const DEFAULT_FAIL_200 = [
    'response_code' => 'default_fail_200',
    'message' => 'action failed'
];

const DEFAULT_PAID_200 = [
    'response_code' => 'default_paid_200',
    'message' => 'already paid'
];


const DEFAULT_STORE_200 = [
    'response_code' => 'default_store_200',
    'message' => 'successfully added'
];

const DEFAULT_UPDATE_200 = [
    'response_code' => 'default_update_200',
    'message' => 'successfully updated'
];

const DEFAULT_STATUS_UPDATE_200 = [
    'response_code' => 'default_status_update_200',
    'message' => 'successfully status updated'
];

const TOO_MANY_ATTEMPT_403 = [
    'response_code' => 'too_many_attempt_403',
    'message' => 'your api hit limit exceeded, try after a minute.'
];


const REGISTRATION_200 = [
    'response_code' => 'registration_200',
    'message' => 'successfully registered'
];

//auth module
const AUTH_LOGIN_200 = [
    'response_code' => 'auth_login_200',
    'message' => 'successfully logged in'
];

const AUTH_LOGOUT_200 = [
    'response_code' => 'auth_logout_200',
    'message' => 'successfully logged out'
];

const AUTH_LOGIN_401 = [
    'response_code' => 'auth_login_401',
    'message' => 'user credential does not match'
];

const ACCOUNT_DISABLED = [
    'response_code' => 'account_disabled_401',
    'message' => 'user account has been disabled, please talk to the admin.'
];

const AUTH_LOGIN_403 = [
    'response_code' => 'auth_login_403',
    'message' => 'wrong login credentials'
];

const AUTH_LOGIN_404 = [
    'response_code' => 'auth_login_404',
    'message' => 'User does not exist'
];

const ACCESS_DENIED = [
    'response_code' => 'access_denied_403',
    'message' => 'access denied'
];

const UNVERIFIED_EMAIL = [
    'response_code' => 'unverified_email_401',
    'message' => 'Verify your email'
];

const UNVERIFIED_PHONE = [
    'response_code' => 'unverified_phone_401',
    'message' => 'Verify your phone'
];

const REFERRAL_CODE_INVALID_400 = [
    'response_code' => 'referral_code_400',
    'message' => 'referral code is invalid'
];


//user management module
const USER_ROLE_CREATE_400 = [
    'response_code' => 'user_role_create_400',
    'message' => 'invalid or missing information'
];

const USER_ROLE_CREATE_200 = [
    'response_code' => 'user_role_create_200',
    'message' => 'successfully added'
];

const USER_ROLE_UPDATE_200 = [
    'response_code' => 'user_role_update_200',
    'message' => 'successfully updated'
];

const USER_ROLE_UPDATE_400 = [
    'response_code' => 'user_role_update_400',
    'message' => 'invalid or missing data'
];

//zone management module
const ZONE_STORE_200 = [
    'response_code' => 'zone_store_200',
    'message' => 'successfully added'
];

const ZONE_UPDATE_200 = [
    'response_code' => 'zone_update_200',
    'message' => 'successfully updated'
];

const ZONE_DESTROY_200 = [
    'response_code' => 'zone_destroy_200',
    'message' => 'successfully deleted'
];

const ZONE_404 = [
    'response_code' => 'zone_404',
    'message' => 'resource not found'
];

const ZONE_RESOURCE_404 = [
    'response_code' => 'zone_404',
    'message' => 'No provider or service is available within this zone'
];

//category management module
const CATEGORY_STORE_200 = [
    'response_code' => 'category_store_200',
    'message' => 'successfully added'
];

const CATEGORY_UPDATE_200 = [
    'response_code' => 'category_update_200',
    'message' => 'successfully updated'
];

const CATEGORY_DESTROY_200 = [
    'response_code' => 'category_destroy_200',
    'message' => 'successfully deleted'
];

const CATEGORY_204 = [
    'response_code' => 'category_404',
    'message' => 'resource not found'
];

//discount section
const DISCOUNT_CREATE_200 = [
    'response_code' => 'discount_create_200',
    'message' => 'successfully added discount'
];

const DISCOUNT_UPDATE_200 = [
    'response_code' => 'discount_update_200',
    'message' => 'successfully updated discount'
];

//service management module

const SERVICE_STORE_200 = [
    'response_code' => 'service_store_200',
    'message' => 'successfully added'
];

//coupon section
const COUPON_UPDATE_200 = [
    'response_code' => 'coupon_update_200',
    'message' => 'successfully updated'
];

const CAMPAIGN_UPDATE_200 = [
    'response_code' => 'coupon_update_200',
    'message' => 'successfully updated'
];

//banner section
const BANNER_CREATE_200 = [
    'response_code' => 'banner_create_200',
    'message' => 'successfully added'
];

const BANNER_UPDATE_200 = [
    'response_code' => 'banner_update_200',
    'message' => 'successfully updated'
];


const COUPON_NOT_VALID_FOR_CART=[
    'response_code' => 'coupon_not_valid_for_your_cart',
    'message' => 'this coupon is not valid for your cart'
];

//provider management module
const PROVIDER_STORE_200 = [
    'response_code' => 'provider_store_200',
    'message' => 'successfully added'
];
const PROVIDER_REGISTERED_200 = [
    'response_code' => 'provider_store_200',
    'message' => 'successfully registered'
];

const PROVIDER_400 = [
    'response_code' => 'provider_store_400',
    'message' => 'invalid or missing information'
];


//transaction
const COLLECT_CASH_SUCCESS_200 = [
    'response_code' => 'collect_cash_success_200',
    'message' => 'cash collected successfully'
];

const COLLECT_CASH_FAIL_200 = [
    'response_code' => 'collect_cash_fail_200',
    'message' => 'failed to collect the cash'
];

//booking
const BOOKING_PLACE_SUCCESS_200 = [
    'response_code' => 'booking_place_success_200',
    'message' => 'Booking Placed successfully'
];
const BOOKING_PLACE_FAIL_200 = [
    'response_code' => 'booking_place_fail_200',
    'message' => 'Booking Place failed'
];
const BOOKING_STATUS_UPDATE_SUCCESS_200 = [
    'response_code' => 'status_update_success_200',
    'message' => 'status changed successfully'
];
const BOOKING_STATUS_UPDATE_FAIL_200 = [
    'response_code' => 'status_update_fail_200',
    'message' => 'failed to change the status'
];

const DELIVERYMAN_ASSIGN_200 = [
    'response_code' => 'deliveryman_assign_200',
    'message' => 'Deliveryman must assign first'
];

const MINIMUM_BOOKING_AMOUNT_200 = [
    'response_code' => 'minimum_booking_amount_200',
    'message' => 'Booking amount must be greater than minimum booking amount'
];

const UPDATE_FAILED_FOR_OFFLINE_PAYMENT_VERIFICATION_200 = [
    'response_code' => 'minimum_booking_amount_200',
    'message' => 'Admin must verify the offline payment before completing the booking'
];


//Random
const DEFAULT_STATUS_FAILED_200 = [
    'response_code' => 'default_status_change_failed_200',
    'message' => 'Minimum one method must be selected as default'
];
const INSUFFICIENT_WALLET_BALANCE_400 = [
    'response_code' => 'insufficient_wallet_balance_400',
    'message' => 'Wallet balance is insufficient'
];

const NOTIFICATION_SEND_SUCCESSFULLY_200 = [
    'response_code' => 'notification_send_successfully_200',
    'message' => 'Notification has been sent successfully'
];

const NOTIFICATION_SEND_FAILED_200 = [
    'response_code' => 'notification_send_failed_200',
    'message' => 'Notification has been failed to send'
];
