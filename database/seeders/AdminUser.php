<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->email = 'yavor@grind.bg';
        $user->name = 'Yavor Grind.bg';
        $user->password = bcrypt('123456789');
        $user->acc_type = 10;
        $user->save();
        echo "Админ създаден \n";
    }
}
