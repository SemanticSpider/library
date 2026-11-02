CREATE DATABASE library;

USE library;

CREATE TABLE IF NOT EXISTS authors (
    id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(64) NOT NULL,
    surname VARCHAR(64),
    birth_date DATE,
    death_date DATE,
    img text
);

CREATE TABLE IF NOT EXISTS books (
    id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    title VARCHAR(128),
    author BIGINT,
    publication DATE,
    url text,
    CONSTRAINT books_author FOREIGN KEY(author) REFERENCES authors(id)
);

CREATE TABLE IF NOT EXISTS bookinstance (
    id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    book BIGINT,
    CONSTRAINT book_instances FOREIGN KEY(book) REFERENCES books(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS genres (
    id BIGINT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    name VARCHAR(128),
    description TEXT
);

CREATE TABLE IF NOT EXISTS genre_book (
    id_genre BIGINT NOT NULL,
    id_book BIGINT NOT NULL,
    CONSTRAINT pk_genre_book PRIMARY KEY(id_book, id_genre),
    CONSTRAINT book_genre FOREIGN KEY(id_book) REFERENCES books(id) ON DELETE CASCADE,
    CONSTRAINT genre_book FOREIGN KEY(id_genre) REFERENCES genres(id) ON DELETE CASCADE
);

INSERT INTO authors(name, surname, birth_date, death_date, img) VALUES
    ('Fedor', 'Dostoevsky', '1821-11-11', '1881-01-28', './src/authors/Dostoevsky.jpg');

INSERT INTO books(title, author, publication, url) VALUES
    ('Idiot', 1, '1861-01-01', './src/изображение_2023-06-10_000140823.png');

INSERT INTO bookinstance (book) VALUES 
    (1), (1), (1);

INSERT INTO genres (name, description) VALUES
    ('roman', 'extended work of narrative fiction usually written in prose and published as a book');

INSERT INTO genre_book(id_genre, id_book) VALUES
    (1, 1);
