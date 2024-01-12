<?php

namespace Jatmy\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;
use Jatmy\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';
    private const MIGRATIONS_TABLE = 'migrations';
    
    public function __construct(
        private Connection $connection
    ) {
    }

    public function execute(array $parametrs = []): int
    {
        $this->createMigrationTable();
        echo 'migrate';
        return 0;
    }

    private function createMigrationTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if(!$schemaManager->tablesExist([self::MIGRATIONS_TABLE])) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS_TABLE);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true,
            ]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP',
            ]);
            $table->setPrimaryKey(['id']);

            $sql = $schema->toSql($this->connection->getDatabasePlatform())[0];
            $this->connection->executeQuery($sql);

            echo 'Migrations table created' . PHP_EOL;
        }

    }
}
