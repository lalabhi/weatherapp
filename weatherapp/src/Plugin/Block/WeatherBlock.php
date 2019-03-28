<?php

namespace Drupal\weatherapp\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\weatherapp\WeatherApi;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;



/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "weather_app_block",
 *   admin_label = @Translation("weather_app_block"),
 * )
 */
class WeatherBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * {@inheritdoc}
   */
  protected $callapi;

  /**
   * WeatherBlock constructor.
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param weatherapi $api
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WeatherApi $api) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->callapi = $api;
  }
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get( 'weatherapp.getapi')
    );
  }
  public function build() {

    if(isset($this->configuration['city'])) {
      $image_field = $this->configuration['image'];
      $image_uri = \Drupal\file\Entity\File::load($image_field[0]);
      $val = $this->callapi->weatherapi($this->configuration['city']);
      $response = Json::decode($val);
      $image_url = $image_uri->uri->value;
      return [
        '#theme' => 'weather_app_block',
        '#title' => $this->configuration['city'],
        '#type' => 'markup',
        '#data' => $response['main'],
        '#description' => $this->configuration['description'],
        '#url' => $image_url,
        '#markup'=>$response['main']
      ];


      
    }
    else{
      return array(
        '#type' => 'markup',
        '#markup' => 'no city',
      );
    }

  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('city'),
    ];
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('description'),
    ];
    $form['upload'] = [
      '#type' => 'managed_file',
      '#name' => 'my_file',
      '#title' => t('picture'),
      '#upload_location' => 'public://my_files/',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['city'] = $form_state->getValue('city');
    $this->configuration['description'] = $form_state->getValue('description');
    $this->configuration['image']= $form_state->getValue('upload');
    $image =  $form_state->getValue('upload');
    $file = \Drupal\file\Entity\File::load( $image[0] );

    /* Set the status flag permanent of the file object */
    $file->setPermanent();

    /* Save the file in database */
    $file->save();

  }
}
