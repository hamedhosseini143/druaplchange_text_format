<?php

declare(strict_types=1);

namespace Drupal\change_text_format\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for change text format routes.
 */
final class ChangeTextFormatController extends ControllerBase {

  /**
   * The controller constructor.
   */
  public function __construct() {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    $form = $this->formBuilder()
      ->getForm('Drupal\change_text_format\Form\ChangeTextFormatForm');

    return [
      'form' => $form,
    ];
  }

}
