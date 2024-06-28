<?php

namespace Drupal\teste\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\webform_manager\FileManager\FileManagerInterface;

/**
 * Provides an 'ExcelUploadBlock' block.
 *
 * @Block(
 *   id = "excel_upload_block",
 *   admin_label = @Translation("Excel Upload Block"),
 * )
 */
class ExcelUploadBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The file manager.
   *
   * @var \Drupal\webform_manager\FileManager\FileManagerInterface
   */
  protected $fileManager;

  /**
   * Constructs a new ExcelUploadBlock object.
   *
   * @param array $configuration
   *   A configuration array containing configuration settings.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\webform_manager\FileManager\FileManagerInterface $file_manager
   *   The file manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder, FileManagerInterface $file_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->fileManager = $file_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('webform_manager.file_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = $this->formBuilder->getForm('Drupal\teste\Form\ExcelUploadForm');
    return $form;
  }

}
