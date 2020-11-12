<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200927173141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea DROP FOREIGN KEY FK_3CA05366DB38439E');
        $this->addSql('DROP INDEX IDX_3CA05366DB38439E ON tarea');
        $this->addSql('ALTER TABLE tarea DROP usuario_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tarea ADD usuario_id INT NOT NULL');
        $this->addSql('ALTER TABLE tarea ADD CONSTRAINT FK_3CA05366DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_3CA05366DB38439E ON tarea (usuario_id)');
        $this->addSql('ALTER TABLE user DROP password');
    }
}
