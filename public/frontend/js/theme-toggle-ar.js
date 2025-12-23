
class ThemeToggle {
    constructor() {
        this.htmlElement = document.documentElement;
        this.toggleButton = document.getElementById('theme-toggle');
        this.storageKey = 'theme-preference';

        this.init();
    }

    init() {
        // تحميل الـ Theme المحفوظ أو استخدام النظام
        const savedTheme = localStorage.getItem(this.storageKey);
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        this.setTheme(theme);

        // ربط الزر
        if (this.toggleButton) {
            this.toggleButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleTheme();
            });
        }
    }

    setTheme(theme) {
        // التحقق من أن الـ Theme صحيح
        if (theme !== 'light' && theme !== 'dark') return;

        // تطبيق الـ Theme على HTML
        this.htmlElement.setAttribute('data-color-scheme', theme);

        // حفظ في localStorage
        localStorage.setItem(this.storageKey, theme);

        // تحديث الزر
        this.updateButton(theme);

        // إطلاق حدث لـ Components الأخرى
        document.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme: theme }
        }));
    }

    toggleTheme() {
        const currentTheme = this.htmlElement.getAttribute('data-color-scheme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);
    }

    updateButton(theme) {
        if (!this.toggleButton) return;

        const icon = this.toggleButton.querySelector('i');
        if (!icon) return;

        // تغيير الأيقونة والـ Title
        if (theme === 'dark') {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            this.toggleButton.setAttribute('title', 'تبديل إلى Light Mode');
        } else {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            this.toggleButton.setAttribute('title', 'تبديل إلى Dark Mode');
        }
    }
}

// تهيئة عند تحميل الصفحة
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ThemeToggle();
    });
} else {
    new ThemeToggle();
}
