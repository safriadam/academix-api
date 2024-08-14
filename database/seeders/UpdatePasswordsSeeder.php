<?php

// database/seeders/UpdatePasswordsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UpdatePasswordsSeeder extends Seeder
{
    public function run()
    {
        // Example: Update all users' passwords to a hashed version
        $users = User::all();
        foreach ($users as $user) {
            // Replace 'your_plain_password' with the actual plain password
            $user->password = Hash::make('your_plain_password');
            $user->save();
        }
    }
}