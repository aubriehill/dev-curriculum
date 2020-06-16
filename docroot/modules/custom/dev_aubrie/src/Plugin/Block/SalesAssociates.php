<?php

namespace Drupal\dev_aubrie\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Provides a 'TestBlock' block.
 *
 * @Block(
 *  id = "aubrie_sales_associates",
 *  admin_label = @Translation("Aubrie Sales Associates"),
 * )
 */
class SalesAssociates extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['#theme'] = 'aubrie_sales_associates';


    $request = \Drupal::request();
    $sales_code = $request->query->get('sales_code');
    $cookies = $request->cookies;
    $sales_associates_cookie = json_decode($cookies->get('aubrie_sales_associates'));

    // Check if query matches cookie, if not update it
    if ($sales_code && $sales_code != $sales_associates_cookie) {
      $response = new Response();
      $response->headers->setCookie(new Cookie('aubrie_sales_associates', json_encode($sales_code), time() + (86400 * 30), '/', $request->getHost(), FALSE, FALSE));
      $response->sendHeaders();
    } if (empty($sales_code)) {
      $sales_code = $sales_associates_cookie;
    }

    if ($sales_code) {
      $entityStorage = \Drupal::entityTypeManager()->getStorage('node');
      $node = $entityStorage->load($sales_code);
      $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');

      // Return List
      $build['#content'] = $view_builder->view($node, 'teaser');
      $build['#cache']['contexts'] = ['url.query_args:sales_code'];
      return $build;
    }
  }
}

