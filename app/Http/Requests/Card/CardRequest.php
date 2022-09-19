<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required',
            'expected_date' => 'required|date_format:Y-m-d H:i:s',
            'list' => 'required|array',
            'list.id' => 'required|exists:lists,id',
            'category' => 'required|array',
            'category.id' => 'required|exists:categories,id',
            'project' => 'required|array',
            'project.id' => 'required|exists:projects,id',
            'members' => 'nullable|array',
            'members.*.id' => 'required_with:members|exists:users,id',
        ];
    }

    public function withValidator($validator)
    {
        $id = (int)$this->route('cards');

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'msg' => 'Ops! Falha ao validar dados.',
                'status' => false,
                'errors' => $validator->errors(),
                'url' => $id ? route('cards.update', ['cards' => $id]) : route('cards.store')
            ], 403));
        }
    }
}
