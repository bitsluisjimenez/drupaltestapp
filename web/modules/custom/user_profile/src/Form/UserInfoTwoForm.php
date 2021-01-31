<?php

namespace Drupal\user_profile\Form;

use Drupal\user_profile\Form\MultistepFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Url;

/**
 * Class UserInfoTwoForm.
 */
class UserInfoTwoForm extends MultistepFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_info_two_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    try {
      $curl = curl_init();
      curl_setopt_array($curl, [
        CURLOPT_URL => "https://wft-geo-db.p.rapidapi.com/v1/geo/cities",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
          "x-rapidapi-host: wft-geo-db.p.rapidapi.com",
          "x-rapidapi-key: 29b393ab42msh8c04b982ce13ab7p1ece05jsn4bebfb1137ae"
        ],
      ]);

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        \Drupal::logger('zemoga1')->error(print_r($err, TRUE));
        $cities = [
          'Popayán' => $this->t('Popayán'),
          'Bogotá' => $this->t('Bogotá'),
        ];
      }
      else {
        $responseObj = json_decode($response); 
        $responseData = $responseObj->data;
        foreach ($responseData as $key => $value) {
          if ($value->type == 'CITY') {
            $cities[$value->city] = $value->city;
          }
        }
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('zemoga2')->error(print_r($e, TRUE));
      $cities = [
        'Popayán' => $this->t('Popayán'),
        'Bogotá' => $this->t('Bogotá'),
      ];
    }
    
    $form['city'] = [
      '#type' => 'select',
      '#title' => $this->t('City'),
      '#description' => $this->t('User city'),
      '#default_value' => $this->store->get('city') ?? '',
      '#options' => $cities,
      '#size' => 10,
      '#required' => TRUE,
      '#weight' => '0',
    ];
    $form['phone_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Phone number'),
      '#description' => $this->t('User phone number'),
      '#default_value' => $this->store->get('phone_number') ?? '',
      '#weight' => '0',
    ];
    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
      '#description' => $this->t('User Address'),
      '#default_value' => $this->store->get('address') ?? '',
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
    ];
    $form['actions']['previous'] = [
      '#type' => 'button',
      '#value' => $this->t('Previous'),
      '#ajax' => [
        'callback' => '::storeInfoTwoCallback', // don't forget :: when calling a class method.
        'disable-refocus' => TRUE, // Or TRUE to prevent re-focusing on the triggering element.
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Verifying entry...'),
        ],
      ],
    ];

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
    // Set extra user info in private store.
    $this->store->set('city', $form_state->getValue('city'));
    $this->store->set('phone_number', $form_state->getValue('phone_number'));
    $this->store->set('address', $form_state->getValue('address'));

    // Save user info in DB and clean the user store data.
    parent::saveData();
    $form_state->setRedirect('user_profile.user_info_one_form');
  }

  // Add form two values to private store.
  public function storeInfoTwoCallback(array &$form, FormStateInterface $form_state) {
    // Set extra user info in private store.
    $this->store->set('city', $form_state->getValue('city'));
    $this->store->set('phone_number', $form_state->getValue('phone_number'));
    $this->store->set('address', $form_state->getValue('address'));
    // Redirect to step one.
    $response = new AjaxResponse();
    $url = Url::fromRoute('user_profile.user_info_one_form');
    $command = new RedirectCommand($url->toString());
    $response->addCommand($command);
    return $response;
  }

}
