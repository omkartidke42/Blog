<?php

namespace Drupal\Omkar_PluginAPI_text_transformer\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Omkar_PluginAPI_text_transformer\TextTransformerManager;

class TextTransformerController extends ControllerBase {

  protected $pluginManager;

  public function __construct(TextTransformerManager $plugin_manager) {
    $this->pluginManager = $plugin_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('text_transformer.manager')
    );
  }

  public function content() {
    $plugin = $this->pluginManager->createInstance('uppercase_transformer');
    $transformed = $plugin->transform("This is a test blog text");

    return [
      '#markup' => $this->t('Transformed text: @text', ['@text' => $transformed]),
    ];
  }
}
