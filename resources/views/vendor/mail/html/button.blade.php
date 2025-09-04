@props([
    'url',
    'color' => '#1d72b8',
    'align' => 'center',
])

<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}"
   target="_blank" rel="noopener"
   style="background-color: {{ $color }}; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">
   {!! $slot !!}
</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
