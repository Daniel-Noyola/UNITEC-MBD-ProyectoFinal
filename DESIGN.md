# Design System: Sistema de Gestión Bibliotecaria
**Project ID:** ProyectoFinal-SGB-2026 (Local / Stitch-Ready)

## 1. Visual Theme & Atmosphere
The **Sistema de Gestión Bibliotecaria** embodies a **modern, refined academic** aesthetic engineered for **fluid, effortless counter operations**. Designed primarily for librarians working at a busy university service desk, the interface balances scholarly gravitas with contemporary SaaS clarity. 

The atmosphere is **airy, calm, and structured**, using generous whitespace and crisp hairline borders to prevent visual fatigue during long shifts. Rather than feeling like a dense legacy database tool, every interaction—from scanning a book's inventory barcode to verifying a student's solvency—flows naturally with clear visual hierarchy, immediate semantic feedback, and whisper-soft elevation.

## 2. Color Palette & Roles
The palette pairs an authoritative university navy with warm paper-like neutrals and high-clarity semantic indicators for book and user states:

* **Warm Alabaster (`#f8fafc`):** Main workspace canvas background. Provides a soft, low-glare neutral surface inspired by archival paper so content cards stand out effortlessly.
* **Pure Crisp White (`#ffffff`):** Elevated surface for data cards, modals, slide-over drawers, and circulation tables. Creates clean separation from the canvas.
* **Subtle Mist Border (`#e2e8f0`):** Hairline 1px borders and table row dividers. Defines structure without heavy visual noise.
* **Deep Academic Navy (`#1e3a8a`):** Primary brand and action accent. Used for primary call-to-action buttons ("Confirmar Préstamo", "Iniciar Sesión"), active sidebar navigation indicators, and institutional headers to evoke academic trust.
* **Slate Teal (`#0f766e`):** Secondary operational accent. Used for circulation flow highlights, active step indicators, and quick-action counter badges.
* **Deep Ink Slate (`#0f172a`):** Primary text color for page headings, book titles, user names, and key KPI figures, ensuring maximum legibility.
* **Muted Slate (`#475569`):** Secondary text color for form labels, table metadata (ISBNs, shelf locations), and helper descriptions.
* **Emerald Green (`#15803d`) on Soft Mint (`#dcfce7`):** Positive semantic pair used exclusively for **Disponible** (copy status) and **Habilitado** (user solvency) pill badges.
* **Warm Amber (`#b45309`) on Soft Cream (`#fef3c7`):** Cautionary semantic pair used for **Prestado** and **En Mantenimiento** copy statuses, as well as loans approaching their due date.
* **Crimson Red (`#b91c1c`) on Soft Rose (`#fee2e2`):** Alert semantic pair used for **Bloqueado** user status, **Vencido** loans, **Extraviado / Baja** copies, and **Pendiente** overdue fines ($10.00 MXN/día).

## 3. Typography Rules
Typography bridges **scholarly heritage** with **high-speed operational scannability**:

* **Display & Page Headings (Editorial Serif — *Newsreader* or *Merriweather*):** Used for main view titles, login branding, and empty-state headers in semi-bold weight with tight, balanced tracking. This subtly anchors the system in an academic library context.
* **UI, Tables & Controls (Geometric Sans-Serif — *Inter*):** Used across navigation, buttons, form inputs, and data tables in regular (400) and medium (500) weights for crisp legibility at small sizes.
* **Tabular Numerals (`tabular-nums`):** Enforced on all ISBNs, inventory barcodes, student/employee IDs, dates, loan quotas (e.g., `2 / 3`), and monetary fine amounts (`$30.00 MXN`) so columns align vertically and numbers never jitter during live filtering.

## 4. Component Stylings
* **Buttons:** Subtly rounded corners (`8px`) with comfortable horizontal padding (`16px–20px`) and smooth hover transitions. Primary actions use solid Deep Academic Navy (`#1e3a8a`) with white text; secondary actions use crisp white surfaces with Subtle Mist Borders (`#e2e8f0`); destructive or fine-collection actions use clear semantic styling with confirmation steps.
* **Cards & Containers:** Gently rounded corners (`12px`) on Pure Crisp White (`#ffffff`) with a 1px hairline border (`#e2e8f0`) and whisper-soft diffused shadows on hover or modal elevation. Generous internal padding (`20px–24px`) keeps KPIs and circulation forms breathable.
* **Inputs & Forms:** Clean white backgrounds with subtle 1px borders (`#cbd5e1`), `8px` rounded corners, and a crisp Deep Academic Navy (`#1e3a8a`) focus ring. Search and barcode inputs feature a leading magnifying glass or barcode icon and generous height (`42px`) for rapid desk scanning.
* **Status Badges:** Pill-shaped (`9999px`) compact tags with medium-weight uppercase or title-case text and semantic pastel backgrounds so librarians can assess availability and user eligibility at a single glance.
* **Data Tables & Slide-Over Drawers:** Airy row height (`52px–56px`) with subtle hover tint (`#f1f5f9`) and zero vertical gridlines. Detailed inspections (such as a book's physical copies or a user's solvency history) open in smooth right-hand slide-over drawers to preserve context without jarring full-page reloads.

## 5. Layout Principles
* **Persistent Application Shell:** Authenticated views share a collapsible left navigation sidebar (`240px` expanded) and a clean top utility bar (`64px` height) housing a global quick-search bar, active librarian badge, and a persistent `+ Nuevo Préstamo` shortcut.
* **Desktop-First Counter Ergonomics:** Designed around a `1280px–1440px` desktop viewport with a 12-column responsive grid and `24px` gutters. High-frequency desk tasks (Préstamos y Devoluciones) use focused two-column or two-step layouts that keep the primary action above the fold.
* **Progressive Disclosure:** High-level catalogs and directories stay clean and scannable, deferring secondary details (physical copy barcodes, shelf/aisle locations, fine payment history) into slide-over panels and clean modals.
