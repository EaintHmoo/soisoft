<div {{ $attributes }}>
    <div class="divide-y">
        <div class="grid py-16 md:grid-cols-3 mb-2.5">
          <div>
            <h2 class="text-base font-semibold leading-7">{{ $getLabel() }}</h2>
          </div>

          <div class="md:col-span-2 pt-0">
                {{ $getChildComponentContainer() }}  
          </div>
        </div>
    </div>
</div>
