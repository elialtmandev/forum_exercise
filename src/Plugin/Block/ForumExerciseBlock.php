<?php

namespace Drupal\forum_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxy;

/**
 * Provides a 'Forum Exercise' Block.
 *
 * @Block(
 *   id = "forum_exercise",
 *   admin_label = @Translation("Forum Exercise"),
 *   category = @Translation("Forum Exercise"),
 * )
 */
class ForumExerciseBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new ForumExerciseBlock.
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxy $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->current_user = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
//    $user = \Drupal\user\Entity\User::load($this->current_user->id());
    $user = $this->current_user->getAccount();
    $user_name = $this->current_user->getDisplayName();
    $user_last_login = $user->login;
dsm($user_last_login);

    $markup = "Hello " . $user_name . "!";
    $markup .= "<br>Your last login was " . $user_last_login;
    $markup .= "<br><a href='/user'>Visit your profile</a>";

    return [
      '#markup' => $markup,
    ];
  }
}
