@props(['url'])
<tr>
<td class="header">
<div style="padding: 20px 0">
    <img src="{{asset("/images/progman_white.png")}}" class="logo" alt="Logo">
</div>
{{ $slot }}
</td>
</tr>
