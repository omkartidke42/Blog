<?php

namespace Drupal\blog_interactions\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlogInteractionController extends ControllerBase {
  
  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Increment Views Count.
   */
  public function incrementViews($nid) {
    $node = Node::load($nid);
    if (!$node) {
      return new JsonResponse(['error' => 'Blog not found'], 404);
    }

    // Get current views
    $views = $node->get('field_views')->value ?? 0;
    $views++;

    // Update views count
    $node->set('field_views', $views);
    $node->save();

    return new JsonResponse(['views' => $views]);
  }

  /**
   * Add Like to Blog.
   */
  public function addLike($nid, Request $request) {
    $node = Node::load($nid);
    if (!$node) {
      return new JsonResponse(['error' => 'Blog not found'], 404);
    }

    // Get current likes
    $likes = $node->get('field_likes')->value ?? 0;
    $likes++;

    // Update likes count
    $node->set('field_likes', $likes);
    $node->save();

    return new JsonResponse(['likes' => $likes]);
  }
}
