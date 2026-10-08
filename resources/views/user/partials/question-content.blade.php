<div data-question-id="{{ $currentQuestion->id }}" data-question-index="{{ $currentQuestionIndex }}">
    @if(isset($currentQuestion->image))
    <div class="mb-6">
        <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
            <img src="{{ $currentQuestion->image }}"
                 alt="Question Image"
                 class="w-full h-auto max-h-64 object-contain">
            <div class="bg-gray-50 px-4 py-2 text-center text-sm text-gray-500 border-t border-gray-200">
                Gambar Referensi
            </div>
        </div>
    </div>
    @endif
    <div class="mb-8">
        <div class="flex items-start mb-4">
            <div class="text-justify">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $currentQuestion->question_text }}</h3>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $currentQuestion->question_optional }}</h3>
            </div>
        </div>
    </div>
    <div class="space-y-4">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Pilih Jawaban:</h4>
        @foreach($currentQuestion->choices as $choice)
        <div class="group">
            <input type="radio"
                   name="answer"
                   id="option_{{ $choice->id }}"
                   value="{{ $choice->id }}"
                   class="hidden peer"
                   @if($previousAnswer && $previousAnswer->answer == $choice->id) checked @endif>
            <label for="option_{{ $choice->id }}"
                   class="flex items-start p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all duration-200 hover:border-red-300 hover:bg-red-50 peer-checked:border-red-500 peer-checked:bg-gradient-to-r peer-checked:from-red-50 peer-checked:to-red-100 peer-checked:shadow-sm">
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-8 h-8 flex items-center justify-center border-2 border-gray-300 rounded-lg group-hover:border-red-400 peer-checked:border-red-500 peer-checked:bg-red-500 transition-all duration-200">
                        <span class="text-sm font-semibold text-gray-600 group-hover:text-red-600 peer-checked:text-white">
                            {{ $choice->option_label }}
                        </span>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600">{{ $choice->option_text }}</p>
                </div>
                <div class="ml-auto flex-shrink-0 opacity-0 peer-checked:opacity-100 transition-opacity duration-200">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </label>
        </div>
        @endforeach
    </div>
</div>
