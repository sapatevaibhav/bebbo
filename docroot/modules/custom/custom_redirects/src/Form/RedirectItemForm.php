<?php

namespace Drupal\custom_redirects\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * The RedirectItemForm class.
 */
class RedirectItemForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $redirect_item = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $redirect_item->label(),
      '#description' => $this->t("Label for the Redirect Item."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $redirect_item->id(),
      '#machine_name' => [
        'exists' => '\Drupal\custom_redirects\Entity\RedirectItem::load',
      ],
      '#disabled' => !$redirect_item->isNew(),
    ];

    $form['source'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source Path'),
      '#maxlength' => 255,
      '#default_value' => $redirect_item->getSource(),
      '#description' => $this->t('The source path to redirect from (e.g., /old-path).'),
      '#required' => TRUE,
    ];

    $form['target'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Target URL'),
      '#maxlength' => 255,
      '#default_value' => $redirect_item->getTarget(),
      '#description' => $this->t('The target URL to redirect to (e.g., /new-path or https://example.com).'),
      '#required' => TRUE,
    ];

    $form['code'] = [
      '#type' => 'select',
      '#title' => $this->t('Redirect Code'),
      '#default_value' => $redirect_item->getCode(),
      '#options' => [
        301 => $this->t('301 - Permanent'),
        302 => $this->t('302 - Temporary'),
      ],
      '#description' => $this->t('The HTTP redirect status code.'),
      '#required' => TRUE,
    ];

    $form['host'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Host Condition'),
      '#maxlength' => 255,
      '#default_value' => $redirect_item->getHost(),
      '#description' => $this->t('Optional: Restrict to specific host (e.g., example.ddev.site). Leave empty for all hosts.'),
    ];

    $form['regex'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use Regular Expression'),
      '#default_value' => $redirect_item->isRegex(),
      '#description' => $this->t('Check if the source path is a regular expression pattern.'),
    ];

    $form['language'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Language Condition'),
      '#maxlength' => 10,
      '#default_value' => $redirect_item->getLanguage(),
      '#description' => $this->t('Optional: Language code (e.g., en, fr). Leave empty for all languages.'),
    ];

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $redirect_item->isEnabled(),
      '#description' => $this->t('Whether this redirect is active.'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $redirect_item = $this->entity;
    $status = $redirect_item->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Redirect Item.', [
          '%label' => $redirect_item->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Redirect Item.', [
          '%label' => $redirect_item->label(),
        ]));
    }
    $form_state->setRedirectUrl($redirect_item->toUrl('collection'));
  }

}
