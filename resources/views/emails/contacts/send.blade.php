<x-mail::message>
# <span style="color: #000055">Vous avez reçu un nouveau message.</span>

<span style="color: black;">Obtenez dans le tableau ci-dessous le contenu du message.</span>

<x-mail::table>
| Libellé          | Contenu                                                       |
| -------------------- |:-------------------------------------------------------------:|
| Nom          | {{ $data['name'] }}     |
| Téléphone          | {{ $data['phone'] }}     |
| Email          | {{ $data['email'] }}     |
| Sujet          | {{ $data['subject'] }} |
| Message          | {{ $data['message'] }} |
</x-mail::table>

<span style="color: black;">Cordialement,<br> <b>l'Équipe {{ config('app.name') }}</b></span>
</x-mail::message>
