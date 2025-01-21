CREATE TABLE buses (
id INT AUTO_INCREMENT PRIMARY KEY,
bus_number VARCHAR(255) NOT NULL,
capacity INT NOT NULL
);

CREATE TABLE deliveries (
id INT AUTO_INCREMENT PRIMARY KEY,
delivery_date DATE NOT NULL,
bus_id INT,
FOREIGN KEY (bus_id) REFERENCES buses(id)
);

CREATE TABLE loads (
id INT AUTO_INCREMENT PRIMARY KEY,
delivery_id INT,
description VARCHAR(255),
weight INT,
FOREIGN KEY (delivery_id) REFERENCES deliveries(id)
);

ALTER TABLE deliveries
ADD COLUMN estimated_delivery_datetime DATETIME,
ADD COLUMN is_express BOOLEAN;

CREATE TABLE locations (
id INT AUTO_INCREMENT PRIMARY KEY,
delivery_id INT,
starting_point VARCHAR(255) NOT NULL,
ending_point VARCHAR(255) NOT NULL,
FOREIGN KEY (delivery_id) REFERENCES deliveries(id)
);