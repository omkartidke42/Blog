<?php

namespace Drupal\Omkar_PluginAPI_text_transformer\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a TextTransformer annotation object.
 *
 * @Annotation
 */
class TextTransformer extends Plugin {
  public $id;
  public $label;
}
