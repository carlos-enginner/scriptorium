"use client";

import { useState, useEffect } from "react";
import { useRouter, useParams } from "next/navigation";
import { Book, createBook, fetchBookById, updateBook, deleteBook } from "@/app/services/bookService";
import { fetchAuthors, Author } from "@/app/services/authorService";
import { fetchSubjects, Subject } from "@/app/services/subjectService";
import { Loader2 } from "lucide-react";

const BookForm = () => {
  const router = useRouter();
  const params = useParams();
  const bookId = params?.id ? Number(params.id) : undefined;

  const [book, setBook] = useState<Book>({
    title: "",
    publisher: "",
    edition: 0,
    publication_year: 0,
    subjects: [],
    authors: [],
    price: 0,
  });

  const [authors, setAuthors] = useState<Author[]>([]);
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        setAuthors(await fetchAuthors());
        setSubjects(await fetchSubjects());
        if (bookId) {
          const fetchedBook = await fetchBookById(bookId);
          setBook(fetchedBook);
        }
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, [bookId]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (bookId) {
      await updateBook(bookId, book);
    } else {
      await createBook(book);
    }
    router.push("/books");
  };

  const handleDelete = async () => {
    if (bookId && confirm("Tem certeza que deseja excluir este livro?")) {
      await deleteBook(bookId);
      router.push("/books");
    }
  };

  if (loading)
    return (
      <div className="flex flex-col items-center justify-center min-h-screen">
        <Loader2 className="w-12 h-12 text-blue-500 animate-spin" />
        <p className="text-gray-600 mt-2 text-lg">Carregando...</p>
      </div>
    );

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">{bookId ? "Edite os detalhes do livro" : "Cadastre um novo livro"}</p>

      <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          type="text"
          placeholder="Título"
          value={book.title}
          onChange={(e) => setBook({ ...book, title: e.target.value })}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <input
          type="text"
          placeholder="Editora"
          value={book.publisher}
          onChange={(e) => setBook({ ...book, publisher: e.target.value })}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <input
          type="number"
          placeholder="Edição"
          value={book.edition}
          onChange={(e) => setBook({ ...book, edition: Number(e.target.value) })}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <input
          type="number"
          placeholder="Ano de Publicação"
          value={book.publication_year || ""}
          onChange={(e) => setBook({ ...book, publication_year: parseInt(e.target.value) || 0 })}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <input
          type="number"
          placeholder="Valor"
          step="0.01"
          value={book.price || ""}
          onChange={(e) => setBook({ ...book, price: parseFloat(e.target.value) || 0 })}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        {/* Seleção de Autor */}
        <div className="border rounded-lg p-4 mb-3 bg-gray-50 shadow-sm">
          <h3 className="text-lg font-semibold mb-2">Selecione os autores</h3>
          <div className="space-y-2">
            {authors.map((author) => (
              <label key={author.id} className="flex items-center gap-2 text-gray-700">
                <input
                  type="checkbox"
                  checked={book.authors?.includes(author.id)}
                  onChange={() => {
                    const selectedAuthors = book.authors?.includes(author.id)
                      ? book.authors.filter((id) => id !== author.id)
                      : [...book.authors, author.id];
                    setBook({ ...book, authors: selectedAuthors });
                  }}
                />
                {author.name}
              </label>
            ))}
          </div>
        </div>

        {/* Seleção de Assuntos */}
        <div className="border rounded-lg p-4 mb-3 bg-gray-50 shadow-sm">
          <h3 className="text-lg font-semibold mb-2">Selecione os assuntos</h3>
          <div className="space-y-2">
            {subjects.map((subject) => (
              <label key={subject.id} className="flex items-center gap-2 text-gray-700">
                <input
                  type="checkbox"
                  checked={book.subjects?.includes(subject.id)}
                  onChange={() => {
                    const selectedSubjects = book.subjects?.includes(subject.id)
                      ? book.subjects.filter((id) => id !== subject.id)
                      : [...book.subjects, subject.id];
                    setBook({ ...book, subjects: selectedSubjects });
                  }}
                />
                {subject.description}
              </label>
            ))}
          </div>
        </div>

        <div className="flex gap-3 mt-4">
          <button
            type="submit"
            className="bg-blue-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-blue-600 transition"
          >
            {bookId ? "Salvar" : "Cadastrar"}
          </button>

          {bookId && (
            <button
              onClick={(e) => {
                e.preventDefault();
                handleDelete();
              }}
              className="bg-red-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-red-600 transition"
            >
              Excluir
            </button>
          )}

          <a
            href="/books"
            className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition"
          >
            Cancelar
          </a>
        </div>
      </form>
    </div>

  );
};

export default BookForm;
