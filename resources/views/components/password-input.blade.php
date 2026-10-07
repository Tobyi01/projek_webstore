<div class="relative" data-password-toggle>
    <x-text-input {{ $attributes->merge(['type' => 'password', 'class' => 'pe-24']) }} />
    <button
        type="button"
        class="absolute inset-y-0 end-0 flex items-center px-3 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:text-gray-400 dark:hover:text-gray-200"
        data-password-toggle-button
        aria-label="Tampilkan password"
        aria-pressed="false"
    >
        <svg data-password-visible-icon aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.5-6.75 9.75-6.75S21.75 12 21.75 12s-3.5 6.75-9.75 6.75S2.25 12 2.25 12Z" />
            <circle cx="12" cy="12" r="3" />
        </svg>
        <svg data-password-hidden-icon aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.58 10.59a2 2 0 0 0 2.83 2.83M9.88 5.24A10.9 10.9 0 0 1 12 5.03c6.25 0 9.75 6.97 9.75 6.97a16.2 16.2 0 0 1-3.06 3.92M6.23 6.23C3.61 7.99 2.25 12 2.25 12s3.5 6.97 9.75 6.97c1.1 0 2.1-.23 3-.61" />
        </svg>
    </button>
</div>
