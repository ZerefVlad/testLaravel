<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Unique Link</title>
</head>
<body>
<h1>Your Unique Link</h1>
<p>Username: {{ $linkData->Username }}</p>
<p>Phone: {{ $linkData->PhoneNumber }}</p>
<p>This link will expire on: {{ $linkData->expires_at }}</p>

@if ($linkData->expires_at > now())
    <form action="{{ route('link.generate') }}" method="POST">
        @csrf
        <input type="hidden" name="phone" value="{{ $linkData->PhoneNumber }}">
        <button type="submit" class="btn btn-primary">Generate new uniq link</button>
    </form>

    <form action="{{ route('link.deactivate') }}" method="POST" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="phone" value="{{ $linkData->PhoneNumber }}">
        <button type="submit" class="btn btn-danger">Deactivate this link</button>
    </form>

    <form action="{{ route('link.drop') }}" method="POST" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="phone" value="{{ $linkData->PhoneNumber }}">
        <input type="hidden" name="token" value="{{ $linkData->token }}">
        <input type="hidden" name="username" value="{{ $linkData->Username }}">
        <button type="submit" class="btn btn-warning">Imfeelinglucky</button>
    </form>

    <form action="{{ route('link.history') }}" method="POST" style="margin-top: 10px;">
        @csrf
        <input type="hidden" name="token" value="{{ $linkData->token }}">
        <button type="submit" class="btn btn-info">History</button>
    </form>
@else
    <p>Link was expired</p>
@endif

@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('randomNumber'))
    <h2>Result</h2>
    <p>Random Number: {{ session('randomNumber') }}</p>
    <p>You result: {{ session('result') }}</p>
    <p>Cost: {{ number_format(session('winningAmount'), 2) }} (USDT)</p>
@endif

@if(session('history'))
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Случайное число</th>
            <th>Результат</th>
            <th>Сумма выигрыша</th>
            <th>Дата</th>
        </tr>
        </thead>
        <tbody>
        @foreach(session('history') as $entry)
            <tr>
                <td>{{ $entry->random_number }}</td>
                <td>{{ $entry->result }}</td>
                <td>{{ number_format($entry->winning_amount, 2) }}</td>
                <td>{{ $entry->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

</body>
</html>
