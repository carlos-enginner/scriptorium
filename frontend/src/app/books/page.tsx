"use client";

import { useState, useEffect } from "react";
import { Book, createBook, fetchBookById, updateBook } from "@/app/services/bookService";
import { fetchAuthors, Author } from "@/app/services/authorService";
import { fetchSubjects, Subject } from "@/app/services/subjectService";
import { useRouter } from "next/navigation";

interface BookFormProps {
  bookId?: number;
}

const BookForm = ({ bookId }: BookFormProps) => {
  const [book, setBook] = useState<Book>({
    title: "",
    publisher: "",
    edition: 0,
    publication_year: 0,
    subjects: [],
    authors: [],
    price: 0
  });

  const [authors, setAuthors] = useState<Author[]>([]);
  const [subjects, setSubjects] = useState<Subject[]>([]);

  const router = useRouter();

  useEffect(() => {
    fetchAuthors().then(setAuthors);
    fetchSubjects().then(setSubjects);
    if (bookId) fetchBookById(bookId).then(setBook);
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

  return (
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">{bookId ? "Editar Livro" : "Novo Livro"}</h2>

      <input type="text" placeholder="Título" value={book.title} onChange={(e) => setBook({ ...book, title: e.target.value })} className="w-full p-2 border rounded mb-2" required />

      <input type="text" placeholder="Editora" value={book.publisher} onChange={(e) => setBook({ ...book, publisher: e.target.value })} className="w-full p-2 border rounded mb-2" required />

      <input type="number" placeholder="Edição" value={book.edition} onChange={(e) => setBook({ ...book, edition: Number(e.target.value) })} className="w-full p-2 border rounded mb-2" required />

      <input type="text" placeholder="Ano de publicação" value={book.publication_year == 1 ? "" : book.publication_year} onChange={(e) => setBook({ ...book, publication_year: parseInt(e.target.value) || 0 })} className="w-full p-2 border rounded mb-2" required />

      <input type="number" placeholder="Valor" value={book.price == 0 ? "" : book.price}  onChange={(e) => setBook({ ...book, price: parseFloat(e.target.value) || 0 })} className="w-full p-2 border rounded mb-2" required />

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
            {authors?.map((author) => (
              <tr key={author.id} className="border">
                <td className="border p-2 text-center">
                  <input
                    type="checkbox"
                    checked={book.authors?.includes(author.id)}
                    onChange={(e) => {
                      const selectedAuthors = book.authors?.includes(author.id)
                        ? book.authors.filter((id) => id !== author.id) // Remove se já estiver selecionado
                        : [...book.authors, author.id]; // Adiciona se não estiver

                      setBook({ ...book, authors: selectedAuthors });
                    }}
                  />
                </td>
                <td className="border p-2">{author.name}</td>
              </tr>
            ))}
          </tbody>
        </table>

        {/* Botão para adicionar novo autor */}
        <button
          type="button"
          onClick={() => router.push("/authors")}
          className="mt-3 bg-blue-500 text-white px-4 py-2 rounded"
        >
          Gerenciar
        </button>
      </div>


      {/* Seleção de Assuntos */}
      <div className="border rounded-lg p-4 mb-2">
        <h3 className="text-lg font-semibold mb-2">Selecione os assuntos</h3>

        {/* Tabela de Assuntos */}
        <table className="w-full border-collapse border border-gray-300">
          <thead>
            <tr className="bg-gray-100">
              <th className="border p-2">Selecionar</th>
              <th className="border p-2">Descrição</th>
            </tr>
          </thead>
          <tbody>
            {subjects?.map((subject) => (
              <tr key={subject.id} className="border">
                <td className="border p-2 text-center">
                  <input
                    type="checkbox"
                    checked={book.subjects?.includes(subject.id) ?? false}
                    onChange={(e) => {
                      const selectedSubjects = book.subjects
                        ? book.subjects.includes(subject.id)
                          ? book.subjects.filter((id) => id !== subject.id) // Remove se já estiver
                          : [...book.subjects, subject.id] // Adiciona se não estiver
                        : [subject.id]; // Se for undefined, inicializa com o primeiro item

                      setBook({ ...book, subjects: selectedSubjects });
                    }}
                  />
                </td>
                <td className="border p-2">{subject.description}</td>
              </tr>
            ))}
          </tbody>
        </table>

        {/* Botão para adicionar novo assunto */}
        <button
          type="button"
          onClick={() => router.push("/subjects")}
          className="mt-3 bg-green-500 text-white px-4 py-2 rounded"
        >
          Gerenciar
        </button>
      </div>


      <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full">
        {bookId ? "Salvar Alterações" : "Cadastrar"}
      </button>

    </form>
  );
};

export default BookForm;
