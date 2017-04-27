SET NAMES 'utf8';
SET CHARACTER SET utf8;


-- Create Database
DROP DATABASE IF EXISTS `ansible`;
CREATE DATABASE `ansible` DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE `ansible`;


-- Create MySQL user and grant access to ansible database --
DROP USER IF EXISTS ansible@localhost;
CREATE USER ansible@localhost IDENTIFIED BY 'q1w2e3!';
GRANT SELECT,INSERT,UPDATE,DELETE ON ansible.* TO ansible@localhost;


-- Create Tables
CREATE TABLE `packages` (
  id INT UNSIGNED AUTO_INCREMENT,
  name VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;


INSERT INTO packages(name) VALUES ('postfix'),('munin-node');