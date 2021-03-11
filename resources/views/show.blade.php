@extends('scripts::base')
@section('main')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 my-4">
            <h1 class="text-2xl font-semibold text-gray-900">Details</h1>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Script Information
                                    </h3>
                                    <p class="mt-1 max-w-2xl text-sm text-gray-500">

                                    </p>
                                </div>
                                <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                                    <dl class="sm:divide-y sm:divide-gray-200">
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Date
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $scriptRun->created_at }}
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                ID
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $scriptRun->id }}
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Name
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $scriptRun->script_name }}
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Description
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $scriptRun->description ?: '-' }}
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Status
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                @if($scriptRun->status === 'succeeded')
                                                    @php($additionalClasses = 'bg-green-100 text-green-800')
                                                @elseif($scriptRun->status === 'failed')
                                                    @php($additionalClasses = 'bg-red-100 text-red-800')
                                                @elseif($scriptRun->status === 'overridden')
                                                    @php($additionalClasses = 'bg-yellow-100 text-yellow-800')
                                                @endif
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $additionalClasses }} capitalize">
                                                  {{ $scriptRun->status }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Message
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $scriptRun->message }}
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Runner IP
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                {{ $scriptRun->runner_id }}
                                            </dd>
                                        </div>
                                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Dependencies
                                            </dt>
                                            <dd class="mt-1 text-sm sm:mt-0 sm:col-span-2">
                                                @foreach(get_object_vars($scriptRun->dependencies) as $key => $value)
                                                <p class="bg-gray-800 rounded text-yellow-500 p-2 my-1">{{ $key }} => {{ $value }}</p>
                                                @endforeach
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                            <h1 class="text-xl font-semibold text-gray-900 my-4">Executed Queries</h1>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Query
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" x-max="1">
                                    @foreach($scriptRun->executed_queries as $query)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <p class="bg-gray-800 rounded text-yellow-500 p-2 my-1">
                                                {{ \Illuminate\Support\Str::of($query->query)->replaceArray('?', $query->bindings) }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $query->time }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
