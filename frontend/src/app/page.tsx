"use client";

import { useState } from "react";
import { Menu, Book, Users, LayoutList, BarChart2 } from "lucide-react";

const menuItems = [
  { label: "Livros", path: "/books", icon: <Book size={20} />, color: "bg-blue-600", hover: "hover:bg-blue-700" },
  { label: "Autores", path: "/authors", icon: <Users size={20} />, color: "bg-green-600", hover: "hover:bg-green-700" },
  { label: "Assuntos", path: "/subjects", icon: <LayoutList size={20} />, color: "bg-purple-600", hover: "hover:bg-purple-700" },
  { label: "Relatório", path: "/reports", icon: <BarChart2 size={20} />, color: "bg-red-600", hover: "hover:bg-red-700" },
];

const Home = () => {
  const [activePage, setActivePage] = useState("/books");

  const handleNavigation = (path: string) => {
    setActivePage(path);
  };

  return (
    <div className="flex min-h-screen bg-gray-100">
      <aside className="w-64 bg-white shadow-lg p-4 flex flex-col gap-2 border-r">
        <h1 className="text-2xl font-bold text-gray-1000 mb-4 flex items-center gap-2">
          <Menu size={24} /> Scriptorium
        </h1>
        {menuItems.map(({ label, path, icon }) => (
          <button
            key={path}
            onClick={() => handleNavigation(path)}
            className={`w-full flex items-center gap-2 px-4 py-3 rounded-lg text-lg font-medium text-gray-700 hover:bg-gray-200 transition ${activePage === path ? "bg-gray-300" : ""}`}
          >
            {icon} {label}
          </button>
        ))}
      </aside>

      <main className="flex-1 p-6">
        <iframe src={activePage} className="w-full h-full border-none rounded-lg" title="Conteúdo da página"></iframe>
      </main>
    </div>
  );
};

export default Home;
