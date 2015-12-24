<?php
define('WORD_WIDTH', 14);
define('WORD_HIGHT', 14);
define('OFFSET_X', 0);
define('OFFSET_Y', 3);
define('WORD_SPACING', 0);

class valite {
	public function setImage($Image) {
		$this -> ImagePath = $Image;
	}

	public function getData() {
		return $data;
	}

	public function getResult() {
		return $DataArray;
	}


	public function getHec() {

		$res = imagecreatefromjpeg($this -> ImagePath);
		$size = getimagesize($this -> ImagePath);
		$data = array();
		for ($i = 0; $i < $size[1]; ++$i) {
			for ($j = 0; $j < $size[0]; ++$j) {
				$rgb = imagecolorat($res, $j, $i);
				$rgbarray = imagecolorsforindex($res, $rgb);
				if ($rgbarray['red'] < 125 || $rgbarray['green'] < 125 || $rgbarray['blue'] < 125) {
					$data[$i][$j] = 1;
//					print $data[$i][$j];
					//查看验证码所有的编码

				} else {
					$data[$i][$j] = 0;
//					print $data[$i][$j];

				}
			}
		}
		$this -> DataArray = $data;
		$this -> ImageSize = $size;
	}

	public function run() {

		//初始化数组$sum， 然后用$sum数组用来统计每一列是否有零，全是零则那一列的sum数组值为0，否则为1
		$sum = array();
		for ($o = 0; $o < 60; $o++) {
			$sum[$o] = 0;
		}

		for ($o = 0; $o < 60; $o++) {
			for ($q = 0; $q < 20; $q++) {
				$sum[$o] += $this -> DataArray[$q][$o];

				if ($this -> DataArray[$q][$o] == 1) {
					$sum[$o] == 1;
					break;
				}
			}
		}

		$result = "";

		$data = array("", "", "", "");

		for ($i = 0; $i < 4; ++$i) {
			$x = ($i * (WORD_WIDTH + WORD_SPACING)) + OFFSET_X;
			$y = OFFSET_Y;

			for ($h = $y; $h < (OFFSET_Y + WORD_HIGHT); ++$h) {
				for ($w = $x; $w < ($x + WORD_WIDTH); ++$w) {
					if ($sum[$w] > 0) {
						$data[$i] .= $this -> DataArray[$h][$w];

					}
				}
			}

		}

		// 进行关键字匹配
		foreach ($data as $numKey => $numString) {
			$max = 0.0;
			$num = 0;
			foreach ($this->Keys as $key => $value) {
				$percent = 0.0;
				similar_text($value, $numString, $percent);
				if (intval($percent) > $max) {
					$max = $percent;
					$num = $key;
					if (intval($percent) > 90)
						break;
				}
			}
			$result .= $num;
		}
		$this -> data = $result;
		// 查找最佳匹配数字
		return $result;
	}

	public function Draw() {
		for ($i = 0; $i < $this -> ImageSize[1]; ++$i) {
			for ($j = 0; $j < $this -> ImageSize[0]; ++$j) {
				echo $this -> DataArray[$i][$j];
			}
			echo "\n";
		}
	}

	public function __construct() {
		$this -> Keys = array('0' => '00000000011100011011001000101100011110001111000111100011110001111000110100010011011000111000000000', '1' => '000000001100111100001100001100001100001100001100001100001100001100001100111111000000', '2' => '0000000000111100010011101000011000000110000001100000010000001100000010000001000000100001011111111111111000000000', '3' => '00000000011100010011100000110000011000011000011100000111000001100000110000011010011011111000000000', '4' => '0000000000000110000001100000111000010110001001100010011001000110100001101111111100000110000001100000011000000000', '5' => '000000001111001111010000011100111110000111000011000001000001000001100010111100000000', '6' => '0000000000000111000111000011000001100000010111001110011011000011110000111100001111000011011001100011110000000000', '7' => '0000000001111111011111101000001000000100000001000000010000001000000010000001000000010000000100000010000000000000', '8' => '0000000000111100011000111100001111000011011101100011100000111100010001101100001111000011011001100011110000000000', '9' => '0000000000111100011001101100001111000011110000111100001101100011001111100000011000001100000110001110000000000000');
	}

	protected $ImagePath;
	protected $DataArray;
	protected $ImageSize;
	protected $data;
	protected $Keys;
	protected $NumStringArray;

}
?>