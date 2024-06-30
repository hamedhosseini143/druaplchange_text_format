<?php

declare(strict_types=1);

namespace Drupal\change_text_format;

use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * @todo Add class description.
 */
final class ChangeTextFormatService implements ChangeTextFormatServiceInterface {

  /**
   * The entity type bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected EntityTypeBundleInfoInterface $bundleInfo;

  protected $fieldManager;

  private readonly EntityTypeManagerInterface $entityTypeManager;

  /**
   * Constructs a ChangeTextFormat object.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, EntityTypeBundleInfoInterface $bundle_info, EntityFieldManagerInterface $field_manager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->bundleInfo = $bundle_info;
    $this->fieldManager = $field_manager;
  }

  /**
   * @param $data
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getNode($data): array {
    $node_storage = $this->entityTypeManager->getStorage('node');
    $nodes = $node_storage->loadByProperties([
      'status' => 1,
      'body.format' => 'restricted_html',
    ]);
    $node_options = [];
    foreach ($nodes as $node) {
      $node_options[$node->id()] = $node->getTitle();
    }
    return $node_options;
  }

  /**
   * @param $nodes
   *
   * @return void
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function changeTextFormat($nodes): void {
    $nodes = $this->entityTypeManager->getStorage('node')->load($nodes);
    foreach ($nodes as $node) {
      $node->body->format = 'full_html';
      $node->save();
    }

  }

  public function getEntityOptions(): array {
    $entity_types = $this->entityTypeManager->getDefinitions();
    $entity_type_names = [];

    foreach ($entity_types as $entity_type_id => $entity_type) {
      $entity_type_names[$entity_type_id] = $entity_type_id;
    }
    return $entity_type_names;
  }

  public function getBundleOptions($entity_type_id): array {
    $bundles = $this->bundleInfo->getBundleInfo($entity_type_id);
    $bundle_options = [];
    foreach ($bundles as $bundle => $bundle_info) {
      $bundle_options[$bundle] = $bundle;
    }
    return $bundle_options;
  }

  public function getFields($entity_type_id, $bundle): array {
    $fields = $this->fieldManager->getFieldDefinitions($entity_type_id, $bundle);
    return array_keys($fields);
  }

}
