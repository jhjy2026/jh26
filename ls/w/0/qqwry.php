<?php
class QQwry {
    private $fp;
    private $firstIndex;
    private $lastIndex;
    private $totalIP;

    public function __construct($filePath) {
        $this->fp = fopen($filePath, 'rb');
        $this->firstIndex = $this->getLong();
        $this->lastIndex = $this->getLong();
        $this->totalIP = ($this->lastIndex - $this->firstIndex) / 7 + 1;
    }

    public function getLong() {
        return ($this->getByte() << 24) + ($this->getByte() << 16) + ($this->getByte() << 8) + $this->getByte();
    }

    public function getByte() {
        return ord(fgetc($this->fp));
    }

    public function getOffset($ip) {
        $ip = ip2long($ip);
        $low = 0;
        $high = $this->totalIP - 1;
        while ($low <= $high) {
            $mid = intval(($low + $high) / 2);
            $offset = $this->firstIndex + 7 * $mid;
            fseek($this->fp, $offset);
            $startIp = $this->getLong();
            if ($ip >= $startIp) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }
        $offset = $this->firstIndex + 7 * $low;
        fseek($this->fp, $offset);
        $startIp = $this->getLong();
        if ($ip < $startIp) {
            $offset = $this->firstIndex + 7 * ($low - 1);
            fseek($this->fp, $offset);
        }
        $offset = ftell($this->fp);
        $nextIpStart = $this->getLong();
        while ($nextIpStart == 0xFFFFFFFF) {
            $offset = $this->getLong();
            if ($offset == 0) {
                break;
            }
            fseek($this->fp, $offset);
            $nextIpStart = $this->getLong();
        }
        return $offset;
    }

    public function getLocation($ip) {
        $offset = $this->getOffset($ip);
        if ($offset == 0) {
            return '未知';
        }
        fseek($this->fp, $offset);
        $countryOffset = $this->getLong();
        $regionOffset = $this->getLong();
        $flag = $this->getByte();
        fseek($this->fp, $countryOffset);
        $country = $this->getString();
        fseek($this->fp, $regionOffset);
        $region = $this->getString();
        return $country . $region;
    }

    private function getString() {
        $str = '';
        while (1) {
            $char = fgetc($this->fp);
            if ($char == "\0") {
                break;
            }
            $str .= $char;
        }
        return $str;
    }

    public function __destruct() {
        fclose($this->fp);
    }
}
?>