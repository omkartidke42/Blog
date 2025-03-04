<?php

namespace Drupal\blog_interactions\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BlogInteractionsController extends ControllerBase {

  public function like($nid) {
    $database = \Drupal::database();

    // Increment likes in database
    $database->merge('blog_interactions')
      ->key('nid', $nid)
      ->fields(['likes' => 1])
      ->expression('likes', 'likes + 1')
      ->execute();

    // Get updated count
    $query = $database->select('blog_interactions', 'b')
      ->fields('b', ['likes'])
      ->condition('nid', $nid)
      ->execute();
    $likes = $query->fetchField();

    return new JsonResponse(['status' => 'success', 'new_likes_count' => $likes]);
  }

  public function view($nid) {
    $database = \Drupal::database();

    // Increment views in database
    $database->merge('blog_interactions')
      ->key('nid', $nid)
      ->fields(['views' => 1])
      ->expression('views', 'views + 1')
      ->execute();

    // Get updated count
    $query = $database->select('blog_interactions', 'b')
      ->fields('b', ['views'])
      ->condition('nid', $nid)
      ->execute();
    $views = $query->fetchField();

    return new JsonResponse(['status' => 'success', 'new_views_count' => $views]);
  }
}
