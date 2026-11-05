<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakeAdmin extends Command
{
    protected $signature = 'peniel:admin {email} {name=Administrateur}';
    protected $description = "Crée (ou met à jour) un compte d'administration";

    public function handle(): int
    {
        $password = $this->secret('Mot de passe (8 caractères minimum)');

        $v = Validator::make(
            ['email' => $this->argument('email'), 'password' => $password],
            ['email' => 'required|email', 'password' => 'required|min:8']
        );

        if ($v->fails()) {
            $this->error($v->errors()->first());
            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => $this->argument('email')],
            ['name' => $this->argument('name'), 'password' => Hash::make($password)]
        );

        $this->info('Administrateur enregistré. Connexion : /connexion');
        return self::SUCCESS;
    }
}
