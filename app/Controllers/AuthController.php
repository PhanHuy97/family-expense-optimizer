<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

final class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }

        $this->authView('auth.login', [
            'title' => 'Đăng nhập',
            'email' => '',
        ]);
    }

    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->setFlash('danger', 'Vui lòng nhập email và mật khẩu.');
            $this->authView('auth.login', [
                'title' => 'Đăng nhập',
                'email' => $email,
            ]);
            return;
        }

        $userModel = new User($this->db());
        $user = $userModel->findByEmail($email);

        if ($user === null || !password_verify($password, (string) $user['password'])) {
            $this->setFlash('danger', 'Email hoặc mật khẩu không đúng.');
            $this->authView('auth.login', [
                'title' => 'Đăng nhập',
                'email' => $email,
            ]);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = (string) $user['name'];
        $_SESSION['user_email'] = (string) $user['email'];

        $this->setFlash('success', 'Đăng nhập thành công.');
        $this->redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }

        $this->authView('auth.register', [
            'title' => 'Đăng ký',
            'name' => '',
            'email' => '',
        ]);
    }

    public function register(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

        if ($name === '' || $email === '' || $password === '') {
            $this->setFlash('danger', 'Vui lòng nhập đầy đủ thông tin.');
            $this->showRegisterWithOldInput($name, $email);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('danger', 'Email không hợp lệ.');
            $this->showRegisterWithOldInput($name, $email);
            return;
        }

        if (strlen($password) < 6) {
            $this->setFlash('danger', 'Mật khẩu phải có ít nhất 6 ký tự.');
            $this->showRegisterWithOldInput($name, $email);
            return;
        }

        if ($password !== $passwordConfirmation) {
            $this->setFlash('danger', 'Xác nhận mật khẩu không khớp.');
            $this->showRegisterWithOldInput($name, $email);
            return;
        }

        $userModel = new User($this->db());

        if ($userModel->findByEmail($email) !== null) {
            $this->setFlash('danger', 'Email đã được sử dụng.');
            $this->showRegisterWithOldInput($name, $email);
            return;
        }

        $userModel->create($name, $email, $password);

        $this->setFlash('success', 'Đăng ký thành công. Vui lòng đăng nhập.');
        $this->redirect('/login');
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        session_start();
        $this->setFlash('success', 'Đã đăng xuất.');
        $this->redirect('/login');
    }

    private function showRegisterWithOldInput(string $name, string $email): void
    {
        $this->authView('auth.register', [
            'title' => 'Đăng ký',
            'name' => $name,
            'email' => $email,
        ]);
    }
}
