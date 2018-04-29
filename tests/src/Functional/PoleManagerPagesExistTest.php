<?php

namespace Drupal\Tests\pole_manager\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * @group pole_manager
 */
class PoleManagerPagesExistTest extends BrowserTestBase {
  protected static $modules = ['pole_manager'];

  public function testPoleCollectionPage() {
    $account = $this->drupalCreateUser(['administer pole entity entities']);
    $this->drupalLogin($account);

    $this->drupalGet('poles/collection');
    $this->assertSession()->statusCodeEquals(200);
  }
}
