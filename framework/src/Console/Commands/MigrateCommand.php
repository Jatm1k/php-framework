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
    private int $batch = 1;
    
    public function __construct(
        private Connection $connection,
        private string $migrationsPath,
    ) {
    }

    public function execute(array $parametrs = []): int
    {
        try {
            // $this->connection->setAutoCommit(false);
            $this->createMigrationTable();

            // $this->connection->beginTransaction();

            $appliedMigrations = $this->getAppliedMigrations();

            $migrationFiles = $this->getMigraionFiles();

            $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));

            $this->updateBatch();

            $schema = new Schema();
            foreach($migrationsToApply as $migration) {
                $migrationInstance = require $this->migrationsPath . "/$migration";
                $migrationInstance->up($schema);

                $this->addMigration($migration);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
            foreach($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }
            // $this->connection->commit();
        } catch (\Throwable $e) {
            // $this->connection->rollBack();
            throw $e;
        }
        // $this->connection->setAutoCommit(true);
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
            $table->addColumn('batch', Types::INTEGER, [
                'unsigned' => true,
                'default' => 1,
            ]);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP',
            ]);
            $table->setPrimaryKey(['id']);

            $sql = $schema->toSql($this->connection->getDatabasePlatform())[0];
            $this->connection->executeQuery($sql);

            echo 'Migrations table created' . PHP_EOL;
        }

    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        return $queryBuilder->select('migration')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigraionFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);
        $arrayFiltered = array_filter($migrationFiles, fn ($file) => !in_array($file, ['.', '..']));
        return array_values($arrayFiltered);
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATIONS_TABLE)
            ->values([
                'migration' => ':migration',
                'batch' => ':batch',
                ])
            ->setParameter('migration', $migration)
            ->setParameter('batch', $this->batch)
            ->executeQuery();
    }

    private function updateBatch(): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $currentBatch = $queryBuilder->select('max(batch)')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchOne();
        $this->batch = $currentBatch + 1;
    }
}
