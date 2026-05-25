# Kế Hoạch Triển Khai Tiếp Theo Cho Đồ Án Quản Lý Chi Tiêu Gia Đình

## 1. Tình Trạng Source Code Hiện Tại

Dự án hiện tại đã có nền tảng ban đầu cho một website PHP thuần dùng giao diện AdminLTE.

Các phần đã có:

- `public/index.php` làm front controller.
- Autoload đơn giản cho namespace `App\`.
- `app/Core/Router.php` xử lý route GET cơ bản.
- `app/Core/Controller.php` có hàm `view()`, `url()` và `db()`.
- `app/Core/Database.php` kết nối MySQL bằng PDO.
- `config/app.php` chứa cấu hình tên ứng dụng.
- `config/database.php` chứa cấu hình database `family_expense_optimizer`.
- Layout AdminLTE tách thành:
  - `app/Views/layouts/header.php`
  - `app/Views/layouts/sidebar.php`
  - `app/Views/layouts/footer.php`
- Dashboard hiện có:
  - `app/Controllers/DashboardController.php`
  - `app/Views/dashboard/index.php`
- Dashboard đang dùng AdminLTE `small-box`, `card` và Chart.js.
- CSS tùy chỉnh nằm ở `public/assets/css/app.css`.
- File `test-db.php` dùng để kiểm tra kết nối database.

Các phần chưa có:

- Chưa có session đăng nhập.
- Chưa có đăng ký, đăng nhập, đăng xuất.
- Chưa có bảng database chính thức trong `database/schema.sql`.
- Chưa có model `User`, `Income`, `Expense`, `Setting`, `PurchasePlan`.
- Chưa có CRUD khoản thu.
- Chưa có CRUD khoản chi.
- Chưa có trang thiết lập tài chính.
- Chưa có kế hoạch mua sắm.
- Dashboard hiện vẫn dùng dữ liệu mẫu hard-code, chưa lấy dữ liệu từ MySQL.
- Sidebar hiện mới có link dashboard hoạt động, các menu khác chưa có route thật.

## 2. Định Hướng Phù Hợp Với Source Hiện Tại

Đây là đồ án tốt nghiệp sinh viên năm 4, nên tiếp tục phát triển theo hướng đơn giản, vừa đủ và dễ giải thích.

Không cần đổi sang framework lớn. Không cần viết lại toàn bộ project. Source hiện tại đã có MVC mini và AdminLTE, vì vậy kế hoạch nên tiếp tục mở rộng trên nền tảng này.

Mục tiêu cuối cùng:

- Hoàn thiện đăng ký, đăng nhập, đăng xuất.
- Tạo database đủ cho quản lý thu chi.
- Thay dữ liệu mẫu dashboard bằng dữ liệu thật.
- Làm CRUD khoản thu và khoản chi.
- Làm thiết lập hạn mức chi tiêu.
- Làm kế hoạch mua sắm với thuật toán đề xuất tháng mua đơn giản.
- Giữ giao diện AdminLTE nhất quán.

## 3. Công Nghệ Giữ Nguyên

- PHP thuần.
- MySQL.
- PDO.
- AdminLTE qua CDN như source hiện tại.
- Font Awesome qua CDN.
- Chart.js qua CDN.
- Laragon hoặc XAMPP.

Không cần cài Composer nếu không có nhu cầu. Không cần Laravel, React, Vue hoặc API riêng.

## 4. Cấu Trúc Source Code Mục Tiêu

Giữ cấu trúc hiện tại và bổ sung các file còn thiếu:

```text
family-expense-optimizer/
|-- app/
|   |-- Controllers/
|   |   |-- AuthController.php
|   |   |-- DashboardController.php
|   |   |-- IncomeController.php
|   |   |-- ExpenseController.php
|   |   |-- SettingController.php
|   |   `-- PurchasePlanController.php
|   |-- Core/
|   |   |-- Controller.php
|   |   |-- Database.php
|   |   `-- Router.php
|   |-- Models/
|   |   |-- User.php
|   |   |-- Income.php
|   |   |-- Expense.php
|   |   |-- Setting.php
|   |   `-- PurchasePlan.php
|   `-- Views/
|       |-- layouts/
|       |-- auth/
|       |-- dashboard/
|       |-- incomes/
|       |-- expenses/
|       |-- settings/
|       `-- purchase_plans/
|-- config/
|   |-- app.php
|   `-- database.php
|-- public/
|   |-- index.php
|   `-- assets/
|       `-- css/
|           `-- app.css
|-- database/
|   `-- schema.sql
|-- .htaccess
|-- index.php
`-- test-db.php
```

## 5. Bước 1: Hoàn Thiện Nền Tảng Đang Có

### Mục tiêu

Sửa và bổ sung nhẹ phần core để các module sau dùng được.

### Việc cần làm

- Thêm `session_start()` trong `public/index.php`.
- Bổ sung route POST trong `Router.php` vì các form cần submit.
- Bổ sung hàm `post()` trong `Router.php`.
- Bổ sung hàm `redirect()` trong `Controller.php`.
- Bổ sung hàm `requireLogin()` trong `Controller.php`.
- Bổ sung flash message đơn giản qua session.
- Kiểm tra lại `base_path` để chạy được khi đặt project trong Laragon.

### Kết quả cần đạt

- Route GET và POST đều hoạt động.
- Controller có thể redirect sau khi thêm, sửa, xóa.
- Có thể chặn trang nội bộ nếu chưa đăng nhập.
- Layout AdminLTE vẫn chạy bình thường.

## 6. Bước 2: Tạo Database Schema

### Mục tiêu

Tạo file `database/schema.sql` đúng với phạm vi đồ án, không quá nhiều bảng.

### Schema đề xuất

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE incomes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    income_date DATE NOT NULL,
    note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category VARCHAR(100) DEFAULT 'Khác',
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    expense_date DATE NOT NULL,
    note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    monthly_budget DECIMAL(15,2) DEFAULT 0,
    minimum_balance DECIMAL(15,2) DEFAULT 0,
    expected_monthly_income DECIMAL(15,2) DEFAULT 0,
    expected_monthly_expense DECIMAL(15,2) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE purchase_plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    desired_month DATE NOT NULL,
    suggested_month DATE NULL,
    status VARCHAR(50) DEFAULT 'pending',
    note TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Lý do thiết kế

- Không tạo bảng danh mục riêng để tránh làm đồ án quá lớn.
- Khoản chi dùng cột `category` dạng text là đủ.
- Mỗi dữ liệu nghiệp vụ đều có `user_id` để tách dữ liệu theo tài khoản.

## 7. Bước 3: Làm Đăng Ký, Đăng Nhập, Đăng Xuất

### File cần thêm

- `app/Controllers/AuthController.php`
- `app/Models/User.php`
- `app/Views/auth/login.php`
- `app/Views/auth/register.php`

### Route cần thêm trong `public/index.php`

```php
$router->get('/login', [App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [App\Controllers\AuthController::class, 'login']);
$router->get('/register', [App\Controllers\AuthController::class, 'showRegister']);
$router->post('/register', [App\Controllers\AuthController::class, 'register']);
$router->post('/logout', [App\Controllers\AuthController::class, 'logout']);
```

### Yêu cầu xử lý

- Mật khẩu lưu bằng `password_hash`.
- Đăng nhập kiểm tra bằng `password_verify`.
- Sau khi login lưu `user_id`, `user_name`, `user_email` vào session.
- Sau khi register tạo luôn setting mặc định cho user.
- Nếu chưa đăng nhập thì redirect về `/login`.

### Giao diện AdminLTE

- Trang login/register có thể dùng layout riêng đơn giản của AdminLTE.
- Không cần dùng sidebar ở trang login/register.
- Form dùng `card`, `form-control`, `btn btn-primary`.

## 8. Bước 4: Cập Nhật Layout AdminLTE Theo Trạng Thái Đăng Nhập

### Việc cần sửa

- `header.php` hiển thị tên user nếu đã đăng nhập.
- `sidebar.php` đổi các link `#` thành route thật.
- Sidebar active theo URL hiện tại.
- Thêm menu:
  - `/dashboard`
  - `/incomes`
  - `/expenses`
  - `/settings`
  - `/purchase-plans`
- Thêm form logout dùng POST.

### Lưu ý source hiện tại

Hiện `sidebar.php` đang có các menu khoản thu, khoản chi, hạn mức nhưng link vẫn là `#`. Cần thay bằng route thật sau khi tạo controller.

## 9. Bước 5: Làm CRUD Khoản Thu

### File cần thêm

- `app/Controllers/IncomeController.php`
- `app/Models/Income.php`
- `app/Views/incomes/index.php`
- `app/Views/incomes/create.php`
- `app/Views/incomes/edit.php`

### Route cần thêm

```php
$router->get('/incomes', [App\Controllers\IncomeController::class, 'index']);
$router->get('/incomes/create', [App\Controllers\IncomeController::class, 'create']);
$router->post('/incomes', [App\Controllers\IncomeController::class, 'store']);
$router->get('/incomes/edit', [App\Controllers\IncomeController::class, 'edit']);
$router->post('/incomes/update', [App\Controllers\IncomeController::class, 'update']);
$router->post('/incomes/delete', [App\Controllers\IncomeController::class, 'delete']);
```

### Chức năng

- Danh sách khoản thu của user đang đăng nhập.
- Thêm khoản thu.
- Sửa khoản thu.
- Xóa khoản thu.

### Giao diện AdminLTE

- Danh sách dùng `card` và `table table-bordered table-hover`.
- Form thêm/sửa dùng `card card-primary`.
- Nút thêm dùng `btn btn-primary`.
- Nút sửa dùng `btn btn-warning btn-sm`.
- Nút xóa dùng `btn btn-danger btn-sm`.

## 10. Bước 6: Làm CRUD Khoản Chi

### File cần thêm

- `app/Controllers/ExpenseController.php`
- `app/Models/Expense.php`
- `app/Views/expenses/index.php`
- `app/Views/expenses/create.php`
- `app/Views/expenses/edit.php`

### Chức năng

- Danh sách khoản chi của user đang đăng nhập.
- Thêm khoản chi.
- Sửa khoản chi.
- Xóa khoản chi.
- Chọn loại chi từ danh sách cố định.

### Loại chi cố định

- Ăn uống
- Đi lại
- Học tập
- Điện nước
- Mua sắm
- Y tế
- Khác

### Giao diện AdminLTE

Tương tự khoản thu: dùng `card`, `table`, `form-control`, `btn`.

## 11. Bước 7: Chuyển Dashboard Từ Dữ Liệu Mẫu Sang Dữ Liệu Thật

### Tình trạng hiện tại

`DashboardController.php` đang hard-code:

- `$income = 38500000`
- `$expense = 29200000`
- `$budgetLimit = 30000000`
- Dữ liệu biểu đồ 6 tháng cũng đang là mảng mẫu.

### Việc cần làm

- Tạo model `Income` có hàm tính tổng thu.
- Tạo model `Expense` có hàm tính tổng chi.
- Tạo model `Setting` có hàm lấy setting của user.
- Trong `DashboardController`, lấy `user_id` từ session.
- Tính tổng thu tháng hiện tại từ bảng `incomes`.
- Tính tổng chi tháng hiện tại từ bảng `expenses`.
- Tính số dư hiện tại = tổng thu toàn bộ - tổng chi toàn bộ.
- Lấy `monthly_budget` từ bảng `settings`.
- Tạo dữ liệu biểu đồ 6 tháng gần nhất bằng query thật.

### Giữ lại phần giao diện hiện có

View `app/Views/dashboard/index.php` hiện đã dùng AdminLTE tốt, nên chỉ cần thay dữ liệu truyền từ controller. Không cần viết lại toàn bộ view.

## 12. Bước 8: Làm Trang Thiết Lập Tài Chính

### File cần thêm

- `app/Controllers/SettingController.php`
- `app/Models/Setting.php`
- `app/Views/settings/edit.php`

### Dữ liệu cần nhập

- `monthly_budget`: hạn mức chi tiêu tháng.
- `minimum_balance`: số dư tối thiểu.
- `expected_monthly_income`: thu nhập dự kiến mỗi tháng.
- `expected_monthly_expense`: chi tiêu dự kiến mỗi tháng.

### Route cần thêm

```php
$router->get('/settings', [App\Controllers\SettingController::class, 'edit']);
$router->post('/settings', [App\Controllers\SettingController::class, 'update']);
```

### Kết quả cần đạt

- User cập nhật được thiết lập.
- Dashboard dùng thiết lập để cảnh báo.
- Kế hoạch mua sắm dùng thiết lập để tính tháng đề xuất.

## 13. Bước 9: Làm Kế Hoạch Mua Sắm

### File cần thêm

- `app/Controllers/PurchasePlanController.php`
- `app/Models/PurchasePlan.php`
- `app/Views/purchase_plans/index.php`
- `app/Views/purchase_plans/create.php`

### Chức năng vừa đủ

- Xem danh sách kế hoạch.
- Thêm kế hoạch.
- Xóa kế hoạch.
- Tự động tính tháng đề xuất khi thêm.

Không bắt buộc làm sửa kế hoạch để giữ đồ án gọn.

### Thuật toán đề xuất

```php
function suggestPurchaseMonth(
    float $currentBalance,
    float $planAmount,
    float $minimumBalance,
    float $expectedIncome,
    float $expectedExpense,
    string $desiredMonth
): ?string {
    $balance = $currentBalance;
    $month = new DateTime($desiredMonth);

    for ($i = 0; $i < 12; $i++) {
        if (($balance - $planAmount) >= $minimumBalance) {
            return $month->format('Y-m-01');
        }

        $balance = $balance + $expectedIncome - $expectedExpense;
        $month->modify('+1 month');
    }

    return null;
}
```

### Trạng thái

- `can_buy`: mua được đúng tháng mong muốn.
- `delayed`: nên dời sang tháng khác.
- `not_found`: chưa tìm được tháng phù hợp trong 12 tháng.

## 14. Bước 10: Kiểm Tra Luồng Chính

Sau khi hoàn thiện các module, test theo thứ tự:

1. Import `database/schema.sql`.
2. Kiểm tra `test-db.php` kết nối được database.
3. Đăng ký tài khoản.
4. Đăng nhập.
5. Thêm khoản thu.
6. Thêm khoản chi.
7. Kiểm tra dashboard đã lấy dữ liệu thật.
8. Cập nhật thiết lập tài chính.
9. Kiểm tra cảnh báo hạn mức.
10. Tạo kế hoạch mua sắm.
11. Kiểm tra tháng đề xuất.
12. Đăng xuất.
13. Thử vào `/dashboard` khi chưa đăng nhập, phải bị chuyển về `/login`.

## 15. Bảo Mật Cơ Bản Cần Có

Với đồ án này, chỉ cần làm các điểm cơ bản nhưng phải đúng:

- Dùng `password_hash`.
- Dùng `password_verify`.
- Query dùng prepared statement.
- Dữ liệu hiển thị ra view dùng `htmlspecialchars`.
- Các trang nội bộ gọi `requireLogin()`.
- Sửa và xóa dữ liệu luôn có điều kiện `user_id`.
- Không hiển thị lỗi SQL thô cho người dùng cuối.

## 16. Thứ Tự Ưu Tiên Từ Tình Trạng Hiện Tại

Thứ tự nên làm tiếp:

1. Sửa `Router.php` để hỗ trợ POST.
2. Thêm session, redirect, requireLogin, flash message.
3. Tạo `database/schema.sql`.
4. Làm auth.
5. Cập nhật sidebar dùng route thật.
6. Làm CRUD khoản thu.
7. Làm CRUD khoản chi.
8. Chuyển dashboard sang dữ liệu thật.
9. Làm settings.
10. Làm purchase plans.
11. Kiểm tra lại giao diện AdminLTE.
12. Dọn dữ liệu mẫu hard-code.

## 17. Những Phần Không Nên Làm Lúc Này

- Không đổi framework.
- Không làm API riêng.
- Không làm nhiều vai trò người dùng.
- Không làm nhiều ví tiền.
- Không làm export Excel/PDF trước khi chức năng chính chạy ổn.
- Không thêm quá nhiều biểu đồ.
- Không làm thuật toán phức tạp hơn mức cần thiết.

## 18. Tiêu Chuẩn Hoàn Thành Theo Source Hiện Tại

Dự án đạt yêu cầu khi:

- Nền MVC hiện tại vẫn chạy ổn.
- AdminLTE hiển thị đúng ở tất cả trang nội bộ.
- Login/register/logout hoạt động.
- Database có đủ bảng cần thiết.
- Khoản thu thêm, sửa, xóa được.
- Khoản chi thêm, sửa, xóa được.
- Dashboard không còn dùng số liệu hard-code.
- Settings lưu được hạn mức và số dư tối thiểu.
- Purchase plan đề xuất được tháng mua.
- Mỗi user chỉ thấy dữ liệu của chính mình.
- Code vẫn đủ đơn giản để giải thích trong đồ án tốt nghiệp.
