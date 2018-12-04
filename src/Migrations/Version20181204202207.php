<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181204202207 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('
            CREATE TABLE transaction (
                id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
                sender_account_id VARCHAR(20) NOT NULL,
                recipient_account_id VARCHAR(20) NOT NULL,
                money_minor_units INT UNSIGNED NOT NULL,
                status_code TINYINT UNSIGNED NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE transaction');
    }
}
