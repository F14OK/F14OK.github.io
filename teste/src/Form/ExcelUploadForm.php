<?php

namespace Drupal\teste\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\File\FileSystemInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Messenger\MessengerInterface;

class ExcelUploadForm extends FormBase {

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new ExcelUploadForm object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'excel_upload_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['file_upload'] = [
      '#type' => 'file',
      '#title' => 'Upload Excel File',
      '#description' => 'Upload an Excel file.',
      '#upload_validators' => [
        'file_validate_extensions' => ['xls', 'xlsx'],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Upload',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $validators = ['file_validate_extensions' => ['xls', 'xlsx']];
    $file = file_save_upload('file_upload', $validators, 'public://uploads/', FileSystemInterface::EXISTS_REPLACE);

    if (!$file) {
      $form_state->setErrorByName('file_upload', 'No file uploaded or the uploaded file is not valid.');
    } else {
      // Make the file permanent.
      $file->setPermanent();
      $file->save();

      // Optionally, you can store the file entity ID in the form state for further processing.
      $form_state->setValue('file_upload_fid', $file->id());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->messenger->addStatus('File uploaded successfully.');
  }

}
