<?php

namespace Drupal\teste\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Controller for processing Excel files.
 */
class ExcelController extends ControllerBase {

  /**
   * Processes an Excel file and returns the data as JSON.
   *
   * @param string $filepath
   *   The path to the Excel file.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   JSON response containing the Excel data.
   */
  public function processExcel($filepath) {
    try {
      // Check if the file exists.
      if (!file_exists($filepath)) {
        throw new \Exception('The file was not found.');
      }

      // Load the Excel file using PhpSpreadsheet.
      $spreadsheet = IOFactory::load($filepath);
      $worksheet = $spreadsheet->getActiveSheet();
      $data = [];

      // Iterate through rows and cells to retrieve data.
      foreach ($worksheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);
        $rowData = [];
        foreach ($cellIterator as $cell) {
          $rowData[] = $cell->getValue();
        }
        $data[] = $rowData;
      }

      // Convert data to JSON and return as HTTP response.
      return new Response(json_encode($data), 200, ['Content-Type' => 'application/json']);
    } catch (\Exception $e) {
      // In case of error, return an error response with a message.
      return new Response(json_encode(['error' => $e->getMessage()]), 500, ['Content-Type' => 'application/json']);
    }
  }

}
