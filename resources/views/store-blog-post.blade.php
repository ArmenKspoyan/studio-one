<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __("Create New Posts") }}
                            </h2>
                        </header>
                        <form method="post" action="{{ route('store.blog-post') }}" class="mt-6 space-y-6">
                            @csrf
                            <div>
                                <x-input-label for="title" :value="__('Title')"/>
                                <x-text-input id="title" name="title" type="text"
                                              class="mt-1 block w-full" required
                                              autofocus autocomplete="name"/>
                                <x-input-error class="mt-2" :messages="$errors->get('title')"/>
                            </div>
                            <div>
                                <x-input-label for="name" :value="__('Content')"/>
                                <x-textarea name="content" placeholder="Content ..."
                                            class="mb-3">
                                </x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('content')"/>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </form>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
