<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn text-white', 'style' => 'background:#2176ae; border-color:#2176ae;']) }}>
    {{ $slot }}
</button>
