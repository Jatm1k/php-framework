<?php

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

return new class {
    public function up(Schema $schema): void
    {
        $table = $schema->createTable('posts');
        $table->addColumn('id', Types::INTEGER, [
            'autoincrement' => true,
            'unsigned' => true,
        ]);
        $table->setPrimaryKey(['id']);
        $table->addColumn('title', Types::STRING);
        $table->addColumn('body', Types::STRING);
        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
            'default' => 'CURRENT_TIMESTAMP',
        ]);
    }
    public function down(Schema $schema): void
    {
        //
    }
};
