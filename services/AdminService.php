<?php
declare(strict_types=1);
class AdminService
{
    public function getFiles(): array 
    {
        header('Content-Type: application/json'); 
        
        $requestedPath = isset($_GET['path']) ? $_GET['path'] : '';
        $fullPath = realpath($requestedPath);

        if (!$this->checkPathAccess($requestedPath)) {
            http_response_code(403);
            die(json_encode(['error' => 'Доступ запрещён: ' . $requestedPath]));
        }

        if (!is_dir($fullPath)) {
            http_response_code(404);
            die(json_encode(['error' => 'Каталог не найден: '  . $requestedPath]));
        }

        
        $files = scandir($fullPath);
        $result = [];

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $filePath = $fullPath . '/' . $file;
            $result[] = [
                'name' => $file,
                'type' => is_dir($filePath)?'directory':'file',
                'size' => filesize($fullPath),
                'modified' => date("d.m.Y", filemtime($filePath))
            ];
        }
        return $result;
    }
    public function getFileContent(): bool|string
    {
        $filePath = $_GET['path'] ?? null;

        if (!$filePath) {
            $this->sendJsonError('Не указан путь к файлу.');
            return false;
        }

        if (!file_exists($filePath)) {
            $this->sendJsonError('Файл не найден.');
            return false;
        }

        
        if (!$this->checkPathAccess($filePath)) {
            $this->sendJsonError('Доступ запрещен к данному файлу.');
            return false;
        }
        
        $content = file_get_contents($filePath);
        
        return $content; 
    }

    public function saveFile()
    {
        $filePath = $_POST['path'] ?? null;
        $content = $_POST['content'] ?? null;

        if (!$filePath || $content === null) {
            $this->sendJsonError('Не указан путь к файлу или содержимое.');
            return;
        }

        if (!$this->checkPathAccess($filePath)) {
            $this->sendJsonError('Доступ запрещен к данному файлу.');
            return;
        }

        if (file_put_contents($filePath, $content) === false) {
            $this->sendJsonError('Ошибка при сохранении файла.');
            return;
        }

        $this->sendJsonResponse(['success' => true]);
    }

    public function getFile(): void
    {
        $filePath = $_GET['path'] ?? null;

        if (!$filePath) {
            $this->sendJsonError('Не указан путь к файлу.');
            return;
        }
        
        if (!file_exists($filePath)) {
            $this->sendJsonError('Файл не найден.');
            return;
        }

        if (!$this->checkPathAccess($filePath)) {
            $this->sendJsonError('Доступ запрещен к данному файлу.');
            return;
        }

        // Заголовки для скачивания файла
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
    }
    
    public function deleteFile(): void
    {
        $path = $_POST['path'] ?? null;
        $type = $_POST['type'] ?? 'file'; 

        if (!$path) {
            $this->sendJsonError('Не указан путь к файлу/директории.');
            return;
        }

        if (!$this->checkPathAccess($path)) {
            $this->sendJsonError('Доступ запрещен к данному файлу/директории.');
            return;
        }
        
        if ($type === 'directory') {
            if (!is_dir($path)) {
                $this->sendJsonError('Директория не существует.');
                return;
            }
            if (!$this->deleteDirectory($path)) {
                $this->sendJsonError('Ошибка при удалении директории.');
                return;
            }
        } else {
            if (!file_exists($path)) {
                $this->sendJsonError('Файл не существует.');
                return;
            }
            if (!unlink($path)) {
                $this->sendJsonError('Ошибка при удалении файла.');
                return;
            }
        }

        $this->sendJsonResponse(['success' => true]);
    }

    public function mkDir(): void
    {
        $path = $_POST['path'] ?? null;
        $name = $_POST['name'] ?? null;

        if (!$path || !$name) {
            $this->sendJsonError('Не указан путь или имя для новой директории.');
            return;
        }

        if (!$this->checkPathAccess($path)) {
            $this->sendJsonError('Доступ запрещен к данной директории.');
            return;
        }

        $newDir = $path . $name;
        if (!mkdir($newDir, 0777, true)) {
            $this->sendJsonError('Ошибка при создании директории.');
            return;
        }

        $this->sendJsonResponse(['success' => true]);
    }
    
    private function checkPathAccess(string $path): bool
    {
        return str_starts_with($path, 'public/');
    }
    private function sendJsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    private function sendJsonError(string $message): void
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => $message]);
    }

    private function deleteDirectory(string $dir): bool
    {
        if (!is_dir($dir)) {
            return false;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        return rmdir($dir);
    }
}