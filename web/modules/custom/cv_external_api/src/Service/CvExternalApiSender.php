<?php

namespace Drupal\cv_external_api\Service;

use Drupal\node\Entity\Node;
use GuzzleHttp\ClientInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;

class CvExternalApiSender {

  protected ClientInterface $httpClient;
  protected ConfigFactoryInterface $configFactory;
  protected LoggerChannelInterface $logger;

  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $config_factory, LoggerChannelInterface $logger) {
    $this->httpClient = $http_client;
    $this->configFactory = $config_factory;
    $this->logger = $logger;
  }

  public function send(Node $node) {
    $config = $this->configFactory->get('cv_external_api.settings');
    $url = $config->get('endpoint_url') ?: 'https://jsonplaceholder.typicode.com/posts';

    $this->logger->notice('send() chamado para o node ID: ' . $node->id());

    $data = [
      'nome' => $node->get('field_nome')->value ?? '',
      'morada' => $node->get('field_morada')->value ?? '',
      'distrito' => $node->get('field_distrito')->value ?? '',
      'idade' => $node->get('field_idade')->value ?? '',
      'ficheiro_cv' => $node->get('field_anexo_do_cv')->entity?->getFilename() ?? '',
    ];

    try {
      $response = $this->httpClient->request('POST', $url, ['json' => $data]);

      if ($response->getStatusCode() === 201) {
        $node->set('field_enviado_externamente', TRUE);
      }
      else {
        $this->logger->error('Erro no envio para API: HTTP ' . $response->getStatusCode());
        $node->set('field_enviado_externamente', FALSE);
      }
    }
    catch (\Exception $e) {
      $this->logger->error('Erro ao enviar para API: ' . $e->getMessage());
      $node->set('field_enviado_externamente', FALSE);
    }

    $node->save();
  }
}
