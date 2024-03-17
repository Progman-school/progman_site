@component('mail::message')
    <div style="text-align: center">
        <h1>Персональный план обучения #{{$testResultData['id']}}</h1>
        <h2 class="important_text">Курс: "{{$testResultData['course_name']}}"</h2>
    </div>
    @component('mail::table')
        <strong class="important_line_info">СОЗДАН: {{date('F j, Y')}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ОБУЧАЮЩИЙСЯ: {{$testResultData['name']}}</strong>
    @endcomponent
    <div>
        <h5>Идея проэкта:</h5>
        <p>{{$testResultData['details']}}</p>
    </div>
    <div>
        @component('mail::table')
            | дней в неделю | дни недели | часов в день |
            |:---------:|:-----------:|:-----------:|
            | {{count(explode(",", $testResultData['weekdays']))}} | {{$testResultData['weekdays']}} | {{$testResultData['day_hours']}} |
        @endcomponent
    </div>
    @if(!empty($testResultData['$technologies']))
        <div>
            <h5>Технологии, которым нужно обучиться:</h5>
            @component('mail::table')
                | тезхнологии | часы | описание |
                @foreach($testResultData['weekdays'] as $technology)
                    |:---------:|:-----------:|:-----------:|
                    | {{$technology['name']}} | {{$technology['hours']}} | {{$testResultData['description']}} |
                @endforeach
            @endcomponent
        </div>
    @endif
    <div>
        <h5>Список дел для изучения:</h5>
        <ul>
            <li>Определите детальную функциональность вашей идеи проэкта.</li>
            <li>Составьте список технологий, которые можно использовать на случай, если вам понадобится другой стек. Или просто используйте перечисленные выше.</li>
            <li>Опишите точные этапы разработки проекта. Лучше обратиться за помощью к любому знакомому профессионалу.</li>
            <li>Выберите программное обеспечение, необходимое для рисования архитектуры вашего приложения и разработки кода.</li>
            <li>Научитесь создавать приложения с помощью книг и YouTube.</li>
            <li>Поймите, какие части вашего приложения у вас будут.</li>
            <li>Сделал схему связей между отдельными частями вашего приложения(фронт, бэк, БД и т.д.).</li>
            <li>Научитесь создавать базы данных с помощью книг и YouTube.</li>
            <li>Придумайте точные модели (таблицы БД) и их свойства в своем приложении.</li>
            <li>Создать схему отношений базы данных, включая все атрибуты свойств и типы данных.</li>
            <li>Узнайте, что такое Git и GitHub, и создайте свой репозиторий GitHub для хранения кода проекта.</li>
            <li>Изучите основы выбранных языков и технологий программирования (бэкенд/фронтенд), возможно, с помощью книг и YouTube.</li>
            <li>Начните программировать свое приложение. Вам придется самостоятельно выбрать, какая часть (бэкэнд/интерфейс) будет создана в первую очередь.</li>
            <li>Поиск информации обо всех трудностях и непонимании кода во время программирования приложения.</li>
            <li>Найдите все проблемы с синтаксисом и опечатками, скрывающиеся в вашем коде, и устраните все логические ошибки.</li>
            <li>Узнайте, как развернуть приложение в вашем конкретном случае.</li>
            <li>Разверните приложение, когда оно будет готово.</li>
            <li>Создайте свой проект на GitHub и используйте его в качестве портфолио.</li>
            <li>Подготовьте резюме, найдите информацию, как его правильно составить.</li>
            <li>Найдите профессионала в отрасли, который сможет поддержать вас и доказать ваши навыки.</li>
            <li>Создавайте страницы в наиболее важных социальных сетях и сайтах для поиска работы в сфере ИТ. Найдите эти порталы самостоятельно, воспользовавшись советами из Интернета, и создайте дизайн своих страниц как можно лучше.</li>
            <li>Подготовьтесь к собеседованию на работу в сфере ИТ, найдите того, кто сможет помочь.</li>
            <li>Вы готовы! Начните искать работу и не останавливайтесь, пока не найдете ее.</li>
        </ul>
    </div>
    <br>
    <div class="important_line_info" style="padding: 10px">
        <h5>Общее время, чтобы успеть:</h5>
        <div class="important_text" style="text-align: center; width: 100%; font-size: 1.2rem; font-weight: bold">
            ~ {{$testResultData['yourself_result']}}
        </div>
    </div>
    <hr />
    <div>
        <h5>Список дел с профессиональным наставником:</h5>
        <ul>
            <li>one thing</li>
            <li>another thing</li>
            <li>and more more things</li>
        </ul>
    </div>
    <br>
    <div class="important_line_info" style="padding: 10px">
        <h5>Общее время, чтобы сделать это с наставником:</h5>
        <div class="important_text" style="text-align: center; width: 100%; font-size: 1.2rem; font-weight: bold">
            ~ {{$testResultData['result']}}
        </div>
    </div>
    @if($coupon)
        <hr/>
        <h3>ПОДАРОК ЗА ИНИЦИАТИВУ</h3>
        <div style="border: #58cc02 2px dashed; padding: 15px">
            <h3 class="important_text">Special coupon</h3>
            <p style="text-align: center">Консультация разовая персоональная профессионального наставника(ментора) бесплатно!</p>
            @component('mail::button', ['url' => "{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"])
                - {{$coupon->serial_number}} -
            @endcomponent
            <p style="text-align: center">
                Используй до: <a href="{{"{$coupon->couponType->use_link}?coupon={$coupon->serial_number}"}}">{{$coupon->couponType->use_link}}</a>
                <br/>
                <br/>
                <b>Купон дествителен до {{date('F j, Y', $coupon->expired_ad)}}</b>
            </p>
        </div>
    @endif
@endcomponent

