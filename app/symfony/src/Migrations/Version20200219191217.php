<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219191217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders ADD shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4D16C4DD ON orders (shop_id)');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA2CFFE9AD6');
        $this->addSql('DROP INDEX IDX_AC6A4CA2CFFE9AD6 ON shop');
        $this->addSql('ALTER TABLE shop DROP orders_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4D16C4DD');
        $this->addSql('DROP INDEX IDX_E52FFDEE4D16C4DD ON orders');
        $this->addSql('ALTER TABLE orders DROP shop_id');
        $this->addSql('ALTER TABLE shop ADD orders_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA2CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_AC6A4CA2CFFE9AD6 ON shop (orders_id)');
    }
}
