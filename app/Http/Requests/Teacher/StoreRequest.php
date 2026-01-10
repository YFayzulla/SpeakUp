<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Prepare phone for validation (add prefix if needed for uniqueness check)
        // However, standard validation usually checks the input value.
        // If you store '998' + phone in DB, you need a custom rule or prepareForValidation.
        // For simplicity, let's assume we check uniqueness against the stored format if possible,
        // or just rely on the input.
        // Since the controller adds '998', the DB has '998xxxxxxxxx'.
        // The input is just 'xxxxxxxxx'.
        // To check uniqueness correctly, we might need a closure or a custom rule,
        // but often 'unique:users,phone' works if the input matches the DB.
        // Here, input is 9 digits, DB is 12. This is the problem.
        
        return [
            'name' => 'required|string|max:255',
            'passport' => 'nullable|string|regex:/^[A-Z]{2}\d{7}$/|unique:users,passport|max:9',
            'date_born' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'phone' => [
                'required',
                'digits:9',
                // Custom unique check because DB has prefix '998' but input doesn't
                function ($attribute, $value, $fail) {
                    if (\App\Models\User::where('phone', '998' . $value)->exists()) {
                        $fail('The phone number has already been taken.');
                    }
                },
            ],
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10048',
            'percent' => 'nullable|integer|min:0|max:100',
            'room_id' => 'nullable|exists:rooms,id',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'passport.regex' => 'The passport must consist of 2 uppercase English letters followed by 7 digits.',
            'passport.unique' => 'This passport number already exists.',
            'phone.required' => 'The phone number is required.',
            'phone.digits' => 'The phone number must be exactly 9 digits.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'The image must be in one of the following formats: jpeg, png, jpg, gif, or svg.',
            'photo.max' => 'The image size must not exceed 20MB.',
            'percent.integer' => 'The percent must be an integer.',
            'percent.min' => 'The percent must be at least 0.',
            'percent.max' => 'The percent must not exceed 100.',
            'room_id.exists' => 'The selected room does not exist.',
        ];
    }
}
