"use client";

import { useState } from "react";
import { Book, fetchBooks, deleteBook } from "@/app/services/bookService";
import { Search, BookPlus, Edit, Trash } from "lucide-react";

const BookSearch = () => {
  const [search, setSearch] = useState("");
  const [books, setBooks] = useState<Book[]>([]);
  const [foundBook, setFoundBook] = useState<Book | null>(null);
  const [searched, setSearched] = useState(false);

  const handleSearch = async () => {
    if (search.trim().length === 0) return;

    setSearched(true);
    const results = await fetchBooks(search);

    if (results.length === 1) {
      setFoundBook(results[0]);
      setBooks([]);
    } else {
      setFoundBook(null);
      setBooks(results);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-3xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">Encontre seus livros favoritos facilmente</p>

      <form
        onSubmit={(e) => {
          e.preventDefault();
          handleSearch();
        }}
        className="bg-white shadow-md rounded-full flex items-center w-full max-w-2xl px-4 py-2"
      >
        <button type="submit" className="text-gray-500 hover:text-gray-700 transition">
          <Search size={20} />
        </button>
        <input
          type="text"
          placeholder="Busca livro pelo nome"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="w-full px-3 py-2 text-lg border-none outline-none bg-transparent"
        />
        <a href="/books/new" className="text-blue-500 hover:text-blue-600 transition flex items-center justify-center">
          <BookPlus size={24} />
        </a>
      </form>

      {foundBook ? (
        <div className="bg-white shadow-lg rounded-lg p-6 mt-6 w-full max-w-2xl">
          <h3 className="text-2xl font-semibold">{foundBook.title}</h3>
          <p className="text-gray-600">
            <strong>Editora:</strong> {foundBook.publisher}
          </p>
          <p className="text-gray-600">
            <strong>Edição:</strong> {foundBook.edition}
          </p>
          <p className="text-gray-600">
            <strong>Ano:</strong> {foundBook.publication_year}
          </p>
          <p className="text-gray-600">
            <strong>Preço:</strong> R$ {(Number(foundBook.price) || 0).toFixed(2)}
          </p>

          <div className="flex justify-end mt-4 gap-4">
            <a href={`/books/edit/${foundBook.id}`} className="text-blue-500 hover:text-blue-600 transition">
              <Edit size={20} />
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
              className="text-blue-500 hover:text-blue-600 transition"
            >
              <Trash size={20} />
            </a>
          </div>
        </div>
      ) : books.length > 0 ? (
        <div className="w-full max-w-2xl mt-6">
          <ul className="bg-white shadow-lg rounded-lg p-4">
            {books.map((book) => (
              <li key={book.id} className="border-b last:border-none py-4 px-2 flex justify-between items-center">
                <div>
                  <h3 className="text-lg font-semibold">{book.title}</h3>
                  <p className="text-gray-600">
                    <strong>Editora:</strong> {book.publisher}
                  </p>
                </div>
                <div className="flex gap-3">
                  <a href={`/books/edit/${book.id}`} className="text-blue-500 hover:text-blue-600 transition">
                    <Edit size={20} />
                  </a>
                  <a
                    href="#"
                    onClick={async (e) => {
                      e.preventDefault();
                      if (confirm("Tem certeza que deseja excluir este livro?")) {
                        await deleteBook(book.id);
                        setBooks(books.filter((b) => b.id !== book.id));
                      }
                    }}
                    className="text-blue-500 hover:text-blue-600 transition"
                  >
                    <Trash size={20} />
                  </a>
                </div>
              </li>
            ))}
          </ul>
        </div>
      ) : searched ? (
        <p className="text-gray-500 mt-6">Nenhum livro encontrado.</p>
      ) : null}
    </div>

  );
};

export default BookSearch;
