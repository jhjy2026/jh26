<html>
<head>
<title>ip检测试验</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<?php
class QQWry {
    private $dataFile;
    private $ipStart;      // IP起始数值
    private $ipEnd;        // IP结束数值
    private $country;     // 国家代码
    private $province;     // 省份名称
    private $city;        // 城市名称
    private $isp;         // 运营商信息

    public function __construct($dataPath = 'QQWry.dat') {
        if (!file_exists($dataPath)) {
            throw new Exception("IP数据库文件不存在: {$dataPath}");
        }
        
        $this->dataFile = fopen($dataPath, 'rb');
        $this->parseHeader();
    }

    private function parseHeader() {
        // 跳过固定头部(6个字节)
        fseek($this->dataFile, 6);
        
        // 读取第一条记录作为测试
        $record = $this->readRecord();
        if ($record === false) {
            throw new Exception("无法解析IP数据库文件");
        }
        
        // 检测记录长度(判断版本)
        $this->recordLength = strlen($record);
        if ($this->recordLength === 12) {
            // 旧版格式：起始IP(3字节)+结束IP(3字节)+国家代码(1字节)+省份(2字节)+城市(2字节)+运营商(2字节)
            $this->parseRecordV3($record);
        } elseif ($this->recordLength === 16) {
            // 新版格式：起始IP(4字节)+结束IP(4字节)+国家代码(1字节)+省份(2字节)+城市(2字节)+运营商(2字节)
            $this->parseRecordV4($record);
        } else {
            throw new Exception("不支持的IP数据库版本");
        }
    }

    private function readRecord() {
        $buffer = fread($this->dataFile, 16); // 先读取16字节试探
        if (strlen($buffer) < 4) {
            return false;
        }
        
        // 检查记录长度标志（第4-7字节）
        $recordLength = ord($buffer[3]);
        if ($recordLength < 4) {
            // 补充剩余部分
            do {
                $buffer .= fread($this->dataFile, $recordLength - strlen($buffer));
            } while (strlen($buffer) < $recordLength);
        }
        
        return $buffer;
    }

    private function parseRecordV3($record) {
        $this->ipStart = unpack('N', substr($record, 0, 3))[1];
        $this->ipEnd = unpack('N', substr($record, 3, 3))[1];
        $this->country = substr($record, 6, 1);
        $this->province = substr($record, 7, 2);
        $this->city = substr($record, 9, 2);
        $this->isp = substr($record, 11, 2);
    }

    private function parseRecordV4($record) {
        $this->ipStart = unpack('N', substr($record, 0, 4))[1];
        $this->ipEnd = unpack('N', substr($record, 4, 4))[1];
        $this->country = substr($record, 8, 1);
        $this->province = substr($record, 9, 2);
        $this->city = substr($record, 11, 2);
        $this->isp = substr($record, 13, 2);
    }

    public function getIpInfo($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return ['error' => '无效的IP地址'];
        }
        
        $ipNum = ip2long($ip);
        if ($ipNum === false) {
            return ['error' => '无效的IP地址'];
        }
        
        // 二分查找IP段
        $left = 0;
        $right = filesize($this->dataFile) - $this->recordLength;
        $found = false;
        
        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);
            fseek($this->dataFile, $mid * $this->recordLength);
            
            $record = $this->readRecord();
            if ($record === false) {
                break;
            }
            
            $currentStart = unpack('N', substr($record, 0, ($this->recordLength >=4 ?4 :3)))[1];
            $currentEnd = unpack('N', substr($record, ($this->recordLength >=4 ?4 :3), ($this->recordLength >=4 ?4 :3)))[1];
            
            if ($ipNum >= $currentStart && $ipNum <= $currentEnd) {
                $this->parseRecord($record);
                $found = true;
                break;
            } elseif ($ipNum < $currentStart) {
                $right = $mid - 1;
            } else {
                $left = $mid + 1;
            }
        }
        
        if (!$found) {
            return ['error' => '未找到匹配的IP记录'];
        }
        
        return [
            'country_code' => $this->country,
            'province' => $this->province,
            'city' => $this->city,
            'isp' => $this->isp
        ];
    }

    private function parseRecord($record) {
        if ($this->recordLength == 12) {
            $this->parseRecordV3($record);
        } else {
            $this->parseRecordV4($record);
        }
        
        // 转换中文编码
        $this->country = mb_convert_encoding($this->country, 'UTF-8', 'GBK');
        $this->province = mb_convert_encoding($this->province, 'UTF-8', 'GBK');
        $this->city = mb_convert_encoding($this->city, 'UTF-8', 'GBK');
        $this->isp = mb_convert_encoding($this->isp, 'UTF-8', 'GBK');
    }

    public function __destruct() {
        fclose($this->dataFile);
    }
}

// 使用示例
try {
    $qqwry = new QQWry();
    $result = $qqwry->getIpInfo('8.8.8.8');
    
    if (isset($result['error'])) {
        echo "错误：{$result['error']}";
    } else {
        echo "IP地址：8.8.8.8\n";
        echo "国家：{$result['country']}\n";
        echo "省份：{$result['province']}\n";
        echo "城市：{$result['city']}\n";
        echo "运营商：{$result['isp']}\n";
    }
} catch (Exception $e) {
    echo "发生错误：", $e->getMessage(), "\n";
}
?>
</body>
</html>