<?php

namespace Drupal\saudacao\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Fornece um bloco de saudação baseado na hora do dia.
 *
 * @Block(
 *   id = "saudacao_block",
 *   admin_label = @Translation("Bloco de Saudação"),
 *   category = @Translation("Custom")
 * )
 */
class SaudacaoBlock extends BlockBase {

  public function build() {
    $hora = (int) date('H');

    $mensagem = match (true) {
      $hora < 12 => 'Bom dia!',
      $hora < 19 => 'Boa tarde!',
      default => 'Boa noite!',
    };

    return [
      '#markup' => '<h2>' . $mensagem . '</h2>',
      '#cache' => ['max-age' => 0],
    ];
  }

}
