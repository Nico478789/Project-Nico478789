<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250127145532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ride_user (ride_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C6ACE33D302A8A70 (ride_id), INDEX IDX_C6ACE33DA76ED395 (user_id), PRIMARY KEY(ride_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33D302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_773DE69D9D86650F ON car (user_id_id)');
        $this->addSql('ALTER TABLE review ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C69D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C69D86650F ON review (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33D302A8A70');
        $this->addSql('ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33DA76ED395');
        $this->addSql('DROP TABLE ride_user');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D9D86650F');
        $this->addSql('DROP INDEX IDX_773DE69D9D86650F ON car');
        $this->addSql('ALTER TABLE car DROP user_id_id');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C69D86650F');
        $this->addSql('DROP INDEX IDX_794381C69D86650F ON review');
        $this->addSql('ALTER TABLE review DROP user_id_id');
    }
}
