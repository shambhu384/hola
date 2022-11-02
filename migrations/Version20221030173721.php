<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221030173721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_tag (notification_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_826782B4EF1A9D84 (notification_id), INDEX IDX_826782B4BAD26311 (tag_id), PRIMARY KEY(notification_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE symfony_demo_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4D5855405E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_tag ADD CONSTRAINT FK_826782B4EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_tag ADD CONSTRAINT FK_826782B4BAD26311 FOREIGN KEY (tag_id) REFERENCES symfony_demo_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification DROP tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification_tag DROP FOREIGN KEY FK_826782B4EF1A9D84');
        $this->addSql('ALTER TABLE notification_tag DROP FOREIGN KEY FK_826782B4BAD26311');
        $this->addSql('DROP TABLE notification_tag');
        $this->addSql('DROP TABLE symfony_demo_tag');
        $this->addSql('ALTER TABLE notification ADD tags VARCHAR(255) NOT NULL');
    }
}
