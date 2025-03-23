<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Contract\ValidatorInterface;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\ValidationException;
use Hyperf\Validation\Validator;

class BookRequest extends FormRequest
{
    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:40',
            'publisher' => 'required|string|max:40',
            'edition' => 'required|integer|min:1',
            'publication_year' => 'required|integer|min:1900|max:' . date('Y'),
            'price' => 'required|numeric|min:1',
            'authors' => 'required|array|min:1',
            'authors.*' => 'required|integer|min:1',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.string' => 'O título deve ser uma string.',
            'title.max' => 'O título não pode ter mais de 40 caracteres.',

            'publisher.required' => 'O nome da editora é obrigatório.',
            'publisher.string' => 'O nome da editora deve ser uma string.',
            'publisher.max' => 'O nome da editora não pode ter mais de 40 caracteres.',

            'edition.required' => 'A edição é obrigatória.',
            'edition.integer' => 'A edição deve ser um número inteiro.',
            'edition.min' => 'A edição deve ser no mínimo 1.',

            'publication_year.required' => 'O ano de publicação é obrigatório.',
            'publication_year.integer' => 'O ano de publicação deve ser um número inteiro.',
            'publication_year.min' => 'O ano de publicação deve ser no mínimo 1900.',
            'publication_year.max' => 'O ano de publicação não pode ser maior que ' . date('Y') . '.',

            'price.required' => 'O preço é obrigatório.',
            'price.numeric' => 'O preço deve ser um valor numérico.',
            'price.min' => 'O preço deve ser no mínimo 1.',

            'authors.required' => 'Os autores são obrigatórios.',
            'authors.array' => 'Os autores devem ser fornecidos como um array.',
            'authors.min' => 'Deve haver pelo menos 1 autor.',
            'authors.*.required' => 'Cada autor deve ser informado.',
            'authors.*.integer' => 'Cada autor deve ser um número inteiro.',
            'authors.*.min' => 'Cada autor deve ser maior que zero.',
            
            'subjects.required' => 'Os assuntos são obrigatórios.',
            'subjects.array' => 'Os assuntos devem ser fornecidos como um array.',
            'subjects.min' => 'Deve haver pelo menos 1 assunto.',
            'subjects.*.required' => 'Cada assunto deve ser informado.',
            'subjects.*.integer' => 'Cada assunto deve ser um número inteiro.',
            'subjects.*.min' => 'Cada assunto deve ser maior que zero.',
        ];
    }
}
