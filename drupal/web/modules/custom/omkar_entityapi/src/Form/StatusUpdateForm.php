<?php

declare(strict_types=1);

namespace Drupal\omkar_entityapi\Form;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Provides an Anytown Status form.
 */
class StatusUpdateForm extends FormBase {

  // /**
  //  * The entity type manager.
  //  *
  //  * @var \Drupal\Core\Entity\EntityTypeManagerInterface
  //  */
  // protected EntityTypeManagerInterface $entityTypeManager;

  // /**
  //  * Constructs a new StatusUpdateForm.
  //  *
  //  * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
  //  *   The entity type manager.
  //  */
  // public function __construct(EntityTypeManagerInterface $entity_type_manager) {
  //   $this->entityTypeManager = $entity_type_manager;
  // }

  // /**
  //  * {@inheritdoc}
  //  */
  // public static function create(ContainerInterface $container) {
  //   return new static(
  //     $container->get('entity_type.manager')
  //   );
  // }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'omkar_entityapi_status_update';
  }

  /**
   * Form building callback.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form state.
   * @param int|null $node
   *   ID of the node to edit the status for passed in from the route's {node}
   *   slug.
   *
   * @return array
   *   The form array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function buildForm(array $form, FormStateInterface $form_state,NodeInterface $node = NULL): array {
    // Load the current node.
    // $node = $this->entityTypeManager->getStorage('node')->load($node);

    // Verify that it is a vendor node.
    if ($node->bundle() !== 'vendor') {
      throw new NotFoundHttpException();
    }

    // Save the $node object into the form state, temporary storage, so that we
    // can use it later in the submit handler without having to load it again.
    $form_state->set('node', $node);

    $form['attending'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Attending'),
      '#description' => $this->t('Check this box if you plan to attend this weekends market.'),
    ];

   
    $form['contact_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Contact name'),
      '#required' => TRUE,
      '#default_value' => $node->field_vendor_contact_name->value,
    ];


    $form['contact_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Contact email'),
      '#default_value' => $node->field_vendor_contact_email->value,
      '#required' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Update status'),
      ],
    ];

    return $form;
  }
  
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Get the node object we saved in the buildForm method.
    /** @var \Drupal\node\NodeInterface $node */
    $node = $form_state->get('node');

    // Read the values from our form fields, and use them to update the fields
    // on the vendor node.
    $node->set('field_vendor_attending', $form_state->getValue('attending'));
    $node->set('field_vendor_contact_name', $form_state->getValue('contact_name'));
    $node->set('field_vendor_contact_email', $form_state->getValue('contact_email'));

    try {
      // Persist the changes to the database.
      $node->save();

      // Set a success message and redirect to the node view page.
      $this->messenger()->addStatus($this->t('Thank you for updating your attendance status.'));
      $form_state->setRedirectUrl($node->toUrl());
    }
    catch (EntityStorageException $exception) {
      // Log the error.
      $this->logger('anytown_status')->error($exception->getMessage());
      // And display a message to the user.
      $this->messenger()->addError($this->t('An error occurred while saving. Please try again.'));
    }
  }

}