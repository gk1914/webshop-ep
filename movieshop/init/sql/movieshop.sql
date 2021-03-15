-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema movieshop
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `movieshop` ;

-- -----------------------------------------------------
-- Schema movieshop
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `movieshop` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci ;
USE `movieshop` ;

-- -----------------------------------------------------
-- Table `movieshop`.`movies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movieshop`.`movies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `director` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `year` INT(11) NOT NULL,
  `runlength` INT(11) NOT NULL,
  `description` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `price` FLOAT NOT NULL,
  `activated` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_slovenian_ci;


-- -----------------------------------------------------
-- Table `movieshop`.`post_adress`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movieshop`.`post_adress` (
  `zipcode` INT(11) NOT NULL,
  `city` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  PRIMARY KEY (`zipcode`),
  UNIQUE INDEX `id_UNIQUE` (`zipcode` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_slovenian_ci;


-- -----------------------------------------------------
-- Table `movieshop`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movieshop`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `surname` VARCHAR(100) CHARACTER SET 'utf8' NOT NULL,
  `email` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `phone` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `post_adress_zipcode` INT(11) NULL DEFAULT NULL,
  `adress` VARCHAR(255) NULL DEFAULT NULL,
  `activated` TINYINT(1) NOT NULL DEFAULT '0',
  `type` INT(11) NOT NULL,
  `password` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `fk_users_post_adress_idx` (`post_adress_zipcode` ASC),
  CONSTRAINT `fk_users_post_adress`
    FOREIGN KEY (`post_adress_zipcode`)
    REFERENCES `movieshop`.`post_adress` (`zipcode`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_slovenian_ci;


-- -----------------------------------------------------
-- Table `movieshop`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movieshop`.`orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `time_of_order` DATETIME NOT NULL,
  `price_total` FLOAT NOT NULL,
  `state` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_orders_user_id_idx` (`user_id` ASC),
  CONSTRAINT `fk_orders_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `movieshop`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movieshop`.`order_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movieshop`.`order_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `movie_id` INT(11) NOT NULL,
  `amount` INT(11) NOT NULL,
  `price` FLOAT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_order_order_id_idx` (`order_id` ASC),
  INDEX `fk_order_movie_id_idx` (`movie_id` ASC),
  CONSTRAINT `fk_order_order_id`
    FOREIGN KEY (`order_id`)
    REFERENCES `movieshop`.`orders` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_movie_id`
    FOREIGN KEY (`movie_id`)
    REFERENCES `movieshop`.`movies` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `movieshop`.`scores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `movieshop`.`scores` (
  `movie_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `score` INT(11) NOT NULL,
  PRIMARY KEY (`movie_id`, `user_id`),
  INDEX `fk_scores_movie_id_idx` (`movie_id` ASC),
  INDEX `fk_scores_user_id_idx` (`user_id` ASC),
  CONSTRAINT `fk_scores_movie_id`
    FOREIGN KEY (`movie_id`)
    REFERENCES `movieshop`.`movies` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_scores_user_id`
    FOREIGN KEY (`user_id`)
    REFERENCES `movieshop`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- -----------------------------------------------------
-- Data for table `movieshop`.`movies`
-- -----------------------------------------------------
START TRANSACTION;
USE `movieshop`;
INSERT INTO `movieshop`.`movies` (`id`, `title`, `director`, `year`, `runlength`, `description`, `price`, `activated`) VALUES (DEFAULT, 'Titanik', 'James Cameron', 1997, 195, 'FIlm o znameniti potniški ladji iz začetka 20. stoletja. Mladi umetnik Jack na ladji spozna prepovedano ljubezen, a tragedija konča njuno zgodbo.', 9.99, true);
INSERT INTO `movieshop`.`movies` (`id`, `title`, `director`, `year`, `runlength`, `description`, `price`, `activated`) VALUES (DEFAULT, 'Avatar', 'James Cameron', 2009, 162, 'Znanstveno-fantastični film, ki se dogaja v letu 2154 v fiktivnem oddaljenem ozvezdju z posebnimi bitji. Film temelji na uporabi napredne 3D računalniške grafike.', 12.99, true);
INSERT INTO `movieshop`.`movies` (`id`, `title`, `director`, `year`, `runlength`, `description`, `price`, `activated`) VALUES (DEFAULT, 'Kekec', 'Jože Gale', 1954, 97, 'Kekec je črno-beli slovenski mladinski film iz leta 1951, posnet po planinski pripovedki Kekec nad samotnim breznom. Film se dogaja v gorah, kjer pogumni in navihani Kekec reši žrtve strašnega Bedanca in ga prisili, da za vedno zapusti naše kraje.', 5.99, true);
INSERT INTO `movieshop`.`movies` (`id`, `title`, `director`, `year`, `runlength`, `description`, `price`, `activated`) VALUES (DEFAULT, 'Vojna zvezd: Vzpon Skywalkerja', 'J.J. Abrams', 2019, 155, 'Zadnji del najnovejše trilogije kultnega znanstveno-fantastičnega filma Vojna zvezd. V zaključnem delu sage o Skywalkerju, se bodo pojavile nove legende in tako zadnji boj za svobodo šele prihaja.', 14.99, false);

COMMIT;


-- -----------------------------------------------------
-- Data for table `movieshop`.`post_adress`
-- -----------------------------------------------------
START TRANSACTION;
USE `movieshop`;
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (1000, 'Ljubljana');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (8000, 'Novo mesto');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (2000, 'Maribor');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (3000, 'Celje');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (4000, 'Kranj');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (5000, 'Nova gorica');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (6000, 'Koper');
INSERT INTO `movieshop`.`post_adress` (`zipcode`, `city`) VALUES (9000, 'Murska sobota');

COMMIT;


-- -----------------------------------------------------
-- Data for table `movieshop`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `movieshop`;
INSERT INTO `movieshop`.`users` (`id`, `name`, `surname`, `email`, `phone`, `post_adress_zipcode`, `adress`, `activated`, `type`, `password`) VALUES (DEFAULT, 'Gašper', 'Kočjaž', 'gasper.kocjaz@movie.si', DEFAULT, DEFAULT, DEFAULT, 1, 2, '$2y$10$N5x0T2yvVw7pgkgtUQLMzus5jq4bIy.WGBYuNOg/.O2./3SUpCOX6');
INSERT INTO `movieshop`.`users` (`id`, `name`, `surname`, `email`, `phone`, `post_adress_zipcode`, `adress`, `activated`, `type`, `password`) VALUES (DEFAULT, 'Mitja', 'Mišič', 'mitja.misic@movie.si', DEFAULT, DEFAULT, DEFAULT, 1, 1, '$2y$10$LV2bARJtCm8jq1wYWJZYaubxUYYs.MVEOoMTao3BBF/551UBXtnzS');
INSERT INTO `movieshop`.`users` (`id`, `name`, `surname`, `email`, `phone`, `post_adress_zipcode`, `adress`, `activated`, `type`, `password`) VALUES (DEFAULT, 'Peter', 'Prosenc', 'peter.prosenc@movie.si', DEFAULT, DEFAULT, DEFAULT, DEFAULT, 1, '$2y$10$Ksj2b20Mwu2jN7MCI11C0eey71ZoVlwEdc20XZcICTMqLUXNiuH1K');
INSERT INTO `movieshop`.`users` (`id`, `name`, `surname`, `email`, `phone`, `post_adress_zipcode`, `adress`, `activated`, `type`, `password`) VALUES (DEFAULT, 'Ana', 'Ambrožič', 'ana.ambrozic@email.si', '041-111-111', 2000, 'naslov od ane 12', 1, 0, '$2y$10$nZleTFNOCA6Qutbk9HI0ROSMnGo2XGH1GXChMjyIEELUlTYF7UDS.');
INSERT INTO `movieshop`.`users` (`id`, `name`, `surname`, `email`, `phone`, `post_adress_zipcode`, `adress`, `activated`, `type`, `password`) VALUES (DEFAULT, 'Brane', 'Bajuk', 'brane.bajuk@email.si', '041-222-222', 5000, 'brane haus 1', 1, 0, '$2y$10$IKwpNyRmGBkDSQQhtTQWVO7kPg8yhg.WxoDp2xNUi6RlukgonSkIC');
INSERT INTO `movieshop`.`users` (`id`, `name`, `surname`, `email`, `phone`, `post_adress_zipcode`, `adress`, `activated`, `type`, `password`) VALUES (DEFAULT, 'Cene', 'Cujnik', 'cene.cunik@email.si', '041-333-333', 9000, 'poceni ulica 23', DEFAULT, 0, '$2y$10$.fuvvU7nHu4itVYseWYxd.KVnLkcOze6AZLtwVgxPm4ie/hffUjV6');

COMMIT;



-- -----------------------------------------------------
-- Creating SQL users
-- -----------------------------------------------------
CREATE USER IF NOT EXISTS 'shop'@'localhost' IDENTIFIED BY 'shoppass';
GRANT ALL PRIVILEGES ON movieshop.* TO 'shop'@'localhost';

