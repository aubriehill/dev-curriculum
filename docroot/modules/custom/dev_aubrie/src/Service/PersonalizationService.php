<?php

namespace Drupal\dev_aubrie\Service;

use Drupal\Core\Session\AccountProxyInterface;

/**
 * Class PersonalizationService.
 */
class PersonalizationService {

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Constructs a new PersonalizationService object.
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }


  public function encodeName($name = '') {
    $encoded_name = urlencode($name);
    return $encoded_name;
  }

  public function decodeName($name = '') {
    $decoded_name = urldecode($name);
    return $decoded_name;
  }
}
