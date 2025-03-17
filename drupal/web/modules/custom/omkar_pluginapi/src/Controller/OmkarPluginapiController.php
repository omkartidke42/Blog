<?php

namespace Drupal\omkar_pluginapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\omkar_pluginapi\OmkarPluginapiPluginManager;

class OmkarPluginapiController extends ControllerBase {

  protected $pluginManager;

  public function __construct(OmkarPluginapiPluginManager $pluginManager) {
    $this->pluginManager = $pluginManager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.omkar_pluginapi')
    );
  }

  public function showPlugins() {
    $build = [];

    $definitions = $this->pluginManager->getDefinitions();

    foreach ($definitions as $id => $plugin_def) {
      $plugin = $this->pluginManager->createInstance($id);

      $build[] = [
        '#markup' => $this->t('<h3>@label</h3><p>@desc</p><p>Calories: @cal</p>', [
          '@label' => $plugin->label(),
          '@desc' => $plugin->description(),
          '@cal' => $plugin->calories(),
        ]),
      ];
    }

    return $build;
  }
}
