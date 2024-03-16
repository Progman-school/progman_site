@component('mail::message')
    <div style="text-align: center">
        <h3><span class="important_text">Please, confirm your request!</span></h3>
        <p><b>{!! $text !!}</b></p>
    </div>
    @component('mail::button', ['url' => $confirmUrl])
        Confirm
    @endcomponent
@endcomponent
