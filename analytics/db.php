<?php
// Hearth Analytics — SQLite connection + schema (first-party, privacy-respecting).
// DB + secrets live OUTSIDE the web docroot (subscription root / hearth-analytics-data).

function hearth_data_dir(){
    return __DIR__ . '/../../hearth-analytics-data';
}

function hearth_config(){
    static $cfg = null;
    if ($cfg === null) {
        $path = hearth_data_dir() . '/config.php';
        $cfg = is_file($path) ? (require $path) : ['admin_hash' => '', 'ip_salt' => 'unset'];
        if (!is_array($cfg)) $cfg = ['admin_hash' => '', 'ip_salt' => 'unset'];
    }
    return $cfg;
}

function hearth_db(){
    static $db = null;
    if ($db === null) {
        $dir = hearth_data_dir();
        if (!is_dir($dir)) @mkdir($dir, 0750, true);
        $db = new PDO('sqlite:' . $dir . '/hearth.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->exec('PRAGMA journal_mode=WAL;');
        $db->exec('PRAGMA busy_timeout=4000;');
        $db->exec("CREATE TABLE IF NOT EXISTS events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ts INTEGER NOT NULL,
            day TEXT NOT NULL,
            event TEXT NOT NULL,
            page TEXT,
            session TEXT,
            visitor TEXT,
            os TEXT, browser TEXT, device TEXT,
            is_mobile INTEGER, is_bot INTEGER,
            screen TEXT, viewport TEXT, dpr REAL,
            locale TEXT, tz TEXT, referrer TEXT,
            webgpu INTEGER, tier TEXT, load_ms INTEGER,
            value TEXT, ip_hash TEXT, ua TEXT
        )");
        foreach (['ts','event','visitor','day'] as $c) {
            $db->exec("CREATE INDEX IF NOT EXISTS idx_ev_$c ON events($c)");
        }
    }
    return $db;
}
