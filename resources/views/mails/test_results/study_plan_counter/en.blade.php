@component('mail::message')
    <div style="text-align: center">
        <h3><span class="important_text">Personal study plan #{{$testResultData['id']}}</span></h3>
    </div>
    @component('mail::table')
            | Creation date | Participant name |
            |:---------:|:-----------:|
            | {{$testResultData['created_at']}} | {{$testResultData['name']}} |
    @endcomponent
    <div>
        <h5>Study load:</h5>
        @component('mail::table')
            | Count days per week | Week days | Hours per day |
            |:---------:|:-----------:|:-----------:|
            | {{count(explode(",", $testResultData['weekdays']))}} | {{$testResultData['weekdays']}} | {{$testResultData['day_hours']}} |
        @endcomponent
    </div>
    @if(!empty($testResultData['$technologies']))
        <div>
            <h5>Technologies to learn:</h5>
            @component('mail::table')
                | Technology | Hours | Description |
                @foreach($testResultData['weekdays'] as $technology)
                    |:---------:|:-----------:|:-----------:|
                    | {{$technology['name']}} | {{$technology['hours']}} | {{$testResultData['description']}} |
                @endforeach
            @endcomponent
        </div>
    @endif
    <hr />
    <h5>Plan to study along:</h5>
    <div>
        <h5>TO DO list:</h5>
        <ul>
            <li>one thing</li>
            <li>another thing</li>
            <li>and more more things</li>
        </ul>
    </div>
    <div style="text-align: center">
        <h5 class="important_text">Total time to make it:</h5>
        <h3 style="text-align: center">~ {{$testResultData['yourself_result']}}</h3>
        <h6>or ~ {{$testResultData['yourself_score']}} hours of none stop learning</h6>
    </div>
    <hr />
    <h5>Plan to study with a professional mentor:</h5>
    <div>
        <h5>TO DO list:</h5>
        <ul>
            <li>one thing</li>
            <li>another thing</li>
            <li>and more more things</li>
        </ul>
    </div>
    <div style="text-align: center">
        <h5 class="important_text">Total time to make it:</h5>
        <h3 style="text-align: center">~ {{$testResultData['result']}}</h3>
        <h6>or ~ {{$testResultData['score']}} hours of none stop learning</h6>
    </div>
    @if($coupon)
        <hr />
        <h5 class="important_text">Special coupon:</h5>
        @component('mail::button', ['url' => "{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"])
            {{$coupon->serial_number}}
        @endcomponent
        <p>
            Use this at <a href="{{"{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"}}">{{$coupon->couponType->use_link}}</a>
            <br/>
            <b>Until {{date('F j, Y', $coupon->expired_ad)}}</b>
        </p>
    @endif
@endcomponent
