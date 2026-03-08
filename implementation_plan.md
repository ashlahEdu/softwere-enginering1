# Laravel Financial App - Backend Implementation Plan

## Goal
Complete the Laravel `finance-app` with full backend functionality: database, models, controllers, and AI features.

## Tech Stack
- **Laravel 11** (existing)
- **SQLite** database (simple, no MySQL setup needed)
- **Tailwind CSS v4** (existing)
- **Gemini API** for AI features

---

## Database Schema

### members
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| name | string | required |
| role | string | nullable |
| status | enum | active/inactive |
| timestamps | | |

### income
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| date | date | required |
| amount | decimal | required |
| source | string | required |
| description | text | nullable |
| member_id | bigint | FK, nullable |
| timestamps | | |

### expenses
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| date | date | required |
| category | string | required |
| amount | decimal | required |
| description | text | nullable |
| member_id | bigint | FK, required |
| proof_image | string | nullable |
| invoice_number | string | auto-generated |
| timestamps | | |

### invoices
| Column | Type | Notes |
|--------|------|-------|
| id | bigint | PK |
| invoice_number | string | unique |
| expense_id | bigint | FK |
| org_name | string | default |
| timestamps | | |

---

## Controllers

| Controller | Actions |
|------------|---------|
| DashboardController | index (summary stats) |
| MemberController | index, store, update, destroy, toggleStatus |
| IncomeController | index, store, update, destroy |
| ExpenseController | index, store, update, destroy, analyzeImage |
| InvoiceController | index, show, downloadPdf |
| ReportController | index, askAI |

---

## AI Integration
- **Expense Image Analysis**: POST image to Gemini Vision API
- **Financial Q&A**: Build context from DB, send to Gemini

---

## Verification
1. Run migrations
2. Test CRUD on all entities
3. Verify dashboard calculations
4. Test AI features with API key
