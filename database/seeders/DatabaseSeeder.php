<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Offre;
use App\Models\Profil;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->count(2)->admin()->create();

        User::factory()
            ->count(5)
            ->recruteur()
            ->has(Offre::factory()->count(rand(2, 3)), 'offres')
            ->create();

        User::factory()
            ->count(10)
            ->state(['role' => 'candidat'])
            ->create()
            ->each(function ($user) {
                $user->profile()->create(
                    Profil::factory()->make()->toArray()
                );
            });
    }
}
