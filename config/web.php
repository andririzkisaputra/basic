<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Reset Password Buku Pengunjung',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    // 'bootstrap' => ['log', 'oauth2'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'u7tV1XfDBio9d-l7Z-o3rpTfVmSDo7IV',
            'parsers' => [
              'application/json' => 'yii\web\JsonParser',
            ]
        ],
        // 'response' => [
        //   'format' => yii\web\Response::FORMAT_JSON,
        //   'charset' => 'UTF-8',
        // ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession'   => true,
            'loginUrl'        => null
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'  => [
          'class' => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@app/mail',
            'useFileTransport' => false,
            'transport'        => [
              'class'      => 'Swift_SmtpTransport',
              'host'       => 'smtp.gmail.com',
              'username'   => 'arsgaming159@gmail.com',
              'password'   => 'Andririzki12345',
              'port'       => '465',
              'encryption' => 'ssl',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
          'enablePrettyUrl'     => true,
          'enableStrictParsing' => false,
          'showScriptName'      => false,
          'rules' => [
              ['class' => 'yii\rest\UrlRule', 'controller' => ['api/anggota', 'api/kehadiran']],
              // ['class' => 'yii\rest\UrlRule', 'controller' => 'api/kehadiran']
          ],
            // 'class'           => 'yii\web\UrlManager',
            // 'enablePrettyUrl' => true,
            // 'enableStrictParsing'   => true,
            // 'showScriptName'  => false,
            // 'rules' => [
                // 'POST oauth2<action:\w+>' => 'api/user/login<action>',
                // 'oauth2' => 'oauth2/login',
                // [
                //   'class'      => 'yii\rest\UrlRule',
                //   'pluralize'  => false,
                //   'controller' => [
                //     'authorize' => 'site/login'
                //   ]
                // ],
                // [
                //   'class'      => 'yii\rest\UrlRule',
                //   'pluralize'  => false,
                //   'controller' => ['api/v1/anggota' => 'api/v1/anggota'],
                // ],
                // [
                //   'class'      => 'yii\rest\UrlRule',
                //   'pluralize'  => false,
                //   'controller' => ['api/v1/kehadiran' => 'api/v1/kehadiran'],
                // ]
            // ],
        ],
    ],
    'modules' => [
      'api' => [
        // 'basePath' => '@app/modules/v1',
        'class' => '\app\modules\api\Module'
      ],
      // 'oauth2' => [
      //   'class' => 'filsh\yii2\oauth2server\Module',
      //   'tokenParamName' => 'accessToken',
      //   'tokenAccessLifetime' => 3600 * 24,
      //   'storageMap' => [
      //       'user_credentials' => 'app\models\User',
      //   ],
      //   'grantTypes' => [
      //       'user_credentials' => [
      //           'class' => 'OAuth2\GrantType\UserCredentials',
      //       ],
      //       'refresh_token' => [
      //           'class' => 'OAuth2\GrantType\RefreshToken',
      //           'always_issue_new_refresh_token' => true
      //       ]
      //   ]
      // ]
    ],
    'params' => $params,
    'timeZone' => 'Asia/Jakarta',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1', '192.168.83.*', '192.168.10.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1', '192.168.83.*', '192.168.10.*'],
    ];
}

return $config;
