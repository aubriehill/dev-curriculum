<?php

namespace Drupal\dev_aubrie\Form;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SalesAssociatesForm.
 */
class SalesAssociatesForm extends FormBase {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   */
//  protected $entityTypeManager;

//  public function __construct(EntityTypeManager $entityTypeManager) {
//    $this->entityTypeManager = $entityTypeManager;
//  }

  /**
   * {@inheritdoc}
   */
//  public static function create(ContainerInterface $container) {
//    return $container->get('entity_type.manager');
//  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sales_associates_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
//    $entityStorage = $this->entityTypeManager->getStorage('node');
    $entityStorage = \Drupal::entityTypeManager()->getStorage('node');
    $entity_ids = $entityStorage->getQuery()
      ->condition('status', 1)
      ->condition('type', 'sales_associate')
      ->sort('field_last_name', 'ASC')
      ->execute();

    $nodes = $entityStorage->loadMultiple($entity_ids);
    $options = [
      '' => $this->t('- Select Sales Associate -'),
    ];
    foreach ($nodes as $node) {
      $options[$node->id()] = $node->getTitle();
    }

    $form['select_person'] = [
      '#type' => 'select',
      '#title' => $this->t('Sales Associates'),
      '#options' => $options,
      '#weight' => '0',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
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
    $person = $form_state->getValue('select_person');
    $form_state->setRedirect('<front>', ['sales_code' => $person]);
  }

}
