<?php

namespace Drupal\pb_custom_form\EventSubscriber;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\path_alias\AliasManagerInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Event Subscriber for node view redirect.
 */
class InternalContentNodeRedirect implements EventSubscriberInterface {

  /**
   * CurrentRouteMatch var.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatch;

  /**
   * LanguageManager var.
   *
   * @var \Drupal\Core\Language\LanguageManager
   */
  protected $languageManager;

  /**
   * AccountProxy var.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The current path service.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  protected $pathCurrent;

  /**
   * The path alias manager service.
   *
   * @var \Drupal\path_alias\AliasManagerInterface
   */
  protected $pathAliasManager;

  /**
   * The page cache kill switch service.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $pageCacheKillSwitch;

  /**
   * Construct method.
   *
   * @inheritDoc
   */
  public function __construct(
    CurrentRouteMatch $route_match,
    LanguageManager $language_manager,
    AccountProxy $current_user,
    EntityTypeManagerInterface $entity_type_manager,
    CurrentPathStack $path_current,
    AliasManagerInterface $path_alias_manager,
    KillSwitch $page_cache_kill_switch,
  ) {
    $this->routeMatch = $route_match;
    $this->languageManager = $language_manager;
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->pathCurrent = $path_current;
    $this->pathAliasManager = $path_alias_manager;
    $this->pageCacheKillSwitch = $page_cache_kill_switch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new self(
      $container->get('current_route_match'),
      $container->get('language_manager'),
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('path.current'),
      $container->get('path_alias.manager'),
      $container->get('page_cache_kill_switch')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['nodeViewRedirect'];
    return $events;
  }

  /**
   * {@inheritdoc}
   *
   * MIGRATION COMPLETE: All node redirects have been migrated to .htaccess
   * for better performance and SEO. This method is now disabled.
   */
  public function nodeViewRedirect(RequestEvent $event) {
    // All redirect logic has been migrated to .htaccess configuration
    // managed through the custom_redirects module for better performance.
  }

  /**
   * Check if current route is a node route.
   *
   * @return bool
   *   TRUE if node entity route, FALSE otherwise.
   */
  protected function isNodeRoute() {
    return strpos($this->routeMatch->getRouteName(), 'entity.node.canonical') === 0;
  }

}
