<?php

namespace Drupal\custom_redirects\Commands;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\File\FileSystemInterface;
use Drush\Commands\DrushCommands;

/**
 * Drush commands for exporting redirects to .htaccess.
 */
class ExportRedirects extends DrushCommands {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * Constructs a new ExportRedirects object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   The file system service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, FileSystemInterface $file_system) {
    $this->entityTypeManager = $entity_type_manager;
    $this->fileSystem = $file_system;
  }

  /**
   * Export redirects to .htaccess file.
   *
   * @command custom-redirects:export
   * @aliases cre
   * @usage custom-redirects:export
   *   Export all enabled redirects to .htaccess
   */
  public function exportRedirects() {
    $storage = $this->entityTypeManager->getStorage('redirect_item');
    $redirects = $storage->loadMultiple();

    $htaccess_rules = [];
    $htaccess_rules[] = '# ---- BEGIN MIGRATED REDIRECTS (automatically generated) ----';
    $htaccess_rules[] = 'RewriteEngine On';

    foreach ($redirects as $redirect) {
      if (!$redirect->isEnabled()) {
        continue;
      }

      $source = $redirect->getSource();
      $target = $redirect->getTarget();
      $code = $redirect->getCode();
      $host = $redirect->getHost();
      $regex = $redirect->isRegex();

      // Add host condition if specified.
      if ($host) {
        $htaccess_rules[] = "RewriteCond %{HTTP_HOST} ^" . preg_quote($host, '/') . "$ [NC]";
      }

      // Build the rewrite rule.
      if ($regex) {
        $rule = "RewriteRule {$source} {$target} [R={$code},L]";
      }
      else {
        $escaped_source = preg_quote($source, '/');
        $rule = "RewriteRule ^{$escaped_source}/?$ {$target} [R={$code},L]";
      }

      $htaccess_rules[] = $rule;
    }

    $htaccess_rules[] = '# ---- END MIGRATED REDIRECTS ----';

    // Read current .htaccess.
    $htaccess_path = DRUPAL_ROOT . '/.htaccess';
    $htaccess_content = file_get_contents($htaccess_path);

    // Remove existing redirect block.
    $pattern = '/# ---- BEGIN MIGRATED REDIRECTS.*?# ---- END MIGRATED REDIRECTS ----\n?/s';
    $htaccess_content = preg_replace($pattern, '', $htaccess_content);

    // Find the position to insert redirects (before the main rewrite block)
    $insert_position = strpos($htaccess_content, '<IfModule mod_rewrite.c>');
    if ($insert_position !== FALSE) {
      $insert_position = strpos($htaccess_content, 'RewriteEngine on', $insert_position);
      if ($insert_position !== FALSE) {
        $insert_position = strpos($htaccess_content, "\n", $insert_position) + 1;
      }
    }

    if ($insert_position === FALSE) {
      $this->logger()->error('Could not find insertion point in .htaccess file');
      return;
    }

    // Insert the new redirect rules.
    $new_rules = "\n" . implode("\n", $htaccess_rules) . "\n\n";
    $new_htaccess = substr_replace($htaccess_content, $new_rules, $insert_position, 0);

    // Write the updated .htaccess.
    if (file_put_contents($htaccess_path, $new_htaccess)) {
      $this->logger()->success('Successfully exported ' . count($redirects) . ' redirects to .htaccess');
    }
    else {
      $this->logger()->error('Failed to write .htaccess file');
    }
  }

}
