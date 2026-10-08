@if ($paginator->hasPages())
    <ul class="flex flex-wrap items-center">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li>
                <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed text-sm">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </span>
            </li>
        @else
            <li>
                <a href="{{ $paginator->previousPageUrl() }}" 
                   class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-sm">
                    <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li>
                    <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 text-sm">{{ $element }}</span>
                </li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="mx-1">
                            <span class="px-3 py-2 border border-red-600 bg-red-600 text-white rounded-lg text-sm">{{ $page }}</span>
                        </li>
                    @else
                        <li class="mx-1">
                            <a href="{{ $url }}" 
                               class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-sm">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a href="{{ $paginator->nextPageUrl() }}" 
                   class="px-3 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all text-sm">
                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                </a>
            </li>
        @else
            <li>
                <span class="px-3 py-2 border border-gray-300 rounded-lg text-gray-400 cursor-not-allowed text-sm">
                    Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                </span>
            </li>
        @endif
    </ul>
@endif