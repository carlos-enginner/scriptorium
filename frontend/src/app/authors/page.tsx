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
    <div className="p-6 max-w-2xl mx-auto">
      <h2 className="text-xl font-bold mb-4">Lista de Autores</h2>
      <Link href="/authors/new" className="bg-blue-500 text-white px-3 py-2 rounded">Novo Autor</Link>
      <ul className="mt-4">
        {authors.map((author) => (
          <li key={author.id} className="border-b py-2 flex justify-between">
            <span>{author.name}</span>
            <div>
              <Link href={`/authors/edit/${author.id}`} className="text-blue-500 mr-2">Editar</Link>
              <button onClick={() => handleDelete(author.id)} className="text-red-500">Excluir</button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default AuthorsPage;
