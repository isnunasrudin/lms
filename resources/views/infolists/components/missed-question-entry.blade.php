<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <table border="1">

            @foreach($getRecord()->exam->questions()->whereNotIn('id', $getRecord()->questions()->pluck('id'))->pluck('content') as $pertanyaan)
        
            <tr>
                <td>
                    {!! $pertanyaan !!}
                </td>
            </tr>

            @endforeach
        </table>
        </ol>
    </div>
</x-dynamic-component>
