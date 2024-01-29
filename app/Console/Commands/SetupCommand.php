<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetupCommand extends Command
{


    /**
     * Execute the console command.
     */
    protected $signature = 'setup';
    protected $description = 'Initialize the setup';

    public function handle()
    {
        $credentials = [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ];

        if (!Auth::attempt($credentials)) {
            $user = new User();
            $user->nom = 'Admin';
            $user->prenom = 'Admin';
            $user->cin = 'xxsx';
            $user->address = 'Admin';
            $user->date = Carbon::now();
            $user->sex = 'female';
            $user->role = 'doctor';
            $user->phone_number = '03666666';

            $user->email = $credentials['email'];
            $user->password = Hash::make($credentials['password']);
            $user->save();

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $adminToken = $user->createToken('admin-token');
                $basicToken = $user->createToken('basic-token', ['create', 'update']);
                $this->info('Admin Token: ' . $adminToken->plainTextToken);
                $this->info('Basic Token: ' . $basicToken->plainTextToken);
            }
        }
    }
}
