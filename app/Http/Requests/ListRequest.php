<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListRequest extends FormRequest
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
            'description' => 'required',
            'board' => 'required|array',
            'board.id' => 'required|exists:boards,id',
        ];
    }

    public function withValidator($validator)
    {
        $id = (int)$this->route('lists');

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'msg' => 'Ops! Falha ao validar dados.',
                'status' => false,
                'errors' => $validator->errors(),
                'url' => $id ? route('lists.update', ['lists' => $id]) : route('lists.store')
            ], 403));
        }
    }
}
