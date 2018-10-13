<?php
header("content-type:text/html;charset=utf-8");

// default value
$source = '.';
$destination = 'test.txt';
$message = '';

// get command parameter
if (is_array($argv) && count($argv) > 1) {
    // 移除此檔案本身
    array_shift($argv);

    $varAry = ['-s' => 'source', '-d' => 'destination'];

    foreach ($argv as $argValue) {
        $argValue = explode('=', $argValue);
        if (array_key_exists($argValue[0], $varAry) && isset($argValue[1])) {
            ${$varAry[$argValue[0]]} = $argValue[1];
        }
    }
}

// designation file or scan directory
if (is_file($source)) {
    $analysis = analysis(dirname($source) . DIRECTORY_SEPARATOR, basename($source));
    setFileContent($destination, $analysis);
} else {
    $source = (!in_array($source, ['.', '', '/'])) ? preg_replace("/(\/|\\\\)$/", "", $source) : '.';
    $source = $source . DIRECTORY_SEPARATOR;
    if (count($scandir = scandir($source)) > 2) {
        $passAry = ['.', '..', basename(__FILE__)];

        foreach ($scandir as $item) {
            $trueAry = [];
            if (in_array($item, $passAry) || is_dir($item)) continue;
            else {
                setFileContent($destination, analysis($source, $item));
            }
        }
    }
}

if (!empty($message)) {
    $message = PHP_EOL . date('Y-m-d H:i') . " Reported.";
    setFileContent($destination, $message);
}

echo "Finished!";

exit;

/**
 * analysis 檔案分析
 * @param $cardnumber
 * @return bool
 */
function analysis($source, $item)
{

    $message = realpath($source . $item) . PHP_EOL . "\t ";

    $file = file_get_contents($source . $item);

    if (trim($file)) {
        $fileContentAry = explode("\n", $file);
    } else {
        return $message . '檔案內容有誤!';
    }

    $total = count($fileContentAry);
    $fileContentAry = array_unique($fileContentAry);

    $true = $false = 0;

    foreach ($fileContentAry as $cardNum) {
        if (validateCard($cardNum)) {
            $trueAry[] = $cardNum;
            $true++;
        } else $false++;
    }

    $message .= "TOTAL: " . $total . ' MATCHED: ' . $true . ' NOT MATCHED: ' . $false . ' DUPLICATED: ' . ($total - $true - $false) . PHP_EOL;
    return $message = $message . "\t" . implode(PHP_EOL . "\t", $trueAry) . PHP_EOL;
}

/**
 * setFileContent
 *
 * @param  string $destination
 * @param  string $message
 * @param  const $method
 *
 * @return void
 */
function setFileContent($destination, $message, $method = FILE_APPEND)
{
    file_put_contents($destination, $message, $method);
}

/**
 * 驗證銀行卡號是否是信用卡
 * @param $cardnumber
 * @return bool
 */
function validateCard($cardnumber)
{
    $cardnumber = preg_replace("/\D|\s/", "", $cardnumber);
    $cardlength = strlen($cardnumber);
    if ($cardlength == 16) {
        // $parity = $cardlength %2;
        $sum = 0;
        for ($i = 1; $i <= $cardlength; $i++) {
            # 跑回圈取單一數字出來判斷
            $digit = $cardnumber[$i - 1];
            # 基數*2
            if ($i % 2 == 1) {
                $digit = $digit * 2;
                # 
                if ($digit > 9) $digit = $digit - 9;
            }

            $sum = $sum + $digit;
        }
        $valid = ($sum % 10 == 0);
        return $valid;
    }
    return false;
}
?>