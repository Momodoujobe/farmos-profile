<?php

namespace Drupal\farm_weather\Plugin\QuickForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\farm_quick\Plugin\QuickForm\QuickFormBase;
use Drupal\farm_quick\Traits\QuickLogTrait;

/**
 * Weather quick form.
 *
 * @QuickForm(
 *   id = "weather",
 *   label = @Translation("Weather"),
 *   description = @Translation("Records the weather of the given time."),
 *   helpText = @Translation("Use this form to record the weather."),
 *   permissions = {
 *     "create weather log",
 *   }
 * )
 */
class Weather extends QuickFormBase {

  use QuickLogTrait;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {

    // Weather timestamp.
    $form['date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Time'),
      '#description' => $this->t("The date and time is required to get a accurate temperature"),
      '#required' => TRUE,
    ];

    // Weather name.
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t("The name is required to get a accurate temperature"),
      '#required' => TRUE,
    ];

    // Weather quantity.
    $form['quantity'] = [
      '#type' => 'number',
      '#title' => $this->t('Quantity'),
      '#description' => $this->t("The quantity is optional"),
      '#min' => 0,
      '#step' => 1,
    ];

    // Weather location.
    $form['location'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Location'),
      '#target_type' => 'asset',
      '#selection_handler' => 'views',
      '#selection_settings' => [
        'view' => [
          'view_name' => 'farm_location_reference',
          'display_name' => 'entity_reference',
          'arguments' => [],
        ],
        'match_operator' => 'CONTAINS',
      ]
    ];

    // Weather notes.
    $form['notes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Notes'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Create observation log from the user-submitted data.
    $date = $form_state->getValue('date');
    $name = $form_state->getValue('name');
    $quantity = $form_state->getValue('quantity');
    $location = $form_state->getValue('location');
    $notes = $form_state->getValue('notes');

    $log = [
      'timestamp' => $date->getTimestamp(),
      'name' => $this->$name,
      'type' => 'observation',
      'quantity' => [
        [
          'measure' => 'count',
          'value' => $quantity,
        ],
      ],
      'location' => $location ?? NULL,
      'notes' => [
        'value' => $this->$notes,
        'format' => 'default',
      ],
    ];

    $this->createLog($log);
  }

}
