"use client";

import { useForm } from "react-hook-form";
import { createSubject } from "@/app/services/subjectService";
import { useRouter } from "next/navigation";
import * as Yup from "yup";
import { yupResolver } from "@hookform/resolvers/yup";

// Defina o esquema de validação com o Yup
const validationSchema = Yup.object().shape({
  description: Yup.string().trim()
    .required("A descrição é obrigatória")
    .max(40, "A descrição não pode ter mais de 40 caracteres"),
});

const NewSubjectPage = () => {
  const router = useRouter();

  // Usando React Hook Form
  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: yupResolver(validationSchema),
  });

  const handleFormSubmit = async (data: { description: string }) => {
    await createSubject({ description: data.description });
    router.push("/subjects");
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-100 px-4">
      <form onSubmit={handleSubmit(handleFormSubmit)} className="p-6 max-w-lg mx-auto border rounded-lg bg-white shadow-md w-full">
        <input
          {...register("description")}
          type="text"
          placeholder="Descrição do assunto"
          maxLength={40}
          className={`w-full p-3 border rounded mb-3 text-gray-700 shadow-sm focus:ring focus:ring-blue-200 ${errors.description ? 'border-red-500' : ''}`}
        />
        {errors.description && (
          <p className="text-red-500 text-sm">{errors.description.message}</p>
        )}

        <div className="flex gap-3 mt-4">
          <button
            type="submit"
            className="bg-blue-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-blue-600 transition"
          >
            Cadastrar
          </button>

          <a
            href="/subjects"
            className="bg-gray-500 text-white px-4 py-2 rounded w-full text-center font-semibold hover:bg-gray-600 transition"
          >
            Cancelar
          </a>
        </div>
      </form>
    </div>
  );
};

export default NewSubjectPage;
