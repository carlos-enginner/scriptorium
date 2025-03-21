import api from "./api";

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

export const fetchBooks = async (): Promise<Book[]> => {
  const response = await api.get("/books");
  return response.data?.data || [];
};

export const fetchBookById = async (id: number): Promise<Book> => {
  const response = await api.get(`/books/${id}`);
  return response.data?.data || [];
};

export const createBook = async (book: Book) => {
  const response = await api.post("/books", book);
  return response.data;
};

export const updateBook = async (id: number, book: Book) => {
  const response = await api.put(`/books/${id}`, book);
  return response.data;
};

export const deleteBook = async (id: number) => {
  await api.delete(`/books/${id}`);
};
