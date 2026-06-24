<div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-0.5">
    <form method="POST" action="{{ route('language.switch', 'fr') }}">
        @csrf
        <button type="submit"
                class="px-2.5 py-1 text-xs font-semibold rounded-md transition-all duration-150
                       {{ app()->getLocale() === 'fr'
                          ? 'bg-white dark:bg-gray-700 text-primary-600 shadow-sm'
                          : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
            FR
        </button>
    </form>
    <form method="POST" action="{{ route('language.switch', 'en') }}">
        @csrf
        <button type="submit"
                class="px-2.5 py-1 text-xs font-semibold rounded-md transition-all duration-150
                       {{ app()->getLocale() === 'en'
                          ? 'bg-white dark:bg-gray-700 text-primary-600 shadow-sm'
                          : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
            EN
        </button>
    </form>
</div>
