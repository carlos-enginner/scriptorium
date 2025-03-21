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
    <form onSubmit={handleSubmit} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md">
      <h2 className="text-xl font-bold mb-4">Editar Assunto</h2>
      <input type="text" value={description ?? ""} onChange={(e) => setDescription(e.target.value)} className="w-full p-2 border rounded mb-2" required />
      <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full">Salvar</button>
    </form>
  );
};

export default EditSubjectPage;
