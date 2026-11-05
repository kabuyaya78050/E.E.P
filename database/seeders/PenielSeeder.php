<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class PenielSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['bienvenue-sur-le-nouveau-site', 'actualite', 'Bienvenue sur le nouveau site',
                "Retrouvez ici les actualités, les retraites, les séminaires et la possibilité de soutenir l'œuvre.",
                "Le site de l'Église Évangélique de Peniel est en ligne.\n\n[Texte à remplacer.]", null, null],
            ['retraite-spirituelle', 'retraite', 'Retraite spirituelle',
                'Un temps à l\'écart pour prier, se ressourcer et écouter la Parole.',
                "[Programme, lieu et inscriptions à compléter.]", now()->addMonth()->setTime(8, 0), '[Lieu de la retraite]'],
            ['seminaire-de-formation', 'seminaire', 'Séminaire de formation',
                'Enseignement et formation pour grandir dans la foi et dans le service.',
                "[Thème, intervenants et programme à compléter.]", now()->addMonths(2)->setTime(9, 0), 'Église Évangélique de Peniel'],
        ];

        foreach ($items as [$slug, $type, $title, $excerpt, $body, $date, $place]) {
            Event::updateOrCreate(['slug' => $slug], [
                'type' => $type, 'title' => $title, 'excerpt' => $excerpt,
                'body' => $body, 'starts_at' => $date, 'location' => $place, 'is_published' => true,
            ]);
        }
    }
}
