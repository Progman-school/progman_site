@component('mail::message')
    <div style="text-align: center">
        <h3><span class="important_text">Test score: {{$score}}%</span></h3>
        <p><b>{!! $text !!}</b></p>
    </div>
    @component('mail::button', ['url' => $confirmUrl])
        Confirm application
    @endcomponent
@endcomponent
