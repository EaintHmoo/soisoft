<x-mail::message>
<h1 style="text-align: center;margin-bottom:1rem;">Supplier Registration Information</h1>

Dear {{ $mailData['name']}},<br>
Your account creditional information:
<x-mail::panel>
    <p>Email:  <span style="color:#1155CC;text-decoration: none;">{{ $mailData['email'] }}</span></p>
    <p>Password: <span style="color:#1155CC;">{{ $mailData['password'] }}</span> </p>
</x-mail::panel>


Thank you for providing your details information.,<br>
{{ config('app.name') }}
</x-mail::message>
