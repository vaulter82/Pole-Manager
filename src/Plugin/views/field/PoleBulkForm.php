<?php

namespace Drupal\pole_manager\Plugin\views\field;

use Drupal\system\Plugin\views\field\BulkForm;

/**
 * @ViewsField("pole_bulk_form")
 */
class PoleBulkForm extends BulkForm {
    protected function emptySelectedMessage() {
        return $this->t('No poles selected.');
    }
    
    public function viewsForm(&$form, FormStateInterface $form_state) {
        $form = parent::viewsForm($form, $form_state);
        
        $form['#attached']['library'][] = 'pole_manager/qr';
    }
}
