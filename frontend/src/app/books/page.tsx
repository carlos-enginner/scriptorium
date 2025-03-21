"use client";

import { useState, useEffect } from "react";
import { Book, createBook, fetchBookById, updateBook } from "@/app/services/bookService";
import { fetchAuthors, Author, createAuthor } from "@/app/services/authorService";
import { fetchSubjects, Subject, createSubject } from "@/app/services/subjectService";
import { useRouter } from "next/navigation";

interface BookFormProps {
  bookId?: number;
}

const BookForm = ({ bookId }: BookFormProps) => {
  const [book, setBook] = useState<Book>({
    title: "",
    publisher: "",
    edition: 1,
    publication_year: "",
    author_id: 0,
    subjects: [],
  });

  const [authors, setAuthors] = useState<Author[]>([]);
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [newAuthor, setNewAuthor] = useState("");
  const [newSubject, setNewSubject] = useState("");

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

  const handleAddAuthor = async () => {
    if (newAuthor) {
      const author = await createAuthor({ name: newAuthor });
      setAuthors([...authors, author]);
      setBook({ ...book, author_id: author.id! });
      setNewAuthor("");
    }
  };

  const handleAddSubject = async () => {
    if (newSubject) {
      const subject = await createSubject({ description: newSubject });
      setSubjects([...subjects, subject]);
      setBook({ ...book, subjects: [...book.subjects, subject.id!] });
      setNewSubject("");
    }
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">{bookId ? "Editar Livro" : "Novo Livro"}</h2>

      <input type="text" placeholder="Título" value={book.title} onChange={(e) => setBook({ ...book, title: e.target.value })} className="w-full p-2 border rounded mb-2" required />

      <input type="text" placeholder="Editora" value={book.publisher} onChange={(e) => setBook({ ...book, publisher: e.target.value })} className="w-full p-2 border rounded mb-2" required />

      <input type="number" placeholder="Edição" value={book.edition} onChange={(e) => setBook({ ...book, edition: Number(e.target.value) })} className="w-full p-2 border rounded mb-2" required />

      <input type="text" placeholder="Ano de Publicação" value={book.publication_year} onChange={(e) => setBook({ ...book, publication_year: e.target.value })} className="w-full p-2 border rounded mb-2" required />

      {/* Seleção de Autor */}
      <div className="flex items-center gap-2 mb-2">
        <select
          multiple
          value={book.authors} // Agora `authors` é uma array
          onChange={(e) =>
            setBook({ ...book, authors: Array.from(e.target.selectedOptions, (option) => Number(option.value)) })
          }
          className="w-full p-2 border rounded"
        >
          {authors?.map((author) => (
            <option key={author.id} value={author.id}>
              {author.name}
            </option>
          ))}
        </select>

        {/* Botão para gerenciar autores */}
        <button
          type="button"
          onClick={() => router.push("/authors")}
          className="bg-blue-500 text-white px-3 py-2 rounded"
        >
          Adicionar autor
        </button>
      </div>


      {/* Seleção de Assuntos */}
      <div className="flex items-center gap-2 mb-2">
        <select
          multiple
          value={book.subjects}
          onChange={(e) =>
            setBook({ ...book, subjects: Array.from(e.target.selectedOptions, (option) => Number(option.value)) })
          }
          className="w-full p-2 border rounded"
        >
          {subjects?.map((subject) => (
            <option key={subject.id} value={subject.id}>
              {subject.description}
            </option>
          ))}
        </select>

        {/* Botão para gerenciar assuntos */}
        <button
          type="button"
          onClick={() => router.push("/subjects")}
          className="bg-green-500 text-white px-3 py-2 rounded"
        >
          Gerenciar Assuntos
        </button>
      </div>


      <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full">
        {bookId ? "Salvar Alterações" : "Cadastrar"}
      </button>
    </form>
  );
};

export default BookForm;
