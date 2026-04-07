<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260407000257 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipo_pet (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE view_user (id INT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, data_nascimento DATE NOT NULL, idade_user INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pet ADD tipo_pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B853D689AE FOREIGN KEY (tipo_pet_id) REFERENCES tipo_pet (id)');
        $this->addSql('CREATE INDEX IDX_E4529B853D689AE ON pet (tipo_pet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tipo_pet');
        $this->addSql('DROP TABLE view_user');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B85A76ED395');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B853D689AE');
        $this->addSql('DROP INDEX IDX_E4529B853D689AE ON pet');
        $this->addSql('ALTER TABLE pet DROP tipo_pet_id');
    }
}
