@component('components.elements.element', ['type' => 'div'])
    @slot('id')
        {{ $id or '' }}
    @endslot
    @slot('class')
        {{ $class or '' }}
    @endslot
    @slot('options')
        {{ $options or '' }}
    @endslot
    {{ $slot or '' }}
@endcomponent