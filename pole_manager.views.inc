<?php

/**
 * @file
 * Contains pole_manager\pole_manager.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Render\Markup;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageConfigInterface;
use Drupal\system\ActionConfigEntityInterface;

/**
* Implements hook_views_data().
*/
function pole_manager_views_data() {

    $data['views']['table']['group'] = t('Custom Global');
    $data['views']['table']['join'] = [
      // #global is a special flag which allows a table to appear all the time.
      '#global' => [],
    ];


    $data['views']['pole_bulk_form'] = [
        'title' => t('Pole bulk form'),
        'help' => t('A field to do bulk operations on Pole entities'),
        'field' => [
            'id' => 'pole_bulk_form',
        ],
    ];

    return $data;
}
