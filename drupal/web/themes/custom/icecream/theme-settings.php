<?php

/**
 * Implements hook_preprocess_HOOK() for page templates.
 */
// function icecream_preprocess_page(array &$variables) {
//     // Attach the CSS file dynamically.
//     $variables['#attached']['library'][] = 'icecream/style';
//   }

  function icecream_form_system_theme_settings_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {
    // Add a text field
    $form['icecream_custom_text'] = [
        '#type' => 'textfield',
        '#title' => t('Custom Text'),
        '#default_value' => theme_get_setting('mytheme_custom_text'),
    ];

    // Add a color field
    $form['icecream_custom_color'] = [
        '#type' => 'color',
        '#title' => t('Custom Color'),
        '#default_value' => theme_get_setting('mytheme_custom_color'),
    ];

    // Add an image upload field
    $form['icecream_custom_logo'] = [
        '#type' => 'managed_file',
        '#title' => t('Custom Logo'),
        '#upload_location' => 'public://theme_images/',
        '#default_value' => theme_get_setting('mytheme_custom_logo'),
        '#description' => t('Upload a custom logo for your theme.'),
    ];

    // Add a checkbox field
    $form['icecream_enable_featured_posts'] = [
        '#type' => 'checkbox',
        '#title' => t('Enable Featured Posts'),
        '#default_value' => theme_get_setting('mytheme_enable_featured_posts'),
    ];
}

  
