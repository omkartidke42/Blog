<?php

namespace Drupal\omkar_state_api\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block to display last blog published time.
 *
 * @Block(
 *   id = "last_blog_published_block",
 *   admin_label = @Translation("Last Blog Published Time")
 * )
 */
class LastBlogPublishedBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected StateInterface $state;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, StateInterface $state) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->state = $state;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('state')
    );
  }

  public function build() {
    $last_published = $this->state->get('omkar_state_api.last_published_time', 0);
    return [
      '#markup' => $last_published ? date('Y-m-d H:i:s', $last_published) : 'No blogs published yet.',
    ];
  }
}