<?php

namespace Drupal\omkar_formapi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Egulias\EmailValidator\EmailValidator;

/**
 * Provides a config form with AJAX functionality.
 */
final class OmkarForm extends ConfigFormBase {

  /**
   * Config settings key.
   */
  const SETTINGS = 'omkar_formapi.settings';

  /**
   * The email validator service.
   *
   * @var \Egulias\EmailValidator\EmailValidator
   */
  protected EmailValidator $emailValidator;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new OmkarForm object.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typed_config,
    EmailValidator $email_validator,
    MessengerInterface $messenger
  ) {
    parent::__construct($config_factory, $typed_config);
    $this->emailValidator = $email_validator;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('email.validator'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'omkar_formapi_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [self::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config(self::SETTINGS);

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your Name'),
      '#default_value' => $config->get('name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Your Email'),
      '#default_value' => $config->get('email'),
      '#required' => TRUE,
    ];

    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#default_value' => $config->get('message'),
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::updateMessagePreview',
        'event' => 'keyup',
        'wrapper' => 'message-preview-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => '',
        ],
      ],
    ];

    // Live preview container.
    $form['message_preview'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'message-preview-wrapper'],
      'preview_text' => [
        '#markup' => '<strong>' . $this->t('Live Preview:') . '</strong><br>' .
          nl2br(htmlspecialchars($form_state->getValue('message') ?? '')),
      ],
    ];

    // AJAX submit button.
    $form['submit_ajax'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit via AJAX'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'form-output-wrapper',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Processing...'),
        ],
      ],
      '#submit' => ['::ajaxFormSubmitHandler'],
    ];

    // Output container for submitted data.
    $form['form_output'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'form-output-wrapper'],
    ];

    // Display stored data if available after AJAX submit.
    if ($form_state->getStorage()) {
      $stored_data = $form_state->getStorage();
      $form['form_output']['result'] = [
        '#markup' => '<div class="form-result">' .
          '<p><strong>' . $this->t('Name') . ':</strong> ' . htmlspecialchars($stored_data['name']) . '</p>' .
          '<p><strong>' . $this->t('Email') . ':</strong> ' . htmlspecialchars($stored_data['email']) . '</p>' .
          '<p><strong>' . $this->t('Message') . ':</strong><br>' . nl2br(htmlspecialchars($stored_data['message'])) . '</p>' .
          '</div>',
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * AJAX callback to update live preview.
   */
  public function updateMessagePreview(array &$form, FormStateInterface $form_state): array {
    return $form['message_preview'];
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);

    if (!$this->emailValidator->isValid($form_state->getValue('email'))) {
      $form_state->setErrorByName('email', $this->t('Please enter a valid email address.'));
    }
  }

  /**
   * AJAX form submit handler.
   */
  public function ajaxFormSubmitHandler(array &$form, FormStateInterface $form_state): void {
    // Store submitted values into form_state internal storage.
    $form_state->setStorage([
      'name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'message' => $form_state->getValue('message'),
    ]);

    // Rebuild the form to display submitted data.
    $form_state->setRebuild(TRUE);
  }

  /**
   * AJAX callback to return output container only.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state): array {
    return $form['form_output'];
  }

}
