@props(['name', 'show' => false])

<div class="modal fade" id="modal-{{ $name }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>

@if($show)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new bootstrap.Modal(document.getElementById('modal-{{ $name }}')).show();
            });
        </script>
    @endpush
@endif
