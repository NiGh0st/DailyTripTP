<?php

// Informations de connexion à la base de données
$host = 'localhost:3306';
$user = 'root';
$password = '';
$database = 'dailytrip_0';

try {
    // Connexion au serveur MySQL sans sélectionner de base de données
    $conn = new PDO("mysql:host=$host", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Créer la base de données si elle n'existe pas
    $sql = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET = 'utf8mb4'";
    $conn->exec($sql);
    echo "Base de données '$database' créée avec succès.\n"; //OK

    // Se connecter à la base de données créée
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Définir le moteur InnoDB pour la création des tables
    $engine = 'ENGINE = InnoDB';

    // Création des tables
    $tables = [
        // TODO: Ajoutez vos requêtes SQL de création de tables ici
        "CREATE TABLE category(
            `id` BIGINT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255),
            `image` VARCHAR(255)
        );
        
        CREATE TABLE localisation(
            `id` BIGINT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
            `start` VARCHAR(255),
            `finish` VARCHAR(255),
            `distance` DECIMAL(8, 2),
            `duration` TIME
        );

        CREATE TABLE trips(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `ref` VARCHAR(255),
            `title` VARCHAR(255),
            `description` TEXT NULL,
            `cover` VARCHAR(255),
            `email` VARCHAR(255),
            `localisation_id` BIGINT UNSIGNED,
            `category_id` BIGINT UNSIGNED,
            `gallery_id` BIGINT UNSIGNED NULL,
            `status` BOOLEAN
        );

        CREATE TABLE poi(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `point` VARCHAR(255),
            `localisation_id` BIGINT UNSIGNED,
            `gallery_id` BIGINT UNSIGNED NULL
        );

        CREATE TABLE rating(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `note` BIGINT,
            `ip_address` VARCHAR(255),
            `trip_id` BIGINT UNSIGNED
        );

        CREATE TABLE review(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `fullname` VARCHAR(255),
            `content` TEXT,
            `email` VARCHAR(255),
            `trip_id` BIGINT UNSIGNED
        );

        CREATE TABLE gallery(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        );

        CREATE TABLE gallery_images(
            `gallery_id` BIGINT UNSIGNED,
            `image_id` BIGINT UNSIGNED
        );

        CREATE TABLE images(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        );

        CREATE TABLE admin(
            `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `email` VARCHAR(255),
            `password` VARCHAR(255)
        );",
    ];

    // Exécution de la création des tables
    foreach ($tables as $tableSql) {
        try {
            $conn->exec($tableSql);
            echo "Table créée avec succès.\n"; //OK
        } catch (PDOException $e) {
            echo "Erreur lors de la création de la table : " . $e->getMessage() . "\n";
        }
    }

    // Ajout des clés étrangères
    $constraints = [
        // TODO: Ajoutez vos requêtes SQL de contraintes ici
        "ALTER TABLE
            trips ADD CONSTRAINT FK_Trips_LocalisationID FOREIGN KEY(localisation_id) REFERENCES localisation(id);
        ALTER TABLE
            trips ADD CONSTRAINT FK_Trips_CategoryID FOREIGN KEY(category_id) REFERENCES category(id);
        ALTER TABLE
            trips ADD CONSTRAINT FK_Trips_GalleryID FOREIGN KEY(gallery_id) REFERENCES gallery(id);
        ALTER TABLE
            poi ADD CONSTRAINT FK_Poi_LocalisationID FOREIGN KEY(localisation_id) REFERENCES localisation(id);
        ALTER TABLE
            poi ADD CONSTRAINT FK_Poi_GalleryID FOREIGN KEY(gallery_id) REFERENCES gallery(id);
        ALTER TABLE
            rating ADD CONSTRAINT FK_Rating_TripID FOREIGN KEY(trip_id) REFERENCES trips(id);
        ALTER TABLE
            review ADD CONSTRAINT FK_Review_TripID FOREIGN KEY(trip_id) REFERENCES trips(id);
        ALTER TABLE
            gallery_images ADD CONSTRAINT FK_GalleryImages_galleryID FOREIGN KEY(gallery_id) REFERENCES gallery(id);
        ALTER TABLE
            gallery_images ADD CONSTRAINT FK_GalleryImages_ImageID FOREIGN KEY(image_id) REFERENCES images(id);"
    ];

    // Exécution des contraintes de clés étrangères
    foreach ($constraints as $constraintSql) {
        try {
            $conn->exec($constraintSql);
            echo "Contrainte de clé étrangère ajoutée avec succès.\n"; //OK
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la contrainte : " . $e->getMessage() . "\n";
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit;
} finally {
    // Fermer la connexion
    $conn = null;
}
