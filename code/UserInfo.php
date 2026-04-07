<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'Неизвестно',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Неизвестно',
            'time' => date('Y-m-d H:i:s')
        ];
    }
}