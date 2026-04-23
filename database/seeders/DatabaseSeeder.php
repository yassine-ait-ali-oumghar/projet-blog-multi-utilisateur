<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@blog.com'],
            ['name' => 'Super Admin', 'password' => \Illuminate\Support\Facades\Hash::make('admin123')]
        );

        // Create Test User
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => \Illuminate\Support\Facades\Hash::make('password')]
        );

        // Create some posts
        \App\Models\Post::create([
            'title' => 'Bienvenue sur notre nouveau blog !',
            'description' => "Ceci est le tout premier post de notre plateforme multi-utilisateur. Nous sommes ravis de vous accueillir ici pour partager vos idées et vos articles.\n\nN'hésitez pas à explorer les différentes fonctionnalités comme les likes, les reposts et le suivi d'utilisateurs.",
            'user_id' => $admin->id,
        ]);

        \App\Models\Post::create([
            'title' => 'Comment utiliser l\'assistant AI',
            'description' => "Notre assistant AI est là pour vous aider à rédiger de meilleurs articles. Posez-lui des questions sur le fonctionnement du blog ou demandez-lui des conseils d'écriture directement dans la barre latérale droite.",
            'user_id' => $admin->id,
        ]);

        \App\Models\Post::create([
            'title' => 'Ma première expérience avec Vue.js',
            'description' => "Vue.js est un framework incroyable pour construire des interfaces utilisateur réactives. Dans cet article, je partage mon parcours d'apprentissage et pourquoi j'ai choisi Vue pour ce projet.",
            'user_id' => $user->id,
        ]);
    }
}
