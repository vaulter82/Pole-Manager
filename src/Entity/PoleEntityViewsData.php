<?php

namespace Drupal\pole_manager\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Pole entity entities.
 */
class PoleEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
