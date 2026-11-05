<?php

/*
 * Informations de l'église, affichées sur tout le site.
 * Modifiez ce fichier pour changer les horaires, le contact ou la chronologie.
 */
return [
    'name' => 'Église Évangélique de Peniel',
    'pastor' => 'Jean Paul MANKONI ILANGA Polidor',
    'address' => "130,Rue Kingabwa,Direction Assosa-Birmanie,C/Ngiri-Ngiri,Kinshsa-RDC",
    'phone' => '+243 897 315 323',
   

    // Liens et carte : modifiables dans l'administration (Réglages)
    'whatsapp' => null,
    'map_query' => null,
    'youtube_url' => null,
    'facebook_url' => null,
    'tiktok_url' => null,
    'instagram_url' => null,
    'live_url' => null,

    // Horaires des cultes. Photo du jour : public/images/cultes/<jour>.jpg
    'services' => [
        ['day' => 'Lundi', 'image' => 'images/cultes/Screenshot_20261101_202155_Samsung Browser.jpg', 'slots' => [
            ['time' => '14h00 à 17h30', 'title' => 'Culte des mamans', 'note' => 'Lundi solutions'],
            ['time' => '18h00 à 20h00', 'title' => 'Culte des papas', 'note' => "J'élève mes yeux vers les montagnes"],
        ]],
        ['day' => 'Mercredi', 'image' => 'images/cultes/Screenshot_20261101_202228_Samsung Browser.jpg', 'slots' => [
            ['time' => '17h00 à 19h30', 'title' => "Culte d'intercession et d'attaque continuelle sans recul"],
        ]],
        ['day' => 'Vendredi', 'image' => 'images/cultes/Screenshot_20261101_205238_Samsung Browser.jpg', 'slots' => [
            ['time' => '17h00 à 19h30', 'title' => "Culte d'étude biblique et Sainte-Cène"],
        ]],
        ['day' => 'Samedi', 'image' => 'images/cultes/1793453308736.jpg', 'slots' => [
            ['time' => '16h30 à 18h30', 'title' => 'Culte des jeunes et voyageurs'],
        ]],
        ['day' => 'Dimanche', 'image' => 'images/cultes/Screenshot_20261101_202235_Samsung Browser.jpg', 'slots' => [
            ['time' => '07h30 à 10h30', 'title' => "Culte d'actions de grâce"],
            ['time' => '10h45 à 12h30', 'title' => 'Culte des familles'],
        ]],
    ],

    // Vision de l'E.E.P. : 7 points
    'vision' => [
        'intro' => "La vision de l'Église Évangélique de Peniel, reçue par le Visionnaire, le Pasteur Jean-Paul MANKONI.",
        'points' => [
            ['title' => "La restauration de l'Église du Seigneur", 'refs' => 'Actes 2:37-47'],
            ['title' => "Le combat spirituel ou comment vaincre les forces du mal", 'refs' => 'Apocalypse 12:7-12 ; Ézéchiel 28:12-19 ; Ésaïe 14:12-17'],
            ['title' => "Parler du Directeur de la circulation de l'autorité divine, le Saint-Esprit", 'refs' => 'Jean 14:13-31'],
            ['title' => "La paternité de Dieu", 'refs' => 'Ésaïe 41:10 ; Apocalypse 21:7 ; Deutéronome 7:9'],
            ['title' => "Les signes du temps ou le temps prophétique ou l'eschatologie", 'refs' => 'Amos 3:7 ; Ézéchiel 33:1-20'],
            ['title' => "L'encadrement des jeunes", 'refs' => '1 Pierre 5:5 ; 1 Timothée 4:6-16 ; Psaumes 89:20 ; Luc 7:11 ; Psaumes 119:9'],
            ['title' => "Autres messages inspirés pour l'avancement du Royaume des cieux", 'refs' => '2 Pierre 1:20-21 ; 2 Timothée 3:16'],
        ],
    ],

    // Dates clés (page Historique)
    'timeline' => [
        ['year' => '1996', 'title' => 'Prisonnier du Christ', 'text' => 'Le Pasteur Jean Paul MANKONI ILANGA Polidor se consacre au Christ.'],
        ['year' => '2000', 'title' => 'Le repli sur Ngiri-Ngiri', 'text' => "Trois jours avant l'ouverture, le site prévu à Kintambo est confisqué. L'église s'installe avenue Yolo, n° 122, à Ngiri-Ngiri."],
        ['year' => '20 février 2000', 'title' => 'Le culte inaugural', 'text' => "8 fidèles au début du culte, 154 à la fin, sous une pluie battante reçue comme l'approbation divine."],
    ],
];
