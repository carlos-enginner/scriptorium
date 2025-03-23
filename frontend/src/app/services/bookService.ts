import api from "./api";
import { toast } from "react-toastify";

export interface Book {
  id?: number;
  title: string;
  publisher: string;
  edition: number;
  publication_year: number;
  authors: number[];
  subjects: number[];
  price: number;
}

export const fetchBooks = async (title?: string): Promise<Book[]> => {
  const response = await api.get("/books", {
    params: title ? { title } : {},
  });
  return response.data?.data || [];
};
export const showError = (message) => {
  toast.error(message)
};

export const fetchBookById = async (id: number): Promise<Book> => {
  const response = await api.get(`/books/${id}`);
  return response.data?.data || [];
};

export const createBook = async (book: Book) => {
  try {
    const response = await api.post("/books", book);
    return response.data;
  } catch (error) {
    toast.error("Algo deu errado!", {
      position: "top-right",
      autoClose: 5000, // 5 segundos
      hideProgressBar: false,
      closeOnClick: true,
      pauseOnHover: true,
      draggable: true,
      rtl: false
    });
  }
};

export const updateBook = async (id: number, book: Book) => {
  try {
    const response = await api.put(`/books/${id}`, book);
    return response.data;
  } catch (error) {
    toast.error("Algo deu errado");
  }
};

export const deleteBook = async (id: number) => {
  try {
    const response = await api.delete(`/books/${id}`);
    return response.data;
  } catch (error) {
    toast.error("Algo deu errado");
  }
};
