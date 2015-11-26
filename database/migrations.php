<?php

$db = new PDO('mysql:host=localhost;dbname=db_starwars', 'tony', 'tony') or die('no database selected');

/**
 * @create table users
 */

$sql = "
  CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(100),
    password VARCHAR(100),
    address TEXT,
    number_card VARCHAR(100),
    status ENUM('administrator', 'visitor')
    NOT NULL DEFAULT 'visitor',
    PRIMARY KEY (id)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ";

$db->exec($sql);

$sql = "
  CREATE TABLE IF NOT EXISTS customers (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    email VARCHAR(100),
    address TEXT,
    number_card VARCHAR(100),
    number_command INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (id)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ";

$db->exec($sql);

/**
 * @create table categories
 */

$sql = "
  CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ";

$db->exec($sql);

/**
 * @create table categories
 */
$db->exec("
  CREATE TABLE tags (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100),
    PRIMARY KEY (id)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table products
 */
$db->exec("
  CREATE TABLE products (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(100),
    category_id INT UNSIGNED,
    abstract TEXT,
    content TEXT,
    price DECIMAL(10,2),
    status ENUM('published', 'unpublished') NOT NULL DEFAULT 'unpublished',
    published_at DATETIME NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT products_category_id__categories_foreign FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE SET NULL
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table medias
 */
$db->exec("
  CREATE TABLE images (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id int(10) unsigned DEFAULT NULL,
    uri VARCHAR(100),
    `size` INT NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT images_products_product_id_foreign FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

/**
 * @create table product_tag (abc order)
 */
$db->exec("
  CREATE TABLE product_tag (
  product_id INT UNSIGNED,
  tag_id INT UNSIGNED,
  CONSTRAINT product_tag_product_id_products_foreign FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE,
  CONSTRAINT product_tag_product_tag_id__tags_foreign FOREIGN KEY(tag_id) REFERENCES tags(id) ON DELETE CASCADE,
  CONSTRAINT un_product_id_tag_id UNIQUE KEY (product_id, tag_id )
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");

$db->exec("
  CREATE TABLE histories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    product_id INT UNSIGNED NOT NULL,
    customer_id INT UNSIGNED,
    price DECIMAL(10,2),
    total DECIMAL(10,2),
    quantity INT UNSIGNED,
    commanded_at DATETIME,
    status ENUM('finalized', 'unfinalized') NOT NULL DEFAULT 'unfinalized',
    PRIMARY KEY (id),
    CONSTRAINT histories_product_id_products_foreign FOREIGN KEY(product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT histories_customer_id_users_foreign FOREIGN KEY(customer_id) REFERENCES customers(id) ON DELETE CASCADE
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
  ");