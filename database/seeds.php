<?php

$db = new PDO('mysql:host=localhost;dbname=db_starwars', 'tony', 'tony');

$date = new DateTime(null, new DateTimeZone('Europe/Paris'));
$now = $date->format('Y-m-d h:i:s');


function generate($nb, $fixture = __DIR__ . '/fixtures/lorem.txt')
{
    $lorems = new SplFileObject($fixture, 'a+');
    $txt = '';
    while ($nb > 0 && $lorems->valid()) {
        $txt .= $lorems->current();
        $lorems->next();
        $nb--;
    }

    return $txt;
}


$db->exec("
  INSERT INTO users (email,password, status)
  VALUES
 ('tony@tony.fr', '" . password_hash('tony', PASSWORD_BCRYPT, ['cost' => 12, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)]) . "', 'administrator')
  ");


$db->exec("
  INSERT INTO categories (title)
  VALUES
  ('Laser' ),
  ('Casque' )
  ");

$param2 = generate(2);
$param4 = generate(4);
$param5 = generate(5);
$db->exec("
  INSERT INTO products (title, category_id, abstract, content, price, status, published_at)
  VALUES
  ('sabre Yoda', 1, '$param2', '$param5', 1450.55, 'published', '$now'),
  ('casque Dark vador', 2, '$param2', '$param4', 8900.85, 'published', '$now')
  ");

$sabre = new Imagick(__DIR__ . '/../public/uploads/sabre.png');
$casque = new Imagick(__DIR__ . '/../public/uploads/casque.jpg');


$db->exec("
    INSERT INTO images (product_id, uri, `size`) VALUES (1,'sabre.png', '{$sabre->getImageSize()}'), (2, 'casque.jpg', '{$casque->getImageSize()}')
");

$db->exec("
  INSERT INTO tags (`name`)
  VALUES
  ('galaxy' ),
  ('star' ),
  ('jedi'),
  ('dark')
  ");

$db->exec("
  INSERT INTO product_tag (product_id, tag_id)
  VALUES
  (1,1),
  (1,3),
  (2,1),
  (2,2),
  (2,3),
  (2,4)
  ");

