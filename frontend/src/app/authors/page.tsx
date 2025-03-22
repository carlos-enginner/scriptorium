"use client";

import { useEffect, useState } from "react";
import { fetchAuthors, deleteAuthor, Author } from "@/app/services/authorService";
import Link from "next/link";

const AuthorsPage = () => {
  const [authors, setAuthors] = useState<Author[]>([]);

  useEffect(() => {
    fetchAuthors().then(setAuthors);
  }, []);

  const handleDelete = async (id: number) => {
    if (confirm("Tem certeza que deseja excluir este autor?")) {
      await deleteAuthor(id);
      setAuthors(authors.filter((author) => author.id !== id));
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">Gerencie os assuntos cadastrados</p>

      <div className="p-6 max-w-2xl mx-auto border rounded-lg bg-white shadow-md w-full">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-2xl font-semibold text-gray-800">Lista de Assuntos</h2>
          <Link href="/subjects/new" className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Novo Assunto
          </Link>
        </div>

        <ul className="divide-y divide-gray-200">
          {subjects.map((subject) => (
            <li key={subject.id} className="py-3 flex justify-between items-center">
              <span className="text-gray-700">{subject.description}</span>
              <div className="flex gap-2">
                <Link href={`/subjects/edit/${subject.id}`} className="text-blue-500 hover:underline">
                  Editar
                </Link>
                <button
                  onClick={() => handleDelete(subject.id)}
                  className="text-red-500 hover:underline"
                >
                  Excluir
                </button>
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>

  );
};

export default AuthorsPage;
