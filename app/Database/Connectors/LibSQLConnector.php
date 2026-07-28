<?php

namespace App\Database\Connectors;

use Illuminate\Database\Connectors\Connector;
use Illuminate\Database\Connectors\ConnectorInterface;
use PDO;

class LibSQLConnector extends Connector implements ConnectorInterface
{
    public function getName(): string
    {
        return 'libsql';
    }

    public function connect(array $config): PDO
    {
        $url = $config['url'] ?? null;
        $token = $config['password'] ?? null;

        $pdo = new \Libsql\PDO(
            dsn: $config['database'] ?? null,
            password: $token,
            options: array_filter([
                'url' => $url,
            ])
        );

        $this->configureSession($pdo);

        return $pdo;
    }

    protected function configureSession($pdo): void
    {
        $config = $this->config;

        $queries = [];

        if (isset($config['foreign_key_constraints'])) {
            $queries[] = $config['foreign_key_constraints']
                ? 'PRAGMA foreign_keys = ON'
                : 'PRAGMA foreign_keys = OFF';
        }

        if (! empty($queries)) {
            $pdo->exec(implode('; ', $queries));
        }
    }
}
