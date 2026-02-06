CREATE DATABASE carske_kapi;

CREATE TABLE countries(country_id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,country_flag VARCHAR(8) NOT NULL,country_name VARCHAR(32) NOT NULL,country_code VARCHAR(8) NOT NULL);
CREATE INDEX country_names ON countries(country_name);

CREATE TABLE cities(city_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,city_name VARCHAR(64) NOT NULL,postal_code VARCHAR(8) NOT NULL,country_id SMALLINT UNSIGNED,FOREIGN KEY (country_id) REFERENCES countries(country_id) ON DELETE SET NULL);

CREATE TABLE users(user_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,user_email VARCHAR(256) UNIQUE NOT NULL,user_password VARCHAR(64) NOT NULL,user_role VARCHAR(16) NOT NULL DEFAULT 'user',user_firstname VARCHAR(32) NOT NULL,user_lastname VARCHAR(32) NOT NULL,user_birth_date date NOT NULL,user_phone VARCHAR(16) NOT NULL,country_id SMALLINT UNSIGNED,FOREIGN KEY (country_id) REFERENCES countries(country_id) ON DELETE SET NULL,city_id INT UNSIGNED,FOREIGN KEY (city_id) REFERENCES cities(city_id) ON DELETE SET NULL,user_address VARCHAR(64) NOT NULL,user_details VARCHAR(256),user_newsletter VARCHAR(4) NOT NULL DEFAULT 'no',user_money_spend BIGINT UNSIGNED, user_theme VARCHAR(8) NOT NULL DEFAULT 'dark',user_status VARCHAR(8) NOT NULL DEFAULT 'pending',user_datetime_added datetime NOT NULL DEFAULT NOW());

CREATE TABLE users_image(users_image_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,user_id INT UNSIGNED,FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,users_image_status VARCHAR(8) NOT NULL DEFAULT 'empty',users_image_name VARCHAR(64) NOT NULL DEFAULT 'default_profile_picture.svg',users_image_datetime_added datetime NOT NULL DEFAULT NOW());

CREATE TABLE products(product_id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,category VARCHAR(16) NOT NULL,made_of VARCHAR(16) NOT NULL,quantity VARCHAR(4) NOT NULL,alchol_percantage VARCHAR(4) NOT NULL,old VARCHAR(4) NOT NULL,price INT UNSIGNED NOT NULL,stock SMALLINT UNSIGNED,ingradients VARCHAR(256) NOT NULL,details VARCHAR(256) NOT NULL,rewards VARCHAR(256) NOT NULL,product_datetime_added datetime NOT NULL DEFAULT NOW());

CREATE TABLE products_images(products_images_id SMALLINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,product_id SMALLINT UNSIGNED,FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,products_images_status VARCHAR(8) NOT NULL DEFAULT 'empty',products_images_name VARCHAR(64) NOT NULL DEFAULT 'default_products_image.svg',products_images_count VARCHAR(4) NOT NULL DEFAULT '0',products_images_datetime_added datetime NOT NULL DEFAULT NOW());

CREATE TABLE favorite_products(favorite_product_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,user_id INT UNSIGNED,FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,product_id SMALLINT UNSIGNED,FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,favorite_product_datetime_added datetime NOT NULL DEFAULT NOW());

CREATE TABLE purchased_products(purchased_product_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,user_id INT UNSIGNED,FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,product_id SMALLINT UNSIGNED,FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,product_datetime_purchase datetime NOT NULL DEFAULT NOW());

CREATE TABLE detects(detect_id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,ip_address VARCHAR(16) NOT NULL, operation_system VARCHAR(16) NOT NULL,device_type VARCHAR(8) NOT NULL,country_id SMALLINT UNSIGNED,FOREIGN KEY (country_id) REFERENCES countries(country_id) ON DELETE CASCADE,city_id INT UNSIGNED,FOREIGN KEY (city_id) REFERENCES cities(city_id) ON DELETE CASCADE,time_zone VARCHAR(32) NOT NULL,viewed_page VARCHAR(32) NOT NULL,user_agent VARCHAR(128) NOT NULL,detect_datetime_added datetime DEFAULT NOW() NOT NULL);

CREATE TABLE tokens(id_token INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,token CHAR(128) NOT NULL,token_date_expires datetime NOT NULL,token_datetime_added datetime DEFAULT NOW() NOT NULL);

