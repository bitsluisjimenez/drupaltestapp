<?php

namespace Drupal\user_profile;

/**
 * Class UserProfileStorage.
 */
class UserProfileStorage implements UserProfileStorageInterface {

  /**
   * Constructs a new UserProfileStorage object.
   */
  public function __construct() {

  }

  /**
   * Create a new user in DB.
   */
  public function createUserFromProfile($userData) {
    $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
    $userName = $userData->get('first_name') . ' ' . $userData->get('last_name');
    $values = [
      'field_first_name' => $userData->get('first_name') ?? '',
      'field_last_name' => $userData->get('last_name') ?? '',
      'field_gender' => $userData->get('gender') ?? '',
      'field_date_of_birth' => $userData->get('date_of_birth') ?? '',
      'field_city' => $userData->get('city') ?? '',
      'field_phone_number' => $userData->get('phone_number') ?? '',
      'field_address' => $userData->get('address') ?? '',
      'name' => $userName,
      //'mail' => 'test@test.com',
      'pass' => 'password',
      'status' => 1,
      'langcode' => $lang,
      'preferred_langcode ' => $lang,
      'preferred_admin_langcode' => $lang,
    ];

    return \Drupal::entityTypeManager()
      ->getStorage('user')
      ->create($values)
      ->save();
  }

}
