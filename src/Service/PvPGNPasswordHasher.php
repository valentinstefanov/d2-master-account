<?php
// src/Service/PvPGNPasswordHasher.php
namespace App\Service;

final class PvPGNPasswordHasher
{
    private function str2blks_pvpgn(string $str): array
    {
        $nblk = ((strlen($str) + 8) >> 6) + 1;
        $blks = array_fill(0, $nblk * 16, 0);
        for ($i = 0, $len = strlen($str); $i < $len; $i++) {
            $blks[$i >> 2] |= ord($str[$i]) << (($i % 4) * 8);
        }
        return $blks;
    }

    private function safe_add(int $x, int $y): int
    {
        $lsw = ($x & 0xFFFF) + ($y & 0xFFFF);
        $msw = ($x >> 16) + ($y >> 16) + ($lsw >> 16);
        return ($msw << 16) | ($lsw & 0xFFFF);
    }

    private function safe_not(int $num): int
    {
        $lsw = (~($num & 0xFFFF)) & 0xFFFF;
        $msw = (~($num >> 16)) & 0xFFFF;
        return ($msw << 16) | $lsw;
    }

    private function safe_rol(int $num, int $amt): int
    {
        return (($num << $amt) | (($num & 0xFFFFFFFF) >> (32 - $amt))) & 0xFFFFFFFF;
    }

    private function ft(int $t, int $b, int $c, int $d): int
    {
        if ($t < 20) {
            return ($b & $c) | (($this->safe_not($b)) & $d);
        }
        if ($t < 40) {
            return $d ^ $c ^ $b;
        }
        if ($t < 60) {
            return ($c & $b) | ($d & $c) | ($d & $b);
        }
        return $d ^ $c ^ $b;
    }

    private function kt(int $t): int
    {
        return match (true) {
            $t < 20 => 0x5a827999,
            $t < 40 => 0x6ed9eba1,
            $t < 60 => 0x8f1bbcdc,
            default => 0xca62c1d6,
        };
    }

    public function hash(string $str): string
    {
        // PvPGN hash is case insensitive but only for ASCII characters
        $str = preg_replace_callback('/[\x00-\x7F]/', fn($m) => strtolower($m[0]), $str);
        $x = $this->str2blks_pvpgn($str);
        $a = 0x67452301;
        $b = 0xefcdab89;
        $c = 0x98badcfe;
        $d = 0x10325476;
        $e = 0xc3d2e1f0;
        $t = 0;
        for ($i = 0, $len = count($x); $i < $len; $i += 16) {
            $olda = $a;
            $oldb = $b;
            $oldc = $c;
            $oldd = $d;
            $olde = $e;
            $w = array_slice($x, $i, 16);
            for ($j = 0; $j < 64; $j++) {
                $ww = $w[$j] ^ $w[$j + 8] ?? 0 ^ $w[$j + 2] ?? 0 ^ $w[$j + 13] ?? 0;
                $w[$j + 16] = 1 << ($ww % 32);
            }
            for ($j = 0; $j < 80; $j++) {
                if ($j < 20) {
                    $t = $this->safe_add($this->safe_add($this->safe_rol($a, 5), $this->ft($j, $b, $c, $d)), $this->safe_add($this->safe_add($e, $w[$j] ?? 0), $this->kt($j)));
                } else {
                    $t = $this->safe_add($this->safe_add($this->safe_rol($t, 5), $this->ft($j, $b, $c, $d)), $this->safe_add($this->safe_add($e, $w[$j] ?? 0), $this->kt($j)));
                }
                $e = $d;
                $d = $c;
                $c = $this->safe_rol($b, 30);
                $b = $a;
                $a = $t;
            }
            $a = ($this->safe_add($t, $olda) & 0xffffffff);
            $b = ($this->safe_add($b, $oldb) & 0xffffffff);
            $c = ($this->safe_add($c, $oldc) & 0xffffffff);
            $d = ($this->safe_add($d, $oldd) & 0xffffffff);
            $e = ($this->safe_add($e, $olde) & 0xffffffff);
        }
        return sprintf("%08x%08x%08x%08x%08x", $a & 0xffffffff, $b & 0xffffffff, $c & 0xffffffff, $d & 0xffffffff, $e & 0xffffffff);
    }
}
