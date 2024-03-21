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
        <ol>
            <li>Figure out the detailed functionality of your project idea.</li>
            <li>Make a list of technologies to use in case you want a different stack. Or simply use listed ones above.</li>
            <li>Come up with exact project development steps. Better to have help from any professional you know.</li>
            <li>Choose the software you need for drawing the architecture of your app and for code development.</li>
            <li>Understand what parts of your app you are going to have.</li>
            <li>Learn yourself how to architect an application using books and YouTube.</li>
            <li>Made the scheme of the relations between separated parts of your app(front, back, DB, etc).</li>
            <li>Learn yourself how to architect a DataBases using books and YouTube.</li>
            <li>Come up with exact models(db tables) and their properties in your app.</li>
            <li>Make DataBase relations scheme including all properties attributes and types of data.</li>
            <li>Find out what is Git and GitHub are learn the basic commands of them.</li>
            <li>Make your github repository to store the project code and connect it to your local device(laptop).</li>
            <li>Learn the basics of chosen programming(backend/frontend) languages and technologies probably using books and YouTube.</li>
            <li>Start programming your app, you have to choose yourself which part (backend/frontend) is going to be created fist.</li>
            <li>Search info about all difficulties, and your misunderstanding of code during programming your app.</li>
            <li>Find all syntax/typo issues hiding into your code, and solve all logical bugs.</li>
            <li>Find out how to deploy the application in your particular case.</li>
            <li>Deploy your app when it's ready.</li>
            <li>Make up your project on GitHub to use it as a portfolio.</li>
            <li>Prepare your resume, find the info how to do it properly.</li>
            <li>Find some professional from the industry who can give you endorsement to prove your skills.</li>
            <li>Make pages in the most important social networks and websites for IT job searching. Find these portals your self using advice from the internet, and design your pages as good as you can.</li>
            <li>Train yourself for the IT job interview, find some one how can help.</li>
            <li>You're ready! Start searching for a job, and don't stop until you find it.</li>
        </ol>
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
        <ol>
            <li>Figure out the detailed functionality of your project idea.</li>
            <li>Mentor helps you to chose the best technologies and steps to learn them for your particular project.</li>
            <li>Get the all necessary software you need from the mentor, use his help to set it up and configure your computer.</li>
            <li>Make the architect schemes for your app (the app parts communications and the db tables relations), learning how to architect an app and DataBases with the mentor, step by step.</li>
            <li>Use mentor guid to create you GitHub repository, to set up git on your machine and learn the git commands and roles you need provided by the mentor.</li>
            <li>Mentor provides you the step by step course of learning the languages/technologies you need for the project and teach you to use them personally.</li>
            <li>Start to build your app with the mentor, he helps you to solve all the difficulties, syntax/typo issues and logical bugs you have or fixes them for you in the tough case.</li>
            <li>Deploy your ready app using the mentor's detailed guidelines.</li>
            <li>Create your programming portfolio with the mentor, including GitHub page decoration, a professional resume and pages in the special networks/websites for IT job searching.</li>
            <li>Mentor gives you his personal endorse reference and an official study certificate with a provable serial number to prove your knowledge and skills.</li>
            <li>Get trainings for the IT job interview and the code interview tasks from the mentor.</li>
            <li>You're ready! Mentor will help you to start job resecting or your own startup, just don't stop until you make it.</li>
        </ol>
    </div>
    <br>
    <div class="important_line_info" style="padding: 10px">
        <h5>Total time to make it with the mentor:</h5>
        <div class="important_text" style="text-align: center; width: 100%; font-size: 1.2rem; font-weight: bold">
            ~ {{$testResultData['result']}}
        </div>
    </div>
    @if($coupon)
        <hr/>
        <h3>Gift for your initiative!</h3>
        <div style="border: #58cc02 2px dashed; padding: 15px">
            <h3 class="important_text">Special coupon</h3>
            <p style="text-align: center">Professional the one time personal mentor consulting for free!</p>
            @component('mail::button', ['url' => "{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"])
                - {{$coupon->serial_number}} -
            @endcomponent
            <p style="text-align: center">
                Use this at: <a href="{{"{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"}}">{{$coupon->couponType->use_link}}</a>
                <br/>
                <br/>
                <b>The coupon is available till {{date('F j, Y', $coupon->expired_at)}}</b>
            </p>
        </div>
    @endif
@endcomponent
