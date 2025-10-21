{{--// edit exhisting atribut title dan body di note--}}

<x-app-layout>

    {{-- HEADER SLOT --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Note: {{ $note->title }}
        </h2>
    </x-slot>

    {{-- MAIN CONTENT AREA --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Form Container --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                
                {{-- Form Start --}}
                {{-- 🛑 1. Action includes the note, Method is POST 🛑 --}}
                <form action="{{ route('notes.update', $note) }}" method="POST" class="p-6 lg:p-8">
                    
                    @csrf
                    {{-- 🛑 2. REQUIRED: Tells Laravel to treat POST as PUT/PATCH 🛑 --}}
                    @method('PUT') 

                    {{-- Title Input --}}
                    <div class="mb-6">
                        <label for="title" class="block font-medium text-lg text-gray-700 dark:text-gray-300 mb-2">Note Title</label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            placeholder="Enter a title for your note"
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 @error('title') border-red-500 @enderror" 
                            
                            {{-- 🛑 3. Pre-fill the current or old value 🛑 --}}
                            value="{{ old('title', $note->title) }}" 
                            required
                        />
                        @error('title')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Body Input (Textarea) --}}
                    <div class="mb-8">
                        <label for="body" class="block font-medium text-lg text-gray-700 dark:text-gray-300 mb-2">Note Content</label>
                        <textarea
                            id="content"
                            name="content"
                            rows="10"
                            placeholder="Start writing your note here..."
                            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-lg shadow-sm focus:border-blue-500 dark:focus:border-blue-600 focus:ring-blue-500 dark:focus:ring-blue-600 @error('body') border-red-500 @enderror"
                            required
                        >{{ old('content', $note->content) }}</textarea>  ```

                        @error('content')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-end space-x-4">
                        
                        {{-- Cancel Button --}}
                        <a href="{{ route('notes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                            Cancel
                        </a>

                        {{-- Save Button --}}
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save Changes
                        </button>
                    </div>

                </form>
                {{-- Form End --}}

            </div>
        </div>
    </div>

</x-app-layout>