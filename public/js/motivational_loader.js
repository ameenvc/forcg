export const SVG_ICONS = {

    target: `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="28"
             height="28"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="anim-pulse-glow">

            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="6"/>
            <circle cx="12" cy="12" r="2"/>

        </svg>
    `,

    compass: `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="28"
             height="28"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="anim-spin-slow">

            <circle cx="12" cy="12" r="10"/>

            <polygon points="
                16.24 7.76
                14.12 14.12
                7.76 16.24
                9.88 9.88
                16.24 7.76
            "/>

        </svg>
    `,

    rocket: `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="28"
             height="28"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="anim-bounce-subtle">

            <path d="
                M4.5 16.5
                c-1.5 1.26-2 5-2 5
                s3.74-.5 5-2
                c.71-.84.7-2.13-.09-2.91
                a2.18 2.18 0 0 0-2.91-.09z
            "/>

            <path d="
                m12 15
                -3-3
                a22 22 0 0 1 2-3.95
                A12.88 12.88 0 0 1 22 2
                c0 2.72-.78 7.5-6 11
                a22.35 22.35 0 0 1-4 2z
            "/>

            <path d="
                M9 12H4
                s.55-3.03 2-4
                c1.62-1.08 5 0 5 0
            "/>

            <path d="
                M12 15v5
                s3.03-.55 4-2
                c1.08-1.62 0-5 0-5
            "/>

        </svg>
    `,

    chart: `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="28"
             height="28"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="anim-pulse-glow">

            <line x1="12" x2="12" y1="20" y2="10"/>
            <line x1="18" x2="18" y1="20" y2="4"/>
            <line x1="6" x2="6" y1="20" y2="16"/>

        </svg>
    `,

    trophy: `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="28"
             height="28"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round"
             class="anim-wiggle">

            <path d="
                M6 9H4.5
                a2.5 2.5 0 0 1 0-5H6
            "/>

            <path d="
                M18 9h1.5
                a2.5 2.5 0 0 0 0-5H18
            "/>

            <path d="M4 22h16"/>

            <path d="
                M10 14.66V17
                c0 .55-.45 1-1 1H7v2
                h10v-2h-2
                c-.55 0-1-.45-1-1v-2.34
            "/>

            <path d="
                M6 4h12v7
                a6 6 0 0 1-12 0V4z
            "/>

        </svg>
    `,

    sparkle: `
        <svg xmlns="http://www.w3.org/2000/svg"
             width="14"
             height="14"
             viewBox="0 0 24 24"
             fill="currentColor"
             stroke="none">

            <path d="
                M12 2
                l2.4 7.2
                L22 12
                l-7.6 2.8
                L12 22
                l-2.4-7.2
                L2 12
                l7.6-2.8z
            "/>

        </svg>
    `
};


export const DEFAULT_MESSAGES = [
    {
        id: 'msg-1',
        text: 'Every great achievement begins with a clear objective.',
        category: 'Objectives'
    },
    {
        id: 'msg-2',
        text: 'Define your purpose today, achieve your success tomorrow.',
        category: 'Goals'
    },
    {
        id: 'msg-3',
        text: 'Clear objectives create measurable results.',
        category: 'Objectives'
    },
    {
        id: 'msg-4',
        text: 'Every KPI is a step toward meaningful progress.',
        category: 'KPIs'
    },
    {
        id: 'msg-5',
        text: 'Success starts with a well-defined objective.',
        category: 'Success'
    },
    {
        id: 'msg-6',
        text: 'Small objectives completed consistently lead to great achievements.',
        category: 'Objectives'
    },
    {
        id: 'msg-7',
        text: 'Plan with purpose. Execute with confidence. Succeed with results.',
        category: 'Success'
    },
    {
        id: 'msg-8',
        text: 'Turn your goals into measurable success, one objective at a time.',
        category: 'Goals'
    },
    {
        id: 'msg-9',
        text: 'Your next achievement starts with the objective you define today.',
        category: 'Objectives'
    },
    {
        id: 'msg-10',
        text: 'Progress is measured by the objectives you accomplish, not just the goals you imagine.',
        category: 'KPIs'
    }
];


export class LoaderCardController {

    constructor(options = {}) {
        this.container =
            options.container ||
            document.getElementById(
                'live-loader-card'
            );

        this.messages = options.messages || [...DEFAULT_MESSAGES];

        this.currentIndex = options.startIndex || 0;

        this.config = {
            theme: options.theme || 'slate',
            illustration: options.illustration || 'target',
            animationType: options.animationType || 'typewriter',
            progressBarHeight: options.progressBarHeight || 'medium',
            showProgressPercentage: options.showProgressPercentage !== false,
            messageDurationMs: options.messageDurationMs || 4500
        };

        this.typewriterInterval = null;
        this.quoteTimer = null;

        this.progress = options.initialProgress !== undefined ? options.initialProgress : 0;
    }


    // =========================================================
    // CONFIG
    // =========================================================
    setConfig(newConfig) {
        this.config = { ...this.config, ...newConfig };
        this.render();
    }


    // =========================================================
    // MESSAGES
    // =========================================================

    setMessages(newMessages) {
        if (Array.isArray(newMessages) && newMessages.length > 0) {

            this.messages = newMessages;
            this.currentIndex =
                this.currentIndex %
                this.messages.length;
            this.render();
        }
    }


    // =========================================================
    // QUOTE INDEX
    // =========================================================
    setQuoteIndex(index) {
        if (this.messages.length === 0) {
            return;
        }

        this.currentIndex =
            (index +this.messages.length) %
            this.messages.length;
        this.renderQuote();
    }


    // =========================================================
    // START QUOTE ANIMATION
    // =========================================================
    //
    // Not used by the inline boot script.
    // The inline boot script handles the first-paint
    // typewriter animation.
    //
    // =========================================================

    startQuoteAnimation() {
        this.stopQuoteAnimation();
        this.quoteTimer =
            window.setInterval(
                () => {
                    this.currentIndex =
                        (
                            this.currentIndex + 1
                        ) %
                        this.messages.length;
                    this.renderQuote();
                },
                this.config.messageDurationMs
            );
    }


    // =========================================================
    // STOP QUOTE ANIMATION
    // =========================================================
    stopQuoteAnimation() {
        if (this.quoteTimer) {
            clearInterval(
                this.quoteTimer
            );
            this.quoteTimer = null;
        }
    }


    // =========================================================
    // PROGRESS
    // =========================================================
    setProgress(percent, statusText) {
        this.progress =
            Math.max(
                0,
                Math.min(
                    100,
                    percent
                )
            );


        const fillEl =
            document.getElementById(
                'loader-bar-fill'
            );

        const percentEl =
            document.getElementById(
                'loader-percent-display'
            );

        const statusEl =
            document.getElementById(
                'loader-status-text'
            );


        if (fillEl) {
            fillEl.style.width =
                `${Math.max(3, this.progress)}%`;
        }

        if (percentEl) {
            percentEl.textContent =
                `${this.progress}%`;
        }

        if (statusEl) {
            statusEl.textContent =
                statusText ||
                (
                    this.progress < 100
                        ? 'Syncing Objectives...'
                        : 'Ready!'
                );
        }
    }


    // =========================================================
    // RENDER QUOTE
    // =========================================================

    renderQuote() {
        const currentMsg =
            this.messages[
                this.currentIndex
            ] ||
            this.messages[0];

        if (!currentMsg) {
            return;
        }

        const quoteEl =
            document.getElementById(
                'loader-quote-text'
            );

        const catBadge =
            document.getElementById(
                'loader-category-badge'
            );


        if (catBadge) {
            catBadge.textContent =
                currentMsg.category ||
                'Objectives';
        }


        if (!quoteEl) {
            return;
        }


        if (this.typewriterInterval) {
            clearInterval(
                this.typewriterInterval
            );
            this.typewriterInterval = null;
        }


        quoteEl.classList.remove(
            'anim-fade',
            'anim-slide',
            'anim-scale'
        );

        if (
            this.config.animationType ===
            'typewriter'
        ) {
            quoteEl.textContent = '';
            const text = currentMsg.text;
            let charIdx = 0;
            const cursor = document.createElement(
                                'span'
                            );

            cursor.className = 'typewriter-cursor';

            quoteEl.appendChild(cursor);

            this.typewriterInterval =
                window.setInterval(
                    () => {
                        if (charIdx < text.length) {
                            cursor.before(text.charAt(charIdx));
                            charIdx++;
                        } else {
                            cursor.remove();
                            if (this.typewriterInterval) {
                                clearInterval(
                                    this.typewriterInterval
                                );

                                this.typewriterInterval =
                                    null;
                            }
                        }
                    },
                    30
                );
        } else {
            quoteEl.textContent = `“${currentMsg.text}”`;

            if (this.config.animationType === 'slide') {
                quoteEl.classList.add('anim-slide');
            } else if (
                this.config.animationType ===
                'scale'
            ) {
                quoteEl.classList.add('anim-scale');
            } else {
                quoteEl.classList.add('anim-fade');
            }
        }
    }


    // =========================================================
    // RENDER
    // =========================================================
    render() {
        const cardEl =
            this.container ||
            document.getElementById(
                'live-loader-card'
            );

        if (!cardEl) {
            return;
        }
        // Theme
        cardEl.classList.remove(
            'theme-slate',
            'theme-dark',
            'theme-navy',
            'theme-emerald'
        );

        cardEl.classList.add(
            `theme-${this.config.theme}`
        );

        // Illustration
        const illBox =
            document.getElementById(
                'loader-illustration-box'
            );

        const sparkleBox =
            document.getElementById(
                'loader-illustration-sparkle'
            );

        if (illBox) {
            illBox.innerHTML =
                SVG_ICONS[
                    this.config.illustration
                ] ||
                SVG_ICONS.target;
        }


        if (sparkleBox) {

            sparkleBox.innerHTML =
                SVG_ICONS.sparkle;
        }


        // Progress height
        const trackEl =
            document.getElementById(
                'loader-track'
            );

        if (trackEl) {

            trackEl.classList.remove(
                'height-thin',
                'height-medium',
                'height-thick'
            );

            trackEl.classList.add(
                `height-${this.config.progressBarHeight}`
            );
        }


        // Percentage

        const percentEl =
            document.getElementById(
                'loader-percent-display'
            );


        if (percentEl) {

            percentEl.style.display =
                this.config.showProgressPercentage
                    ? 'inline'
                    : 'none';
        }


        // Quote + progress

        this.renderQuote();

        this.setProgress(
            this.progress
        );
    }


    // =========================================================
    // DESTROY
    // =========================================================

    destroy() {

        if (this.typewriterInterval) {

            clearInterval(
                this.typewriterInterval
            );

            this.typewriterInterval = null;
        }


        this.stopQuoteAnimation();
    }
}
