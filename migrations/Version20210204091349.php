<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210204091349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD nom VARCHAR(50) NOT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD numero_tel VARCHAR(20) DEFAULT NULL, ADD date_naissance DATETIME DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD pseudo VARCHAR(50) NOT NULL, DROP username');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP nom, DROP prenom, DROP email, DROP numero_tel, DROP date_naissance, DROP adresse');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP pseudo');
    }
}
