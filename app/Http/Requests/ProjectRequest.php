<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectRequest extends FormRequest
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
            'members' => 'nullable|array',
            'members.*.id' => 'required_with:members|exists:users,id',
        ];
    }

    public function withValidator($validator)
    {
        $id = (int)$this->route('boards');

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'msg' => 'Ops! Falha ao validar dados.',
                'status' => false,
                'errors' => $validator->errors(),
                'url' => $id ? route('boards.update', ['boards' => $id]) : route('boards.store')
            ], 403));
        }
    }
}
