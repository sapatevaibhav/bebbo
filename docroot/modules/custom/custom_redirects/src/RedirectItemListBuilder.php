<?php

namespace Drupal\custom_redirects;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Redirect Item entities.
 */
class RedirectItemListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Label');
    $header['source'] = $this->t('Source');
    $header['target'] = $this->t('Target');
    $header['code'] = $this->t('Code');
    $header['host'] = $this->t('Host');
    $header['enabled'] = $this->t('Enabled');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['source'] = $entity->getSource();
    $row['target'] = $entity->getTarget();
    $row['code'] = $entity->getCode();
    $row['host'] = $entity->getHost() ?: $this->t('Any');
    $row['enabled'] = $entity->isEnabled() ? $this->t('Yes') : $this->t('No');
    return $row + parent::buildRow($entity);
  }

}
