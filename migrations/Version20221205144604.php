<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205144604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_offer (category_id INT NOT NULL, offer_id INT NOT NULL, INDEX IDX_C1E5C87712469DE2 (category_id), INDEX IDX_C1E5C87753C674EE (offer_id), PRIMARY KEY(category_id, offer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_wish (category_id INT NOT NULL, wish_id INT NOT NULL, INDEX IDX_5C4A0E8E12469DE2 (category_id), INDEX IDX_5C4A0E8E42B83698 (wish_id), PRIMARY KEY(category_id, wish_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_offer ADD CONSTRAINT FK_C1E5C87712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_offer ADD CONSTRAINT FK_C1E5C87753C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_wish ADD CONSTRAINT FK_5C4A0E8E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_wish ADD CONSTRAINT FK_5C4A0E8E42B83698 FOREIGN KEY (wish_id) REFERENCES wish (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_offer DROP FOREIGN KEY FK_C1E5C87712469DE2');
        $this->addSql('ALTER TABLE category_offer DROP FOREIGN KEY FK_C1E5C87753C674EE');
        $this->addSql('ALTER TABLE category_wish DROP FOREIGN KEY FK_5C4A0E8E12469DE2');
        $this->addSql('ALTER TABLE category_wish DROP FOREIGN KEY FK_5C4A0E8E42B83698');
        $this->addSql('DROP TABLE category_offer');
        $this->addSql('DROP TABLE category_wish');
    }
}
