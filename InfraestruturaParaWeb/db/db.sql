CREATE DATABASE myapp;

USE myapp;

CREATE TABLE People (
                        id INT PRIMARY KEY AUTO_INCREMENT,
                        name VARCHAR(100),
                        age INT,
                        cell_phone VARCHAR(15)
);

INSERT INTO People (name, age, cell_phone) VALUES
    ('John', 30, '111-111-1111');


CREATE TABLE user (
    id int primary key AUTO_INCREMENT,
    user_name varchar (200) not null,
    password varchar (255) not null,
    created_at datetime not null,
    last_login datetime
);
