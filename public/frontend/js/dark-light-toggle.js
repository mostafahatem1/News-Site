/* ============================================
   DARK/LIGHT MODE JAVASCRIPT - FIXED
   ============================================ */

(function() {
    'use strict';

    const THEME_KEY = 'theme-preference';
    const DARK_MODE_CLASS = 'dark-mode';
    const THEME_TOGGLE_ID = 'theme-toggle';

    // ✅ Initialize Theme on Page Load
    function initializeTheme() {
        const body = document.body;
        const html = document.documentElement;

        // Get saved theme or default to light
        const savedTheme = localStorage.getItem(THEME_KEY) || 'light';

        // Apply theme
        applyTheme(savedTheme);
        updateToggleIcon(savedTheme);

        // Set data attribute for CSS
        html.setAttribute('data-theme', savedTheme);

        console.log('✅ Theme initialized:', savedTheme);
    }

    // ✅ Apply Theme to Body
    function applyTheme(theme) {
        const body = document.body;

        if (theme === 'dark') {
            body.classList.add(DARK_MODE_CLASS);
            body.classList.remove('light-mode');
        } else {
            body.classList.remove(DARK_MODE_CLASS);
            body.classList.add('light-mode');
        }
    }

    // ✅ Update Toggle Button Icon
    function updateToggleIcon(theme) {
        const toggle = document.getElementById(THEME_TOGGLE_ID);
        if (!toggle) return;

        const icon = toggle.querySelector('i');
        if (!icon) return;

        if (theme === 'dark') {
            // في dark mode، عرض sun icon (للتبديل إلى light)
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            toggle.setAttribute('title', '☀️ Switch to Light Mode');
        } else {
            // في light mode، عرض moon icon (للتبديل إلى dark)
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            toggle.setAttribute('title', '🌙 Switch to Dark Mode');
        }
    }

    // ✅ Toggle Theme Function
    function toggleTheme() {
        const html = document.documentElement;
        const body = document.body;

        const currentTheme = localStorage.getItem(THEME_KEY) || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        // Save to localStorage
        localStorage.setItem(THEME_KEY, newTheme);

        // Apply theme
        applyTheme(newTheme);
        updateToggleIcon(newTheme);
        html.setAttribute('data-theme', newTheme);

        // Show notification
        showNotification(newTheme);

        console.log('🔄 Theme changed to:', newTheme);
    }

    // ✅ Show Notification Message
    function showNotification(theme) {
        // Remove existing notification if any
        const existing = document.getElementById('theme-notification');
        if (existing) existing.remove();

        const message = theme === 'dark' ? '🌙 Dark Mode Activated' : '🌞 Light Mode Activated';

        const notification = document.createElement('div');
        notification.id = 'theme-notification';
        notification.textContent = message;

        // RTL Support
        const isRTL = document.documentElement.dir === 'rtl' ||
                     document.documentElement.lang?.includes('ar');

        notification.style.cssText = `
            position: fixed;
            ${isRTL ? 'left' : 'right'}: 20px;
            top: 80px;
            background: linear-gradient(135deg, #ff6f61 0%, #ff8877 100%);
            color: #ffffff;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(255, 111, 97, 0.4);
            z-index: 9999;
            font-weight: bold;
            font-size: 14px;
            animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            backdrop-filter: blur(10px);
        `;

        document.body.appendChild(notification);

        // Auto remove after 2 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 2000);
    }

    // ✅ Setup Event Listeners
    function setupEventListeners() {
        const themeToggle = document.getElementById(THEME_TOGGLE_ID);

        if (!themeToggle) {
            console.warn('⚠️ Theme toggle button not found!');
            return;
        }

        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleTheme();
        });

        // Prevent double-click issues
        themeToggle.addEventListener('dblclick', function(e) {
            e.preventDefault();
        });
    }

    // ✅ Add Animation Styles
    function addAnimationStyles() {
        // Check if animations already added
        if (document.getElementById('theme-animations')) return;

        const style = document.createElement('style');
        style.id = 'theme-animations';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }

            /* Smooth transitions for all elements */
            body.dark-mode,
            body.light-mode {
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            /* RTL Support */
            [dir="rtl"] #theme-notification {
                animation: slideInRTL 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            }

            @keyframes slideInRTL {
                from {
                    transform: translateX(-400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // ✅ Detect System Theme Preference (Optional)
    function setupSystemPreference() {
        if (!window.matchMedia) return;

        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');

        const handleChange = (e) => {
            // Only apply if user hasn't manually set a preference
            if (!localStorage.getItem(THEME_KEY)) {
                const newTheme = e.matches ? 'dark' : 'light';
                applyTheme(newTheme);
                updateToggleIcon(newTheme);
            }
        };

        darkModeQuery.addEventListener('change', handleChange);
    }

    // ✅ Wait for DOM Ready
    function whenReady(callback) {
        if (document.readyState !== 'loading') {
            callback();
        } else {
            document.addEventListener('DOMContentLoaded', callback);
        }
    }

    // ✅ Initialize Everything
    whenReady(function() {
        console.log('🚀 Initializing Dark/Light Mode System...');

        addAnimationStyles();
        initializeTheme();
        setupEventListeners();
        setupSystemPreference();

        console.log('✅ Dark/Light Mode System Ready!');
    });

    // ✅ Expose to window for debugging
    window.themeUtils = {
        toggleTheme: toggleTheme,
        applyTheme: applyTheme,
        getCurrentTheme: () => localStorage.getItem(THEME_KEY) || 'light',
        setTheme: (theme) => {
            if (['light', 'dark'].includes(theme)) {
                localStorage.setItem(THEME_KEY, theme);
                applyTheme(theme);
                updateToggleIcon(theme);
            }
        }
    };

})();
