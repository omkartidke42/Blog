<?php

namespace Drupal\Omkar_PluginAPI_text_transformer;

use Drupal\Core\Plugin\DefaultPluginManager;

class TextTransformerManager extends DefaultPluginManager {
  public function __construct(\Traversable $namespaces, \Drupal\Core\Cache\CacheBackendInterface $cache_backend, \Drupal\Core\Extension\ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/TextTransformer',
      $namespaces,
      $module_handler,
      'Drupal\\Omkar_PluginAPI_text_transformer\\TextTransformerInterface',
      'Drupal\\Omkar_PluginAPI_text_transformer\\Annotation\\TextTransformer'
    );

    $this->alterInfo('text_transformer_info');
    $this->setCacheBackend($cache_backend, 'text_transformer_plugins');
  }
}
