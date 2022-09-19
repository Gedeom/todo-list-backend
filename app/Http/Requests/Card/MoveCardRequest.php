<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MoveCardRequest extends FormRequest
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
            'from_list_id' => 'required|exists:lists,id',
            'to_list_id' => 'required|exists:lists,id',
            'previous_order' => 'required',
            'next_order' => 'required',

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
                'url' => route('cards.move', ['cards' => $id])
            ], 403));
        }
    }
}
