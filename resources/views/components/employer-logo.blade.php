@props(['employer', 'width' => 90])

<img 
    src="{{ 
        filter_var($employer->logo, FILTER_VALIDATE_URL) 
            ? $employer->logo 
            : asset('storage/'.$employer->logo) 
    }}" 
    alt="{{ $employer->name }}" 
    class="rounded-xl" 
    width="{{ $width }}"
>