<?php
$filename = 'visitors_data.txt';
$file = fopen($filename, "r");
$data = fread($file, filesize($filename));
fclose($file);

// 将数据按行分割
$lines = explode("\n", $data);

// 取最新的50条记录
$latestLines = array_slice($lines, -50);

// 统计每日、每周、每月的来访数和IP数
$stats = [
    'daily' => 0,
    'weekly' => 0,
    'monthly' => 0,
    'daily_ips' => [],
    'weekly_ips' => [],
    'monthly_ips' => []
];

$currentDate = date('Y-m-d');
$lastWeek = date('Y-m-d', strtotime('last week'));
$lastMonth = date('Y-m-d', strtotime('last month'));

foreach ($lines as $line) {
    if ($line) {
        $parts = explode(',', $line);
        $time = trim(end($parts));
        $ip = trim(explode(',', $line)[1]);

        // 每日统计
        if (strpos($time, $currentDate) === 0) {
            $stats['daily']++;
            $stats['daily_ips'][] = $ip;
        }

        // 每周统计
        if (strpos($time, date('Y-m', strtotime($lastWeek))) === 0) {
            $stats['weekly']++;
            $stats['weekly_ips'][] = $ip;
        }

        // 每月统计
        if (strpos($time, date('Y-m', strtotime($lastMonth))) === 0) {
            $stats['monthly']++;
            $stats['monthly_ips'][] = $ip;
        }
    }
}

// 计算IP数
$stats['daily_ip_count'] = count(array_unique($stats['daily_ips']));
$stats['weekly_ip_count'] = count(array_unique($stats['weekly_ips']));
$stats['monthly_ip_count'] = count(array_unique($stats['monthly_ips']));

// 定义表头
$tableHeader = "<table border='1'>
                <tr>
                    <th colspan='6'>Statistics</th>
                </tr>
                <tr>
                    <td>Daily Visits: {$stats['daily']}</td>
                    <td>Daily IPs: {$stats['daily_ip_count']}</td>
                    <td>Weekly Visits: {$stats['weekly']}</td>
                    <td>Weekly IPs: {$stats['weekly_ip_count']}</td>
                    <td>Monthly Visits: {$stats['monthly']}</td>
                    <td>Monthly IPs: {$stats['monthly_ip_count']}</td>
                </tr>
                <tr>
                    <th>ID</th>
                    <th>IP</th>
                    <th>Location</th>
                    <th>OS</th>
                    <th>Time</th>
                </tr>";

// 定义表格内容
$tableBody = '';
foreach ($latestLines as $line) {
    if ($line) {
        $cols = explode(',', $line);
        $id = trim(array_shift($cols));
        $ip = trim(array_shift($cols));
        $location = trim(array_shift($cols));
        $os = trim(array_shift($cols));
        $time = trim(array_shift($cols));
        $tableBody .= "<tr>
                        <td>$id</td>
                        <td>$ip</td>
                        <td>$location</td>
                        <td>$os</td>
                        <td>$time</td>
                     </tr>";
    }
}

// 定义表格结束标签
$tableFooter = "</table>";

// 输出完整的表格
echo $tableHeader . $tableBody . $tableFooter;
?>