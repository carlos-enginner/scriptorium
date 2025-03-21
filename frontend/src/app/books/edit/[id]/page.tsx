"use client";

import { useState, useEffect } from "react";
import { useRouter, useParams } from "next/navigation";
import { Book, createBook, fetchBookById, updateBook, deleteBook } from "@/app/services/bookService";
import { fetchAuthors, Author } from "@/app/services/authorService";
import { fetchSubjects, Subject } from "@/app/services/subjectService";

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

  if (loading) return <p>Carregando...</p>;

  return (
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">{bookId ? "Editar Livro" : "Novo Livro"}</h2>

      <input
        type="text"
        placeholder="Título"
        value={book.title}
        onChange={(e) => setBook({ ...book, title: e.target.value })}
        className="w-full p-2 border rounded mb-2"
        required
      />

      <input
        type="text"
        placeholder="Editora"
        value={book.publisher}
        onChange={(e) => setBook({ ...book, publisher: e.target.value })}
        className="w-full p-2 border rounded mb-2"
        required
      />

      <input
        type="number"
        placeholder="Edição"
        value={book.edition}
        onChange={(e) => setBook({ ...book, edition: Number(e.target.value) })}
        className="w-full p-2 border rounded mb-2"
        required
      />

      <input
        type="number"
        placeholder="Ano de Publicação"
        value={book.publication_year || ""}
        onChange={(e) => setBook({ ...book, publication_year: parseInt(e.target.value) || 0 })}
        className="w-full p-2 border rounded mb-2"
        required
      />

      <input
        type="number"
        placeholder="Valor"
        step="0.01"
        value={book.price || ""}
        onChange={(e) => setBook({ ...book, price: parseFloat(e.target.value) || 0 })}
        className="w-full p-2 border rounded mb-2"
        required
      />

      {/* Seleção de Autor */}
      <div className="border rounded-lg p-4 mb-2">
        <h3 className="text-lg font-semibold mb-2">Selecione os autores</h3>
        <table className="w-full border-collapse border border-gray-300">
          <thead>
            <tr className="bg-gray-100">
              <th className="border p-2">Selecionar</th>
              <th className="border p-2">Nome</th>
            </tr>
          </thead>
          <tbody>
            {authors.map((author) => (
              <tr key={author.id} className="border">
                <td className="border p-2 text-center">
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
                </td>
                <td className="border p-2">{author.name}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Seleção de Assuntos */}
      <div className="border rounded-lg p-4 mb-2">
        <h3 className="text-lg font-semibold mb-2">Selecione os assuntos</h3>
        <table className="w-full border-collapse border border-gray-300">
          <thead>
            <tr className="bg-gray-100">
              <th className="border p-2">Selecionar</th>
              <th className="border p-2">Descrição</th>
            </tr>
          </thead>
          <tbody>
            {subjects.map((subject) => (
              <tr key={subject.id} className="border">
                <td className="border p-2 text-center">
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
                </td>
                <td className="border p-2">{subject.description}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div className="flex gap-2">
        <button
          type="submit"
          className="bg-green-500 text-white px-4 py-2 rounded w-full text-center cursor-pointer"
        >
          {bookId ? "Salvar" : "Cadastrar"}
        </button>

        {bookId && (
          <a
            href="#"
            onClick={(e) => {
              e.preventDefault();
              handleDelete();
            }}
            className="bg-red-500 text-white px-4 py-2 rounded w-full text-center cursor-pointer flex items-center justify-center"
          >
            Excluir
          </a>
        )}

        <a
          href="/books"
          className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center cursor-pointer flex items-center justify-center"
        >
          Cancelar
        </a>
      </div>


    </form>
  );
};

export default BookForm;
