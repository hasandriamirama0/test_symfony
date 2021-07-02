<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702064533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city ADD postal_code_id INT DEFAULT NULL, DROP postal_code');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234BDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES postal_code (id)');
        $this->addSql('CREATE INDEX postal_code_id ON city (postal_code_id)');
        $this->addSql('ALTER TABLE company ADD postal_code_id INT DEFAULT NULL, ADD city_id INT DEFAULT NULL, DROP postal_code, DROP city');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FBDBA6A61 FOREIGN KEY (postal_code_id) REFERENCES postal_code (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094F8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX city_id ON company (city_id)');
        $this->addSql('CREATE INDEX postal_code_id ON company (postal_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234BDBA6A61');
        $this->addSql('DROP INDEX postal_code_id ON city');
        $this->addSql('ALTER TABLE city ADD postal_code VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP postal_code_id');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FBDBA6A61');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094F8BAC62AF');
        $this->addSql('DROP INDEX city_id ON company');
        $this->addSql('DROP INDEX postal_code_id ON company');
        $this->addSql('ALTER TABLE company ADD postal_code INT NOT NULL, ADD city VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP postal_code_id, DROP city_id');
    }
}
