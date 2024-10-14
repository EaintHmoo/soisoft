<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @foreach($getState() as $state)
    <div class="border border-grey-500 p-6">
        <dl class="border-b border-gray-200">
            <div class="sm:grid sm:grid-cols-3 py-2">
                <dt class="text-sm">
                    Contact Person
                </dt>
                <dd class="text-sm sm:col-span-2">                    
                    {{ $state->contact_person }}
                </dd>
            </div>
        </dl>

        <dl class="border-b border-gray-200">
            <div class="sm:grid sm:grid-cols-3 py-2">
                <dt class="text-sm">
                    Designation
                </dt>
                <dd class="text-sm sm:col-span-2">                    
                    {{ $state->designation }}
                </dd>
            </div>
        </dl>

        <dl class="border-b border-gray-200">
            <div class="sm:grid sm:grid-cols-3 py-2">
                <dt class="text-sm">
                    Phone
                </dt>
                <dd class="text-sm sm:col-span-2">                    
                    {{ $state->phone }}
                </dd>
            </div>
        </dl>

        <dl class="border-b border-gray-200">
            <div class="sm:grid sm:grid-cols-3 py-2">
                <dt class="text-sm">
                    Email
                </dt>
                <dd class="text-sm sm:col-span-2">                    
                    {{ $state->email }}
                </dd>
            </div>
        </dl>

        <dl class="border-b border-gray-200">
            <div class="sm:grid sm:grid-cols-3 py-2">
                <dt class="text-sm">
                    Address
                </dt>
                <dd class="text-sm sm:col-span-2">                    
                    {!! $state->address !!}
                </dd>
            </div>
        </dl>
    </div>
    @endforeach
</x-dynamic-component>
