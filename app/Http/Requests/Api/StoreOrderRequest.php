<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * authorize() is FormRequest’s permission check, run before rules()
     * $this->user() — the authenticated user (from Sanctum Bearer token), or null if guest.
     * !== null — only allow the request if someone is logged in
     */

    // creating an order is allowed only for authenticated users
    public function authorize(): bool
    {
        //Returns false → Laravel responds 403 Forbidden and never hits your controller.
        //Returns true → validation runs, then the controller.
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ];
    }
}
