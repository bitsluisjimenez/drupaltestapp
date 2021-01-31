<?php

namespace Drupal\user_profile\Form;

use Drupal\user_profile\Form\MultistepFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class UserInfoOneForm.
 */
class UserInfoOneForm extends MultistepFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_info_one_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#description' => $this->t('User first name'),
      '#default_value' => $this->store->get('first_name') ?? '',
      '#maxlength' => 64,
      '#size' => 64,
      '#required' => TRUE,
      '#weight' => '0',
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#description' => $this->t('User last name'),
      '#default_value' => $this->store->get('last_name') ?? '',
      '#maxlength' => 64,
      '#size' => 64,
      '#required' => TRUE,
      '#weight' => '0',
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#default_value' => $this->store->get('gender') ?? 1,
      '#options' => array(
        1 => $this
          ->t('Male'),
        2 => $this
          ->t('Female'),
      ),
      '#required' => TRUE,
      '#weight' => '0',
    ];
    $form['date_of_birth'] = [
      '#type' => 'date',
      '#title' => $this->t('Date of birth'),
      '#description' => $this->t('User date of birth'),
      '#default_value' => $this->store->get('date_of_birth') ?? '',
      '#required' => TRUE,
      '#weight' => '0',
    ];

    $form['actions']['submit']['#value'] = $this->t('Next');

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Set all user info in private store.
    $this->store->set('first_name', $form_state->getValue('first_name'));
    $this->store->set('last_name', $form_state->getValue('last_name'));
    $this->store->set('gender', $form_state->getValue('gender'));
    $this->store->set('date_of_birth', $form_state->getValue('date_of_birth'));

    // Redirect to second step.
    $form_state->setRedirect('user_profile.user_info_two_form');
  }

}
