"use client";

import { useState, useEffect } from "react";
import { fetchAuthorById, updateAuthor } from "@/app/services/authorService";
import { useRouter, useParams } from "next/navigation";

const EditAuthorPage = () => {
  const { id } = useParams();
  const router = useRouter();
  const [name, setName] = useState("");

  useEffect(() => {
    fetchAuthorById(Number(id)).then((author) => setName(author.name));
  }, [id]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await updateAuthor(Number(id), { name });
    router.push("/authors");
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">Editar Autor</h2>
      <input type="text" value={name} onChange={(e) => setName(e.target.value)} className="w-full p-2 border rounded mb-2" required />
      <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full">Salvar Alterações</button>
    </form>
  );
};

export default EditAuthorPage;
