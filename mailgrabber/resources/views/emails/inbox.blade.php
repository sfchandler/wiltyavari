@foreach ($emails as $email)
    <div>
        <h3>{{ $email->getSubject() }}</h3>
        <p>{{ $email->getTextBody() }}</p>
    </div>
@endforeach
