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
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">Cadastre um novo assunto</p>

      <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          type="text"
          placeholder="Descrição do Assunto"
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <div className="flex gap-3 mt-4">
          <button
            type="submit"
            className="bg-blue-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-blue-600 transition"
          >
            Cadastrar
          </button>

          <a
            href="/subjects"
            className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition"
          >
            Cancelar
          </a>
        </div>
      </form>
    </div>

  );
};

export default NewSubjectPage;
