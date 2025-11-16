import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,ts,jsx,tsx,vue}',
    './resources/css/**/*.css',
  ],
  theme: {
    extend: {},
  },
  plugins: [forms],
};

