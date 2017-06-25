<?php

namespace Drupal\pole_manager;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Pole entity entity.
 *
 * @see \Drupal\pole_manager\Entity\PoleEntity.
 */
class PoleEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\pole_manager\Entity\PoleEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished pole entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published pole entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit pole entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete pole entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add pole entity entities');
  }

}
