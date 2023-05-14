<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230506131558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Article table';
    }
    public function up(Schema $schema): void
    {
        $sql = <<<'SQL'

            CREATE TABLE IF NOT EXISTS `article` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `title` varchar(128) NOT NULL,
                `description` varchar(255),
                `content` text NOT NULL,
                `image` varchar(128) DEFAULT NULL,
                `created_at` int(10) unsigned NOT NULL,
                `updated_at` int(10) unsigned DEFAULT NULL,
                `user_id` int(11) NOT NULL,
                PRIMARY KEY(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `article`');
    }
}
