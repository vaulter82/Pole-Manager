<?php

namespace Drupal\pole_manager\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Pole entity entities.
 *
 * @ingroup pole_manager
 */
interface PoleEntityInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Pole entity name.
   *
   * @return string
   *   Name of the Pole entity.
   */
  public function getName();

  /**
   * Sets the Pole entity name.
   *
   * @param string $name
   *   The Pole entity name.
   *
   * @return \Drupal\pole_manager\Entity\PoleEntityInterface
   *   The called Pole entity entity.
   */
  public function setName($name);

  /**
   * Gets the Pole entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Pole entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Pole entity creation timestamp.
   *
   * @param int $timestamp
   *   The Pole entity creation timestamp.
   *
   * @return \Drupal\pole_manager\Entity\PoleEntityInterface
   *   The called Pole entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Pole entity published status indicator.
   *
   * Unpublished Pole entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Pole entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Pole entity.
   *
   * @param bool $published
   *   TRUE to set this Pole entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\pole_manager\Entity\PoleEntityInterface
   *   The called Pole entity entity.
   */
  public function setPublished($published);

}
