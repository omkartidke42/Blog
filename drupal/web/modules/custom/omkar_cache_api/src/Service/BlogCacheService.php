<?php

namespace Drupal\omkar_cache_api\Service;

use Drupal\node\Entity\Node;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Service to cache and retrieve latest blog posts.
 */
class BlogCacheService {

  protected $cacheBackend;

  /**
   * Constructor to inject the cache service.
   */
  public function __construct(CacheBackendInterface $cache_backend) {
    $this->cacheBackend = $cache_backend;
  }

  /**
   * Fetch latest blog posts from cache or database.
   */
  public function getLatestBlogs($limit = 5) {
    $cid = 'omkar_cache_api:latest_blogs';
    $cache = $this->cacheBackend->get($cid);

    if ($cache) {
      \Drupal::logger('omkar_cache_api')->notice('Cache HIT: Returning cached data.');
      return $cache->data;
    }
    else {
      \Drupal::logger('omkar_cache_api')->notice('Cache MISS: Fetching from database.');

      // Fetch latest blogs from the database.
      $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'blog')
        ->sort('created', 'DESC')
        ->range(0, $limit)
      // ✅ Explicit access check
        ->accessCheck(TRUE);

      $nids = $query->execute();
      $nodes = Node::loadMultiple($nids);

      $blogs = [];
      foreach ($nodes as $node) {
        $blogs[] = [
          'title' => $node->getTitle(),
          'url' => $node->toUrl()->toString(),
        ];
      }

      // ✅ Cache data permanently
      $this->cacheBackend->set($cid, $blogs, CacheBackendInterface::CACHE_PERMANENT, ['node_list']);

      return $blogs;
    }
  }

  /**
   * Clears the cache when a blog post is updated.
   */
  public function clearCache() {
    $this->cacheBackend->invalidate('omkar_cache_api:latest_blogs');
  }

}
