import BookForm from "@/app/books/form";

export default function EditBookPage({ params }: { params: { id: string } }) {
  return <BookForm bookId={parseInt(params.id)} />;
}
