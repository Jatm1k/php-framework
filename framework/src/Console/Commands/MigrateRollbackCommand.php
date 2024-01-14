<?php

namespace Jatmy\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Jatmy\Framework\Console\CommandInterface;

class MigrateRollbackCommand implements CommandInterface
{
    private string $name = 'migrate:rollback';
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
            $this->connection->beginTransaction();
    
            $this->updateCurrentBatch();
            $appliedMigrations = $this->getAppliedMigrations();
    
            $fromSchema = $this->connection->createSchemaManager()->introspectSchema();
            $toSchema = clone $fromSchema;
    
            foreach($appliedMigrations as $migration) {
                $migrationInstance = require $this->migrationsPath . "/$migration";
                $migrationInstance->down($toSchema);
    
                $this->removeMigration($migration);
            }
    
            $platform = $this->connection->getDatabasePlatform();
            $schemaDiff = $fromSchema->getMigrateToSql($toSchema, $platform);
    
            foreach($schemaDiff as $sql) {
                $this->connection->executeQuery($sql);
            }
    
            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
        return 0;
    }
    

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        return $queryBuilder->select('migration')
            ->from(self::MIGRATIONS_TABLE)
            ->where('batch = :batch')
            ->setParameter('batch', $this->batch)
            ->orderBy('id', 'DESC')
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function removeMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->delete(self::MIGRATIONS_TABLE)
            ->where('migration = :migration')
            ->setParameter('migration', $migration)
            ->executeQuery();
    }

    private function updateCurrentBatch(): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $currentBatch = $queryBuilder->select('max(batch)')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchOne();
        $this->batch = $currentBatch;
    }
}
