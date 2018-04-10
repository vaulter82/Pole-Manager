<?php

namespace Drupal\pole_manager\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\pole_manager\Entity\PoleEntity;

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
      $form_state->setValue('file', $file);
    } else {
      $form_state->setErrorByName('file', t("Must be a .csv file"));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $poles = [];
    $file = $form_state->getValue('file');

    $form_state->unsetValue('file');

    $uri = $file[0]->getFileUri();

    $fhandle = fopen($uri, 'r');
    $result = fread($fhandle, filesize($uri));
    $results = preg_split("/\r\n|\r|\n/", $result);
    $headers = array_shift($results);

    foreach ($results as $value) {
      $pole = [];
      $row = explode(",", $value);

      foreach ($headers as $i => $h) {
        $pole[$h] = $row[$i];
      }

      PoleEntity::create([
        'make' => $pole["Make"],
        'length' => $pole["Length"],
        'weight' => $pole["Weight"],
        'flex' => $pole["Flex"],
        'serial_number' => $pole["Serial Number"],
        'model_number' => $pole["Model Number"],
        'notes' => $pole["Notes"],
      ])->save();
    }

    drupal_set_message(t("Found something! {$results[0]}"), 'status');

    fclose($fhandle);
    $file[0]->delete();
  }
}
