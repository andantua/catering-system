@tailwind base;
@tailwind components;
@tailwind utilities;

@layer components {
    .card {
        @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700;
    }
    .card-header {
        @apply px-5 py-3 border-b border-gray-200 dark:border-gray-700 font-semibold text-gray-800 dark:text-gray-200;
    }
    .card-body {
        @apply p-5;
    }
    .btn-primary {
        @apply bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg transition duration-200 font-medium;
    }
    .btn-secondary {
        @apply bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg transition duration-200 font-medium;
    }
    .btn-success {
        @apply bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 font-medium;
    }
    .btn-danger {
        @apply bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200 font-medium;
    }
    .btn-warning {
        @apply bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition duration-200 font-medium;
    }
    .btn-outline {
        @apply border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg transition duration-200 font-medium;
    }
    .input {
        @apply w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100;
    }
    .stat-card {
        @apply bg-white dark:bg-gray-800 rounded-xl shadow-sm p-4 hover:shadow-md transition-all cursor-default;
    }
    .badge {
        @apply px-2 py-1 rounded-full text-xs font-medium;
    }
    .badge-primary {
        @apply bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200;
    }
    .badge-success {
        @apply bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200;
    }
    .badge-warning {
        @apply bg-amber-100 dark:bg-amber-900 text-amber-800 dark:text-amber-200;
    }
    .badge-danger {
        @apply bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200;
    }
}

/* Dark mode dodatkowe */
.dark .stat-card {
    @apply bg-gray-800 border-gray-700;
}
.dark .bg-white {
    @apply bg-gray-800;
}
.dark .bg-gray-50 {
    @apply bg-gray-900;
}
.dark .text-gray-600 {
    @apply text-gray-400;
}