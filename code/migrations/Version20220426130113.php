<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426130113 extends AbstractMigration{

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE `note` (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `text` TEXT NOT NULL
            );' 
        );

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `note`;');
    }
}
