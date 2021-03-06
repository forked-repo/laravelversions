@push('footer-scripts')
<script>
    if (localStorage.getItem('colorblind_mode') != 'true') {
        localStorage.setItem('colorblind_mode', 'false');
    }

    let $button = document.getElementById('colorblind-mode-toggle');
    let $labels = document.getElementsByClassName('js-colorblind');
    let colorblind_mode = false;

    function colorBlindModeOn() {
        $button.classList.add('font-bold');
        for (let $label of $labels) {
            $label.classList.remove('hidden');
        }
    }
    function colorBlindModeOff() {
        $button.classList.remove('font-bold');
        for (let $label of $labels) {
            $label.classList.add('hidden');
        }
    }

    $button.addEventListener('click', () => {
        if (colorblind_mode) { colorBlindModeOff() } else { colorBlindModeOn() }
        colorblind_mode = ! colorblind_mode;
        localStorage.setItem('colorblind_mode', colorblind_mode ? 'true' : 'false');
    });

    if (localStorage.getItem('colorblind_mode') === 'true') {
        colorBlindModeOn();
        colorblind_mode = true;
    }
</script>
@endpush

<x-app-layout>
    <div class="float-left lg:w-1/2">
        <h1 class="block mb-4 text-5xl text-bold">Laravel Versions</h1>
        <p class="max-w-2xl mb-8 text-lg">Release dates and timelines for security and bug fixes for all versions of Laravel.</p>
    </div>

    <div class="max-w-xs mb-8 lg:w-1/2 lg:float-right">
        <div class="float-right cursor-pointer hover:text-blue-800" id="colorblind-mode-toggle">Colorblind mode</div>
        <div class="font-bold">Colors:</div>

        <div class="p-2 bg-red-300">
            <div class="hidden float-right font-bold js-colorblind">EOL</div>
            End of Life
        </div>
        <div class="p-2 bg-yellow-300">
            <div class="hidden float-right font-bold js-colorblind">SEC</div>
            Security fixes only
        </div>
        <div class="p-2 bg-green-300">
            <div class="hidden float-right font-bold js-colorblind">ALL</div>
            Bug and security fixes
        </div>
        <div class="p-2 bg-blue-300">
            <div class="hidden float-right font-bold js-colorblind">FUT</div>
            Future release
        </div>
    </div>

    <p class="mb-8 3xl clear" style="clear: both">To learn more about Laravel's versioning strategy, check out the <a href="https://laravel-news.com/laravel-releases" class="text-blue-800 underline hover:text-blue-600">Laravel News "Laravel Releases" page</a>.</p>

    <h2 class="block mb-2 text-xl font-bold">Currently supported versions</h2>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="mb-8 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Version
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Release date
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Bug Fixes Until
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Security Fixes Until
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                LTS?
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $statusClassMap = [
                            App\Models\LaravelVersion::STATUS_FUTURE => 'bg-blue-300',
                            App\Models\LaravelVersion::STATUS_ACTIVE => 'bg-green-300',
                            App\Models\LaravelVersion::STATUS_SECURITY => 'bg-yellow-300',
                            App\Models\LaravelVersion::STATUS_ENDOFLIFE => 'bg-red-300',
                        ];
                        $statusTextMap = [
                            App\Models\LaravelVersion::STATUS_FUTURE => 'FUT',
                            App\Models\LaravelVersion::STATUS_ACTIVE => 'ALL',
                            App\Models\LaravelVersion::STATUS_SECURITY => 'SEC',
                            App\Models\LaravelVersion::STATUS_ENDOFLIFE => 'EOL',
                        ];
                    @endphp
                    @foreach ($activeVersions as $version)
                        <tr>
                            <th scope="col" class="w-3 {{ $statusClassMap[$version->status] }}">
                                <span class="hidden js-colorblind mx-2">{{ $statusTextMap[$version->status] }}</span>
                            </th>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                <a href="{{ $version->url }}" class="underline">{{ $version->major }} {{ $version->released_at->gt(now()) ? '(not released yet!)' : '' }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $version->released_at->gt(now()) ? $version->released_at->format('F, Y') . ' (estimated)' : $version->released_at->format('F j, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                @if ($version->ends_bugfixes_at)
                                    {{ $version->released_at->gt(now()) ? $version->ends_bugfixes_at->format('F, Y') . ' (estimated)' : $version->ends_bugfixes_at->format('F j, Y') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                @if ($version->ends_securityfixes_at)
                                    {{ $version->released_at->gt(now()) ? $version->ends_securityfixes_at->format('F, Y') . ' (estimated)' : $version->ends_securityfixes_at->format('F j, Y') }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $version->is_lts ? '✓' : '' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h2 class="block mb-1 text-xl font-bold">No longer receiving security updates!</h2>
    <p class="mb-4">Need help upgrading your app? Try <a href="https://laravelshift.com/" class="text-blue-800 underline hover:text-blue-600">Laravel Shift</a> for automated upgrades or <a href="https://tighten.co/" class="text-blue-800 underline hover:text-blue-600">contact Tighten</a> if you need more than just upgrades.</a></p>
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="mb-8 overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"></th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Version
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Release date
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Bug Fixes Until
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Security Fixes Until
                            </th>
                            <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                LTS?
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($inactiveVersions as $version)
                        <tr>
                            <th scope="col" class="w-3 {{ $statusClassMap[$version->status] }}">
                                <span class="hidden js-colorblind mx-2">{{ $statusTextMap[$version->status] }}</span>
                            </th>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                                <a href="{{ $version->url }}" class="underline">{{ $version->major }}{{ $version->major < 6 ? '.' . $version->minor : '' }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $version->released_at->format('F j, Y') }} {{ $version->released_at->gt(now()) ? '(estimated)' : '' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $version->ends_bugfixes_at ? $version->ends_bugfixes_at->format('F j, Y'): '' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $version->ends_securityfixes_at ? $version->ends_securityfixes_at->format('F j, Y') : '' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $version->is_lts ? '✓' : '' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
