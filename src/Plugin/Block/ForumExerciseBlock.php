<?php

namespace Drupal\forum_exercise\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;

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
   * The current_user service.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $current_user;

  /**
   * The form_builder service.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * {@inheritdoc}
   */

  public function defaultConfiguration() {
    return [
      'forum_exercise_admin_message' => '',
    ];
  }

  /**
   * Constructs a new ForumExerciseBlock.
   *
   * @param array $configuration
   *   An array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $current_user
   *   The current_user service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $current_user, FormBuilderInterface $form_builder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->current_user = $current_user;
    $this->formBuilder = $form_builder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('form_builder')
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
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $form['forum_exercise_admin_message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Forum Admin Message'),
      '#default_value' => $this->configuration['forum_exercise_admin_message'],
    ];
dsm($form, "blockForm form");
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
dsm($values, "blockSubmit values");
    $this->configuration['forum_exercise_admin_message'] = $values['forum_exercise_admin_message'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Get the current account from AccountInterface so that we can
    // access 'login' timestamp.
    $user = $this->current_user->getAccount();
    $user_name = $this->current_user->getDisplayName();
    $user_last_login = date('F jS, Y g:i a', $user->login); 

    $config = $this->getConfiguration();
    $admin_message = $config['forum_exercise_admin_message'];

    $content['hello_message'] = $this->t("Hello ") . $user_name . "!";
    $content['last_login'] = $this->t("Your last login was ") . $user_last_login . '.';
    $content['profile_link'] = Link::fromTextAndUrl($this->t("Visit your profile"), Url::fromRoute('user.page'));
    $content['admin_message'] = $admin_message;

dsm($content, "content");

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
