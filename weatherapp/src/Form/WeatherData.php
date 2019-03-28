<?php

namespace Drupal\weatherapp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class WeatherData extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'weatherapp_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'weatherapp.settings'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('weatherapp.settings');
    $form['AppID'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App Id'),
      '#default_value' => $config->get('AppID'),
      '#required' => TRUE,

    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('weatherapp.settings')
      ->set('AppID', $form_state->getValue('AppID'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
