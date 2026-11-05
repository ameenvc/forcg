# Shared form design — risk pilot

The risk form is the pilot for a shared design across CGBOD modules. Only this form currently adopts it. Finalize its appearance with the product owner before rolling it out to other modules.

## Common foundation

- `public/css/cgbod-forms.css`: opt-in palette, fields, focus states, buttons, cards, and section headings. Tokens are defined on `.cgbod-form`, so branding can be adjusted centrally.
- `resources/views/shared/forms/assets.blade.php`: loads the stylesheet with an asset timestamp.
- `resources/views/shared/forms/section-heading.blade.php`: reusable numbered section heading with an accessible heading ID.
- `public/css/risk-form.css`: risk-specific layout, matrix status, and AI dialog. Keep these features out of the common foundation until another module needs them.

The palette uses navy for content, indigo for primary actions, teal for assistance and section accents, and neutral surfaces. Green, amber and red remain reserved for status meaning. Example-library notices remain clearly labelled; color does not imply that a live AI provider is connected.

## Adopt in a future module

```blade
@include('shared.forms.assets')
<div class="cgbod-form">
    <div class="cgbod-form-card">
        <header class="cgbod-form-header">...</header>
        <section class="cgbod-form-section" aria-labelledby="audit-context-title">
            @include('shared.forms.section-heading', [
                'number' => '01',
                'headingId' => 'audit-context-title',
                'title' => 'Audit context',
                'description' => 'Set the scope and responsibilities.'
            ])
            {{-- Existing fields and validation stay here. --}}
        </section>
    </div>
</div>
```

Give each module its own layout spacing where needed. Reuse the common tokens and controls rather than duplicating hex colors or risk-specific classes. Keep required fields and status labels explicit, include visible focus styles, and support reduced motion.

## Rollout checklist

1. Approve the risk pilot on desktop and mobile, including enabled, disabled, error, and review states.
2. Inventory create/edit/modal forms in the next module and map their current field names, dependent selectors, file uploads, rich text and validation.
3. Apply the shared assets and section component to one representative form; preserve routes, permissions, submission payloads and approval behavior.
4. Check server validation and actual browser interactions, including keyboard navigation and responsive layout, before expanding to that module's remaining forms.

This is a styling foundation, not a replacement for module-specific models, controllers or permissions. Do not apply a global CSS override to every form at once.
