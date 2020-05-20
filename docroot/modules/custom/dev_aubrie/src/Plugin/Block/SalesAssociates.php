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
    $cookies = $request->cookies;
    $aubrie_sales_associates_cookie = json_decode($cookies->get('aubrie_sales_associates'));

    // Check if cookie has been set, if not set it.
    if (empty($aubrie_sales_associates_cookie)) {
      $response = new Response();
      $response->headers->setCookie(new Cookie('aubrie_sales_associates', 1, time() + (86400 * 30), '/', $request->getHost(), FALSE, FALSE));
      $response->sendHeaders();
    }

    // Data set
    $sales_associates = [
      'John Smith',
      'Jane Smith',
      'Jon Doe',
      'Jan Doe',
      'John Johnson',
      'Don Johnson',
      'Jons Johnsohn',
      'Colonel Mustard',
      'Miss Scarlett',
      'Professor Plum',
      'Mrs. Peacock',
      'Princess Peach',
      'Kilgore Trout',
      'Herman Melville',
      'Ethel Merman',
      'Björk Guðmundsdóttir',
      'Dudley Dudley',
      'Harry Liss',
      'Harry Potter',
      'Harrison Liss',
      'Santos L. Halper',
      'R.L. Stine',
      'R.L. Stevenson',
      'Tamara de Lempicka',
      'Frida Kahlo',
      'Hilma af Klint',
      'Henri Toulouse-Lautrec',
      'Beyonce Knowles-Carter',
      'Cormac McCarthy',
      'Joe McCarthy',
      'Jenny McCarthy',
      'Mao Zedong',
      'Võ Nguyên Giáp',
      'Cantiflas',
      'Guillermo del Toro',
      'Gael García Bernal',
      'Ursula K. Le Guin',
      'Ron Artest',
      'Metta World Peace',
      'Lesane Crooks',
      'Rakim Mayers',
      'Christopher Wallace',
      'Russell Jones',
      'Kimberly Jones',
      'Onika Maraj',
      'William Drayton Jr.',
      'Belcalis Almanzar',
      'Sinead O\'Connor',
      'Bart Simpson',
      'Bort Sampson',
      'Santos L. Halper',
    ];

    /** @var \Drupal\dev_aubrie\Service\PersonalizationService $my_service */
    $personalization_service = \Drupal::service('dev_aubrie.personalization_service');

    $structured_associates = [];
    foreach ($sales_associates as $associate) {
      // Take the first word as first name, remaining string will be last name
      $split = explode(' ', $associate,2);
      $structured_associates[] = [
        'first_name' => $split[0],
        'last_name' => array_key_exists('1', $split) ? $split[1] : '',
        'machine_name' => $personalization_service->encodeName($associate),
      ];
    }

    // Sort by last name and then first name, regardless of letter case
    usort($structured_associates, function($a, $b) {
      $order = strtolower($a['last_name']) <=> strtolower($b['last_name']);
      if ($order == 0) {
        $order = strtolower($a['first_name']) <=> strtolower($b['first_name']);
      }
      return $order;
    });

    // Return List
    $build['#content'] = $structured_associates;
    return $build;
  }

}
