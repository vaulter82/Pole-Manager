<?php

namespace Drupal\pole_manager\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Pole entity entity.
 *
 * @ingroup pole_manager
 *
 * @ContentEntityType(
 *   id = "pole_entity",
 *   label = @Translation("Pole"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\pole_manager\PoleEntityListBuilder",
 *     "views_data" = "Drupal\pole_manager\Entity\PoleEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\pole_manager\Form\PoleEntityForm",
 *       "add" = "Drupal\pole_manager\Form\PoleEntityForm",
 *       "edit" = "Drupal\pole_manager\Form\PoleEntityForm",
 *       "delete" = "Drupal\pole_manager\Form\PoleEntityDeleteForm",
 *     },
 *     "access" = "Drupal\pole_manager\PoleEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\pole_manager\PoleEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "pole_entity",
 *   admin_permission = "administer pole entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "serial_number",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/poles/pole/{pole_entity}",
 *     "add-form" = "/poles/add",
 *     "edit-form" = "/poles/pole/{pole_entity}/edit",
 *     "delete-form" = "/poles/pole/{pole_entity}/delete",
 *     "collection" = "/poles/collection",
 *   },
 *   field_ui_base_route = "pole_entity.settings"
 * )
 */
class PoleEntity extends ContentEntityBase implements PoleEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Checked out by'))
      ->setDescription(t('The club member that currently has this pole checked out.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 1,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Model - string
    $fields['make'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Make'))
      ->setDescription(t('The make of the Pole.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 64,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Length - string (formatted?)
    $fields['length'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Length'))
      ->setDescription(t('The length of the Pole.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 16,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Weight - integer (possibly decimal)
    $fields['weight'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Weight'))
      ->setDescription(t('The weight rating of the Pole.'))
      ->setSettings(array(
        'default_value' => NULL,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number_integer',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    // Flex - decimal
    $fields['flex'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Flex'))
      ->setDescription(t('The flex number of the Pole.'))
      ->setSettings(array(
        'default_value' => NULL,
        'scale' => 1,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'weight' => -3,
      ))
      ->setDisplayOptions('form', array(
        'weight' => -3,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Serial Number - string (formatted?)
    $fields['serial_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Serial Number'))
      ->setDescription(t('The serial number of the Pole. Should be unique.'))
      ->setPropertyConstraints('value', array('UniqueField' => null))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 16,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -2,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -2,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Model Number - string
    $fields['model_number'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Model Number'))
      ->setDescription(t('The model number of the Pole.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 16,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -1,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -1,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    // Notes - string_long
    $fields['notes'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Notes'))
      ->setDescription(t('Notes about the Pole.'))
      ->setSettings(array(
        'default_value' => '',
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textarea',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['checkout_date'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Checkout Date'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Pole entity is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
}
