/**
 * Creative Tech Tutorials — main.js
 * Enhances Elementor-built pages with interactive features.
 */

(function ($) {
    'use strict';

    /* ── Reading Progress Bar ─────────────────────────────── */
    function initProgressBar() {
        if ( !CT.is_single ) return;
        const bar = $('<div class="ct-progress-bar"></div>');
        $('body').prepend(bar);
        $(window).on('scroll.ctProgress', function () {
            const pct = ($(window).scrollTop() / ($(document).height() - $(window).height())) * 100;
            bar.css('width', pct.toFixed(1) + '%');
        });
    }

    /* ── Back to Top ──────────────────────────────────────── */
    function initBackToTop() {
        const btn = $('<button class="ct-back-top" aria-label="Back to top">↑</button>');
        $('body').append(btn);
        $(window).on('scroll.ctTop', () => btn.toggleClass('visible', $(window).scrollTop() > 400));
        btn.on('click', () => $('html, body').animate({ scrollTop: 0 }, 600));
    }

    /* ── Code Copy Buttons ────────────────────────────────── */
    function initCodeCopy() {
        $('.ct-body pre, .tutorial-body pre').each(function () {
            const pre = $(this);
            if ( pre.find('.ct-copy-btn').length ) return;
            const btn = $('<button class="ct-copy-btn">Copy</button>');
            btn.on('click', function () {
                const text = pre.find('code').text() || pre.text();
                navigator.clipboard && navigator.clipboard.writeText(text).then(() => {
                    btn.text('Copied!');
                    setTimeout(() => btn.text('Copy'), 2000);
                });
            });
            pre.css('position', 'relative').prepend(btn);
        });
    }

    /* ── Auto Table of Contents ───────────────────────────── */
    function initTOC() {
        const headings = $('.ct-body h2');
        if ( headings.length < 3 ) return;

        const toc  = $('<div class="ct-box" style="margin-bottom:28px;"><h3>📑 In This Tutorial</h3><ol style="margin:0;padding-left:20px;"></ol></div>');
        const list = toc.find('ol');

        headings.each(function (i) {
            const id = 'ct-section-' + i;
            $(this).attr('id', id);
            list.append('<li style="margin-bottom:6px;"><a href="#' + id + '" style="color:var(--ct-blue);text-decoration:none;">' + $(this).text() + '</a></li>');
        });

        headings.first().before(toc);

        toc.find('a').on('click', function (e) {
            e.preventDefault();
            const target = $($(this).attr('href'));
            if ( target.length ) $('html, body').animate({ scrollTop: target.offset().top - 80 }, 500);
        });
    }

    /* ── Image Lightbox ───────────────────────────────────── */
    function initLightbox() {
        $('.ct-body img, .tutorial-body img').each(function () {
            const img = $(this).css('cursor', 'zoom-in');
            img.on('click', function () {
                const overlay = $(
                    '<div style="position:fixed;inset:0;background:rgba(0,0,0,0.92);z-index:99999;display:flex;align-items:center;justify-content:center;cursor:zoom-out;padding:20px;"></div>'
                );
                $('<img>').attr({ src: img.attr('src'), alt: img.attr('alt') })
                          .css({ maxWidth:'90vw', maxHeight:'90vh', objectFit:'contain', borderRadius:'8px' })
                          .appendTo(overlay);
                overlay.appendTo('body').on('click', () => overlay.remove());
            });
        });
    }

    /* ── Archive Filter Dropdowns ─────────────────────────── */
    function initFilters() {
        $('#ct-filter-category, #ct-filter-tool, #ct-filter-difficulty').on('change', function () {
            window.location.href = $(this).val() || CT.archive_url;
        });
    }

    /* ── Init ─────────────────────────────────────────────── */
    $(document).ready(function () {
        initProgressBar();
        initBackToTop();
        initCodeCopy();
        initTOC();
        initLightbox();
        initFilters();
    });

})(jQuery);
