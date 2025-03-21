"use client";

import { useState } from "react";
import { createAuthor } from "@/app/services/authorService";
import { useRouter } from "next/navigation";

const NewAuthorPage = () => {
  const [name, setName] = useState("");
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await createAuthor({ name });
    router.push("/authors");
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">Novo Autor</h2>
      <input type="text" placeholder="Nome do Autor" value={name} onChange={(e) => setName(e.target.value)} className="w-full p-2 border rounded mb-2" required />
      <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full">Cadastrar</button>
    </form>
  );
};

export default NewAuthorPage;
