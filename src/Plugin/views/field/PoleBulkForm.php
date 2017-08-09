<?php

namespace Drupal\pole_manager\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * @ViewsField("pole_bulk_form")
 */
class PoleBulkForm extends BulkForm {
    protected function emptySelectedMessage() {
        return $this->t('No poles selected.');
    }
    
    public function viewsForm(&$form, FormStateInterface $form_state) {
        parent::viewsForm($form, $form_state);

        $form['#attached']['library'][] = 'pole_manager/qr';
    }
}
