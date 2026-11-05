@extends('layouts.app')
@section('title', "Notre histoire | " . config('church.name'))

@section('content')
<section class="page-head">
    <div class="container">
        <h1>Notre histoire</h1>
        <p>Comment Dieu a conduit l'Église Évangélique de Peniel jusqu'ici.</p>
    </div>
</section>
@include('pages._tabs')

<section class="section">
    <div class="container narrow prose">
        <p class="lead">Le 20 février 2000, sous un soleil ardent, Dieu ordonna l'accomplissement de ce qu'il avait prédit à son serviteur, le Pasteur Jean Paul MANKONI ILANGA Polidor, prisonnier du Christ depuis 1996.</p>

        <h2>L'appel et le nom Peniel</h2>
        <p>Dès l'âge de 12 ans, 21 fois sous un manguier, l'Élu reçut un message par voix audible. Dieu lui donna le livre et le verset d'où est tiré le nom de PENIEL. Ce nom reflète la vision qu'eut l'homme de Dieu à 4 heures du matin : il se battait avec une personne qui lui fracturait la jambe, comme Jacob.</p>
        <p>L'église Peniel est née de la foi ferme, de la volonté farouche, de l'obéissance et de l'espérance en l'Éternel, après 38 ans (1987).</p>

        <h2>Le repli sur Ngiri-Ngiri</h2>
        <p>Trois jours avant l'ouverture à l'emplacement prévu à Kintambo, l'autorité étatique de l'époque confisqua ce site par un assaut de troupes blindées. Après examen de la situation à la résidence de la famille MAYELE, le Pasteur décida de descendre à Ngiri-Ngiri, sur l'avenue Yolo, numéro 122, avec l'accord de l'héritier Jean Baptiste MBALA, qui céda cette parcelle pour les réunions de prière.</p>
        <p>Une nuit durant, des travaux intenses furent effectués pour rendre cet espace propre et fréquentable. La date fut respectée, malgré les doutes des uns et des autres, des proches, et même de certains serviteurs de Dieu qui connaissaient la date prédite.</p>

        <h2>Le culte inaugural</h2>
        <p>Le culte commença timidement à 8 heures, avec 8 fidèles à ciel ouvert. Après la location de 200 chaises, il se termina avec 154 fidèles, au terme d'une journée puissante en révélations, en miracles et en prodiges : la guérison d'un aveugle, divers cas de sorcellerie ramenés à la normale, 9 cas de séropositivité guéris et confirmés par des tests médicaux.</p>
        <p>Ce culte s'acheva sous une pluie battante, qui signifie pour les Penieliennes et les Penieliens, selon le Rhéma, « l'approbation divine ».</p>
    </div>

    <div class="container video-wrap">
        <figure class="video-card">
            <video controls preload="metadata" playsinline poster="{{ asset('images/culte-inaugural.jpg') }}">
                <source src="{{ asset('videos/culte-inaugural.mp4') }}" type="video/mp4">
                Votre navigateur ne peut pas lire cette vidéo.
                <a href="{{ asset('videos/culte-inaugural.mp4') }}">Télécharger la vidéo</a>.
            </video>
            <figcaption>Images du culte inaugural du 20 février 2000, à Ngiri-Ngiri.</figcaption>
        </figure>
    </div>

    <div class="container narrow prose">
        <h2>Une vision préparée</h2>
        <p>Connaissant sa vision et l'église que Dieu allait lui confier, il s'entraînait à enseigner dans les différents lieux de retraite, utilisant un tronc d'arbre comme chaire. Il se rendait souvent à Maluku (Kinshasa), près du fleuve Congo, baptisé « fleuve Hava » par le Révérend Jean Paul MANKONI ILANGA.</p>
    </div>
</section>

<section class="section section-alt">
    <div class="container narrow">
        <h2>Les dates clés</h2>
        <ol class="timeline">
            @foreach (config('church.timeline') as $step)
                <li>
                    <span class="year">{{ $step['year'] }}</span>
                    <h3>{{ $step['title'] }}</h3>
                    <p>{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

<section class="section" id="pasteur">
    <div class="container split">
        <div class="photo-frame photo-portrait">
            @if (file_exists(public_path('images/jpm2.jpg')))
                <img src="{{ asset('images/jpm2.jpg') }}" alt="Pasteur {{ config('church.pastor') }}">
            @else
                <span>Photo du pasteur</span>
            @endif
        </div>
        <div>
            <h2>Le pasteur</h2>
            <h3>Pasteur {{ config('church.pastor') }}</h3>
            <p>Serviteur de Dieu, prisonnier du Christ depuis 1996, il a reçu la vision de l'église Peniel et l'a vue naître le 20 février 2000.</p>
            <p>Le parcours d’un serviteur dévoué.

Qu’est cet élu de Dieu ?
Fils de Papa Philipe MANKONI et de Maman Véronique LOBOTA qui aurait vaillamment veillée à la vision de Dieu et la vie de son fils. Déjà à la naissance du second, un prêtre avait prédit que le fils sera un grand Pasteur par qui Dieu manifestera sa gloire. Il naquit un certain 10 Novembre avec confusion de sexe auprès du personnel soignant.

<br>* A 6 ans il recevait des versets bibliques;
<br>A 7 ans déjà, des rêves et des visions s’accomplissaient;
<br>A 12 ans, il eut 21 fois le message sous un manguier de Peniel ;
<br>A 17 ans, Polidor marquait sa supériorité spirituelle en chassant les mauvais sorts auprès des proches et lire ce qui était caché au fond du cœur, demandant de quitter la maison mais son père refuser de le libérer;
<br>A 20 ans il prophétisait clairement;
<br>A 22 ans lors d’un voyage à Bandundu, les chefs coutumiers voyant son étoile et l’autorité de en lui, l’interdit de traverser le village, pendant qu’ils se rendaient à un marché avec son oncle. Retenu pendant 13 heures jusqu’à l’intervention de son père. Il fut parler un sourd-muet à Isaka.</p>
        </div>
    </div>
</section>
@endsection
