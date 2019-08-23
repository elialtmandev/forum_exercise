<?php

namespace Drupal\forum_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Provides a 'Forum Exercise' Block.
 *
 * @Block(
 *   id = "forum_exercise",
 *   admin_label = @Translation("Forum Exercise"),
 *   category = @Translation("Forum Exercise"),
 *   context = {
 *     "user" = @ContextDefinition("entity:user", label = @Translation("User"))
 *   }
 * )
 */
class ForumExerciseBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a new ForumExerciseBlock.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user) {
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
  public function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->isAuthenticated());
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->current_user->getAccount();
    $user_name = $this->current_user->getDisplayName();
    $user_last_login = date('F jS, Y g:i a', $user->login); 

    $content['hello_message'] = $this->t("Hello ") . $user_name . "!";
    $content['last_login'] = $this->t("Your last login was a") . $user_last_login . '.';
    $content['profile_link'] = Link::fromTextAndUrl($this->t("Visit your profile"), Url::fromRoute('user.page'));

    return [
      '#theme' => 'forum_exercise_block',
      '#content' => $content,
      '#cache' => array(
        'contexts' => array(
          'session'
        )
      ),
    ];
  }
}
