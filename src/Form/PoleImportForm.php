<?php

namespace Drupal\pole_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pole_manager\Form\PoleEntityForm;

class PoleImportForm extends FormBase {
  public function getFormId() {
    return 'pole_import_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form["file"] = [
      '#type' => 'file',
    ];

    $form["submit"] = [
      '#type' => 'submit',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $file = file_save_upload('file', array(
      'file_validate_extensions' => array(
        'csv',
      ),
    ));

    if( $file ) {
      $form_state['storage']['file'] = $file;
    } else {
      form_set_error('file', t("Must be a .csv file"));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $file = $form_state['storage']['file'];

    unset($form_state['storage']['file']);

    $uri = $file->absolutePath();

    $fhandle = fopen($uri, 'r');
    $result = fread($fhandle, filesize($uri));
    $results = split('\n', $result);

    drupal_set_message(t("Found something! {$results[0]}"), 'status');

    fclose($fhandle);
    $file->delete();
  }
}