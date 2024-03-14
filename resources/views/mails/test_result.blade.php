@component('mail::message')
    <div style="text-align: center">
        <h3><span class="important_text">Progman test results</span></h3>
        <p><b>{!! $text !!}</b></p>
    </div>
    @if($coupon)
        <h5>Special coupon:</h5>
        @component('mail::button', ['url' => $coupon->type->url])
            {{$coupon->serial_number}}
        @endcomponent
        <p>
            Use this at <a href="https://{{$coupon->type->url}}">{{$coupon->type->url}}</a>
            <b>Until {{date('F j, Y', $coupon->expired_ad)}}</b>
        </p>
    @endif
@endcomponent
