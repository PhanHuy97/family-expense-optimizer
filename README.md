# Family Expense Optimizer

Mini MVC PHP project using AdminLTE for the dashboard UI.

## Run

Point your web server document root to `public`.

With PHP built-in server:

```bash
php -S localhost:8000 -t public
```

Then open:

```text
http://localhost:8000/dashboard
```

## Structure

- `public/index.php`: front controller and route registration.
- `app/Core`: minimal router and base controller.
- `app/Controllers/DashboardController.php`: dashboard sample data.
- `app/Views/layouts`: AdminLTE layout split into `header.php`, `sidebar.php`, `footer.php`.
- `app/Views/dashboard/index.php`: Family Expense Optimizer dashboard with Chart.js.
