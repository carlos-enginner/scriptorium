import { Metadata } from "next";

export const metadata: Metadata = {
    title: "Scriptorium - Gerenciamento de Livros",
    description: "Encontre e gerencie livros, autores e assuntos.",
};

export default function BooksLayout({ children }: { children: React.ReactNode }) {
    return <>{children}</>;
}