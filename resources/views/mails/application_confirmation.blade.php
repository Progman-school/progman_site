@component('mail::message')

    <div style="text-align: center">
        <h3>Your score: {{$score}}</h3>
        <p><b>{{$text}}</b></p>
    </div>
    @component('mail::button', ['url' => $url])
    @endcomponent
@endcomponent
