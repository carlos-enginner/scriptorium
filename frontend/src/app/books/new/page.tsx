"use client";

import { useState, useEffect } from "react";
import { Book, createBook, fetchBookById, updateBook } from "@/app/services/bookService";
import { fetchAuthors, Author } from "@/app/services/authorService";
import { fetchSubjects, Subject } from "@/app/services/subjectService";
import { useRouter } from "next/navigation";
import { Settings } from "lucide-react";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import * as Yup from "yup";

// Definindo o esquema de validação com Yup
const validationSchema = Yup.object().shape({
  title: Yup.string().required("O título é obrigatório").max(40, "O título não pode ter mais de 40 caracteres"),
  publisher: Yup.string().required("A editora é obrigatória").max(40, "A editora não pode ter mais de 40 caracteres"),
  edition: Yup.number().required("A edição é obrigatória").min(1, "A edição deve ser maior que 0"),
  publication_year: Yup.number()
    .required("O ano de publicação é obrigatório")
    .min(1000, "Ano de publicação inválido")
    .max(new Date().getFullYear(), `O ano de publicação não pode ser maior que ${new Date().getFullYear()}`),
  price: Yup.number().required("O preço é obrigatório").min(0, "O preço não pode ser negativo"),
});

interface BookFormProps {
  bookId?: number;
}

const BookForm = ({ bookId }: BookFormProps) => {
  const [book, setBook] = useState<Book>({
    title: "",
    publisher: "",
    edition: 0,
    publication_year: 0,
    subjects: [],
    authors: [],
    price: 0
  });

  const [authors, setAuthors] = useState<Author[]>([]);
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [currentYear, setCurrentYear] = useState<number>(new Date().getFullYear());

  const router = useRouter();

  // Configuração do react-hook-form com yup
  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
  } = useForm({
    resolver: yupResolver(validationSchema),
  });

  useEffect(() => {
    fetchAuthors().then(setAuthors);
    fetchSubjects().then(setSubjects);

    const year = new Date().getFullYear();
    setCurrentYear(year);

    if (bookId) {
      fetchBookById(bookId).then((bookData) => {
        setBook(bookData);

        // Definir valores no formulário com setValue
        setValue("title", bookData.title);
        setValue("publisher", bookData.publisher);
        setValue("edition", bookData.edition);
        setValue("publication_year", bookData.publication_year);
        setValue("price", bookData.price);
      });
    }

  }, [bookId, setValue]);

  const onSubmit = async (data: Book) => {
    await createBook(data);
    // router.push("/books");
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <h1 className="text-5xl font-bold text-gray-800 mt-4">Scriptorium</h1>
      <p className="text-gray-600 mb-6">{bookId ? "Edite os detalhes do livro" : "Cadastre um novo livro"}</p>

      <form onSubmit={handleSubmit(onSubmit)} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          {...register("title")}
          type="text"
          placeholder="Título"
          maxLength={40}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.title ? 'border-red-500' : ''}`}
        />
        {errors.title && <p className="text-red-500 text-sm">{errors.title.message}</p>}

        <input
          {...register("publisher")}
          type="text"
          placeholder="Editora"
          maxLength={40}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.publisher ? 'border-red-500' : ''}`}
        />
        {errors.publisher && <p className="text-red-500 text-sm">{errors.publisher.message}</p>}

        <input
          {...register("edition")}
          type="number"
          placeholder="Edição"
          min={1}
          max={100}
          value={1}
          onChange={(e) => setBook({ ...book, edition: Number(e.target.value) })}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.edition ? 'border-red-500' : ''}`}
        />
        {errors.edition && <p className="text-red-500 text-sm">{errors.edition.message}</p>}

        <input
          {...register("publication_year")}
          type="number"
          placeholder="Ano de publicação"
          min={currentYear - 500}
          max={currentYear}
          value={currentYear}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.publication_year ? 'border-red-500' : ''}`}
        />
        {errors.publication_year && <p className="text-red-500 text-sm">{errors.publication_year.message}</p>}

        <input
          {...register("price")}
          type="number"
          placeholder="Valor"
          step="0.01"
          value={book.price || 1}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.price ? 'border-red-500' : ''}`}
        />
        {errors.price && <p className="text-red-500 text-sm">{errors.price.message}</p>}

        <div className="border rounded-lg p-4 mb-3 bg-gray-50 shadow-sm">
          <div className="flex items-center justify-between mb-2">
            <h3 className="text-lg font-semibold">Selecione os autores</h3>
            <button
              type="button"
              onClick={() => router.push("/authors")}
              className="text-gray-500 hover:text-blue-600 transition"
            >
              <Settings className="w-6 h-6 animate-spin-on-hover" />
            </button>
          </div>

          <div className="space-y-2">
            {authors.map((author) => (
              <label key={author.id} className="flex items-center gap-2 text-gray-700">
                <input
                  type="checkbox"
                  checked={book.authors?.includes(author.id) ?? false}
                  onChange={() => {
                    const selectedAuthors = book.authors.includes(author.id)
                      ? book.authors.filter((id) => id !== author.id)  // Remove o autor
                      : [...book.authors, author.id];  // Adiciona o autor

                    console.log(selectedAuthors)

                    setBook(book => ({
                      ...book,
                      authors: [1],
                    }));
                  }}
                />
                {author.name}
              </label>
            ))}
          </div>
        </div>
        {errors.authors && <p className="text-red-500 text-sm">{errors.authors.message}</p>}

        <div className="border rounded-lg p-4 mb-3 bg-gray-50 shadow-sm">
          <div className="flex items-center justify-between mb-2">
            <h3 className="text-lg font-semibold">Selecione os assuntos</h3>
            <button
              type="button"
              onClick={() => router.push("/subjects")}
              className="text-gray-500 hover:text-blue-600 transition"
            >
              <Settings className="w-6 h-6 animate-spin-on-hover" />
            </button>
          </div>

          <div className="space-y-2">
            {subjects.map((subject) => (
              <label key={subject.id} className="flex items-center gap-2 text-gray-700">
                <input
                  type="checkbox"
                  checked={book.subjects?.includes(subject.id) ?? false}
                  onChange={() => {
                    const selectedSubjects = book.subjects?.includes(subject.id)
                      ? book.subjects.filter((id) => id !== subject.id)
                      : [...book.subjects, subject.id];
                    setBook({ ...book, subjects: selectedSubjects });
                  }}
                />
                {subject.description}
              </label>
            ))}
          </div>
        </div>
        {errors.subjects && <p className="text-red-500 text-sm">{errors.subjects.message}</p>}

        <div className="flex gap-3 mt-4">
          <button type="submit" className="bg-blue-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-blue-600 transition">
            {bookId ? "Salvar Alterações" : "Cadastrar"}
          </button>

          <a href="/books" className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition">
            Cancelar
          </a>
        </div>
      </form>
    </div>
  );
};

export default BookForm;
