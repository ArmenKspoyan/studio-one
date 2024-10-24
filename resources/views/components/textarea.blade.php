<div>
<textarea
    name="{{ $name }}"
    placeholder="{{ $placeholder }}"
    class="form-control"
    rows="8"
    {{ $attributes }}
>
    {{ $slot }}
</textarea>
</div>
