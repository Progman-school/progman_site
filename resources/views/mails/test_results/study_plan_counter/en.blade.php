@component('mail::message')
    <div style="text-align: center">
        <h1>Personal study plan #{{$testResultData['id']}}</h1>
        <h2 class="important_text">Course: "{{$testResultData['course_name']}}"</h2>
    </div>
    @component('mail::table')
        <strong class="important_line_info">BUILT: {{date('F j, Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;LEANER: {{$testResultData['name']}}</strong>
    @endcomponent
    <div>
        <h5>The project idea:</h5>
        <p>{{$testResultData['details']}}</p>
    </div>
    <div>
        @component('mail::table')
            | count days per week | weekdays | hours per day |
            |:---------:|:-----------:|:-----------:|
            | {{count(explode(",", $testResultData['weekdays']))}} | {{$testResultData['weekdays']}} | {{$testResultData['day_hours']}} |
        @endcomponent
    </div>
    @if(!empty($testResultData['$technologies']))
        <div>
            <h5>Technologies to learn:</h5>
            @component('mail::table')
                | technology | hours | description |
                @foreach($testResultData['weekdays'] as $technology)
                    |:---------:|:-----------:|:-----------:|
                    | {{$technology['name']}} | {{$technology['hours']}} | {{$testResultData['description']}} |
                @endforeach
            @endcomponent
        </div>
    @endif
    <div>
        <h5>TO DO list to study along:</h5>
        <ul>
            <li>one thing</li>
            <li>another thing</li>
            <li>and more more things</li>
        </ul>
    </div>
    <br>
    <div class="important_line_info" style="padding: 10px">
        <h5>Total time to make it along:</h5>
        <div class="important_text" style="text-align: center; width: 100%; font-size: 1.2rem; font-weight: bold">
            ~ {{$testResultData['yourself_result']}}
        </div>
    </div>
    <hr />
    <div>
        <h5>TO DO list with a professional mentor:</h5>
        <ul>
            <li>one thing</li>
            <li>another thing</li>
            <li>and more more things</li>
        </ul>
    </div>
    <br>
    <div class="important_line_info" style="padding: 10px">
        <h5>Total time to make it with a mentor:</h5>
        <div class="important_text" style="text-align: center; width: 100%; font-size: 1.2rem; font-weight: bold">
            ~ {{$testResultData['result']}}
        </div>
    </div>
    @if($coupon)
        <hr/>
        <div style="border: #58cc02 2px dashed; padding: 15px">
            <h3 class="important_text">Special coupon</h3>
            <p style="text-align: center">Professional mentor consulting for free!</p>
            @component('mail::button', ['url' => "{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"])
                - {{$coupon->serial_number}} -
            @endcomponent
            <p style="text-align: center">
                Use this at: <a href="{{"{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"}}">{{$coupon->couponType->use_link}}</a>
                <br/>
                <br/>
                <b>The coupon is available till {{date('F j, Y', $coupon->expired_ad)}}</b>
            </p>
        </div>
    @endif
@endcomponent
