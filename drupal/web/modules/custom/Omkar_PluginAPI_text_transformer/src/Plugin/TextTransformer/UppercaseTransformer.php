<?php

namespace Drupal\Omkar_PluginAPI_text_transformer\Plugin\TextTransformer;

use Drupal\Omkar_PluginAPI_text_transformer\TextTransformerBase;
use Drupal\Omkar_PluginAPI_text_transformer\Annotation\TextTransformer;

/**
 * Provides an Uppercase Text Transformer plugin.
 *
 * @TextTransformer(
 *   id = "uppercase_transformer",
 *   label = @Translation("Uppercase Transformer")
 * )
 */
class UppercaseTransformer extends TextTransformerBase {
  public function transform($text) {
    return strtoupper($text);
  }
}
