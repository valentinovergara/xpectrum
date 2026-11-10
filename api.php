<?php
/*
 * Xpectrum — biblioteca de videos e imágenes
 * Guarda los archivos en htdocs/xpectrum/biblioteca/ (también puedes copiarlos ahí desde Finder).
 * Solo responde a peticiones desde este mismo computador.
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($ip, ['127.0.0.1', '::1'], true)) { http_response_code(403); echo json_encode(['error' => 'solo local']); exit; }

$dir = __DIR__ . '/biblioteca';
if (!is_dir($dir) && !@mkdir($dir, 0755, true)) { http_response_code(500); echo json_encode(['error' => 'no se pudo crear la carpeta biblioteca']); exit; }

$allowed = ['mp4', 'm4v', 'mov', 'webm', 'png', 'jpg', 'jpeg', 'gif', 'webp'];
$images  = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

function clean_name($n) {
    $n = basename((string)$n);
    $n = preg_replace('/[^\w.\- ]+/u', '_', $n);
    $n = trim($n, " .");
    return $n !== '' ? $n : 'archivo';
}
function fail($msg, $code = 400, $extra = []) { http_response_code($code); echo json_encode(['error' => $msg] + $extra); exit; }

$a = $_GET['a'] ?? 'list';

if ($a === 'list') {
    $out = [];
    foreach (scandir($dir) as $f) {
        if ($f[0] === '.') continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) continue;
        $out[] = [
            'name'  => $f,
            'url'   => 'biblioteca/' . rawurlencode($f),
            'type'  => in_array($ext, $images, true) ? 'image' : 'video',
            'size'  => filesize("$dir/$f"),
            'mtime' => filemtime("$dir/$f"),
        ];
    }
    usort($out, fn($x, $y) => $y['mtime'] <=> $x['mtime']);
    echo json_encode(['items' => $out]);
    exit;
}

if ($a === 'upload') {
    $name = clean_name($_POST['name'] ?? '');
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed, true)) fail('tipo de archivo no permitido');
    $idx   = (int)($_POST['index'] ?? 0);
    $total = max(1, (int)($_POST['total'] ?? 1));
    $id    = preg_replace('/[^a-z0-9]/i', '', $_POST['id'] ?? '') ?: 'x';
    if (!isset($_FILES['chunk']) || $_FILES['chunk']['error'] !== UPLOAD_ERR_OK) fail('falló la subida del trozo', 400, ['code' => $_FILES['chunk']['error'] ?? -1]);
    $part = "$dir/.$id.part";
    $in  = fopen($_FILES['chunk']['tmp_name'], 'rb');
    $out = fopen($part, $idx === 0 ? 'wb' : 'ab');
    if (!$in || !$out) fail('no se pudo escribir en la carpeta biblioteca', 500);
    stream_copy_to_stream($in, $out);
    fclose($in); fclose($out);
    if ($idx < $total - 1) { echo json_encode(['ok' => true]); exit; }
    $base = pathinfo($name, PATHINFO_FILENAME);
    $final = "$dir/$name"; $n = 1;
    while (file_exists($final)) { $final = "$dir/$base-$n.$ext"; $n++; }
    rename($part, $final);
    $fn = basename($final);
    echo json_encode(['done' => true, 'name' => $fn, 'url' => 'biblioteca/' . rawurlencode($fn), 'type' => in_array($ext, $images, true) ? 'image' : 'video']);
    exit;
}

if ($a === 'delete') {
    $name = clean_name($_POST['name'] ?? '');
    $f = "$dir/$name";
    echo json_encode(['deleted' => is_file($f) ? @unlink($f) : false]);
    exit;
}

fail('acción desconocida');
