@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{asset("/images/progman_white.png")}}" class="logo" alt="Logo">
{{ $slot }}
</a>
</td>
</tr>
