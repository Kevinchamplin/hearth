<?php
// Minimal, dependency-free User-Agent parser → {os, browser, device, is_mobile, is_bot}.
function hearth_parse_ua($ua){
    $ua = (string)$ua;

    $is_bot = (bool)preg_match('/bot|crawl|spider|slurp|bingpreview|headless|curl|wget|python-|axios|node-fetch|monitor|lighthouse|pingdom|gtmetrix|uptime|facebookexternalhit|embedly|preview/i', $ua);

    $os = 'Other';
    if (preg_match('/windows/i', $ua))                 $os = 'Windows';
    elseif (preg_match('/iphone|ipod/i', $ua))         $os = 'iOS';
    elseif (preg_match('/ipad/i', $ua))                $os = 'iPadOS';
    elseif (preg_match('/mac os x|macintosh/i', $ua))  $os = 'macOS';
    elseif (preg_match('/android/i', $ua))             $os = 'Android';
    elseif (preg_match('/cros/i', $ua))                $os = 'ChromeOS';
    elseif (preg_match('/linux/i', $ua))               $os = 'Linux';

    $browser = 'Other';
    if (preg_match('/edg(a|ios|)\//i', $ua))                                $browser = 'Edge';
    elseif (preg_match('/opr\/|opera/i', $ua))                              $browser = 'Opera';
    elseif (preg_match('/samsungbrowser/i', $ua))                           $browser = 'Samsung';
    elseif (preg_match('/firefox\/|fxios/i', $ua))                          $browser = 'Firefox';
    elseif (preg_match('/chromium/i', $ua))                                 $browser = 'Chromium';
    elseif (preg_match('/crios|chrome\//i', $ua))                           $browser = 'Chrome';
    elseif (preg_match('/version\/[\d.]+.*safari|^.*safari/i', $ua) && preg_match('/safari/i', $ua)) $browser = 'Safari';

    $device = 'Desktop';
    if (preg_match('/ipad|tablet|playbook|silk|(android(?!.*mobile))/i', $ua)) $device = 'Tablet';
    elseif (preg_match('/mobile|iphone|ipod|android.*mobile|windows phone/i', $ua)) $device = 'Mobile';

    return [
        'os'        => $os,
        'browser'   => $browser,
        'device'    => $device,
        'is_mobile' => $device !== 'Desktop',
        'is_bot'    => $is_bot,
    ];
}
