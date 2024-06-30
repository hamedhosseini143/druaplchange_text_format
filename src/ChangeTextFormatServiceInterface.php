<?php

namespace Drupal\change_text_format;

/**
 * Interface ChangeTextFormatServiceInterface.
 *
 * @package Drupal\change_text_format
 */
interface ChangeTextFormatServiceInterface {

  /**
   * @param $data
   *
   * @return array
   */
  public function getNode($data): array;

  /**
   * @param $nodes
   *
   * @return void
   */
  public function changeTextFormat($nodes): void;

  /**
   * @return array
   */
  public function getEntityOptions(): array;

  /**
   * @param $entity_type_id
   *
   * @return array
   */
  public function getBundleOptions($entity_type_id): array;

  /**
   * @param $entity_type_id
   * @param $bundle
   *
   * @return array
   */
  public function getFields($entity_type_id, $bundle) : array;
}
