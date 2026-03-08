# Laravel Finance App & AI Integration 💰✨

A modern financial management system built with **Laravel 12**, featuring smart insights powered by **Google Gemini AI**, built-in PDF invoice generation, and full membership management.

## 🌟 Key Features

- **Dashboard**: Comprehensive overview of total income, expenses, and current financial balance.
- **Member Management**: Manage users/members (CRUD) with status toggling capabilities.
- **Income Tracking**: Add, edit, remove, and manage multiple income streams seamlessly.
- **Expense Tracking & AI Analysis**: Keep track of expenses. Upload receipts or expense images and have **Google Gemini AI** analyze them automatically!
- **Invoicing System**: View and generate professional PDF invoices for your transactions.
- **Smart Reports & Q&A**: View financial reports and interact with your financial data by getting advice directly from Gemini AI using the **"Ask AI"** feature.

## 🛠 Tech Stack

- **Backend Framework:** Laravel 12.x (PHP 8.2+)
- **Database:** SQLite (Default, configured for portability)
- **Frontend / UI:** Blade templates, Tailwind CSS (via Vite)
- **AI Integration:** Google Gemini API
- **PDF Generation:** Laravel wrapper for invoice generation

---

## 🚀 Getting Started

### Prerequisites

- [PHP >= 8.2](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/en/)
- Google Gemini API Key

### Installation

1. **Clone or Extract the Repository:**
   Navigate into the project directory:
   ```bash
   cd finance-app
   ```

2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

3. **Install NPM Dependencies:**
   ```bash
   npm install
   ```

4. **Environment Setup:**
   Copy the example environment configuration file:
   ```bash
   cp .env.example .env
   ```

   Generate an application key:
   ```bash
   php artisan key:generate
   ```

5. **Configure Database & AI (`.env`):**
   Open the `.env` file. By default, it's set to SQLite. Ensure you add your Google Gemini API Key (replace `your_gemini_api_key_here` with your actual key):
   ```env
   DB_CONNECTION=sqlite
   GEMINI_API_KEY="your_gemini_api_key_here"
   ```

6. **Run Migrations (Database Setup):**
   ```bash
   php artisan migrate
   ```

7. **Build Frontend Assets:**
   Compile the Tailwind CSS and assets for production:
   ```bash
   npm run build
   ```
   *(Or run `npm run dev` for local hot-reloading)*

8. **Start the Application Server:**
   ```bash
   php artisan serve
   ```
   Then optionally start the queue or queue listener if queueing API interactions for Gemini:
   ```bash
   php artisan queue:listen
   ```
   *(If you are setting this up quickly, you can use the built-in dev setup via `npm run dev`, which uses concurrently to run the server, vite, and workers).*

Visit the app at `http://localhost:8000`.

---

## 📂 Project Structure Walkthrough

- `app/Http/Controllers/`: Contains the logic for the Dashboard, Members, Income, Expenses, Invoices, and Reports.
  - *e.g., `ExpenseController::analyzeImage` and `ReportController::askAI` manage the AI integrations.*
- `resources/views/`: Blade templates structured by feature (`income`, `expenses`, `members`, `reports`, `invoice`, `dashboard`).
- `routes/web.php`: Defines all web entry points and API integration endpoints.

## 🤝 Contributing & Maintenance

If you wish to modify or extend the functionality:
- **Routing:** Inspect and modify `routes/web.php`.
- **Styling:** Tailwind configuration is available in `tailwind.config.js`. Ensure you run `npm run dev` when modifying Blade templates or styling.
- **Controllers & Logic:** Review `app/Http/Controllers` for any specific endpoint adjustments.
