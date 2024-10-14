<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @php
        $arrayState = $getState();
        if ($arrayState instanceof \Illuminate\Support\Collection) {
            $arrayState = $arrayState->all();
        }
        if(is_null($arrayState)) $arrayState = [];
    @endphp
    <div class="hs-accordion-group">
        @foreach($arrayState as $state)
            <div class="hs-accordion mb-2" id="heading-{{ $state->id }}">
                <button class="hs-accordion-toggle inline-flex items-center justify-between gap-x-3 w-full font-semibold text-start text-gray-800 hover:text-gray-500 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:hs-accordion-active:text-blue-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:outline-none dark:focus:text-neutral-400" aria-expanded="false" aria-controls="collapse-{{ $state->id }}">
                    {{ $state->description }}
                    <svg class="hs-accordion-active:hidden block size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"></path>
                    </svg>
                    <svg class="hs-accordion-active:block hidden size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m18 15-6-6-6 6"></path>
                    </svg>
                </button>

                <div id="collapse-{{ $state->id }}" class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300 py-3" role="region" aria-labelledby="heading-{{ $state->id }}">
                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Type
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->type }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Quantity
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->quantity }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                UOM
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->uom }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Category
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->category->name }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Expected Delivery Date
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->expected_delivery_date }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Delivery Terms
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->delivery_terms }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Payment Terms
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->payment_terms }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Payment Mode
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->payment_mode }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Estimated Price
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->company_estimated_unit_price }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Historical Price
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->historical_unit_price }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Delivery Contact Person
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->delivery_contact_person }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Delivery Address
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->delivery_address }}
                            </dd>
                        </div>
                    </dl>

                    <dl class="border-b border-gray-200">
                        <div class="sm:grid sm:grid-cols-3 py-2">
                            <dt class="text-sm">
                                Remark
                            </dt>
                            <dd class="text-sm sm:col-span-2">                    
                                {{ $state->remark }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        @endforeach
    </div>

    
</x-dynamic-component>
