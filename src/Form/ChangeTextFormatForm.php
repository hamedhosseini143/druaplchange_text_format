<?php

declare(strict_types=1);

namespace Drupal\change_text_format\Form;

use Drupal\change_text_format\ChangeTextFormatServiceInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a change text format form.
 */
final class ChangeTextFormatForm extends FormBase {

  protected ChangeTextFormatServiceInterface $changeTextFormatService;


  /**
   * Constructs a ChangeTextFormatForm object.
   */
  public function __construct(ChangeTextFormatServiceInterface $changeTextFormatService) {
    $this->changeTextFormatService = $changeTextFormatService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self($container->get('change_text_format.change'),);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'change_text_format_change_text_format';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $entityOptions = $this->changeTextFormatService->getEntityOptions();

    $node = $this->changeTextFormatService->getNode([]);
    dump($node);

    $form['entity'] = [
      '#type' => 'select',
      '#title' => $this->t('Entity'),
      '#options' => $entityOptions,
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::updateBundleOptions',
        'wrapper' => 'bundle-options-wrapper',
      ],
    ];
    $form['bundle'] = [
      '#type' => 'select',
      '#title' => $this->t('Bundle'),
      '#options' => [],
      '#required' => TRUE,
      '#prefix' => '<div id="bundle-options-wrapper">',
      '#suffix' => '</div>',
    ];
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }

  private function getEntityOptions() {}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    $form_state->setRedirect('<front>');
  }

  public function updateBundleOptions(array $form, FormStateInterface $form_state): array {
    $entity_type_id = $form_state->getValue('entity');
    $bundleOptions = $this->changeTextFormatService->getBundleOptions($entity_type_id);
    $form['bundle']['#options'] = $bundleOptions;
    return $form['bundle'];
  }

}

