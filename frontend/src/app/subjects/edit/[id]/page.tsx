"use client";

import { useState, useEffect } from "react";
import { fetchSubjectById, updateSubject } from "@/app/services/subjectService";
import { useRouter, useParams } from "next/navigation";

const EditSubjectPage = () => {
  const { id } = useParams();
  const router = useRouter();
  const [description, setDescription] = useState<string>("");

  useEffect(() => {
    fetchSubjectById(Number(id)).then((subject) => setDescription(subject.description));
  }, [id]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    await updateSubject(Number(id), { description });
    router.push("/subjects");
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">Edite os detalhes do assunto</p>

      <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          type="text"
          placeholder="Descrição do Assunto"
          value={description ?? ""}
          onChange={(e) => setDescription(e.target.value)}
          className="w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200"
          required
        />

        <div className="flex gap-3 mt-4">
          <button
            type="submit"
            className="bg-blue-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-blue-600 transition"
          >
            Salvar
          </button>

          <button
            onClick={(e) => {
              e.preventDefault();
              handleDelete();
            }}
            className="bg-red-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-red-600 transition"
          >
            Excluir
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

export default EditSubjectPage;
