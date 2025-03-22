"use client";

import { useState, useEffect } from "react";
import { fetchAuthorById, updateAuthor } from "@/app/services/authorService";
import { useRouter, useParams } from "next/navigation";

const EditAuthorPage = () => {
  const { id } = useParams();
  const router = useRouter();
  const [name, setName] = useState<string>("");

  useEffect(() => {
    fetchAuthorById(Number(id)).then((author) => setName(author.name));
  }, [id]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await updateAuthor(Number(id), { name });
    router.push("/authors");
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">Edite os detalhes do autor</p>

      <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          type="text"
          placeholder="Nome do autor"
          value={name ?? ""}
          maxLength={40}
          onChange={(e) => setName(e.target.value)}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <div className="flex gap-3 mt-4">
          <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-green-600 transition">
            Salvar Alterações
          </button>

          <a href="/authors" className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition">
            Cancelar
          </a>
        </div>
      </form>
    </div>

  );
};

export default EditAuthorPage;
