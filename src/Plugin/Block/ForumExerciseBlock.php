<?php

namespace Drupal\forum_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Forum Exercise' Block.
 *
 * @Block(
 *   id = "forum_exercise",
 *   admin_label = @Translation("Forum Exercise"),
 *   category = @Translation("Forum Exercise"),
 * )
 */
class ForumExerciseBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

global $user;
dsm($user);
    return [
      '#markup' => $this->t('Hello, World!'),
    ];
  }
}
