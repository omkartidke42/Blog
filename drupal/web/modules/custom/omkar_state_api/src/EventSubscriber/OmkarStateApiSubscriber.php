<?php

namespace Drupal\omkar_state_api\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\omkar_state_api\Event\BlogPublishedEvent;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Messenger\MessengerInterface;

/**
 * Event Subscriber for Omkar State API module.
 */
class OmkarStateApiSubscriber implements EventSubscriberInterface {

  protected StateInterface $state;
  protected MessengerInterface $messenger;

  public function __construct(StateInterface $state, MessengerInterface $messenger) {
    $this->state = $state;
    $this->messenger = $messenger;
  }

  public static function getSubscribedEvents() {
    return [
      BlogPublishedEvent::EVENT_NAME => ['onBlogPublished'],
    ];
  }

  public function onBlogPublished(BlogPublishedEvent $event) {
    $node = $event->getNode();
    $this->state->set('omkar_state_api.last_published_time', \Drupal::time()->getCurrentTime());
    $this->messenger->addMessage(t('Last published blog time updated.'));
  }
}