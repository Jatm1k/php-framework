<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
            'unsigned' => true,
        ]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('name', Types::STRING);
        $table->addColumn('email', Types::STRING);
        $table->addColumn('password', Types::STRING);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);
    }
    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
};
