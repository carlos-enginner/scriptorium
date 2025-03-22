"use client";

import { useState } from "react";
import { Author, fetchAuthors, deleteAuthor } from "@/app/services/authorService";
import { Search, Plus, Edit, Trash } from "lucide-react";

const AuthorSearch = () => {
  const [search, setSearch] = useState("");
  const [authors, setAuthors] = useState<Author[]>([]);
  const [foundAuthor, setFoundAuthor] = useState<Author | null>(null);
  const [searched, setSearched] = useState(false);

  const handleSearch = async () => {
    if (search.trim().length === 0) return;

    setSearched(true);
    const results = await fetchAuthors(search);

    if (results.length === 1) {
      setFoundAuthor(results[0]);
      setAuthors([]);
    } else {
      setFoundAuthor(null);
      setAuthors(results);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">Encontre e gerencie seus autores</p>

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
          placeholder="Buscar autor"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="w-full px-3 py-2 text-lg border-none outline-none bg-transparent"
        />
        <a href="/authors/new" className="text-blue-500 hover:text-blue-600 transition flex items-center justify-center">
          <Plus size={24} />
        </a>
      </form>

      {foundAuthor ? (
        <div className="bg-white shadow-lg rounded-lg p-6 mt-6 w-full max-w-2xl">
          <h3 className="text-2xl font-semibold">{foundAuthor.name}</h3>

          <div className="flex justify-end mt-4 gap-4">
            <a href={`/authors/edit/${foundAuthor.id}`} className="text-blue-500 hover:text-blue-600 transition">
              <Edit size={20} />
            </a>
            <a
              href="#"
              onClick={async (e) => {
                e.preventDefault();
                if (confirm("Tem certeza que deseja excluir este autor?")) {
                  await deleteAuthor(foundAuthor.id);
                  setFoundAuthor(null);
                }
              }}
              className="text-red-500 hover:text-red-600 transition"
            >
              <Trash size={20} />
            </a>
          </div>
        </div>
      ) : authors.length > 0 ? (
        <div className="w-full max-w-2xl mt-6">
          <ul className="bg-white shadow-lg rounded-lg p-4">
            {authors.map((author) => (
              <li key={author.id} className="border-b last:border-none py-4 px-2 flex justify-between items-center">
                <span className="text-lg">{author.name}</span>
                <div className="flex gap-3">
                  <a href={`/authors/edit/${author.id}`} className="text-blue-500 hover:text-blue-600 transition">
                    <Edit size={20} />
                  </a>
                  <a
                    href="#"
                    onClick={async (e) => {
                      e.preventDefault();
                      if (confirm("Tem certeza que deseja excluir este autor?")) {
                        await deleteAuthor(author.id);
                        setAuthors(authors.filter((a) => a.id !== author.id));
                      }
                    }}
                    className="text-red-500 hover:text-red-600 transition"
                  >
                    <Trash size={20} />
                  </a>
                </div>
              </li>
            ))}
          </ul>
        </div>
      ) : searched ? (
        <p className="text-gray-500 mt-6">Nenhum autor encontrado.</p>
      ) : null}
    </div>
  );
};

export default AuthorSearch;
