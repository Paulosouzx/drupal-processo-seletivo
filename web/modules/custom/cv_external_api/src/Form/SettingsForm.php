<?php

namespace Drupal\cv_external_api\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase {

  protected function getEditableConfigNames(): array {
    return ['cv_external_api.settings'];
  }

  public function getFormId(): string {
    return 'cv_external_api_settings_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('cv_external_api.settings');

    $form['endpoint_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API Endpoint URL'),
      '#default_value' => $config->get('endpoint_url') ?? 'https://jsonplaceholder.typicode.com/posts',
      '#required' => TRUE,
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->config('cv_external_api.settings')
      ->set('endpoint_url', $form_state->getValue('endpoint_url'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
