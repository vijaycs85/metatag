<?php

namespace Drupal\metatag_tests_custom_route\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Testing routes for Metatag tests.
 */
class MetatagTestsCustomRouteController extends ControllerBase {

  /**
   * Constructs a page for integration testing.
   */
  public function test() {
    $render = [
      '#markup' => $this->t('<p>Hello world!</p>', []),
    ];

    return $render;
  }

}
