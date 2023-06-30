The cert <b>#{{$certificate->full_number}}</b> issued {{date('m/d/Y', strtotime($certificate->date))}}
<br>
Owner: {{$certificate->user->real_last_name}} {{mb_substr($certificate->user->real_first_name, 0, 1)}} {{mb_substr($certificate->user->real_middle_name, 0, 1)}}
<br>
Passed: <b>{{$certificate->hours}} academic hours</b> individual lessons with a mentor
<br>
Language of study: {{$certificate->language}}
<br>
<br>
Learned technologies:
<ul>
    @foreach ($certificate->technologies as $technology)
        <li title='{{$technology->description}}'>{{$technology->name}}</li>
    @endforeach
</ul>
Special marks:
<br>
<pre>{{$certificate->description}}</pre>
