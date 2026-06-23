@extends('layouts.public')

@section('title', 'Politique de confidentialité')

@section('content')
<article class="prose prose-gray max-w-none">

    <div class="mb-10">
        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold mb-2">Document légal</p>
        <h1 class="text-3xl font-bold text-gray-900 mb-3">Politique de confidentialité</h1>
        <p class="text-sm text-gray-500">Dernière mise à jour : {{ date('d F Y') }} · Version 1.0</p>
    </div>

    {{-- Introduction --}}
    <section class="mb-8 p-5 bg-primary-50 border border-primary-100 rounded-2xl">
        <p class="text-sm text-gray-700 leading-relaxed">
            AfriYuan (ci-après « nous », « notre service ») s'engage à protéger la vie privée de ses utilisateurs.
            Cette politique décrit quelles données nous collectons, pourquoi nous les collectons, comment nous les utilisons,
            et quels sont vos droits. Elle s'applique à notre site web, notre application mobile, et tous nos services de
            transfert de fonds entre les pays d'Afrique de l'Ouest et la Chine.
        </p>
    </section>

    {{-- 1 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">1</span>
            Responsable du traitement des données
        </h2>
        <div class="pl-9 space-y-2 text-sm text-gray-600 leading-relaxed">
            <p><strong class="text-gray-800">AfriYuan SAS</strong> est le responsable du traitement de vos données personnelles au sens du Règlement Général sur la Protection des Données (RGPD – UE 2016/679) et des législations nationales applicables dans les pays d'Afrique de l'Ouest et en République Populaire de Chine.</p>
            <p>Pour toute question relative à vos données : <strong class="text-gray-800">privacy@afriyuan.com</strong></p>
        </div>
    </section>

    {{-- 2 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">2</span>
            Données que nous collectons
        </h2>
        <div class="pl-9 space-y-4 text-sm text-gray-600">
            <div>
                <p class="font-semibold text-gray-800 mb-1">2.1 Données d'identité</p>
                <p>Prénom, nom, date de naissance, nationalité, pays de résidence, photo de profil.</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">2.2 Données de contact</p>
                <p>Adresse email, numéro de téléphone (avec indicatif pays).</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">2.3 Données de vérification d'identité (KYC)</p>
                <p>Pièces d'identité (passeport, carte nationale, titre de séjour), justificatifs de domicile, selfie de vérification. Ces documents sont chiffrés (SHA-256) et stockés sur des serveurs sécurisés (AWS S3 avec chiffrement au repos).</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">2.4 Données financières</p>
                <p>Historique des transferts (montants, devises, pays), informations sur les bénéficiaires. <strong class="text-gray-800">Nous ne stockons jamais les numéros de carte bancaire complets</strong> — nous utilisons uniquement des identifiants de méthode de paiement tokenisés fournis par Stripe.</p>
            </div>
            <div>
                <p class="font-semibold text-gray-800 mb-1">2.5 Données d'utilisation</p>
                <p>Adresse IP, type de navigateur/appareil, pages consultées, logs de connexion, tokens OTP (hachés, durée de vie 5 minutes).</p>
            </div>
        </div>
    </section>

    {{-- 3 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">3</span>
            Pourquoi nous utilisons vos données
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse mt-2">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="py-2 pr-4 font-semibold text-gray-800 w-2/5">Finalité</th>
                            <th class="py-2 pr-4 font-semibold text-gray-800 w-2/5">Base légale</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr><td class="py-2 pr-4">Création et gestion de votre compte</td><td class="py-2 text-gray-500">Exécution du contrat</td></tr>
                        <tr><td class="py-2 pr-4">Traitement des transferts de fonds</td><td class="py-2 text-gray-500">Exécution du contrat</td></tr>
                        <tr><td class="py-2 pr-4">Vérification d'identité (KYC)</td><td class="py-2 text-gray-500">Obligation légale (LCB-FT)</td></tr>
                        <tr><td class="py-2 pr-4">Prévention de la fraude et de blanchiment</td><td class="py-2 text-gray-500">Obligation légale / Intérêt légitime</td></tr>
                        <tr><td class="py-2 pr-4">Envoi des codes OTP de vérification</td><td class="py-2 text-gray-500">Exécution du contrat</td></tr>
                        <tr><td class="py-2 pr-4">Support client</td><td class="py-2 text-gray-500">Exécution du contrat</td></tr>
                        <tr><td class="py-2 pr-4">Notifications sur vos transferts</td><td class="py-2 text-gray-500">Exécution du contrat</td></tr>
                        <tr><td class="py-2 pr-4">Amélioration du service</td><td class="py-2 text-gray-500">Intérêt légitime</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- 4 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">4</span>
            Partage de vos données
        </h2>
        <div class="pl-9 space-y-3 text-sm text-gray-600 leading-relaxed">
            <p>Nous ne vendons jamais vos données. Nous les partageons uniquement avec :</p>
            <ul class="list-disc pl-5 space-y-2">
                <li><strong class="text-gray-800">Stripe, Inc.</strong> — Traitement des paiements par carte bancaire (entreprise enregistrée en Chine). Soumis à sa propre politique de confidentialité.</li>
                <li><strong class="text-gray-800">Partenaires bancaires et de transfert</strong> — Établissements bancaires dans les pays africains desservis et en Chine, uniquement dans le cadre du traitement de vos transferts.</li>
                <li><strong class="text-gray-800">Prestataires d'infrastructure</strong> — Hébergement cloud (AWS), envoi d'emails transactionnels.</li>
                <li><strong class="text-gray-800">Autorités compétentes</strong> — En cas d'obligation légale (réglementation LCB-FT, demandes judiciaires), nous pouvons être tenus de transmettre certaines données.</li>
            </ul>
            <p class="p-3 bg-yellow-50 border border-yellow-200 rounded-xl text-yellow-800">
                <strong>Transferts internationaux :</strong> Certains de nos partenaires sont situés hors de l'EEE (notamment en Afrique de l'Ouest et en Chine). Ces transferts sont encadrés par des clauses contractuelles types (CCT) approuvées par la Commission européenne ou des mécanismes de protection équivalents.
            </p>
        </div>
    </section>

    {{-- 5 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">5</span>
            Conservation des données
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed space-y-2">
            <p>Nous conservons vos données le temps nécessaire à l'atteinte des finalités décrites et conformément aux obligations légales :</p>
            <ul class="list-disc pl-5 space-y-1">
                <li><strong class="text-gray-800">Données de compte actif :</strong> pendant toute la durée de votre relation avec AfriYuan.</li>
                <li><strong class="text-gray-800">Documents KYC :</strong> 5 ans après la fin de la relation commerciale (obligation LCB-FT).</li>
                <li><strong class="text-gray-800">Historique des transactions :</strong> 10 ans (obligation comptable et réglementaire).</li>
                <li><strong class="text-gray-800">Logs de connexion :</strong> 12 mois.</li>
                <li><strong class="text-gray-800">Codes OTP :</strong> 5 minutes (supprimés automatiquement à expiration).</li>
            </ul>
        </div>
    </section>

    {{-- 6 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">6</span>
            Vos droits
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed space-y-2">
            <p>Conformément au RGPD, vous bénéficiez des droits suivants :</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-3">
                @foreach([
                    ['Droit d\'accès', 'Obtenir une copie de vos données personnelles que nous détenons.'],
                    ['Droit de rectification', 'Corriger des données inexactes ou incomplètes.'],
                    ['Droit à l\'effacement', 'Demander la suppression de vos données (sous réserve des obligations légales).'],
                    ['Droit à la portabilité', 'Recevoir vos données dans un format structuré et lisible.'],
                    ['Droit d\'opposition', 'Vous opposer à certains traitements basés sur notre intérêt légitime.'],
                    ['Droit de limitation', 'Demander la limitation du traitement de vos données.'],
                ] as [$title, $desc])
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="font-semibold text-gray-800 text-xs mb-0.5">{{ $title }}</p>
                    <p class="text-xs text-gray-500">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
            <p class="mt-3">Pour exercer vos droits, contactez-nous à <strong class="text-gray-800">privacy@afriyuan.com</strong>. Nous répondrons dans un délai d'un mois. Vous avez également le droit d'introduire une réclamation auprès de votre autorité de protection des données nationale (ex. : CNIL en France).</p>
        </div>
    </section>

    {{-- 7 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">7</span>
            Sécurité des données
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed space-y-2">
            <p>Nous mettons en œuvre des mesures techniques et organisationnelles appropriées :</p>
            <ul class="list-disc pl-5 space-y-1">
                <li>Chiffrement des communications (TLS 1.3)</li>
                <li>Chiffrement des documents KYC au repos (AES-256 via AWS S3)</li>
                <li>Hachage des mots de passe (bcrypt) et des codes OTP (SHA-256)</li>
                <li>Authentification à deux facteurs (OTP) pour chaque connexion</li>
                <li>Code PIN de transaction chiffré pour la confirmation des virements</li>
                <li>Journalisation des accès et surveillance des anomalies</li>
                <li>Limitation des tentatives OTP (5 max, blocage de 5 minutes)</li>
            </ul>
        </div>
    </section>

    {{-- 8 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">8</span>
            Cookies et technologies similaires
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed space-y-2">
            <p>Notre site utilise des cookies strictement nécessaires au fonctionnement du service :</p>
            <ul class="list-disc pl-5 space-y-1">
                <li><strong class="text-gray-800">Cookie de session :</strong> maintient votre connexion sécurisée (durée : session).</li>
                <li><strong class="text-gray-800">Token CSRF :</strong> protection contre les attaques de type CSRF (durée : session).</li>
            </ul>
            <p>Nous n'utilisons pas de cookies publicitaires ou de tracking tiers.</p>
        </div>
    </section>

    {{-- 9 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">9</span>
            Modifications de cette politique
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed">
            <p>Nous pouvons mettre à jour cette politique de confidentialité. En cas de modification substantielle, nous vous en informerons par email et/ou via une notification sur notre plateforme au moins 30 jours avant l'entrée en vigueur des changements. La date de dernière mise à jour est toujours indiquée en haut de ce document.</p>
        </div>
    </section>

    {{-- 10 --}}
    <section class="mb-8">
        <h2 class="text-lg font-bold text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-7 h-7 bg-primary-500 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">10</span>
            Contact et Délégué à la Protection des Données
        </h2>
        <div class="pl-9 text-sm text-gray-600 leading-relaxed">
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                <p class="font-semibold text-gray-800 mb-2">AfriYuan — DPO</p>
                <p>Email : <a href="mailto:privacy@afriyuan.com" class="text-primary-600 hover:underline">privacy@afriyuan.com</a></p>
                <p class="text-xs text-gray-400 mt-2">Nous nous engageons à répondre à toute demande dans un délai de 30 jours.</p>
            </div>
        </div>
    </section>

    <div class="mt-10 pt-8 border-t border-gray-100 text-center">
        <a href="{{ route('register') }}" class="btn-primary inline-flex">
            Créer un compte AfriYuan
        </a>
        <p class="text-xs text-gray-400 mt-4">
            En créant un compte, vous confirmez avoir lu et accepté cette politique de confidentialité.
        </p>
    </div>

</article>
@endsection
