<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <dl class="border-b border-gray-200">
        <div class="sm:grid sm:grid-cols-3 py-2">
            <dt class="text-sm">{{ $getLabel() }}</dt>
            <dd class="text-sm sm:col-span-2">                    
                @switch($getType())
                    @case('datetime')
                        @if(!is_null($getState())){{ Carbon\Carbon::parse($getState())->format('d M Y h:m') }}@endif
                        @break
                    @case('array')
                        @foreach($getState() as $key => $state)
                            {{ $state }} 
                            @if($key+1 != count($getState())), @endif
                        @endforeach
                        @break
                    @case('boolean')
                        {{ $getCustomBooleanText()[$getState()] }}
                        @break
                    @case('file')
                        <ul role="list">
                            @foreach(\Illuminate\Support\Arr::wrap($getState()) as $state)
                            <li class="flex items-center justify-between text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <a href="{{ Storage::url($state) }}" class="font-medium text-indigo-400 hover:text-indigo-300" target="__blank">{{ $state }}</a>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @break
                    @default
                        {!! $getState() !!}
                @endswitch
            </dd>
        </div>
    </dl>
</x-dynamic-component>
