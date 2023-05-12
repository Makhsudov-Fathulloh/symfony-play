<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230507202259 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $sql = <<<'SQL'
           ALTER TABLE 'article' ADD 'user_id' INT NOT NULL, FOREIGN KEY (user_id) REFERENCES user (id)
SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `artcle`');
        $this->addSql('DROP TABLE `user`');
    }
}
