/**
 * Awwwards-Inspired Interactions
 * Modern, subtle interactive elements for enhanced UX
 */

class NurseryInteractions {
    constructor() {
        this.init();
    }

    init() {
        // Initialize all interactions when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initializeAll());
        } else {
            this.initializeAll();
        }
    }

    initializeAll() {
        this.initCustomCursor();
        this.initScrollAnimations();
        this.initMicroInteractions();
        this.initParallax();
        this.initSmoothScroll();
        this.initImageHoverEffects();
    }

    /**
     * Custom Cursor (Subtle, not distracting)
     */
    initCustomCursor() {
        // Only on desktop devices
        if (window.innerWidth < 1024) return;

        const cursor = document.createElement('div');
        cursor.className = 'custom-cursor';
        document.body.appendChild(cursor);

        const cursorDot = document.createElement('div');
        cursorDot.className = 'custom-cursor-dot';
        document.body.appendChild(cursorDot);

        let mouseX = 0, mouseY = 0;
        let cursorX = 0, cursorY = 0;
        let dotX = 0, dotY = 0;

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX;
            mouseY = e.clientY;
        });

        // Smooth cursor follow
        const animateCursor = () => {
            // Cursor ring follows with delay
            cursorX += (mouseX - cursorX) * 0.15;
            cursorY += (mouseY - cursorY) * 0.15;
            cursor.style.transform = `translate(${cursorX}px, ${cursorY}px)`;

            // Dot follows immediately
            dotX += (mouseX - dotX) * 0.5;
            dotY += (mouseY - dotY) * 0.5;
            cursorDot.style.transform = `translate(${dotX}px, ${dotY}px)`;

            requestAnimationFrame(animateCursor);
        };
        animateCursor();

        // Cursor states for interactive elements
        const interactiveElements = 'a, button, [role="button"], input, textarea, select, .card, .product-card';

        document.addEventListener('mouseover', (e) => {
            if (e.target.closest(interactiveElements)) {
                cursor.classList.add('cursor-hover');
                cursorDot.classList.add('cursor-hover');
            }
        });

        document.addEventListener('mouseout', (e) => {
            if (e.target.closest(interactiveElements)) {
                cursor.classList.remove('cursor-hover');
                cursorDot.classList.remove('cursor-hover');
            }
        });

        document.addEventListener('mousedown', () => {
            cursor.classList.add('cursor-click');
            cursorDot.classList.add('cursor-click');
        });

        document.addEventListener('mouseup', () => {
            cursor.classList.remove('cursor-click');
            cursorDot.classList.remove('cursor-click');
        });
    }

    /**
     * Scroll-Triggered Animations (Enhanced AOS)
     */
    initScrollAnimations() {
        // Scroll progress indicator
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        document.body.appendChild(progressBar);

        window.addEventListener('scroll', () => {
            const windowHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (window.scrollY / windowHeight) * 100;
            progressBar.style.width = `${scrolled}%`;
        });

        // Parallax scroll for hero sections
        const heroSections = document.querySelectorAll('.hero, [data-parallax]');
        if (heroSections.length > 0) {
            window.addEventListener('scroll', () => {
                const scrolled = window.scrollY;
                heroSections.forEach(section => {
                    const speed = section.dataset.parallaxSpeed || 0.5;
                    section.style.transform = `translateY(${scrolled * speed}px)`;
                });
            });
        }

        // Staggered fade-in for grids
        this.observeGridItems();
    }

    observeGridItems() {
        const grids = document.querySelectorAll('.grid, [class*="grid-cols"]');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const items = entry.target.children;
                    Array.from(items).forEach((item, index) => {
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 100); // Stagger by 100ms
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        grids.forEach(grid => {
            // Set initial state
            Array.from(grid.children).forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            });
            observer.observe(grid);
        });
    }

    /**
     * Micro-Interactions
     */
    initMicroInteractions() {
        // Button ripple effect
        this.addRippleEffect();

        // Card tilt effect (subtle 3D)
        this.addCardTilt();

        // Input focus animations
        this.enhanceInputs();

        // Add to cart animation
        this.enhanceAddToCart();
    }

    addRippleEffect() {
        const buttons = document.querySelectorAll('button, .btn, [role="button"]');

        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                this.appendChild(ripple);

                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';

                setTimeout(() => ripple.remove(), 600);
            });
        });
    }

    addCardTilt() {
        const cards = document.querySelectorAll('.card, .product-card, .glass-card, [data-tilt]');

        cards.forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 20; // Subtle rotation
                const rotateY = (centerX - x) / 20;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            });

            // Add transition
            card.style.transition = 'transform 0.3s ease';
        });
    }

    enhanceInputs() {
        const inputs = document.querySelectorAll('input, textarea, select');

        inputs.forEach(input => {
            // Add focus ring animation
            input.addEventListener('focus', () => {
                input.style.transform = 'scale(1.02)';
            });

            input.addEventListener('blur', () => {
                input.style.transform = 'scale(1)';
            });

            input.style.transition = 'transform 0.2s ease, box-shadow 0.2s ease';
        });
    }

    enhanceAddToCart() {
        // Listen for add to cart buttons
        document.addEventListener('click', (e) => {
            const addToCartBtn = e.target.closest('[data-add-to-cart], .add-to-cart');
            if (!addToCartBtn) return;

            // Create success animation
            const icon = addToCartBtn.querySelector('i, svg') || addToCartBtn;
            icon.style.transform = 'scale(1.3)';

            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 300);

            // Show success feedback
            this.showToast('Added to cart!', 'success');
        });
    }

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /**
     * Parallax Effects
     */
    initParallax() {
        const parallaxElements = document.querySelectorAll('[data-parallax-speed]');

        if (parallaxElements.length === 0) return;

        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY;

            parallaxElements.forEach(element => {
                const speed = parseFloat(element.dataset.parallaxSpeed) || 0.5;
                const yPos = -(scrolled * speed);
                element.style.transform = `translate3d(0, ${yPos}px, 0)`;
            });
        });
    }

    /**
     * Smooth Scroll
     */
    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    }

    /**
     * Image Hover Effects
     */
    initImageHoverEffects() {
        const productImages = document.querySelectorAll('.product-card img, [data-zoom]');

        productImages.forEach(img => {
            const container = img.parentElement;
            container.style.overflow = 'hidden';

            container.addEventListener('mouseenter', () => {
                img.style.transform = 'scale(1.1)';
            });

            container.addEventListener('mouseleave', () => {
                img.style.transform = 'scale(1)';
            });

            img.style.transition = 'transform 0.5s ease';
        });
    }
}

// Initialize when script loads
new NurseryInteractions();

// Export for use in other scripts if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NurseryInteractions;
}
