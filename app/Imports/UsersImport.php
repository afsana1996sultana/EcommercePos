<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel , WithHeadingRow ,WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $user = User::create([
            'name' => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'address' => $row['address'],
            'role' => 3,
            'status' => 1,
            'password' => Hash::make('12345678'),
            'customer_type' => 1,
        ]);
        Address::create([
            'user_id' => $user->id,
            'address' => $row['address'],
        ]);
        return $user;
    }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'required|numeric|digits:11|unique:users,phone',
            'address' => 'required|max:255',
        ];
    }
    public function onError(ValidationException $e)
    {
        $errors = $e->errors(); // Get errors
        return redirect()->back()->withErrors($errors)->withInput();
    }
}
