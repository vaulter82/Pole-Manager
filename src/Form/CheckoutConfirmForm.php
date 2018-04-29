<?php

namespace Drupal\pole_manager\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CheckoutConfirmForm extends ConfirmFormBase {
  protected $poleInfo = [];

  protected $tempStoreFactory;

  protected $manager;

  public function __construct(PrivateTempStoreFactory $temp_store_factory, EntityManagerInterface $manager) {
    $this->tempStoreFactory = $temp_store_factory;
    $this->storage = $manager->getStorage('pole_entity');
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('tempstore.private'),
      $container->get('entity.manager')
    );
  }

  public function getFormId() {
    return 'checkout_confirm_form';
  }

  public function getDescription() {
    return '';
  }

  public function getQuestion() {
    return 'Checkout poles';
  }

  public function getCancelURL() {
    return new Url('system.admin_content');
  }

  public function getConfirmText() {
    return t('Checkout');
  }

  public function buildForm( array $form, FormStateInterface $form_state ) {
    $this->poleInfo = $this->tempStoreFactory->get('checkout_confirm_form')->get(\Drupal::currentUser()->id());

    if( empty($this->poleInfo) ) {
      return new RedirectResponse($this->getCancelUrl()->setAbsolute()->toString());
    }

    $poles = $this->storage->loadMultiple( array_keys($this->poleInfo) );

    $form['club_member'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'user',
      '#selection_settings' => ['include_anonymous' => FALSE],
      '#title' => $this->t('Club Member'),
      '#required' => TRUE,
    ];

    $form['poles'] = [
      '#type' => 'details',
      '#title' => $this->t('Selected poles'),
      '#open' => TRUE,
    ];

    foreach( $this->poleInfo as $id => $pole ) {
      $form['poles'][$id] = [
        '#type' => 'item',
        '#markup' => $this->t('<li>@label</li>', ['@label' => $pole->label()]),
      ];
    }

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  public function submitForm( array &$form, FormStateInterface $form_state ) {
    if( $form_state->getValue('confirm') && !empty($this->poleInfo) ) {
      $poles = $this->storage->loadMultiple( array_keys($this->poleInfo) );
      $total_count = 0;
      $uid = $form_state->getValue('club_member');

      foreach( $poles as $pole ) {
        $pole->setOwnerId($uid);
        $pole->checkout_date->value = time();
        $pole->save();

        $total_count++;
      }

      drupal_set_message($this->formatPlural($total_count, 'Checked out 1 pole.', '@count poles were checked out.'));

      $this->tempStoreFactory->get('checkout_confirm_form')->delete(\Drupal::currentUser()->id());
    }

    $form_state->setRedirect('system.admin_content');
  }
}
