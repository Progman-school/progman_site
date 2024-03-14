@component('mail::message')
    <div style="text-align: center">
        <h3><span class="important_text">Progman test results</span></h3>
        <p><b>{!! $text !!}</b></p>
    </div>
    @if($coupon)
        <h5>Special coupon:</h5>
        @component('mail::button', ['url' => "{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"])
            {{$coupon->serial_number}}
        @endcomponent
        <p>
            Use this at <a href="{{"{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"}}">{{$coupon->couponType->use_link}}</a>
            <b>Until {{date('F j, Y', $coupon->expired_ad)}}</b>
        </p>
    @endif
@endcomponent
