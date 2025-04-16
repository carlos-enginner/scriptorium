<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
  'enable' => true,
  'port' => 9500,
  'json_dir' => BASE_PATH . '/runtime/swagger',
  'html' => <<<'HTML'
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="description"
      content="SwaggerUI"
    />
    <title>SwaggerUI</title>
    <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css" />
  </head>
  <body>
  <div id="swagger-ui"></div>
  <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>
  <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-standalone-preset.js" crossorigin></script>
  <script>
    window.onload = () => {
      window.ui = SwaggerUIBundle({
        url: GetQueryString("search"),
        dom_id: '#swagger-ui',
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        layout: "StandaloneLayout",
      });
    };
    function GetQueryString(name) {
      var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
      var r = window.location.search.substr(1).match(reg);
      var context = "";
      if (r != null)
        context = decodeURIComponent(r[2]);
      reg = null;
      r = null;
      return context == null || context == "" || context == "undefined" ? "http.json" : context;
    }
  </script>
  </body>
</html>
HTML,
  'url' => '/docs',
  'auto_generate' => true,
  'scan' => [
    'paths' => null,
  ],
  'server' => [
    'http' => [
      'servers' => [
        [
          'url' => 'http://localhost:9501',
          'description' => 'Test Server',
        ],
      ],
      'info' => [
        'title' => 'Scriptorium API',
        'description' => 'Esta é a API do Scriptorium utilizando a especificação OpenAPI 3.0',
        'version' => '1.0.0',
      ],
    ],
    'v2' => [ // Novo grupo v2
      'servers' => [
        [
          'url' => 'http://localhost:9501/v2',
          'description' => 'API Versão 2',
        ],
      ],
      'info' => [
        'title' => 'Scriptorium API v2',
        'description' => 'Nova versão da API com melhorias',
        'version' => '2.0.0',
      ],
    ],
  ],
];
