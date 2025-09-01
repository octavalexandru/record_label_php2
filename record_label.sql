-- RESET (safe while developing)
CREATE DATABASE record_label CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE record_label;

SET NAMES utf8mb4;
SET time_zone = "+00:00";

-- Tables
CREATE TABLE artists (
  artist_id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  bio TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (artist_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE albums (
  album_id INT(11) NOT NULL AUTO_INCREMENT,
  artist_id INT(11) DEFAULT NULL,
  title VARCHAR(100) NOT NULL,
  release_date DATE DEFAULT NULL,
  genre VARCHAR(50) DEFAULT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,   -- add price
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  stock INT(11) DEFAULT 0,
  PRIMARY KEY (album_id),
  KEY (artist_id),
  CONSTRAINT albums_ibfk_1 FOREIGN KEY (artist_id) REFERENCES artists (artist_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE merchandise (
  merchandise_id INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  description TEXT DEFAULT NULL,
  price DECIMAL(10,2) NOT NULL,
  type ENUM('Clothing','Patches','Accessories') DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  stock INT(11) DEFAULT 0,
  PRIMARY KEY (merchandise_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE users (
  user_id INT(11) NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id),
  UNIQUE KEY (username),
  UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE cart (
  cart_id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,     -- helpful for one active cart
  session_token VARCHAR(64) DEFAULT NULL,      -- guest carts (optional)
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cart_id),
  KEY (user_id),
  KEY idx_cart_user_active (user_id, is_active),
  KEY idx_cart_session (session_token),
  CONSTRAINT cart_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE cartitems (
  cart_item_id INT(11) NOT NULL AUTO_INCREMENT,
  cart_id INT(11) DEFAULT NULL,
  merchandise_id INT(11) DEFAULT NULL,
  album_id INT(11) DEFAULT NULL,
  quantity INT(11) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (cart_item_id),
  KEY (cart_id),
  KEY (merchandise_id),
  KEY (album_id),
  CONSTRAINT cartitems_ibfk_1 FOREIGN KEY (cart_id) REFERENCES cart (cart_id) ON DELETE CASCADE,
  CONSTRAINT cartitems_ibfk_2 FOREIGN KEY (merchandise_id) REFERENCES merchandise (merchandise_id),
  CONSTRAINT cartitems_ibfk_3 FOREIGN KEY (album_id) REFERENCES albums (album_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE orders (
  order_id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) DEFAULT NULL,
  order_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total_amount DECIMAL(10,2) DEFAULT NULL,
  status ENUM('Pending','Paid','Shipped','Cancelled','Refunded') DEFAULT 'Pending',
  PRIMARY KEY (order_id),
  KEY (user_id),
  CONSTRAINT orders_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE orderitems (
  order_item_id INT(11) NOT NULL AUTO_INCREMENT,
  order_id INT(11) DEFAULT NULL,
  merchandise_id INT(11) DEFAULT NULL,
  quantity INT(11) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  album_id INT(11) DEFAULT NULL,
  PRIMARY KEY (order_item_id),
  KEY (order_id),
  KEY (merchandise_id),
  KEY fk_album_id (album_id),
  CONSTRAINT orderitems_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (order_id) ON DELETE CASCADE,
  CONSTRAINT orderitems_ibfk_2 FOREIGN KEY (merchandise_id) REFERENCES merchandise (merchandise_id),
  CONSTRAINT fk_album_id FOREIGN KEY (album_id) REFERENCES albums (album_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data
INSERT INTO artists (artist_id, name, bio, created_at) VALUES
(1,'Demolition Hammer','An American thrash metal band from the Bronx, New York, formed in 1986.','2024-11-10 22:00:00'),
(2,'Obituary','A death metal band from Tampa, Florida, formed in 1984, considered pioneers of the genre.','2024-11-10 22:00:00'),
(3,'Judas Priest','An English heavy metal band formed in Birmingham in 1969, widely regarded as one of the greatest metal bands.','2024-11-10 22:00:00'),
(4,'Luzifer','A German heavy metal band known for their traditional sound and powerful riffs.','2024-11-10 22:00:00'),
(5,'Wasteland Riders','A Spanish speed metal band with a punk-influenced sound, active in the underground scene.','2024-11-10 22:00:00'),
(6,'Exciter','A Canadian speed metal band, one of the first to help create the speed and thrash metal genres.','2024-11-10 22:00:00');

INSERT INTO albums (album_id, artist_id, title, release_date, genre, price, created_at, stock) VALUES
(1,1,'Epidemic of Violence','2006-01-01','Thrash Metal',12.99,'2024-11-10 22:00:00',0),
(2,2,'Cause of Death','1990-01-01','Death Metal',11.99,'2024-11-10 22:00:00',0),
(3,3,'Screaming for Vengeance','1982-01-01','Heavy Metal',13.49,'2024-11-10 22:00:00',0),
(4,4,'Iron Shackles','2022-01-01','Heavy Metal',14.99,'2024-11-10 22:00:00',0),
(5,5,'Death Arrives','2018-01-01','Speed Metal',9.99,'2024-11-10 22:00:00',0),
(6,6,'Heavy Metal Maniac','1983-01-01','Speed Metal',10.49,'2024-11-10 22:00:00',0);

INSERT INTO merchandise (merchandise_id, name, description, price, type, created_at, stock) VALUES
(1,'Demolition Hammer T-Shirt','High-quality T-shirt featuring the album art of "Epidemic of Violence".',25.99,'Clothing','2024-11-10 22:00:00',0),
(2,'Obituary Patch','Embroidered patch with the iconic "Cause of Death" album artwork.',9.99,'Patches','2024-11-10 22:00:00',0),
(3,'Judas Priest Leather Wristband','Genuine leather wristband with the "Screaming for Vengeance" logo.',19.99,'Accessories','2024-11-10 22:00:00',0),
(4,'Luzifer Iron Shackles Hoodie','Cozy hoodie inspired by Luzifer’s "Iron Shackles" album.',39.99,'Clothing','2024-11-10 22:00:00',0),
(5,'Wasteland Riders Bandana','Bandana with a design based on "Death Arrives".',14.99,'Accessories','2024-11-10 22:00:00',0),
(6,'Exciter Back Patch','Large back patch for jackets featuring "Heavy Metal Maniac" album art.',12.99,'Patches','2024-11-10 22:00:00',0);

-- VERIFY (run after all the above)
SELECT VERSION() AS server_version, @@version_comment AS flavor;
SELECT @@collation_database AS db_collation;
SHOW TABLES;
SELECT COUNT(*) AS artists_rows FROM artists;
SELECT COUNT(*) AS albums_rows  FROM albums;
SELECT COUNT(*) AS merch_rows   FROM merchandise;
USE record_label;

-- albums: add price if missing
ALTER TABLE albums ADD COLUMN price DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER genre;

-- cart: add helpers for guest carts / active flag
ALTER TABLE cart ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER user_id;
ALTER TABLE cart ADD COLUMN session_token VARCHAR(64) DEFAULT NULL AFTER is_active;
CREATE INDEX idx_cart_user_active ON cart(user_id, is_active);
CREATE INDEX idx_cart_session ON cart(session_token);

-- roles + user_roles (many-to-many)
CREATE TABLE IF NOT EXISTS roles (
  role_id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(30) NOT NULL,
  PRIMARY KEY (role_id),
  UNIQUE KEY uq_roles_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS user_roles (
  user_id INT NOT NULL,
  role_id INT NOT NULL,
  PRIMARY KEY (user_id, role_id),
  CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
  CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT IGNORE INTO roles(name) VALUES ('admin'),('staff'),('user');

-- contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
  message_id INT NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  subject VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  is_handled TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (message_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- analytics
CREATE TABLE IF NOT EXISTS visits (
  visit_id BIGINT NOT NULL AUTO_INCREMENT,
  session_id VARCHAR(64),
  user_id INT NULL,
  path VARCHAR(255),
  referrer VARCHAR(255),
  ip VARCHAR(45),
  ua VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (visit_id),
  INDEX (created_at),
  INDEX (path),
  CONSTRAINT fk_visits_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- external cache
CREATE TABLE IF NOT EXISTS ext_cache (
  cache_key VARCHAR(100) NOT NULL,
  payload MEDIUMTEXT NOT NULL,
  fetched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (cache_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE IF NOT EXISTS analytics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  path VARCHAR(255) NOT NULL,
  ip VARCHAR(64) NOT NULL,
  user_agent VARCHAR(255) NOT NULL,
  referrer VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE merchandise ADD COLUMN artist_id INT NULL AFTER merchandise_id, ADD INDEX idx_merch_artist (artist_id), ADD CONSTRAINT fk_merch_artist FOREIGN KEY (artist_id) REFERENCES artists(artist_id) ON DELETE SET NULL ON UPDATE CASCADE; 