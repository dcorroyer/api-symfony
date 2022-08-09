<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220809212724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E94A4A3511');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E94A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE maintenance DROP FOREIGN KEY FK_2F84F8E94A4A3511');
        $this->addSql('ALTER TABLE maintenance ADD CONSTRAINT FK_2F84F8E94A4A3511 FOREIGN KEY (vehicule_id) REFERENCES vehicule (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
