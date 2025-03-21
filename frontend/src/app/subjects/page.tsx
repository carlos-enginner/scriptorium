"use client";

import { useEffect, useState } from "react";
import { fetchSubjects, deleteSubject, Subject } from "@/app/services/subjectService";
import Link from "next/link";

const SubjectsPage = () => {
  const [subjects, setSubjects] = useState<Subject[]>([]);

  useEffect(() => {
    fetchSubjects().then(setSubjects);
  }, []);

  const handleDelete = async (id: number) => {
    if (confirm("Tem certeza que deseja excluir este assunto?")) {
      await deleteSubject(id);
      setSubjects(subjects.filter((subject) => subject.id !== id));
    }
  };

  return (
    <div className="p-6 max-w-2xl mx-auto">
      <h2 className="text-xl font-bold mb-4">Lista de Assuntos</h2>
      <Link href="/subjects/new" className="bg-blue-500 text-white px-3 py-2 rounded">Novo Assunto</Link>
      <ul className="mt-4">
        {subjects.map((subject) => (
          <li key={subject.id} className="border-b py-2 flex justify-between">
            <span>{subject.description}</span>
            <div>
              <Link href={`/subjects/edit/${subject.id}`} className="text-blue-500 mr-2">Editar</Link>
              <button onClick={() => handleDelete(subject.id)} className="text-red-500">Excluir</button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default SubjectsPage;
