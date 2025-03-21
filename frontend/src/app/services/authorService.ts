import api from './api';

export interface Author {
  id: number;
  name: string;
}

export const fetchAuthors = async (): Promise<Author[]> => {
  const response = await api.get('/authors');
  return response.data?.data || [];
};

export const fetchAuthorById = async (id: number): Promise<Author> => {
  const response = await api.get(`/authors/${id}`);
  return response.data?.data || [];
};

export const createAuthor = async (author: Omit<Author, 'id'>): Promise<Author> => {
  const response = await api.post('/authors', author);
  return response.data;
};

export const updateAuthor = async (id: number, author: Omit<Author, 'id'>): Promise<Author> => {
  const response = await api.put(`/authors/${id}`, author);
  return response.data;
};

export const deleteAuthor = async (id: number): Promise<void> => {
  await api.delete(`/authors/${id}`);
};
