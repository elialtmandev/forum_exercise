<?php

namespace Drupal\forum_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
//use Drupal\Core\Session\AccountProxy;
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
  // prevent block from being initialized for anonymous users (resulting in error)
  public function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->isAuthenticated());
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
//    $user = \Drupal\user\Entity\User::load($this->current_user->id());
    $user = $this->current_user->getAccount();
    $user_name = $this->current_user->getDisplayName();
dsm(drupal_get_user_timezone(), "user tz");
//    $user_last_login_prep = new DateTime($user->login, $user_timezone);
    $user_last_login = date('F jS, Y g:ia', $user->login); // : date('F jS, Y g:ia', \Drupal::time()->getCurrentTime()); //$user->login;
dsm($user_last_login);

    $markup[] = "Hello " . $user_name . "!";
    $markup[] = "Your last login was " . $user_last_login;
    $markup[] = "<a href='/user'>Visit your profile</a>";

    $markup = implode($markup, "<br>");
dsm($markup, "markup");

dsm(Link::fromTextAndUrl("Visit your profile", Url::fromUserInput('/user')), "buildlink");

    $content['foo'] = 'bar';

    return [
      '#markup' => $markup,
      '#theme' => 'forum_exercise_block',
      '#content' => $content,      
      '#cache' => array(
        'contexts' => array(
          'session'
        )
      ),
//      '#cache' => array('max-age' => 0),
    ];
  }
}
