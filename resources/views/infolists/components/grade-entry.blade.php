<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <div class="text-sm leading-6 text-gray-950 dark:text-white">
            {!! $getRecord()->content !!}
        </div>

        <div class="grid gap-2 mt-2">

            @foreach ($getRecord()->options as $index => $option)

            @if($index === $getRecord()->pivot->answer && !$option['is_correct'])
            <x-filament::button size="xs" color="danger">
                <span style="padding: 6px 0px !important; display: block;">{!! $option['value'] !!}</span>
            </x-filament::button>
            @elseif($option['is_correct'])
            <x-filament::button size="xs" color="success">
                <span style="padding: 6px 0px !important; display: block;">{!! $option['value'] !!}</span>
            </x-filament::button>
            @else
            <x-filament::button size="xs" outlined color="gray">
                <span style="padding: 6px 0px !important; display: block;">{!! $option['value'] !!}</span>
            </x-filament::button>
            @endif
            @endforeach

        </div>

    <div>
</x-dynamic-component>
