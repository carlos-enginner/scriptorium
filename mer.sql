CREATE DATABASE IF NOT EXISTS scriptorium

CREATE TABLE books (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(40) NOT NULL,
    editora VARCHAR(40) NOT NULL,
    edicao INTEGER NOT NULL,
    ano_publicacao DATE NOT NULL
);

CREATE TABLE actors (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(40) NOT NULL
);

CREATE TABLE subjects (
    id SERIAL PRIMARY KEY,
    description VARCHAR(20) NOT NULL
);

-- Tabela intermediária para a relação muitos-para-muitos entre books e actors
CREATE TABLE book_actors (
    book_id INTEGER NOT NULL,
    actor_id INTEGER NOT NULL,
    PRIMARY KEY (book_id, actor_id),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (actor_id) REFERENCES actors(id) ON DELETE CASCADE
);

-- Tabela intermediária para a relação muitos-para-muitos entre books e subjects
CREATE TABLE book_subjects (
    book_id INTEGER NOT NULL,
    subject_id INTEGER NOT NULL,
    PRIMARY KEY (book_id, subject_id),
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);
