<?php

namespace Drupal\omkar_cache_api\Controller;

use Drupal\Core\Url;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\omkar_cache_api\Service\BlogCacheService;

/**
 * Controller to display latest cached blog posts.
 */
class BlogController extends ControllerBase {

  /**
   *
   */
  protected $blogCacheService;

  public function __construct(BlogCacheService $blogCacheService) {
    $this->blogCacheService = $blogCacheService;
  }

  /**
   *
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('omkar_cache_api.blog_cache_service')
    );
  }

  /**
   * Returns the latest blog posts.
   */
  public function latestBlogs() {
    $blogs = $this->blogCacheService->getLatestBlogs();

    // Debugging: Check if $blogs contains data.
    \Drupal::logger('omkar_cache_api')->notice('<pre>' . print_r($blogs, TRUE) . '</pre>');

    if (empty($blogs)) {
      return [
        '#markup' => '<p>No blogs available.</p>',
      ];
    }

    $items = [];
    foreach ($blogs as $blog) {
      $items[] = [
        '#type' => 'link',
        '#title' => $blog['title'],
        '#url' => Url::fromUserInput($blog['url']),
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
      '#cache' => [
        'tags' => ['node_list'],
      ],
    ];
  }

}
