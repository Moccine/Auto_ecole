<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200201163908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, shop_id INT DEFAULT NULL, card_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, rest DOUBLE PRECISION NOT NULL, INDEX IDX_1CC16EFE4D16C4DD (shop_id), UNIQUE INDEX UNIQ_1CC16EFE4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE card ADD shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D34D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_161498D34D16C4DD ON card (shop_id)');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA24ACC9A20');
        $this->addSql('DROP INDEX IDX_AC6A4CA24ACC9A20 ON shop');
        $this->addSql('ALTER TABLE shop DROP card_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE credit');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D34D16C4DD');
        $this->addSql('DROP INDEX IDX_161498D34D16C4DD ON card');
        $this->addSql('ALTER TABLE card DROP shop_id');
        $this->addSql('ALTER TABLE shop ADD card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA24ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)');
        $this->addSql('CREATE INDEX IDX_AC6A4CA24ACC9A20 ON shop (card_id)');
    }
}
