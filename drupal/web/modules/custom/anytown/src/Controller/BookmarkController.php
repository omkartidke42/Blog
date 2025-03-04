<?php

namespace Drupal\anytown\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
 * Controller for bookmarking blogs.
 */
class BookmarkController extends ControllerBase {

 
  public function build(string $style): array {

    $build['content'] = [
      '#type' => 'markup',
      '#markup' => '<p>Bookmarked Blogs</p>',
    ];
    if ($style === 'extended') {
      $build['content_extended'] = [
        '#type' => 'markup',
        '#markup' => '<p><strong>Extended forecast:</strong> Looking ahead to next week we expect some snow.</p>',
      ];
    }

    return $build;
  }
}
