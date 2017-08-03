<?php

namespace Drupal\pole_manager\Form;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\user\PrivateTempStoreFactory;
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
      $container->get('user.private_tempstore'),
      $container->get('entity.manager')
    );
  }
  
  public function getFormId() {
    return 'checkin_confirm_form';
  }
  
  public function getQuestion() {
    return 'Check-in poles';
  }
  
  public function getCancelURL() {
    return new Url('system.admin_content');
  }
  
  public function getConfirmText() {
    return t('Check-in');
  }
  
  public function buildForm( array $form, FormStateInterface $form_state ) {
    $this->poleInfo = $this->tempStoreFactory->get('checkin_confirm_form')->get(\Drupal::currentUser()->id());
    if( empty($this->poleInfo) ) {
      return new RedirectResponse($this->getCancelUrl()->setAbsolute()->toString());
    }
    $poles = $this->storage->loadMultiple( array_keys($this->poleInfo) );
    //$form['club_member'] = 
    $form['poles'] = [
      '#theme' => 'item_list',
      '#items' => $poles,
    ];
    $form = parent::buildForm($form, $form_state);
    return $form;
  }
  
  public function submitForm( array &$form, FormStateInterface $form_state ) {
    if( $form_state->getValue('confirm') && !empty($this->poleInfo) ) {
      $poles = $this->storage->loadMultiple( array_keys($this->poleInfo) );
      foreach( $poles as $pole ) {
        $pole->notes->value = "checked out";
      }
      drupal_set_message($this->formatPlural($total_count, 'Checked out 1 pole.', '@count poles were checked out.'));
    }
    $form_state->setRedirect('system.admin_content');
  }
}
