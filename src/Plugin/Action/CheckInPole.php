<?php

namespace Drupal\pole_manager\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Action(
 *   id = "checkin_pole",
 *   label = @Translation("Check-in poles"),
 *   type = "pole_entity",
 *   confirm_form_route_name = "pole_manager.checkin_confirm_form"
 * )
 */
class CheckInPole extends ActionBase implements ContainerFactoryPluginInterface {
  protected $tempStore;

  protected $currentUser;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, PrivateTempStoreFactory $temp_store_factory, AccountInterface $current_user) {
    $this->currentUser = $current_user;
    $this->tempStore = $temp_store_factory->get('checkin_confirm_form');
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('tempstore.private'),
      $container->get('current_user')
    );
  }

  public function executeMultiple(array $entities) {
    $poles = [];
    foreach( $entities as $pole ) {
      $poles[$pole->id()] = $pole;
    }
    $this->tempStore->set($this->currentUser->id(), $poles);
  }

  public function execute($entity = NULL) {
      $this->executeMultiple([$entity]);
  }

  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $result = $object->access('update', $account, TRUE)
      ->andIf($object->notes->access('edit', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }
}
