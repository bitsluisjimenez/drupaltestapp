<?php

namespace Drupal\user_profile;

/**
 * Interface UserProfileStorageInterface.
 */
interface UserProfileStorageInterface {

  public function createUserFromProfile($userData);

}
