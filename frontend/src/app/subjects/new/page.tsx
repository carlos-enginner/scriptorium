"use client";

import { useState } from "react";
import { createSubject } from "@/app/services/subjectService";
import { useRouter } from "next/navigation";

const NewSubjectPage = () => {
  const [description, setDescription] = useState("");
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await createSubject({ description });
    router.push("/subjects");
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">Novo Assunto</h2>
      <input type="text" placeholder="Descrição do Assunto" value={description} onChange={(e) => setDescription(e.target.value)} className="w-full p-2 border rounded mb-2" required />
      <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full">Cadastrar</button>
    </form>
  );
};

export default NewSubjectPage;
