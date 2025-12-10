<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250726000345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       /* $this->addSql('CREATE TABLE curriculum (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, formacion LONGTEXT NOT NULL, experiencia LONGTEXT NOT NULL, habilidad LONGTEXT NOT NULL, idioma LONGTEXT NOT NULL, conocimiento LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_7BE2A7C3DB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, ciudad VARCHAR(100) DEFAULT NULL, sector VARCHAR(100) DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, logo VARCHAR(100) DEFAULT NULL, sitio_web VARCHAR(255) DEFAULT NULL, redes_sociales VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE experiencia (id INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, puesto VARCHAR(255) NOT NULL, empresa VARCHAR(255) NOT NULL, fecha_inicio DATE DEFAULT NULL, fecha_fin DATE DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, INDEX IDX_DD0E3034FCF8192D (id_usuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formacion (id INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, centro VARCHAR(150) NOT NULL, fecha_inicio DATE DEFAULT NULL, fecha_fin DATE DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, INDEX IDX_8D8E4E99FCF8192D (id_usuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habilidad (id INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, nivel VARCHAR(100) DEFAULT NULL, descripcion LONGTEXT DEFAULT NULL, INDEX IDX_4D2A2AF7FCF8192D (id_usuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE idioma (id INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, nombre VARCHAR(100) NOT NULL, nivel VARCHAR(100) DEFAULT NULL, INDEX IDX_1DC85E0CFCF8192D (id_usuario), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oferta_empleo (id INT AUTO_INCREMENT NOT NULL, id_empresa INT DEFAULT NULL, titulo VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, ubicacion VARCHAR(150) DEFAULT NULL, tipo_contrato VARCHAR(100) DEFAULT NULL, salario VARCHAR(100) DEFAULT NULL, fecha_publicacion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_80DA189A17713E5A (titulo), INDEX IDX_80DA189A664AF320 (id_empresa), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE postulacion (id INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, id_oferta_empleo INT DEFAULT NULL, fecha DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', carta_presentacion LONGTEXT DEFAULT NULL, estado VARCHAR(50) DEFAULT \'pendiente\' NOT NULL, score NUMERIC(4, 1) NOT NULL, INDEX IDX_17B321BDFCF8192D (id_usuario), INDEX IDX_17B321BD3EF2C9FF (id_oferta_empleo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recomendacion (id INT AUTO_INCREMENT NOT NULL, id_usuario INT DEFAULT NULL, id_oferta_empleo INT DEFAULT NULL, score NUMERIC(4, 1) NOT NULL, fecha DATETIME NOT NULL, INDEX IDX_739444C1FCF8192D (id_usuario), INDEX IDX_739444C13EF2C9FF (id_oferta_empleo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, ciudad VARCHAR(100) DEFAULT NULL, redes_sociales VARCHAR(255) DEFAULT NULL, foto VARCHAR(100) DEFAULT NULL, resumen_perfil LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

       $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C3DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE experiencia ADD CONSTRAINT FK_DD0E3034FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE formacion ADD CONSTRAINT FK_8D8E4E99FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE habilidad ADD CONSTRAINT FK_4D2A2AF7FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE idioma ADD CONSTRAINT FK_1DC85E0CFCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE oferta_empleo ADD CONSTRAINT FK_80DA189A664AF320 FOREIGN KEY (id_empresa) REFERENCES empresa (id)');
        $this->addSql('ALTER TABLE postulacion ADD CONSTRAINT FK_17B321BDFCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE postulacion ADD CONSTRAINT FK_17B321BD3EF2C9FF FOREIGN KEY (id_oferta_empleo) REFERENCES oferta_empleo (id)');
        $this->addSql('ALTER TABLE recomendacion ADD CONSTRAINT FK_739444C1FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE recomendacion ADD CONSTRAINT FK_739444C13EF2C9FF FOREIGN KEY (id_oferta_empleo) REFERENCES oferta_empleo (id)');
        $this->addSql('ALTER TABLE conocimiento ADD CONSTRAINT FK_132CE629FCF8192D FOREIGN KEY (id_usuario) REFERENCES usuario (id)');
    */
       }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conocimiento DROP FOREIGN KEY FK_132CE629FCF8192D');
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C3DB38439E');
        $this->addSql('ALTER TABLE experiencia DROP FOREIGN KEY FK_DD0E3034FCF8192D');
        $this->addSql('ALTER TABLE formacion DROP FOREIGN KEY FK_8D8E4E99FCF8192D');
        $this->addSql('ALTER TABLE habilidad DROP FOREIGN KEY FK_4D2A2AF7FCF8192D');
        $this->addSql('ALTER TABLE idioma DROP FOREIGN KEY FK_1DC85E0CFCF8192D');
        $this->addSql('ALTER TABLE oferta_empleo DROP FOREIGN KEY FK_80DA189A664AF320');
        $this->addSql('ALTER TABLE postulacion DROP FOREIGN KEY FK_17B321BDFCF8192D');
        $this->addSql('ALTER TABLE postulacion DROP FOREIGN KEY FK_17B321BD3EF2C9FF');
        $this->addSql('ALTER TABLE recomendacion DROP FOREIGN KEY FK_739444C1FCF8192D');
        $this->addSql('ALTER TABLE recomendacion DROP FOREIGN KEY FK_739444C13EF2C9FF');
        $this->addSql('DROP TABLE curriculum');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP TABLE experiencia');
        $this->addSql('DROP TABLE formacion');
        $this->addSql('DROP TABLE habilidad');
        $this->addSql('DROP TABLE idioma');
        $this->addSql('DROP TABLE oferta_empleo');
        $this->addSql('DROP TABLE postulacion');
        $this->addSql('DROP TABLE recomendacion');
        $this->addSql('DROP TABLE usuario');
    }
}
