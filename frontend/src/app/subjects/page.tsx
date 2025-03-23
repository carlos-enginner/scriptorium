"use client";

import { useState } from "react";
import { Subject, fetchSubjects, deleteSubject } from "@/app/services/subjectService";
import { Search, Plus, Edit, Trash } from "lucide-react";

const SubjectSearch = () => {
  const [search, setSearch] = useState("");
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [foundSubject, setFoundSubject] = useState<Subject | null>(null);
  const [searched, setSearched] = useState(false);

  const handleSearch = async () => {
    if (search.trim().length === 0) return;

    setSearched(true);
    const results = await fetchSubjects(search);

    if (results.length === 1) {
      setFoundSubject(results[0]);
      setSubjects([]);
    } else {
      setFoundSubject(null);
      setSubjects(results);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
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
          placeholder="Buscar assunto"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          className="w-full px-3 py-2 text-lg border-none outline-none bg-transparent"
        />
        <a href="/subjects/new" className="text-blue-500 hover:text-blue-600 transition flex items-center justify-center">
          <Plus size={24} />
        </a>
      </form>

      {foundSubject ? (
        <div className="bg-white shadow-lg rounded-lg p-6 mt-6 w-full max-w-2xl">
          <h3 className="text-2xl font-semibold">{foundSubject.description}</h3>

          <div className="flex justify-end mt-4 gap-4">
            <a href={`/subjects/edit/${foundSubject.id}`} className="text-blue-500 hover:text-blue-600 transition">
              <Edit size={20} />
            </a>
            <a
              href="#"
              onClick={async (e) => {
                e.preventDefault();
                if (confirm("Tem certeza que deseja excluir este assunto?")) {
                  await deleteSubject(foundSubject.id);
                  setFoundSubject(null);
                }
              }}
              className="text-red-500 hover:text-red-600 transition"
            >
              <Trash size={20} />
            </a>
          </div>
        </div>
      ) : subjects.length > 0 ? (
        <div className="w-full max-w-2xl mt-6">
          <ul className="bg-white shadow-lg rounded-lg p-4">
            {subjects.map((subject) => (
              <li key={subject.id} className="border-b last:border-none py-4 px-2 flex justify-between items-center">
                <span className="text-lg">{subject.description}</span>
                <div className="flex gap-3">
                  <a href={`/subjects/edit/${subject.id}`} className="text-blue-500 hover:text-blue-600 transition">
                    <Edit size={20} />
                  </a>
                  <a
                    href="#"
                    onClick={async (e) => {
                      e.preventDefault();
                      if (confirm("Tem certeza que deseja excluir este assunto?")) {
                        await deleteSubject(subject.id);
                        setSubjects(subjects.filter((s) => s.id !== subject.id));
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
        <p className="text-gray-500 mt-6">Nenhum assunto encontrado.</p>
      ) : null}
    </div>
  );
};

export default SubjectSearch;
