"use client";

import { useEffect, useState } from "react";
import { useForm } from "react-hook-form";
import { fetchAuthorById, updateAuthor } from "@/app/services/authorService";
import { useRouter, useParams } from "next/navigation";
import * as Yup from "yup";
import { yupResolver } from "@hookform/resolvers/yup";

const validationSchema = Yup.object().shape({
  name: Yup.string().trim()
    .required("Nome é obrigatório")
    .max(40, "O nome não pode ter mais de 40 caracteres"),
});

const EditAuthorPage = () => {
  const { id } = useParams();
  const router = useRouter();
  
  const [author, setAuthor] = useState<{ name: string }>({ name: "" });
  
  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: yupResolver(validationSchema),
  });

  useEffect(() => {
    fetchAuthorById(Number(id)).then((authorData) => setAuthor(authorData));
  }, [id]);

  const handleFormSubmit = async (data: { name: string }) => {
    await updateAuthor(Number(id), { name: data.name });
    router.push("/authors");
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <form onSubmit={handleSubmit(handleFormSubmit)} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          {...register("name")}
          type="text"
          placeholder="Nome do autor"
          defaultValue={author.name}
          maxLength={40}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.name ? 'border-red-500' : ''}`}
        />
        {errors.name && (
          <p className="text-red-500 text-sm">{errors.name.message}</p>
        )}

        <div className="flex gap-3 mt-4">
          <button type="submit" className="bg-green-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-green-600 transition">
            Salvar
          </button>

          <a href="/authors" className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition">
            Cancelar
          </a>
        </div>
      </form>
    </div>
  );
};

export default EditAuthorPage;
