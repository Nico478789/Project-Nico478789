<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123140652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, author VARCHAR(32) DEFAULT NULL, grade SMALLINT NOT NULL, comment LONGTEXT DEFAULT NULL, status VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, car_id_id INT NOT NULL, departure_time DATETIME NOT NULL, arrival_time DATETIME NOT NULL, departure_city VARCHAR(32) NOT NULL, arrival_city VARCHAR(32) NOT NULL, status VARCHAR(32) NOT NULL, number_of_seats SMALLINT NOT NULL, price INT NOT NULL, INDEX IDX_9B3D7CD0A0EF1B80 (car_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0A0EF1B80 FOREIGN KEY (car_id_id) REFERENCES car (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0A0EF1B80');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE ride');
    }
}
