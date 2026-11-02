<style>
    /* ==========================================================================
   Center Motivational Loader Card Component Styles
   ========================================================================== */

:root {
  --loader-font-sans: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  --loader-font-mono: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
  --loader-radius-md: 8px;
  --loader-radius-lg: 12px;
  --loader-radius-xl: 16px;
  --loader-radius-2xl: 24px;
}

.loader-preview-wrapper {
  position: fixed;
  inset: 0;
  width: 100vw;
  height: 100vh;
  z-index: 99999;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  background: transparent;
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transform: scale(1.02);
  transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1),
              visibility 0.3s cubic-bezier(0.16, 1, 0.3, 1),
              transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
  overflow: hidden;
}

html.dark .loader-preview-wrapper {
  background: transparent;
}

.loader-preview-wrapper.is-active {
  opacity: 1;
  visibility: visible;
  pointer-events: auto;
  transform: scale(1);
}

/* Base Card Layout */
.loader-card {
  width: 100%;
  max-width: 440px;
  border-radius: var(--loader-radius-2xl, 24px);
  border: 1px solid #cbd5e1;
  padding: 2.25rem 2rem;
  position: relative;
  overflow: hidden;
  box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 1.5rem;
  font-family: var(--loader-font-sans);
  box-sizing: border-box;
}

/* ==========================================================================
   FIRST-PAINT ANIMATION
   These animations start from CSS before the JavaScript module executes.
   ========================================================================== */

.loader-preview-wrapper.is-active .loader-card {
  animation: loaderFirstPaint 0.45s cubic-bezier(0.16, 1, 0.3, 1) both;
}

.loader-preview-wrapper.is-active .illustration-box {
  animation: loaderIllustrationFirstPaint 1.8s ease-in-out infinite;
}

.loader-preview-wrapper.is-active .quote-text {
  animation: none;
}

/* First-paint typewriter quote state. */
.quote-text.boot-typewriter {
  min-height: 58px;
}

.typewriter-cursor.boot-cursor {
  display: inline-block;
  width: 2px;
  height: 1.1em;
  margin-left: 3px;
  vertical-align: -0.12em;
  background: currentColor;
  animation: pulse 0.8s steps(1, end) infinite;
}

.quote-transition {
  animation: quoteTransition 0.25s ease-out;
}

@keyframes loaderFirstPaint {
  from {
    opacity: 0;
    transform: translateY(10px) scale(0.96);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

@keyframes loaderIllustrationFirstPaint {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.04); }
}

@keyframes quoteTransition {
  from {
    opacity: 0.45;
    transform: translateY(4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ==========================================================================
   Color Themes
   ========================================================================== */

/* 1. Slate / Clean Light */
.loader-card.theme-slate {
  background: #ffffff;
  color: #0f172a;
  border-color: #cbd5e1;
  box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.05);
}
.loader-card.theme-slate .quote-text { color: #1e293b; }
.loader-card.theme-slate .track { background: #e2e8f0; }
.loader-card.theme-slate .bar-fill { background: linear-gradient(90deg, #334155, #0f172a); }
.loader-card.theme-slate .status-text { color: #64748b; }
.loader-card.theme-slate .percent-text { color: #334155; }
.loader-card.theme-slate .illustration-box {
  background: #f1f5f9;
  border-color: #cbd5e1;
  color: #334155;
}

/* 2. Dark Cyber / Indigo */
.loader-card.theme-dark {
  background: #090d16;
  color: #f8fafc;
  border-color: #334155;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(255, 255, 255, 0.1);
}
.loader-card.theme-dark .quote-text { color: #e0e7ff; }
.loader-card.theme-dark .track { background: #1e293b; }
.loader-card.theme-dark .bar-fill {
  background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
  box-shadow: 0 0 12px rgba(99, 102, 241, 0.4);
}
.loader-card.theme-dark .status-text { color: #94a3b8; }
.loader-card.theme-dark .percent-text { color: #a5b4fc; }
.loader-card.theme-dark .illustration-box {
  background: rgba(99, 102, 241, 0.12);
  border-color: rgba(99, 102, 241, 0.3);
  color: #818cf8;
}

/* 3. Midnight Navy / Amber Gold */
.loader-card.theme-navy {
  background: #0f172a;
  color: #f8fafc;
  border-color: rgba(245, 158, 11, 0.35);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(245, 158, 11, 0.2);
}
.loader-card.theme-navy .quote-text { color: #fef3c7; }
.loader-card.theme-navy .track { background: #1e293b; }
.loader-card.theme-navy .bar-fill {
  background: linear-gradient(90deg, #f59e0b, #eab308, #ea580c);
  box-shadow: 0 0 12px rgba(245, 158, 11, 0.35);
}
.loader-card.theme-navy .status-text { color: #94a3b8; }
.loader-card.theme-navy .percent-text { color: #fbbf24; }
.loader-card.theme-navy .illustration-box {
  background: rgba(245, 158, 11, 0.12);
  border-color: rgba(245, 158, 11, 0.3);
  color: #fbbf24;
}

/* 4. Emerald Forest */
.loader-card.theme-emerald {
  background: #022c22;
  color: #ecfdf5;
  border-color: #059669;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(52, 211, 153, 0.2);
}
.loader-card.theme-emerald .quote-text { color: #d1fae5; }
.loader-card.theme-emerald .track { background: #064e3b; }
.loader-card.theme-emerald .bar-fill {
  background: linear-gradient(90deg, #34d399, #10b981, #06b6d4);
  box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
}
.loader-card.theme-emerald .status-text { color: #a7f3d0; }
.loader-card.theme-emerald .percent-text { color: #6ee7b7; }
.loader-card.theme-emerald .illustration-box {
  background: rgba(16, 185, 129, 0.15);
  border-color: rgba(16, 185, 129, 0.3);
  color: #34d399;
}

/* ==========================================================================
   Illustration Badge
   ========================================================================== */
.illustration-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.illustration-ring {
  position: absolute;
  inset: -8px;
  border-radius: 9999px;
  border: 1px solid currentColor;
  opacity: 0.25;
  animation: pingSlow 3s cubic-bezier(0, 0, 0.2, 1) infinite;
  pointer-events: none;
}

.illustration-sparkle {
  position: absolute;
  top: -4px;
  right: -4px;
  color: #f59e0b;
  animation: pulse 2s infinite;
  z-index: 10;
}

.illustration-box {
  width: 4rem;
  height: 4rem;
  border-radius: var(--loader-radius-lg, 12px);
  border: 1px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  transition: all 0.2s;
}

/* ==========================================================================
   Category Badge
   ========================================================================== */
.category-chip {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.6875rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0.2rem 0.6rem;
  border-radius: 9999px;
  background: rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.08);
  transition: opacity 0.25s ease, transform 0.25s ease,
              background-color 0.25s ease, border-color 0.25s ease,
              color 0.25s ease;
}

.category-chip.category-changing {
  opacity: 0;
  transform: translateY(-4px) scale(0.96);
}

.category-chip.category-objectives {
  background: #eff6ff;
  border-color: #bfdbfe;
  color: #1d4ed8;
}

.category-chip.category-goals {
  background: #f5f3ff;
  border-color: #ddd6fe;
  color: #6d28d9;
}

.category-chip.category-kpis {
  background: #ecfdf5;
  border-color: #a7f3d0;
  color: #047857;
}

.category-chip.category-success {
  background: #fffbeb;
  border-color: #fde68a;
  color: #b45309;
}

.theme-dark .category-chip.category-objectives,
.theme-dark .category-chip.category-goals,
.theme-dark .category-chip.category-kpis,
.theme-dark .category-chip.category-success,
.theme-navy .category-chip.category-objectives,
.theme-navy .category-chip.category-goals,
.theme-navy .category-chip.category-kpis,
.theme-navy .category-chip.category-success,
.theme-emerald .category-chip.category-objectives,
.theme-emerald .category-chip.category-goals,
.theme-emerald .category-chip.category-kpis,
.theme-emerald .category-chip.category-success {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.18);
  color: inherit;
}

.theme-dark .category-chip,
.theme-navy .category-chip,
.theme-emerald .category-chip {
  background: rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.15);
}

/* ==========================================================================
   Quote Text Area
   ========================================================================== */
.quote-container {
  min-height: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  padding: 0 0.5rem;
}

.quote-text {
  font-size: 1.125rem;
  font-weight: 500;
  line-height: 1.6;
  letter-spacing: -0.015em;
  margin: 0;
  transition: all 0.3s ease;
}

/* ==========================================================================
   Progress Bar Section
   ========================================================================== */
.progress-section {
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.track {
  width: 100%;
  background: #e2e8f0;
  border-radius: 9999px;
  overflow: hidden;
  position: relative;
}

.track.height-thin { height: 6px; }
.track.height-medium { height: 10px; }
.track.height-thick { height: 14px; }

.bar-fill {
  height: 100%;
  width: 0%;
  border-radius: 9999px;
  transition: width 0.15s ease-out;
}

.status-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 0.75rem;
  font-family: var(--loader-font-mono);
  padding-top: 0.25rem;
}

.status-indicator {
  display: flex;
  align-items: center;
  gap: 0.375rem;
}

.pulsing-dot {
  position: relative;
  display: inline-flex;
  width: 8px;
  height: 8px;
}

.pulsing-dot-ping {
  position: absolute;
  inset: 0;
  border-radius: 9999px;
  background: currentColor;
  opacity: 0.75;
  animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

.pulsing-dot-solid {
  position: relative;
  width: 8px;
  height: 8px;
  border-radius: 9999px;
  background: currentColor;
}

.percent-text {
  font-weight: 700;
}

/* ==========================================================================
   Keyframe Animations
   ========================================================================== */
@keyframes pulseGlow {
  0%, 100% {
    transform: scale(1);
    filter: drop-shadow(0 0 6px rgba(99, 102, 241, 0.3));
  }
  50% {
    transform: scale(1.05);
    filter: drop-shadow(0 0 14px rgba(99, 102, 241, 0.6));
  }
}

@keyframes spinSlow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes bounceSubtle {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-4px); }
}

@keyframes wiggle {
  0%, 100% { transform: rotate(0deg); }
  25% { transform: rotate(-6deg); }
  75% { transform: rotate(6deg); }
}

@keyframes pingSlow {
  0% { transform: scale(1); opacity: 0.7; }
  75%, 100% { transform: scale(1.6); opacity: 0; }
}

@keyframes ping {
  75%, 100% { transform: scale(2); opacity: 0; }
}

@keyframes pulse {
  50% { opacity: 0.5; }
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(6px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(16px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleFade {
  from { opacity: 0; transform: scale(0.92); }
  to { opacity: 1; transform: scale(1); }
}

.anim-pulse-glow { animation: pulseGlow 3s ease-in-out infinite; }
.anim-spin-slow { animation: spinSlow 12s linear infinite; }
.anim-bounce-subtle { animation: bounceSubtle 2s ease-in-out infinite; }
.anim-wiggle { animation: wiggle 3s ease-in-out infinite; }

.anim-fade { animation: fadeIn 0.4s ease-out forwards; }
.anim-slide { animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.anim-scale { animation: scaleFade 0.35s ease-out forwards; }

.typewriter-cursor {
  display: inline-block;
  width: 2px;
  height: 1.1em;
  background-color: currentColor;
  margin-left: 2px;
  vertical-align: middle;
  animation: pulse 0.8s infinite;
}

</style>

<!-- Standalone HTML template for the Center Motivational Loader Card -->
<div class="loader-preview-wrapper is-active" id="loader-preview-wrapper">
<div id="live-loader-card" class="loader-card theme-slate">
  <!-- 1. Top Illustration Badge -->
  <div class="illustration-container">
    <div class="illustration-ring"></div>
    {{-- <div class="illustration-sparkle" id="loader-illustration-sparkle">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none">
        <path d="M12 2l2.4 7.2L22 12l-7.6 2.8L12 22l-2.4-7.2L2 12l7.6-2.8z"/>
      </svg>
    </div> --}}
    <div class="illustration-box" id="loader-illustration-box">
      <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="anim-pulse-glow">
        <circle cx="12" cy="12" r="10"/>
        <circle cx="12" cy="12" r="6"/>
        <circle cx="12" cy="12" r="2"/>
      </svg>
    </div>
  </div>

  <!-- Category Tag -->
  <div>
    <span class="category-chip category-objectives" id="loader-category-badge">Objectives</span>
  </div>

  <!-- 2. Motivational Quote Container -->
  <div class="quote-container" id="loader-quote-container">
    <p class="quote-text" id="loader-quote-text">
      “Every great achievement begins with a clear objective.”
    </p>
  </div>

  <!-- 3. Animated Progress Bar Section -->
  <div class="progress-section">
    <div class="track height-medium" id="loader-track">
      <div class="bar-fill" id="loader-bar-fill" style="width: 0%;"></div>
    </div>

    <!-- Footer Metrics & Status -->
    <div class="status-footer">
      <div class="status-indicator">
        <span class="pulsing-dot">
          <span class="pulsing-dot-ping"></span>
          <span class="pulsing-dot-solid"></span>
        </span>
        <span class="status-text" id="loader-status-text">Loading...</span>
      </div>
      <span class="percent-text" id="loader-percent-display">0%</span>
    </div>
  </div>
</div>
</div>
<!-- ==========================================================================
     IMMEDIATE FIRST-PAINT BOOT + QUOTE ANIMATION

     This normal inline script intentionally runs before the deferred module.
     It starts the progress bar and typewriter immediately, without waiting
     for motivational_loader.js to download/execute.
     ========================================================================== -->
<script>
(function () {
    // =========================================================
    // PROGRESS BOOT
    // =========================================================
    const bar = document.getElementById('loader-bar-fill');
    const percentText = document.getElementById('loader-percent-display');
    const statusText = document.getElementById('loader-status-text');
    if (bar) {
        let progress = 0;
        let finished = false;
        function updateBootProgress() {
            if (finished) {
                return;
            }
            if (progress < 95) {
                progress++;
            }
            bar.style.width = progress + '%';
            if (percentText) {
                percentText.textContent = progress + '%';
            }
            if (statusText) {
                statusText.textContent = 'Syncing Objectives...';
            }
            window.__loaderProgress = progress;
        }
        window.__loaderBootFinish = function () {
            finished = true;
            if (window.__loaderBootTimer) {
                clearInterval(window.__loaderBootTimer);
                window.__loaderBootTimer = null;
            }
        };

        // Start immediately
        updateBootProgress();
        window.__loaderBootTimer = setInterval(
            updateBootProgress,
            50
        );
    }
    // =========================================================
    // QUOTES
    // =========================================================
    const quotes = [
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

    // =========================================================
    // QUOTE STATE
    // =========================================================
    let quoteIndex = Math.floor(Math.random() * quotes.length);

    let quoteTimer = null;
    let typewriterTimer = null;
    // IMPORTANT:
    // This is different from quoteStopped.
    //
    // quoteStopRequested = true means:
    // "Do not start another quote, but let the
    // current quote finish typing."
    let quoteStopRequested = false;

    // =========================================================
    // ELEMENT
    // =========================================================
    function getQuoteElement() {
        return document.getElementById(
            'loader-quote-text'
        );
    }

    // =========================================================
    // CLEAR TIMERS
    // =========================================================
    function clearQuoteTimers() {
        if (quoteTimer) {
            clearTimeout(quoteTimer);
            quoteTimer = null;
        }

        if (typewriterTimer) {
            clearTimeout(typewriterTimer);
            typewriterTimer = null;
        }
    }

    // =========================================================
    // CATEGORY
    // =========================================================

    function updateCategory(category) {
        const badge =
            document.getElementById(
                'loader-category-badge'
            );
        if (!badge) {
            return;
        }
        const categoryClass =
            'category-' +
            category.toLowerCase();

        badge.classList.add(
            'category-changing'
        );

        setTimeout(function () {
            // Don't change the category after
            // the loader has been requested to stop.
            if (quoteStopRequested) {
                return;
            }
            badge.textContent = category;
            badge.classList.remove(
                'category-objectives',
                'category-goals',
                'category-kpis',
                'category-success'
            );
            badge.classList.add(
                categoryClass
            );
            badge.classList.remove(
                'category-changing'
            );
        }, 150);
    }

    // =========================================================
    // TYPE QUOTE
    // =========================================================
    function typeQuote(message) {
        const quoteElement =
            getQuoteElement();
        if (!quoteElement) {
            return;
        }

        // Clear only the previous typing timer.
        if (typewriterTimer) {
            clearTimeout(
                typewriterTimer
            );
            typewriterTimer = null;
        }

        updateCategory(
            message.category
        );

        quoteElement.classList.add(
            'boot-typewriter'
        );

        quoteElement.innerHTML = '';

        // Text node
        const textNode =
            document.createTextNode('');

        // Cursor
        const cursor =
            document.createElement('span');

        cursor.className =
            'typewriter-cursor boot-cursor';

        quoteElement.appendChild(
            textNode
        );

        quoteElement.appendChild(
            cursor
        );

        let characterIndex = 0;

        // =====================================================
        // TYPE ONE CHARACTER
        // =====================================================
        function typeNextCharacter() {
            /*
             * IMPORTANT:
             *
             * DO NOT check quoteStopRequested here.
             *
             * Even if the page has finished loading,
             * the current quote must continue typing
             * until every character is displayed.
             */

            if (
                characterIndex <
                message.text.length
            ) {

                textNode.nodeValue +=
                    message.text.charAt(
                        characterIndex
                    );
                characterIndex++;
                typewriterTimer =
                    setTimeout(
                        typeNextCharacter,
                        32
                    );
                return;
            }

            // =================================================
            // CURRENT QUOTE COMPLETELY FINISHED
            // =================================================

            if (cursor) {
                cursor.remove();
            }
            typewriterTimer = null;

            /*
             * If finishLoader() requested the quote system
             * to stop while this quote was typing,
             * DO NOT start another quote.
             *
             * Instead tell finishLoader() that the quote
             * is now completely finished.
             */

            if (quoteStopRequested) {
                if (
                    typeof window.__loaderQuoteFinished ===
                    'function'
                ) {

                    window.__loaderQuoteFinished();

                }
                return;
            }


            // =================================================
            // NORMAL QUOTE ROTATION
            // =================================================
            // Keep completed quote visible for 2200ms.
            quoteTimer =
                setTimeout(
                    showNextQuote,
                    2200
                );
        }

        // Start typing
        typeNextCharacter();
    }


    // =========================================================
    // SHOW NEXT QUOTE
    // =========================================================
    function showNextQuote() {
        /*
         * If loader finish has already been requested,
         * never start another quote.
         */
        if (quoteStopRequested) {
            return;
        }

        if (quoteTimer) {
            clearTimeout(
                quoteTimer
            );
            quoteTimer = null;
        }

        const quoteElement =
            getQuoteElement();

        if (!quoteElement) {
            return;
        }

        // Transition animation
        quoteElement.classList.remove(
            'quote-transition'
        );

        void quoteElement.offsetWidth;
        quoteElement.classList.add(
            'quote-transition'
        );

        // Pick random quote
        quoteIndex =
            Math.floor(
                Math.random() *
                quotes.length
            );

        /*
         * Small pause before typing
         * the next quote.
         */
        quoteTimer =
            setTimeout(
                function () {
                    if (quoteStopRequested) {
                        return;
                    }
                    typeQuote(
                        quotes[quoteIndex]
                    );
                },
                250
            );
    }


    // =========================================================
    // REQUEST QUOTE STOP
    // =========================================================
    //
    // This function DOES NOT stop the typewriter.
    //
    // It only tells the typewriter:
    //
    // "Finish the current quote, then stop."
    //
    // =========================================================

    window.__loaderStopBootQuotes =
        function () {
            quoteStopRequested = true;
            /*
             * Stop the timer that would start another quote.
             *
             * IMPORTANT:
             * Do NOT clear typewriterTimer here.
             *
             * The current quote needs that timer to continue
             * typing.
             */
            if (quoteTimer) {
                clearTimeout(
                    quoteTimer
                );
                quoteTimer = null;
            }
            /*
             * If there is NO typewriter currently running,
             * the quote has already finished.
             *
             * We can immediately tell finishLoader()
             * that it is safe to close.
             */

            if (!typewriterTimer) {
                if (
                    typeof window.__loaderQuoteFinished ===
                    'function'
                ) {
                    window.__loaderQuoteFinished();
                }
            }
        };


    // =========================================================
    // START FIRST QUOTE
    // =========================================================

    typeQuote(
        quotes[quoteIndex]
    );

})();
</script>
<script type="module">
    import {
        LoaderCardController
    } from "{{ asset('js/motivational_loader.js') }}";

    // =====================================================
    // CREATE LOADER
    // =====================================================
    const loader =
        new LoaderCardController({
            theme: 'slate',
            illustration: 'target',
            animationType: 'typewriter',
            progressBarHeight: 'medium',
            showProgressPercentage: true,
            messageDurationMs: 2000,
            initialProgress: 0
        });

    // =====================================================
    // DO NOT CALL loader.render()
    // =====================================================
    //
    // The inline boot script has already started the
    // typewriter immediately.
    //
    // Calling loader.render() here would start another
    // typewriter animation.
    //
    // =====================================================


    // =====================================================
    // TAKE OVER PROGRESS FROM FIRST-PAINT BOOT
    // =====================================================

    let progress =
        window.__loaderProgress || 0;
    if (window.__loaderBootTimer) {
        clearInterval(
            window.__loaderBootTimer
        );
        window.__loaderBootTimer = null;
    }

    loader.setProgress(
        progress,
        'Syncing Objectives...'
    );

    const progressTimer =
        setInterval(
            () => {
                if (progress < 95) {
                    progress++;
                }
                window.__loaderProgress =
                    progress;
                loader.setProgress(
                    progress,
                    'Syncing Objectives...'
                );
            },
            50
        );

    // =====================================================
    // LOADER FINISH STATE
    // =====================================================

    let loaderFinished = false;
    let finishRequested = false;

    // =====================================================
    // ACTUAL LOADER CLOSE
    // =====================================================
    //
    // This function is called ONLY after the current
    // quote has completely finished typing.
    //
    // =====================================================

    function finishLoaderAfterQuote() {
        if (loaderFinished) {
            return;
        }
        loaderFinished = true;

        // -------------------------------------------------
        // Stop progress timer
        // -------------------------------------------------

        clearInterval(
            progressTimer
        );

        // -------------------------------------------------
        // Stop boot progress
        // -------------------------------------------------

        if (
            window.__loaderBootFinish
        ) {
            window.__loaderBootFinish();
        }

        // -------------------------------------------------
        // Progress = 100%
        // -------------------------------------------------
        loader.setProgress(
            100,
            'Ready!'
        );

        // -------------------------------------------------
        // Keep 100% visible for 500ms
        // -------------------------------------------------
        setTimeout(
            () => {
                const wrapper =
                    document.getElementById(
                        'loader-preview-wrapper'
                    );
                if (wrapper) {
                    wrapper.classList.remove(
                        'is-active'
                    );
                }

                // -------------------------------------------------
                // Destroy controller
                // -------------------------------------------------

                loader.destroy();
            },
            500
        );
    }


    // =====================================================
    // REQUEST LOADER FINISH
    // =====================================================

    function finishLoader() {
        if (finishRequested) {
            return;
        }
        finishRequested = true;

        /*
         * Ask the quote system to stop AFTER the
         * current quote has completely finished typing.
         *
         * __loaderStopBootQuotes() does NOT interrupt
         * the typewriter.
         */

        window.__loaderQuoteFinished =
            finishLoaderAfterQuote;
        if (
            window.__loaderStopBootQuotes
        ) {
            window.__loaderStopBootQuotes();
        } else {
            /*
             * Fallback in case the boot quote script
             * hasn't registered the function.
             */
            finishLoaderAfterQuote();
        }
    }


    // =====================================================
    // PAGE LOAD DETECTION
    // =====================================================

    /*
     * Protect against the module executing after
     * window.load.
     */

    if (
        document.readyState ===
        'complete'
    ) {
        finishLoader();
    } else {
        window.addEventListener(
            'load',
            finishLoader,
            {
                once: true
            }
        );
    }

</script>
