import api from "./api";

export interface Subject {
  id?: number;
  description: string;
}

export const fetchSubjects = async (): Promise<Subject[]> => {
  const response = await api.get("/subjects");
  return response.data?.data || [];
};

export const fetchSubjectById = async (id: number): Promise<Subject> => {
  const response = await api.get(`/subjects/${id}`);
  return response.data;
};

export const createSubject = async (subject: Subject) => {
  const response = await api.post("/subjects", subject);
  return response.data;
};

export const updateSubject = async (id: number, subject: Subject) => {
  const response = await api.put(`/subjects/${id}`, subject);
  return response.data;
};

export const deleteSubject = async (id: number) => {
  await api.delete(`/subjects/${id}`);
};
