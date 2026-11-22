<?php

declare(strict_types=1);

const SITE = [
    'window_title'     => '我的项目导航站',
    'title'            => '我的项目导航站',
    'subtitle'         => '这里是我的开源项目的导航站。',
    'kicker'           => '你好！',
    'owner'            => 'xiaLingLuo',
    'maintainer_label' => '维护者',
    'repo_url'         => 'https://github.com/xiaLingLuo/',
    'snapshot'         => '2026-11-23 02:55 UTC+8',
    'timezone'         => 'Asia/Shanghai',
    'recent_days'      => 30,
    'sort_by_updated'  => true,
    'taskbar' => [
        'start' => '开始',
        'app'   => 'Firefox',
    ],
    'menu' => [
        ['label' => '文件(F)', 'href' => '#projects'],
        ['label' => '查看(V)', 'href' => '#overview'],
        ['label' => '帮助(H)', 'action' => 'about'],
    ],
    'about' => [
        'app'     => 'multimc.cn',
        'version' => '1.0.0',
        'lines'   => [
            '生命真正的凋落。',
        ],
    ],
];

const UI = [
    'refresh'        => '刷新',
    'about'          => '关于',
    'source'         => '源码仓库',
    'snapshot'       => '数据快照',
    'latest'         => '最新动态',
    'distribution'   => '状态分布',
    'projects'       => '项目列表',
    'open'           => '打开仓库',
    'language'       => '语言',
    'license'        => '许可',
    'updated'        => '最近更新',
    'stars'          => 'Star',
    'forks'          => 'Fork',
    'total'          => '项目总数',
    'total_stars'    => 'Star 总数',
    'languages'      => '编程语言',
    'recent'         => '近期更新',
    'note_total'     => '个',
    'note_stars'     => '个',
    'note_languages' => '种',
    'sorted'         => '时间排序',
    'unsorted'       => '排序：配置顺序',
    'sum'            => '合计',
    'generated'      => '渲染于',
    'ok'             => '确定',
    'empty_projects' => '暂无项目，请在顶部 PROJECTS 配置中添加。',
    'empty_latest'   => '暂无项目数据。',
];

const STATUSES = [
    'active'      => ['label' => '活跃开发', 'color' => '#007500'],
    'stable'      => ['label' => '稳定维护', 'color' => '#000080'],
    'beta'        => ['label' => '测试阶段',   'color' => '#7a4b00'],
    'maintenance' => ['label' => '低频维护', 'color' => '#5a5a5a'],
    'archived'    => ['label' => '已归档',   'color' => '#8b0000'],
];

const PROJECTS = [
    [
        'name'     => 'LingYggdrasil',
        'desc'     => '一个现代优雅的 Minecraft Skin/Cape 纹理托管及 Yggdrasil API 系统，高自订能力，旨在作为BlessingSkinServer的现代化替代方案。兼容authlib的外置登录。',
        'version'  => 'v2.10.1',
        'status'   => 'active',
        'language' => 'Java',
        'stars'    => 4,
        'forks'    => 0,
        'license'  => 'AGPL-3.0',
        'release'  => '修复了一些小问题。',
        'updated'  => '2026-11-10',
        'url'      => 'https://github.com/xiaLingLuo/LingYggdrasil-CE',
        'tags'     => ['Yggdrasil', 'WebUI', 'minecraft', 'authlib-injector'],
    ],
    [
        'name'     => 'OpenTLS',
        'desc'     => 'A modern Java SSL/TLS Certificate Authority (CA) self-signed server, equipped with an elegant WebUI. It utilizes the best encryption methods available. The management is simple and user-friendly, while maintaining security.',
        'version'  => 'v0.0.0',
        'status'   => 'beta',
        'language' => 'Java',
        'stars'    => 0,
        'forks'    => 0,
        'license'  => 'AGPL-3.0',
        'release'  => 'null',
        'updated'  => '1999-01-01',
        'url'      => 'https://github.com/xiaLingLuo/OpenTLS',
        'tags'     => ['tls', 'ssl'],
    ],
    [
        'name'     => 'win95like-projects-nav',
        'desc'     => 'This Site',
        'version'  => 'v1.0.0',
        'status'   => 'stable',
        'language' => 'PHP',
        'stars'    => 0,
        'forks'    => 0,
        'license'  => 'MIT',
        'release'  => 'null',
        'updated'  => '1999-01-01',
        'url'      => 'https://github.com/xiaLingLuo/win95like-projects-nav',
        'tags'     => ['php', 'web'],
    ],
];

if (in_array(SITE['timezone'], timezone_identifiers_list(), true)) {
    date_default_timezone_set(SITE['timezone']);
}

function e(?string $value): string
{
    if ($value === null) {
        return '';
    }

    return $value |> trim(...) |> htmlspecialchars(...);
}

function safe_url(string $url): string
{
    $parts = parse_url($url);

    if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
        return '#';
    }

    return in_array(strtolower($parts['scheme']), ['http', 'https'], true) ? $url : '#';
}

function status_meta(string $status): array
{
    return STATUSES[$status] ?? ['label' => $status, 'color' => '#5a5a5a'];
}

function status_color(string $color): string
{
    return preg_match('/^#[0-9a-fA-F]{6}$/', $color) === 1 ? $color : '#5a5a5a';
}

function days_since(string $date): int
{
    try {
        $then = (new DateTimeImmutable($date))->setTime(0, 0);
    } catch (Exception) {
        return 0;
    }

    $today = new DateTimeImmutable('today');

    if ($then > $today) {
        return 0;
    }

    return (int) $then->diff($today)->format('%a');
}

function humanize_days(int $days): string
{
    return match (true) {
        $days <= 0 => '今天',
        $days === 1 => '昨天',
        $days < 7 => $days . ' 天前',
        $days < 30 => intdiv($days, 7) . ' 周前',
        $days < 365 => intdiv($days, 30) . ' 个月前',
        default => intdiv($days, 365) . ' 年前',
    };
}

function percent_label(int $count, int $total): string
{
    if ($total <= 0) {
        return '0%';
    }

    $value = number_format($count / $total * 100, 1, '.', '');

    return rtrim(rtrim($value, '0'), '.') . '%';
}

function trim_number(float $value): string
{
    $formatted = number_format($value, 1, '.', '');

    return rtrim(rtrim($formatted, '0'), '.');
}

function compact_number(int $value): string
{
    if ($value >= 1_000_000) {
        return trim_number($value / 1_000_000) . 'M';
    }

    if ($value >= 1_000) {
        return trim_number($value / 1_000) . 'k';
    }

    return (string) $value;
}

$projects = PROJECTS;

if (SITE['sort_by_updated']) {
    usort($projects, static function (array $left, array $right): int {
        $byDate = strcmp($right['updated'], $left['updated']);

        return $byDate !== 0 ? $byDate : strcmp($left['name'], $right['name']);
    });
}

$projectTotal = count($projects);
$totalStars = (int) array_sum(array_column($projects, 'stars'));
$languageCount = count(array_unique(array_column($projects, 'language')));

$statusCounts = array_fill_keys(array_keys(STATUSES), 0);
foreach ($projects as $project) {
    $statusCounts[$project['status']] = ($statusCounts[$project['status']] ?? 0) + 1;
}

$statusKeys = array_keys(STATUSES);
foreach ($projects as $project) {
    if (!in_array($project['status'], $statusKeys, true)) {
        $statusKeys[] = $project['status'];
    }
}

$recentCount = count(array_filter(
    $projects,
    static fn (array $project): bool => days_since($project['updated']) <= SITE['recent_days']
));

$latest = null;
foreach ($projects as $project) {
    if ($latest === null || strcmp($project['updated'], $latest['updated']) > 0) {
        $latest = $project;
    }
}

$recentNote = sprintf('%d 天内有 Release', SITE['recent_days']);

$stats = [
    ['label' => UI['total'],      'value' => (string) $projectTotal,       'note' => UI['note_total']],
    ['label' => UI['total_stars'], 'value' => compact_number($totalStars),  'note' => UI['note_stars']],
    ['label' => UI['languages'],   'value' => (string) $languageCount,      'note' => UI['note_languages']],
    ['label' => UI['recent'],      'value' => (string) $recentCount,        'note' => $recentNote],
];
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(SITE['subtitle']) ?>">
    <meta name="color-scheme" content="light">
    <title><?= e(SITE['window_title']) ?></title>
    <link rel="icon" href="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2016%2016'%3E%3Crect%20width='16'%20height='16'%20fill='%23c0c0c0'/%3E%3Crect%20width='16'%20height='4'%20fill='%23000080'/%3E%3C/svg%3E">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        :root {
            color-scheme: light;
            --face: #c0c0c0;
            --face-light: #dfdfdf;
            --white: #ffffff;
            --gray: #808080;
            --black: #000000;
            --navy: #000080;
            --teal: #008080;
            --ink: #111111;
            --muted: #3d3d3d;
            --raised: inset -1px -1px #000000, inset 1px 1px #ffffff, inset -2px -2px #808080, inset 2px 2px #dfdfdf;
            --sunken: inset -1px -1px #ffffff, inset 1px 1px #000000, inset -2px -2px #dfdfdf, inset 2px 2px #808080;
        }

        html {
            background: var(--teal);
            scrollbar-color: var(--face) var(--face-light);
        }

        body {
            margin: 0;
            min-height: 100vh;
            padding: 26px 16px 78px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            background: var(--teal);
            color: var(--ink);
            font: 12px/1.5 "MS Sans Serif", "Microsoft Sans Serif", Tahoma, Geneva, Verdana, sans-serif;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        ::selection {
            background: var(--navy);
            color: var(--white);
        }

        a {
            color: var(--navy);
        }

        a:focus-visible,
        button:focus-visible {
            outline: 1px dotted currentColor;
            outline-offset: 2px;
        }

        ::-webkit-scrollbar {
            width: 16px;
            height: 16px;
        }

        ::-webkit-scrollbar-track {
            background: var(--face-light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--face);
            box-shadow: var(--raised);
        }

        ::-webkit-scrollbar-corner {
            background: var(--face);
        }

        .window {
            width: min(1080px, 100%);
            padding: 3px;
            background: var(--face);
            box-shadow: var(--raised), 0 14px 32px rgba(0, 0, 0, 0.3);
        }

        .titlebar {
            display: flex;
            align-items: center;
            gap: 5px;
            min-height: 20px;
            padding: 1px 2px 1px 4px;
            background: var(--navy);
            color: var(--white);
            font-weight: 700;
            user-select: none;
        }

        .titlebar-icon {
            position: relative;
            flex: none;
            width: 16px;
            height: 16px;
            background: var(--white);
            box-shadow: var(--sunken);
        }

        .titlebar-icon::before {
            content: "";
            position: absolute;
            inset: 3px 3px auto;
            height: 4px;
            background: var(--navy);
        }

        .titlebar-icon::after {
            content: "";
            position: absolute;
            inset: 9px 4px 4px;
            border: 1px solid var(--gray);
            background: var(--face);
        }

        .titlebar-text {
            min-width: 0;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .titlebar-controls {
            display: flex;
            gap: 2px;
            margin-left: auto;
            padding-left: 8px;
        }

        .tb-btn {
            display: grid;
            place-items: center;
            width: 16px;
            height: 14px;
            padding: 0;
            border: 0;
            background: var(--face);
            color: var(--black);
            font: 700 9px/1 "MS Sans Serif", Tahoma, sans-serif;
            box-shadow: var(--raised);
            cursor: default;
        }

        button.tb-btn {
            cursor: pointer;
        }

        button.tb-btn:active {
            box-shadow: var(--sunken);
        }

        .menubar {
            display: flex;
            align-items: center;
            gap: 1px;
            padding: 2px 1px;
        }

        .menu-item {
            appearance: none;
            border: 0;
            background: transparent;
            padding: 2px 8px;
            color: var(--ink);
            font: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .menu-item:hover,
        .menu-item:focus-visible {
            background: var(--navy);
            color: var(--white);
            outline: none;
        }

        .menu-item:focus-visible {
            outline: 1px dotted var(--white);
            outline-offset: -3px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 3px 4px;
            border-top: 1px solid var(--gray);
            box-shadow: inset 0 1px 0 var(--white);
        }

        .toolbar .spacer {
            flex: 1;
        }

        .readout {
            padding: 3px 8px;
            box-shadow: var(--sunken);
            color: var(--muted);
            font-size: 11px;
            white-space: nowrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            min-width: 72px;
            padding: 4px 12px;
            border: 0;
            background: var(--face);
            color: var(--ink);
            font: inherit;
            text-decoration: none;
            white-space: nowrap;
            box-shadow: var(--raised);
            cursor: pointer;
        }

        .btn:hover {
            background: #cecece;
        }

        .btn:active {
            box-shadow: var(--sunken);
        }

        .btn:focus-visible {
            outline: 1px dotted var(--black);
            outline-offset: -5px;
        }

        .btn-sm {
            min-width: 0;
            padding: 3px 9px;
            font-size: 11px;
        }

        .hero {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 24px;
            padding: 20px 16px 16px;
        }

        .hero-main {
            min-width: 0;
        }

        .kicker {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0 0 6px;
            color: #3a3a3a;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
        }

        .kicker::before {
            content: "";
            width: 8px;
            height: 8px;
            background: var(--navy);
            box-shadow: 1px 1px 0 var(--white);
        }

        .hero h1 {
            margin: 0 0 6px;
            font-family: Tahoma, "MS Sans Serif", sans-serif;
            font-size: 24px;
            line-height: 1.2;
            letter-spacing: 0.01em;
            color: var(--black);
        }

        .lede {
            margin: 0;
            max-width: 62ch;
            color: #2b2b2b;
        }

        .maintainer {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 11px;
        }

        .maintainer strong {
            color: var(--ink);
        }

        .hero-side {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
            flex: none;
        }

        .hero-side .readout {
            min-width: 190px;
            padding: 6px 10px;
            background: var(--white);
        }

        .readout-label {
            display: block;
            color: var(--muted);
            font-size: 9px;
            letter-spacing: 0.08em;
        }

        .readout strong {
            color: var(--ink);
            font-size: 12px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            padding: 0 16px 16px;
        }

        .stat {
            padding: 9px 12px 10px;
            background: var(--white);
            box-shadow: var(--sunken);
        }

        .stat-label {
            display: block;
            color: var(--muted);
            font-size: 10px;
            letter-spacing: 0.06em;
        }

        .stat-value {
            display: block;
            color: var(--navy);
            font: 700 22px/1.2 Tahoma, "MS Sans Serif", sans-serif;
        }

        .stat-note {
            display: block;
            margin-top: 2px;
            color: #3d3d3d;
            font-size: 10px;
        }

        .split {
            display: grid;
            grid-template-columns: minmax(0, 1.55fr) minmax(0, 1fr);
            gap: 10px;
            padding: 0 16px 16px;
        }

        .group {
            min-width: 0;
            margin: 0;
            padding: 10px 12px 12px;
            border: 1px solid;
            border-color: var(--gray) var(--white) var(--white) var(--gray);
        }

        .group > legend {
            padding: 0 4px;
            color: var(--black);
            font-weight: 700;
        }

        .release {
            display: flex;
            flex-direction: column;
        }

        .release-top {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
        }

        .release-name {
            margin: 8px 0 4px;
            font: 700 18px/1.25 Tahoma, "MS Sans Serif", sans-serif;
        }

        .release-note {
            margin: 0;
            max-width: 64ch;
            color: #2e2e2e;
        }

        .facts {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 16px;
            margin: 12px 0 0;
            padding-top: 10px;
            border-top: 1px solid var(--gray);
            box-shadow: inset 0 1px 0 var(--white);
        }

        .facts > div {
            min-width: 0;
        }

        .facts dt {
            color: #3d3d3d;
            font-size: 9px;
            letter-spacing: 0.08em;
        }

        .facts dd {
            margin: 1px 0 0;
            font-weight: 700;
        }

        .facts .muted {
            color: #3f3f3f;
            font-size: 10px;
            font-weight: 400;
        }

        .chip {
            display: inline-block;
            padding: 1px 6px;
            background: var(--face);
            box-shadow: inset -1px -1px var(--gray), inset 1px 1px var(--white);
            color: #222222;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 1px 7px;
            color: var(--white);
            font-size: 10px;
            font-weight: 700;
            line-height: 1.55;
            white-space: nowrap;
            box-shadow: inset -1px -1px rgba(0, 0, 0, 0.55), inset 1px 1px rgba(255, 255, 255, 0.35);
        }

        .led {
            width: 5px;
            height: 5px;
            background: var(--white);
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.3);
        }

        .badge[data-status="active"] .led {
            animation: led-pulse 2.4s steps(1, end) infinite;
        }

        @keyframes led-pulse {
            50% {
                opacity: 0.35;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .badge[data-status="active"] .led {
                animation: none;
            }
        }

        .chart {
            display: grid;
            gap: 10px;
            margin: 2px 0 0;
            padding: 0;
            list-style: none;
        }

        .chart-line {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 8px;
            font-size: 11px;
        }

        .chart-name {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 700;
        }

        .swatch {
            width: 8px;
            height: 8px;
            box-shadow: inset -1px -1px rgba(0, 0, 0, 0.45), inset 1px 1px rgba(255, 255, 255, 0.45);
        }

        .chart-count {
            color: #3d3d3d;
            font-variant-numeric: tabular-nums;
        }

        .bar {
            height: 11px;
            margin-top: 4px;
            background: var(--white);
            box-shadow: var(--sunken);
        }

        .bar > span {
            display: block;
            height: 100%;
            min-width: 2px;
            box-shadow: inset -1px -1px rgba(0, 0, 0, 0.35), inset 1px 1px rgba(255, 255, 255, 0.35);
        }

        .chart-total {
            margin: 12px 0 0;
            padding-top: 8px;
            border-top: 1px dotted #7a7a7a;
            color: #383838;
            font-size: 10px;
        }

        .projects {
            padding: 0 16px 16px;
        }

        .list {
            margin-top: 2px;
            background: var(--white);
            box-shadow: var(--sunken);
        }

        .project-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 16px 24px;
            align-items: center;
            padding: 11px 12px;
        }

        .project-row + .project-row {
            border-top: 1px solid #d6d6d6;
        }

        .project-row:hover {
            background: #e9e9e9;
        }

        .p-main {
            min-width: 0;
        }

        .p-head {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
        }

        .p-icon {
            position: relative;
            flex: none;
            width: 24px;
            height: 22px;
            background: var(--white);
            box-shadow: var(--sunken);
        }

        .p-icon::before {
            content: "";
            position: absolute;
            left: 3px;
            right: 3px;
            top: 3px;
            height: 4px;
            background: var(--navy);
        }

        .p-icon::after {
            content: "";
            position: absolute;
            left: 4px;
            right: 4px;
            top: 10px;
            height: 1px;
            background: #a0a0a0;
            box-shadow: 0 3px 0 #a0a0a0, 0 6px 0 #a0a0a0;
        }

        .p-name {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
        }

        .p-desc {
            margin: 5px 0 0;
            max-width: 72ch;
            color: #2f2f2f;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin: 7px 0 0;
            padding: 0;
            list-style: none;
        }

        .tags li {
            padding: 0 5px;
            background: #ededed;
            box-shadow: inset 1px 1px var(--gray), inset -1px -1px var(--white);
            color: #333333;
            font-size: 10px;
        }

        .p-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 18px;
        }

        .meta {
            min-width: 52px;
        }

        .meta-label {
            display: block;
            color: #3d3d3d;
            font-size: 9px;
            letter-spacing: 0.06em;
            white-space: nowrap;
        }

        .meta-value {
            display: block;
            font-weight: 700;
            white-space: nowrap;
        }

        .empty {
            margin: 0;
            padding: 14px;
            color: #444444;
        }

        .statusbar {
            display: flex;
            gap: 2px;
            padding: 2px 1px 1px;
        }

        .status-cell {
            display: flex;
            align-items: center;
            gap: 4px;
            min-width: 0;
            padding: 3px 8px;
            background: var(--face);
            box-shadow: inset 1px 1px var(--gray), inset -1px -1px var(--white);
            font-size: 11px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-cell.grow {
            flex: 1;
        }

        .taskbar {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 60;
            display: flex;
            align-items: center;
            gap: 3px;
            padding: 3px 4px;
            background: var(--face);
            box-shadow: inset 0 1px 0 var(--white), inset 0 2px 0 var(--face-light);
        }

        .start-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px 3px 6px;
            background: var(--face);
            box-shadow: var(--raised);
            font-weight: 700;
            cursor: default;
            user-select: none;
        }

        .start-logo {
            display: grid;
            grid-template-columns: 7px 7px;
            grid-template-rows: 7px 7px;
            gap: 1px;
            transform: skewX(-4deg);
        }

        .start-logo i {
            display: block;
        }

        .start-logo i:nth-child(1) {
            background: #ff3b30;
        }

        .start-logo i:nth-child(2) {
            background: #00a800;
        }

        .start-logo i:nth-child(3) {
            background: #0058ff;
        }

        .start-logo i:nth-child(4) {
            background: #ffd400;
        }

        .taskbar-divider {
            align-self: stretch;
            width: 2px;
            margin: 2px;
            border-left: 1px solid var(--gray);
            border-right: 1px solid var(--white);
        }

        .task-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
            max-width: 260px;
            padding: 3px 8px;
            background: #d0d0d0;
            box-shadow: inset 1px 1px var(--gray), inset -1px -1px var(--white);
            font-weight: 700;
            user-select: none;
        }

        .task-btn-icon {
            position: relative;
            flex: none;
            width: 14px;
            height: 12px;
            background: var(--white);
            box-shadow: 0 0 0 1px var(--black);
        }

        .task-btn-icon::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 3px;
            background: var(--navy);
        }

        .task-btn-label {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .tray {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
            padding: 3px 10px;
            box-shadow: inset 1px 1px var(--gray), inset -1px -1px var(--white);
        }

        .tray-led {
            width: 8px;
            height: 8px;
            background: #008000;
            box-shadow: 0 0 0 1px var(--black), inset 1px 1px rgba(255, 255, 255, 0.5);
        }

        .clock {
            font-variant-numeric: tabular-nums;
        }

        .activation-mark {
            position: fixed;
            right: 14px;
            bottom: 40px;
            z-index: 55;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1px;
            max-width: calc(100vw - 28px);
            color: rgba(255, 255, 255, 0.62);
            font-size: 10px;
            line-height: 1.5;
            text-align: right;
            text-shadow: 0 0 1px rgba(0, 0, 0, 0.9), 0 1px 2px rgba(0, 0, 0, 0.7);
            pointer-events: none;
        }

        .activation-mark p {
            margin: 0;
            white-space: nowrap;
        }

        .activation-mark .activation-title {
            color: rgba(255, 255, 255, 0.72);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .activation-mark a {
            color: inherit;
            text-decoration: none;
            pointer-events: auto;
        }

        .activation-mark a:hover,
        .activation-mark a:focus-visible {
            color: rgba(255, 255, 255, 0.96);
            text-decoration: underline;
        }

        dialog.about-dialog {
            margin: auto;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--ink);
        }

        dialog.about-dialog::backdrop {
            background: rgba(0, 0, 0, 0.25);
        }

        .about-window {
            width: min(430px, 92vw);
        }

        .about-body {
            display: flex;
            gap: 14px;
            padding: 16px 16px 10px;
        }

        .about-mark {
            flex: none;
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            background: var(--white);
            box-shadow: var(--sunken);
            color: var(--navy);
            font: italic 700 24px/1 Georgia, "Times New Roman", serif;
        }

        .about-copy {
            min-width: 0;
        }

        .about-app {
            margin: 0 0 2px;
            font-size: 13px;
            font-weight: 700;
        }

        .about-meta {
            margin: 0 0 8px;
            color: #444444;
            font-size: 11px;
        }

        .about-copy p {
            margin: 3px 0;
        }

        .about-actions {
            display: flex;
            justify-content: flex-end;
            padding: 4px 16px 14px;
        }

        @media (max-width: 880px) {
            .hero {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-side {
                flex-direction: row;
                align-items: center;
                width: 100%;
            }

            .hero-side .readout {
                flex: 1;
                min-width: 0;
            }

            .split {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 820px) {
            .project-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .p-meta {
                flex-wrap: wrap;
                justify-content: flex-start;
                gap: 10px 16px;
                padding-top: 8px;
                border-top: 1px solid #d6d6d6;
            }

            .p-meta .btn {
                margin-left: auto;
            }
        }

        @media (max-width: 720px) {
            body {
                padding: 10px 8px 74px;
            }

            .hero h1 {
                font-size: 20px;
            }

            .status-cell.grow {
                display: none;
            }

            .toolbar {
                flex-wrap: wrap;
            }

            .toolbar .spacer {
                display: none;
            }

            .toolbar .readout {
                width: 100%;
            }

            .task-btn {
                max-width: 170px;
            }

            .menu-item {
                padding: 2px 5px;
            }
        }

        @media (max-width: 420px) {
            .stat {
                padding: 8px;
            }

            .stat-value {
                font-size: 18px;
            }

            .tray {
                gap: 5px;
                padding: 3px 6px;
            }

            .activation-mark {
                right: 9px;
                bottom: 38px;
                font-size: 9px;
            }

            .activation-mark .activation-title {
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
<main class="window" aria-label="<?= e(SITE['window_title']) ?>">
    <div class="titlebar">
        <span class="titlebar-icon" aria-hidden="true"></span>
        <span class="titlebar-text"><?= e(SITE['window_title']) ?></span>
        <span class="titlebar-controls" aria-hidden="true">
            <span class="tb-btn">_</span>
            <span class="tb-btn">□</span>
            <span class="tb-btn">×</span>
        </span>
    </div>

    <nav class="menubar" aria-label="主菜单">
        <?php foreach (SITE['menu'] as $item): ?>
            <?php if (($item['action'] ?? null) === 'about'): ?>
                <button type="button" class="menu-item" data-open-about><?= e($item['label']) ?></button>
            <?php else: ?>
                <a class="menu-item" href="<?= e($item['href'] ?? '#') ?>"><?= e($item['label']) ?></a>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>

    <div class="toolbar">
        <a class="btn btn-sm" href=""><?= e(UI['refresh']) ?></a>
        <button type="button" class="btn btn-sm" data-open-about><?= e(UI['about']) ?></button>
        <span class="spacer"></span>
        <span class="readout"><?= e(SITE['sort_by_updated'] ? UI['sorted'] : UI['unsorted']) ?></span>
    </div>

    <header class="hero" id="overview">
        <div class="hero-main">
            <p class="kicker"><?= e(SITE['kicker']) ?></p>
            <h1><?= e(SITE['title']) ?></h1>
            <p class="lede"><?= e(SITE['subtitle']) ?></p>
            <p class="maintainer"><?= e(SITE['maintainer_label']) ?> · <strong><?= e(SITE['owner']) ?></strong></p>
        </div>
        <div class="hero-side">
            <div class="readout">
                <span class="readout-label"><?= e(UI['snapshot']) ?></span>
                <strong><?= e(SITE['snapshot']) ?></strong>
            </div>
            <a class="btn" href="<?= e(safe_url(SITE['repo_url'])) ?>" target="_blank" rel="noopener noreferrer"><?= e(UI['source']) ?></a>
        </div>
    </header>

    <section class="stats" aria-label="项目总览">
        <?php foreach ($stats as $stat): ?>
            <div class="stat">
                <span class="stat-label"><?= e($stat['label']) ?></span>
                <span class="stat-value"><?= e($stat['value']) ?></span>
                <span class="stat-note"><?= e($stat['note']) ?></span>
            </div>
        <?php endforeach; ?>
    </section>

    <section class="split" aria-label="最新动态与状态分布">
        <fieldset class="group">
            <legend><?= e(UI['latest']) ?></legend>
            <?php if ($latest !== null): ?>
                <?php
                $latestMeta = status_meta($latest['status']);
                ?>
                <div class="release">
                    <div class="release-top">
                        <span class="chip"><?= e($latest['version']) ?></span>
                        <span class="badge" data-status="<?= e($latest['status']) ?>" style="background: <?= e(status_color($latestMeta['color'])) ?>">
                            <i class="led" aria-hidden="true"></i><?= e($latestMeta['label']) ?>
                        </span>
                    </div>
                    <h2 class="release-name"><?= e($latest['name']) ?></h2>
                    <p class="release-note"><?= e($latest['release']) ?></p>
                    <dl class="facts">
                        <div>
                            <dt><?= e(UI['language']) ?></dt>
                            <dd><?= e($latest['language']) ?></dd>
                        </div>
                        <div>
                            <dt><?= e(UI['license']) ?></dt>
                            <dd><?= e($latest['license']) ?></dd>
                        </div>
                        <div>
                            <dt><?= e(UI['updated']) ?></dt>
                            <dd><?= e(humanize_days(days_since($latest['updated']))) ?> <span class="muted">· <?= e($latest['updated']) ?></span></dd>
                        </div>
                        <div>
                            <dt><?= e(UI['stars']) ?> / <?= e(UI['forks']) ?></dt>
                            <dd><?= e(number_format($latest['stars'])) ?> / <?= e(number_format($latest['forks'])) ?></dd>
                        </div>
                    </dl>
                </div>
            <?php else: ?>
                <p><?= e(UI['empty_latest']) ?></p>
            <?php endif; ?>
        </fieldset>

        <fieldset class="group">
            <legend><?= e(UI['distribution']) ?></legend>
            <ul class="chart">
                <?php foreach ($statusKeys as $statusKey): ?>
                    <?php
                    $count = (int) ($statusCounts[$statusKey] ?? 0);
                    if ($count === 0) {
                        continue;
                    }
                    $meta = status_meta((string) $statusKey);
                    $pctRaw = $projectTotal > 0 ? $count / $projectTotal * 100 : 0.0;
                    ?>
                    <li class="chart-row">
                        <div class="chart-line">
                            <span class="chart-name">
                                <i class="swatch" style="background: <?= e(status_color($meta['color'])) ?>"></i>
                                <?= e($meta['label']) ?>
                            </span>
                            <span class="chart-count"><?= e((string) $count) ?> · <?= e(percent_label($count, $projectTotal)) ?></span>
                        </div>
                        <div class="bar"><span style="width: <?= e(number_format($pctRaw, 2, '.', '')) ?>%; background: <?= e(status_color($meta['color'])) ?>"></span></div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="chart-total"><?= e(UI['sum']) ?> <?= e((string) $projectTotal) ?> 个项目 · <?= e((string) $languageCount) ?> 种语言</p>
        </fieldset>
    </section>

    <section class="projects" id="projects">
        <fieldset class="group">
            <legend><?= e(UI['projects']) ?> (<?= e((string) $projectTotal) ?>)</legend>
            <div class="list">
                <?php foreach ($projects as $project): ?>
                    <?php
                    $meta = status_meta($project['status']);
                    $updatedDays = days_since($project['updated']);
                    $projectUrl = safe_url($project['url']);
                    ?>
                    <article class="project-row">
                        <div class="p-main">
                            <div class="p-head">
                                <span class="p-icon" aria-hidden="true"></span>
                                <h3 class="p-name"><?= e($project['name']) ?></h3>
                                <span class="chip"><?= e($project['version']) ?></span>
                                <span class="badge" data-status="<?= e($project['status']) ?>" style="background: <?= e(status_color($meta['color'])) ?>">
                                    <i class="led" aria-hidden="true"></i><?= e($meta['label']) ?>
                                </span>
                            </div>
                            <p class="p-desc"><?= e($project['desc']) ?></p>
                            <?php if (!empty($project['tags'])): ?>
                                <ul class="tags">
                                    <?php foreach ($project['tags'] as $tag): ?>
                                        <li><?= e((string) $tag) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <div class="p-meta">
                            <div class="meta">
                                <span class="meta-label"><?= e(UI['language']) ?></span>
                                <span class="meta-value"><?= e($project['language']) ?></span>
                            </div>
                            <div class="meta">
                                <span class="meta-label"><?= e(UI['stars']) ?></span>
                                <span class="meta-value"><?= e(compact_number($project['stars'])) ?></span>
                            </div>
                            <div class="meta">
                                <span class="meta-label"><?= e(UI['license']) ?></span>
                                <span class="meta-value"><?= e($project['license']) ?></span>
                            </div>
                            <div class="meta">
                                <span class="meta-label"><?= e(UI['updated']) ?></span>
                                <span class="meta-value" title="<?= e($project['updated']) ?>"><?= e(humanize_days($updatedDays)) ?></span>
                            </div>
                            <a class="btn btn-sm" href="<?= e($projectUrl) ?>" target="_blank" rel="noopener noreferrer"><?= e(UI['open']) ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
                <?php if ($projectTotal === 0): ?>
                    <p class="empty"><?= e(UI['empty_projects']) ?></p>
                <?php endif; ?>
            </div>
        </fieldset>
    </section>

    <footer class="statusbar">
        <span class="status-cell"><?= e(UI['total']) ?> <?= e((string) $projectTotal) ?></span>
        <span class="status-cell"><?= e(status_meta('active')['label']) ?> <?= e((string) ($statusCounts['active'] ?? 0)) ?></span>
        <span class="status-cell grow"><?= e(SITE['owner']) ?> · <?= e(UI['snapshot']) ?> <?= e(SITE['snapshot']) ?></span>
    </footer>
</main>

<div class="taskbar" aria-label="任务栏">
    <div class="start-btn" aria-hidden="true">
        <span class="start-logo"><i></i><i></i><i></i><i></i></span>
        <span><?= e(SITE['taskbar']['start']) ?></span>
    </div>
    <span class="taskbar-divider" aria-hidden="true"></span>
    <span class="task-btn">
        <span class="task-btn-icon" aria-hidden="true"></span>
        <span class="task-btn-label"><?= e(SITE['taskbar']['app']) ?></span>
    </span>
    <div class="tray">
        <span class="tray-led" aria-hidden="true"></span>
        <span id="clock" class="clock"><?= e(date('H:i')) ?></span>
    </div>
</div>

<aside class="activation-mark">
    <center>
    <p class="activation-title">Windows 95 已激活</p>
    <p><a href="https://beian.miit.gov.cn" target="_blank" rel="noopener noreferrer">渝ICP备2025070372号</a></p>
    <p><a href="https://beian.mps.gov.cn/#/query/webSearch?code=50019002504932" rel="noreferrer" target="_blank">渝公网安备50019002504932号</a></p>
    </center>
</aside>

<dialog id="about" class="about-dialog" aria-labelledby="about-title">
    <div class="window about-window">
        <div class="titlebar">
            <span class="titlebar-icon" aria-hidden="true"></span>
            <span class="titlebar-text" id="about-title"><?= e(UI['about']) ?> — <?= e(SITE['about']['app']) ?></span>
            <span class="titlebar-controls">
                <button type="button" class="tb-btn" data-close-about aria-label="关闭">×</button>
            </span>
        </div>
        <div class="about-body">
            <div class="about-mark" aria-hidden="true">i</div>
            <div class="about-copy">
                <p class="about-app"><?= e(SITE['about']['app']) ?></p>
                <p class="about-meta">版本 <?= e(SITE['about']['version']) ?> · <?= e(UI['generated']) ?> <?= e(date('Y-m-d H:i')) ?></p>
                <?php foreach (SITE['about']['lines'] as $line): ?>
                    <p><?= e($line) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="about-actions">
            <button type="button" class="btn" data-close-about><?= e(UI['ok']) ?></button>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const clock = document.getElementById('clock');
        const about = document.getElementById('about');
        const pad = (value) => String(value).padStart(2, '0');

        const tick = () => {
            if (!clock) {
                return;
            }

            const now = new Date();
            clock.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes());
        };

        tick();
        window.setInterval(tick, 15000);

        if (!about) {
            return;
        }

        document.querySelectorAll('[data-open-about]').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                if (typeof about.showModal === 'function') {
                    about.showModal();
                }
            });
        });

        document.querySelectorAll('[data-close-about]').forEach((trigger) => {
            trigger.addEventListener('click', () => about.close());
        });

        about.addEventListener('click', (event) => {
            if (event.target === about) {
                about.close();
            }
        });
    })();
</script>
</body>
</html>
