<x-mail::message>
# Réinitialisation de vos accès

Bonjour {{ $agent->firstname ?? '' }} {{ $agent->lastname ?? '' }},

Un administrateur a réinitialisé vos accès au panneau DikiTivi. Votre ancien mot de passe ne fonctionne plus.

Utilisez le code unique ci-dessous pour créer un **nouveau mot de passe** (valable {{ $expiresInHours }} heures) :

<x-mail::panel>
**Code : {{ $code }}**
</x-mail::panel>

<x-mail::button :url="$resetUrl">
Créer mon nouveau mot de passe
</x-mail::button>

Si le bouton ne fonctionne pas, ouvrez ce lien :<br>
{{ $resetUrl }}

Sur la page, saisissez votre e-mail, le code ci-dessus, puis votre nouveau mot de passe.

Cordialement,<br>
{{ config('app.name') }}
</x-mail::message>
