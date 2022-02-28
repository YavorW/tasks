<?php

use Illuminate\Http\UploadedFile;

/**
 * Помощна функция за записване на файл в локалният драйвер
 *
 * Пример за създаден локален драйвер (създава се в config/filesystems)
 * 'img' => [
'driver' => 'local',
'root' => public_path('img'),
'url' => env('APP_URL').'/img',
],
 * При проблеми да се обърне внимание дали настройките са кеширани php artisan config:clear
 *
 * @see config/filesystems.php
 * @param UploadedFile $file примерно $input['img']
 * @param string $disk
 * @param null|string $name с какво име да бъде записан файла
 * @return string пътя до файла
 */
function storeFileLocal(UploadedFile $file, $disk, $name = null)
{
    if (!$name) {
        $name = $file->getClientOriginalName();
    } else {
        $explodeName = explode('.', $name);
        if (count($explodeName) < 2) { // няма разширение
            $name .= '.' . $file->getClientOriginalExtension();
        }
    }
    return $file->storeAs('', $name, $disk);
}


/**
 * Помощна фукнция за взимане на URL адрес на файл от диск
 * Всеки диск трябва да има url подаден
 *
 * @param string $file_path Адреса на снимката. Приемерно Avatar.png
 * @param string $disk
 * @param boolean $version дали да добави отзад кога е модифициран файла
 * @return string
 */
function getFileUrl($file_path, $disk, $version = true)
{
    $url = config("filesystems.disks.$disk.url");
    $root = config("filesystems.disks.$disk.root");
    if (!$url) {
        return asset($file_path);
    }
    $file = $root . "/$file_path";
    if (is_readable($file) && $file_path != '' && $version) {
        return $url . "/$file_path?" . filemtime($file);
    }
    return $url . "/$file_path";
}

/**
 * Записване на файл към потребител
 *
 * @param UploadedFile $file
 * @param null|string $name с какво име да бъде записан файла
 * @return string
 */
function storeUserFile($file, $name = null)
{
    return storeFileLocal($file, 'user_files', $name);
}
/**
 * Адреса на файл към потребител
 *
 * @param string $file_path
 * @return string
 */
function getUserFileUrl($file_path)
{
    return getFileUrl($file_path, 'user_files');
}

function storeUpload(UploadedFile $file, ?string $name = null): string
{
    return storeFileLocal($file, 'uploads', $name);
}

function getUploadUrl(string $file_path, bool $version = true): string
{
    return getFileUrl($file_path, 'uploads', $version);
}

function getProjectUrl(string $file_path, bool $version = true): string
{
    return getFileUrl($file_path, 'projects', $version);
}