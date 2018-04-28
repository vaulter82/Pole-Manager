<?php

namespace Drupal\Tests\pole_manager\Functional;

use Drupal\Tests\BrowserTestBase;

class PoleManagerPagesExistTest extends BrowserTestBase {
  protected static $modules = ['pole_manager'];
  
  public function testPoleCollectionPage() {
    $this->drupalGet('poles/collection');
    $this->assertSession()->statusCodeEquals(200);
  }
}
