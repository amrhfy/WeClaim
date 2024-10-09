<x-layout>
    <main>
        <a href="{{ route('claims-dashboard') }}">Back</a>

        <h1>{{ $claim->title }}</h1>

        <p>Claim ID: {{ $claim->claim_id }}</p>
        <p>Submitted At: {{ $claim->submitted_at }}</p>
        <p>Company: {{ $claim->claim_company }}</p>
        <p>Claim Type: {{ $claim->claim_type }}</p>
        <p>Title: {{ $claim->title }}</p>
        <p>Description: {{ $claim->description }}</p>

        <br>
        <p>Amount: {{ 'RM' . $claim->toll_amount }}</p>
        <p>Petrol: {{ 'RM' . $claim->amount }}</p>

        <br>
        <p>From: {{ $claim->from_location }}</p>
        <p>To: {{ $claim->to_location }}</p>
    </main>
</x-layout>