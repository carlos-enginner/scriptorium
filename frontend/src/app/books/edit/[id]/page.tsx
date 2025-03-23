"use client";

import { useState, useEffect } from "react";
import { useRouter, useParams } from "next/navigation";
import { Book, createBook, fetchBookById, updateBook, deleteBook } from "@/app/services/bookService";
import { fetchAuthors, Author } from "@/app/services/authorService";
import { fetchSubjects, Subject } from "@/app/services/subjectService";
import { Loader2 } from "lucide-react";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import * as Yup from "yup";
import { toast } from "react-toastify";
import { NumericFormat } from 'react-number-format';


// Definindo o esquema de validação com Yup
const validationSchema = Yup.object().shape({
  title: Yup.string().required("O título é obrigatório").trim().max(40, "O título não pode ter mais de 40 caracteres"),
  publisher: Yup.string().required("A editora é obrigatória").trim().max(40, "A editora não pode ter mais de 40 caracteres"),
  edition: Yup.number().required("A edição é obrigatória").min(1, "A edição deve ser maior que 0"),
  publication_year: Yup.number()
    .required("O ano de publicação é obrigatório")
    .min(1000, "Ano de publicação inválido")
    .max(new Date().getFullYear(), `O ano de publicação não pode ser maior que ${new Date().getFullYear()}`),
  price: Yup.number().required("O preço é obrigatório").min(0, "O preço não pode ser negativo"),
  authors: Yup.array().min(1, "Ao menos um autor deve ser selecionado"),
  subjects: Yup.array().min(1, "Ao menos um assunto deve ser selecionado"),
});

const BookForm = () => {
  const router = useRouter();
  const params = useParams();
  const bookId = params?.id ? Number(params.id) : undefined;

  const [book, setBook] = useState<Book>({
    title: "",
    publisher: "",
    edition: 0,
    publication_year: 0,
    subjects: [],
    authors: [],
    price: 0,
  });

  const [authors, setAuthors] = useState<Author[]>([]);
  const [subjects, setSubjects] = useState<Subject[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [currentYear, setCurrentYear] = useState(Number);

  const { register, handleSubmit, setValue, formState: { errors } } = useForm({
    resolver: yupResolver(validationSchema),
  });

  const handlePriceChange = (value: any) => {
    setBook({ ...book, price: value });
    setValue("price", value);
  };

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      try {
        if (bookId) {
          const fetchedBook = await fetchBookById(bookId);
          setAuthors(await fetchAuthors());
          setSubjects(await fetchSubjects());
          setBook(fetchedBook);
          setValue("price", fetchedBook.price);
          const year = new Date().getFullYear();
          setCurrentYear(year);
        }
      } finally {
        setLoading(false);
      }
    };
    fetchData();
  }, [bookId]);

  const handleSubmitForm = async (data: Book) => {

    const selectedAuthors = Array.from(document.querySelectorAll('input[name="authors"]:checked'))
      .map(checkbox => checkbox.value);

    const selectedSubjects = Array.from(document.querySelectorAll('input[name="subjects"]:checked'))
      .map(checkbox => checkbox.value);

    if (bookId) {
      const modifiedData = {
        ...data,
        authors: selectedAuthors || [],
        subjects: selectedSubjects || [],
      };

      const result = await updateBook(bookId, modifiedData);
      if (result) {
        toast.info("Registro atualizado com sucesso");
      }
    }

  };

  const handleDelete = async () => {
    if (bookId && confirm("Tem certeza que deseja excluir este livro?")) {
      const result = await deleteBook(bookId);
      if (result) {
        router.push("/books");
      }
    }
  };

  if (loading)
    return (
      <div className="flex flex-col items-center justify-center min-h-screen">
        <Loader2 className="w-12 h-12 text-blue-500 animate-spin" />
        <p className="text-gray-600 mt-2 text-lg">Carregando...</p>
      </div>
    );

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <form onSubmit={handleSubmit(handleSubmitForm)} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          {...register("title")}
          type="text"
          placeholder="Título"
          value={book.title}
          maxLength={40}
          onChange={(e) => setBook({ ...book, title: e.target.value })}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.title ? 'border-red-500' : ''}`}
        />
        {errors.title && <p className="text-red-500 text-sm">{errors.title.message}</p>}

        <input
          {...register("publisher")}
          type="text"
          placeholder="Editora"
          value={book.publisher}
          maxLength={40}
          onChange={(e) => setBook({ ...book, publisher: e.target.value })}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.publisher ? 'border-red-500' : ''}`}
        />
        {errors.publisher && <p className="text-red-500 text-sm">{errors.publisher.message}</p>}

        <input
          {...register("edition")}
          type="number"
          placeholder="Edição"
          min={1}
          value={book.edition}
          onChange={(e) => setBook({ ...book, edition: Number(e.target.value) })}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.edition ? 'border-red-500' : ''}`}
        />
        {errors.edition && <p className="text-red-500 text-sm">{errors.edition.message}</p>}

        <input
          {...register("publication_year")}
          type="number"
          placeholder="Ano de publicação"
          maxLength={4}
          max={currentYear}
          value={book.publication_year || ""}
          onChange={(e) => setBook({ ...book, publication_year: parseInt(e.target.value) || 0 })}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.publication_year ? 'border-red-500' : ''}`}
        />
        {errors.publication_year && <p className="text-red-500 text-sm">{errors.publication_year.message}</p>}

        <NumericFormat
          {...register("price")}
          id="price"
          value={book.price}
          onValueChange={(values) => handlePriceChange(values.floatValue)} 
          thousandSeparator="."
          decimalSeparator=","
          allowNegative={false}
          decimalScale={2}
          prefix="R$ "
          fixedDecimalScale
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.price ? 'border-red-500' : ''}`}
          placeholder="Digite o valor"
        />
        {errors.price && <p className="text-red-500 text-sm">{errors.price.message}</p>}

        <div className="border rounded-lg p-4 mb-3 bg-gray-50 shadow-sm">
          <h3 className="text-lg font-semibold mb-2">Selecione os autores</h3>
          <div className="space-y-2">
            {authors.map((author) => (
              <label key={author.id} className="flex items-center gap-2 text-gray-700">
                <input
                  name="authors"
                  type="checkbox"
                  checked={book.authors?.includes(author.id)}
                  value={author.id}
                  onChange={() => {
                    // Atualiza o estado de autores de forma imutável
                    setBook((prevBook) => {
                      const isSelected = prevBook.authors?.includes(author.id);
                      const updatedAuthors = isSelected
                        ? prevBook.authors.filter((id) => id !== author.id)
                        : [...(prevBook.authors || []), author.id];

                      console.log(updatedAuthors);
                      return { ...prevBook, authors: updatedAuthors };
                    });
                  }}
                />
                {author.name}
              </label>
            ))}
          </div>
        </div>
        {errors.authors && <p className="text-red-500 text-sm">{errors.authors.message}</p>}

        <div className="border rounded-lg p-4 mb-3 bg-gray-50 shadow-sm">
          <h3 className="text-lg font-semibold mb-2">Selecione os assuntos</h3>
          <div className="space-y-2">
            {subjects.map((subject) => (
              <label key={subject.id} className="flex items-center gap-2 text-gray-700">
                <input
                  type="checkbox"
                  name="subjects"
                  checked={book.subjects?.includes(subject.id)}
                  value={subject.id}
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
          <button
            type="submit"
            className="bg-blue-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-blue-600 transition"
          >
            {bookId ? "Salvar" : "Cadastrar"}
          </button>

          {bookId && (
            <button
              onClick={(e) => {
                e.preventDefault();
                handleDelete();
              }}
              className="bg-red-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-red-600 transition"
            >
              Excluir
            </button>
          )}

          <a
            href="/books"
            className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition"
          >
            Cancelar
          </a>
        </div>
      </form>
    </div>
  );
};

export default BookForm;
