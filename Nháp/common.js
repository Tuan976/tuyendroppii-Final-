// Common JavaScript for Tuyến Droppii website
// Navigation and common functionality

// Toggle mobile menu
function toggleMenu() {
    const nav = document.querySelector('.navbar-nav') || document.querySelector('.nav-links');
    const hamburger = document.querySelector('.hamburger') || document.querySelector('.mobile-menu-toggle');
    if (nav) {
        nav.classList.toggle('active');
        document.body.classList.toggle('no-scroll', nav.classList.contains('active'));
    }
    if (hamburger) hamburger.classList.toggle('active');
}

// Toggle QR Code
function toggleQRCode() {
    const qrContainer = document.querySelector('.qr-code-container');
    if (qrContainer) {
        qrContainer.classList.toggle('active');
    }
}

// Highlight active navigation item
function highlightActiveNav() {
    const path = window.location.pathname.toLowerCase();
    const navLinks = document.querySelectorAll('.navbar-nav a, .nav-links a');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (path.endsWith(href.toLowerCase()) || 
            (path === '/' && href === 'index.html') ||
            (path.endsWith('/') && href === 'index.html'))) {
            link.classList.add('active');
            // If it's in a dropdown, also highlight the parent dropdown
            const dropdown = link.closest('.dropdown');
            if (dropdown) {
                dropdown.querySelector('> a').classList.add('active');
            }
        }
    });
}

// Navbar scroll effect
function handleNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
}

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
    highlightActiveNav();
    handleNavbarScroll();
    
    // Attach event listeners
    const hamburger = document.querySelector('.hamburger') || document.querySelector('.mobile-menu-toggle');
    if (hamburger) {
        hamburger.addEventListener('click', toggleMenu);
    }
    
    // Close mobile menu when clicking outside OR clicking a nav link
    document.addEventListener('click', (e) => {
        const nav = document.querySelector('.navbar-nav') || document.querySelector('.nav-links');
        const hamburger = document.querySelector('.hamburger') || document.querySelector('.mobile-menu-toggle');
        if (!nav) return;

        const clickedLink = e.target.closest('.navbar-nav a, .nav-links a');
        if (clickedLink && nav.classList.contains('active')) {
            nav.classList.remove('active');
            document.body.classList.remove('no-scroll');
            if (hamburger) hamburger.classList.remove('active');
            return;
        }

        if (nav.classList.contains('active')) {
            const insideNav = nav.contains(e.target) || (hamburger && hamburger.contains(e.target));
            if (!insideNav) {
                nav.classList.remove('active');
                document.body.classList.remove('no-scroll');
                if (hamburger) hamburger.classList.remove('active');
            }
        }
    });

    // Close on ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const nav = document.querySelector('.navbar-nav') || document.querySelector('.nav-links');
            const hamburger = document.querySelector('.hamburger') || document.querySelector('.mobile-menu-toggle');
            if (nav && nav.classList.contains('active')) {
                nav.classList.remove('active');
                document.body.classList.remove('no-scroll');
                if (hamburger) hamburger.classList.remove('active');
            }
        }
    });
});

// Smooth scroll for anchor links with navbar offset
function scrollWithOffset(target) {
    const navbar = document.querySelector('.navbar');
    const rect = target.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const navH = navbar ? Math.min(navbar.offsetHeight || 64, 96) : 64;
    const y = rect.top + scrollTop - (navH + 8);
    window.scrollTo({ top: y, behavior: 'smooth' });
}

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href.length > 1) {
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                scrollWithOffset(target);
            }
        }
    });
});

// Mobile dropdown toggle
document.querySelectorAll('.navbar-nav .dropdown > a').forEach(a => {
    a.addEventListener('click', e => {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            const parent = a.parentElement;
            parent.classList.toggle('open');
        }
    });
});

