<?php

namespace Drupal\custom_redirects\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Redirect Item entity.
 *
 * @ConfigEntityType(
 *   id = "redirect_item",
 *   label = @Translation("Redirect Item"),
 *   handlers = {
 *     "list_builder" = "Drupal\custom_redirects\RedirectItemListBuilder",
 *     "form" = {
 *       "add" = "Drupal\custom_redirects\Form\RedirectItemForm",
 *       "edit" = "Drupal\custom_redirects\Form\RedirectItemForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "redirect_item",
 *   admin_permission = "administer redirects",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/system/redirects/{redirect_item}",
 *     "add-form" = "/admin/config/system/redirects/add",
 *     "edit-form" = "/admin/config/system/redirects/{redirect_item}/edit",
 *     "delete-form" = "/admin/config/system/redirects/{redirect_item}/delete",
 *     "collection" = "/admin/config/system/redirects"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "source",
 *     "target",
 *     "code",
 *     "enabled",
 *     "host",
 *     "regex",
 *     "language"
 *   }
 * )
 */
class RedirectItem extends ConfigEntityBase {

  /**
   * The Redirect Item ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Redirect Item label.
   *
   * @var string
   */
  protected $label;

  /**
   * The source path or pattern.
   *
   * @var string
   */
  protected $source;

  /**
   * The target URL.
   *
   * @var string
   */
  protected $target;

  /**
   * The redirect code (301, 302, etc.).
   *
   * @var int
   */
  protected $code = 301;

  /**
   * Whether this redirect is enabled.
   *
   * @var bool
   */
  protected $enabled = TRUE;

  /**
   * The host condition.
   *
   * @var string
   */
  protected $host;

  /**
   * Whether source is a regex pattern.
   *
   * @var bool
   */
  protected $regex = FALSE;

  /**
   * The language condition.
   *
   * @var string
   */
  protected $language;

  /**
   * Gets the source path.
   *
   * @return string
   *   The source path.
   */
  public function getSource() {
    return $this->source;
  }

  /**
   * Gets the target URL.
   *
   * @return string
   *   The target URL.
   */
  public function getTarget() {
    return $this->target;
  }

  /**
   * Gets the redirect code.
   *
   * @return int
   *   The redirect code.
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * Gets the enabled status.
   *
   * @return bool
   *   TRUE if enabled, FALSE otherwise.
   */
  public function isEnabled() {
    return $this->enabled;
  }

  /**
   * Gets the host condition.
   *
   * @return string
   *   The host condition.
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * Gets the regex status.
   *
   * @return bool
   *   TRUE if regex, FALSE otherwise.
   */
  public function isRegex() {
    return $this->regex;
  }

  /**
   * Gets the language condition.
   *
   * @return string
   *   The language condition.
   */
  public function getLanguage() {
    return $this->language;
  }

}
