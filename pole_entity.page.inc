<?php

/**
 * @file
 * Contains pole_entity.page.inc.
 *
 * Page callback for Pole entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Pole entity templates.
 *
 * Default template: pole_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_pole_entity(array &$variables) {
  // Fetch PoleEntity Entity Object.
  $pole_entity = $variables['elements']['#pole_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
