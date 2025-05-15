SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Table users
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  user_type VARCHAR(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table products
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  description TEXT,
  image VARCHAR(100) NOT NULL,
  stock INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table cart
CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  quantity INT NOT NULL,
  image VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table orders
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  number VARCHAR(12) NOT NULL,
  email VARCHAR(100) NOT NULL,
  method VARCHAR(50) NOT NULL,
  address VARCHAR(500) NOT NULL,
  total_products VARCHAR(1000) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  placed_on DATETIME NOT NULL,
  payment_status VARCHAR(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table order_details
CREATE TABLE order_details (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  product_id INT,
  quantity INT,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Table message
CREATE TABLE message (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  number VARCHAR(12) NOT NULL,
  message VARCHAR(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table historique_commandes_annulees
CREATE TABLE historique_commandes_annulees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT,
  user_id INT,
  date_commande DATETIME,
  statut VARCHAR(20),
  total DECIMAL(10,2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DELIMITER //

CREATE PROCEDURE afficher_details_commande(IN p_order_id INT)
BEGIN
    SELECT 
        p.name AS nom_produit,
        p.price,
        od.quantity,
        (p.price * od.quantity) AS total_ligne
    FROM order_details od
    JOIN products p ON od.product_id = p.id
    WHERE od.order_id = p_order_id;

    SELECT 
        SUM(p.price * od.quantity) AS total_a_payer
    FROM order_details od
    JOIN products p ON od.product_id = p.id
    WHERE od.order_id = p_order_id;
END //

CREATE PROCEDURE finaliser_commande(IN p_order_id INT)
BEGIN
    UPDATE orders
    SET payment_status = 'validee'
    WHERE id = p_order_id;
END //

CREATE PROCEDURE afficher_historique_commandes(IN p_user_id INT)
BEGIN
    SELECT 
        o.id AS id_commande,
        o.placed_on AS date_commande,
        o.payment_status AS statut,
        SUM(p.price * od.quantity) AS total_commande
    FROM orders o
    JOIN order_details od ON o.id = od.order_id
    JOIN products p ON od.product_id = p.id
    WHERE o.user_id = p_user_id
    GROUP BY o.id, o.placed_on, o.payment_status
    ORDER BY o.placed_on DESC;
END //

DELIMITER ;
DELIMITER //

CREATE TRIGGER maj_stock_apres_validation
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'validee' THEN
        UPDATE products p
        JOIN order_details od ON p.id = od.product_id
        SET p.stock = p.stock - od.quantity
        WHERE od.order_id = NEW.id;
    END IF;
END //

CREATE TRIGGER restaurer_stock_apres_annulation
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'annulee' THEN
        UPDATE products p
        JOIN order_details od ON p.id = od.product_id
        SET p.stock = p.stock + od.quantity
        WHERE od.order_id = NEW.id;
    END IF;
END //

CREATE TRIGGER trace_annulation_commande
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'annulee' THEN
        INSERT INTO historique_commandes_annulees (order_id, user_id, date_commande, statut, total)
        SELECT 
            OLD.id,
            OLD.user_id,
            OLD.placed_on,
            'annulee',
            SUM(p.price * od.quantity)
        FROM order_details od
        JOIN products p ON od.product_id = p.id
        WHERE od.order_id = OLD.id
        GROUP BY od.order_id;
    END IF;
END //

DELIMITER ;


