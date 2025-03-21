"use client";

import { useState, useEffect } from "react";
import { Book, fetchBooks } from "@/app/services/bookService";

const BookSearch = () => {
  const [search, setSearch] = useState("");
  const [books, setBooks] = useState<Book[]>([]);
  const [foundBook, setFoundBook] = useState<Book | null>(null);

  useEffect(() => {
    fetchBooks().then(setBooks); // Carregar todos os livros inicialmente
  }, []);

  const handleSearch = async () => {
    if (!search.trim()) {
      setFoundBook(null);
      fetchBooks().then(setBooks);
      return;
    }

    const results = await fetchBooks(search);
    if (results.length === 1) {
      setFoundBook(results[0]); // Se encontrar apenas um livro, exibe ele no card
    } else {
      setFoundBook(null);
      setBooks(results);
    }
  };

  return (
    <div>
      {/* Campo de busca */}
      <div className="flex gap-2 mb-4">
        <input
          type="text"
          placeholder="Buscar por título..."
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="border p-2 rounded w-full"
        />
        <button onClick={handleSearch} className="bg-blue-500 text-white px-4 py-2 rounded">
          Pesquisar
        </button>
      </div>

      {foundBook ? (
        <div className="border rounded-lg p-4 shadow-md bg-white relative">
          <h3 className="text-xl font-bold">{foundBook.title}</h3>
          <p><strong>Editora:</strong> {foundBook.publisher}</p>
          <p><strong>Edição:</strong> {foundBook.edition}</p>
          <p><strong>Ano de Publicação:</strong> {foundBook.publication_year}</p>
          <p><strong>Preço:</strong> R$ {(Number(foundBook.price) || 0).toFixed(2)}</p>
          <p><strong>Autores:</strong> {foundBook?.authors.join(", ")}</p>
          <p><strong>Assuntos:</strong> {foundBook?.subjects.join(", ")}</p>

          {/* Links de Ação */}
          <div className="mt-4 flex gap-2">
            <a
              href={`/books/edit/${foundBook.id}`}
              className="bg-yellow-500 text-white px-4 py-2 rounded inline-block cursor-pointer"
            >
              Editar
            </a>

            <a
              href="#"
              onClick={async (e) => {
                e.preventDefault();
                if (confirm("Tem certeza que deseja excluir este livro?")) {
                  await deleteBook(foundBook.id);
                  setFoundBook(null);
                }
              }}
              className="bg-red-500 text-white px-4 py-2 rounded inline-block cursor-pointer"
            >
              Excluir
            </a>
          </div>
        </div>
      ) : (
        <ul className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {books.map((book) => (
            <li key={book.id} className="border rounded-lg p-4 shadow-md bg-white relative">
              <h3 className="text-xl font-bold">{book.title}</h3>
              <p><strong>Editora:</strong> {book.publisher}</p>
              <p><strong>Edição:</strong> {book.edition}</p>
              <p><strong>Ano de Publicação:</strong> {book.publication_year}</p>
              <p><strong>Preço:</strong> R$ {(Number(book.price) || 0).toFixed(2)}</p>

              {/* Links de Ação */}
              <div className="mt-4 flex gap-2">
                <a
                  href={`/books/edit/${book.id}`}
                  className="bg-yellow-500 text-white px-4 py-2 rounded inline-block cursor-pointer"
                >
                  Editar
                </a>

                <a
                  href="#"
                  onClick={async (e) => {
                    e.preventDefault();
                    if (confirm("Tem certeza que deseja excluir este livro?")) {
                      await deleteBook(book.id);
                      setBooks(books.filter((b) => b.id !== book.id)); // Remove da listagem
                    }
                  }}
                  className="bg-red-500 text-white px-4 py-2 rounded inline-block cursor-pointer"
                >
                  Excluir
                </a>
              </div>
            </li>
          ))}
        </ul>
      )}


    </div>
  );
};

export default BookSearch;

