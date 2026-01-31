@component('mail::message')
# Confirmation d'inscription Nihao Travel

Bonjour {{ $travel->first_name }} {{ $travel->last_name }},

Nous vous confirmons la bonne réception de votre inscription pour la Foire de Canton avec **Nihao Travel**, filiale du **Groupe Sumbaawa**.

## Détails de votre inscription
- **Référence :** {{ $travel->code }}
- **Édition choisie :** {{ $travel->canton_edition }}
- **Montant payé :** {{ $formattedAmount }}
- **Date d'inscription :** {{ $travel->created_at->format('d/m/Y à H:i') }}

## Prochaines étapes
Notre équipe vous contactera dans les **24 heures ouvrables** pour :
1. Planifier un premier entretien téléphonique
2. Vous envoyer la liste des documents nécessaires pour le visa
3. Définir vos besoins spécifiques en matière d'hébergement et de logistique

## Informations importantes
- Conservez bien votre numéro de référence : **{{ $travel->code }}**
- Notre équipe est disponible au +242044724102
- Email : support@sumbaawa.com

## À propos de la Foire de Canton
La Foire d'Import-Export de Canton est le plus grand événement commercial au monde. Nous vous accompagnerons pour :
- L'obtention du visa chinois
- La réservation d'hôtel à Guangzhou
- L'organisation de votre séjour
- L'assistance sur place pendant la foire

**Merci de votre confiance !**

L'équipe Nihao Travel
Filiale du Groupe Sumbaawa

@component('mail::button', ['url' => config('app.url'), 'color' => 'primary'])
Visiter notre site
@endcomponent

*Cet email est envoyé automatiquement, merci de ne pas y répondre.*
@endcomponent
