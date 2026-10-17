<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarTransaccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Semana 12: con sesión (web), comercio_id debe ser del usuario.
     * Semana 13: en la API todavía no hay sesión ni tokens, así que
     * $this->user() es null y el filtro por dueño se omite.
     * Parche temporal: la próxima clase (Sanctum) lo vuelve a cerrar.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $existeComercio = Rule::exists('comercios', 'id');

        if ($this->user()) {
            $existeComercio->where('user_id', $this->user()->id);
        }

        return [
            'comercio_id' => ['required', $existeComercio],
            'cliente_nombre' => 'required|string|min:3|max:255',
            'monto' => 'required|numeric|min:0.01|max:10000',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'comercio_id.required' => 'Debes seleccionar un comercio.',
            'comercio_id.exists' => 'El comercio seleccionado no es válido.',
            'cliente_nombre.required' => 'Debes indicar el nombre del cliente.',
            'cliente_nombre.min' => 'El nombre del cliente es demasiado corto.',
            'monto.required' => 'Debes indicar un monto.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a cero.',
            'monto.max' => 'El monto no puede superar los $10,000.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'cliente_nombre' => 'nombre del cliente',
            'monto' => 'monto de la transacción',
            'comercio_id' => 'comercio',
        ];
    }
}
