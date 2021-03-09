@foreach($scripts as $script)
    <a href="/{{ request()->path() . '?name=' . $script['name']}}" class="group w-full flex items-center pl-11 pr-2 py-2 text-sm font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50">
        @php($scriptName = ucwords(implode(' ',preg_split('/(?=[A-Z])/', $script['name']))))
        {{ \Illuminate\Support\Str::of($scriptName)->endsWith('Script') ? \Illuminate\Support\Str::of($scriptName)->replaceLast('Script', '') : $scriptName }}
    </a>
@endforeach
