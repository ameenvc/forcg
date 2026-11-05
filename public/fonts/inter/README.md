# CGBOD Inter UI

Locally served UI subset derived from the Inter variable font in google/fonts/ofl/inter (SIL Open Font License 1.1; see OFL.txt). Copyright remains with The Inter Project Authors. Internal family names were changed to CGBOD Inter UI to identify this modified UI subset.

The WOFF2 file retains variable weights 400–700, uses Inter's optical-size 14 design, and includes Latin/extended Latin, Greek, Cyrillic, punctuation, currency and common symbols. Other scripts fall back to the operating system's installed fonts. The Inter CSS alias also points to this same resource for legacy module styles. The application uses one same-origin preloaded resource with font-display: swap; it does not depend on Google Fonts.

Built with fontTools subset/varLib.instancer and Brotli from Inter[opsz,wght].ttf at https://github.com/google/fonts/tree/main/ofl/inter. The original font is not duplicated in the application. This modified font is also distributed under OFL 1.1. On a future font update, change the asset filename to invalidate long-lived font caches.
