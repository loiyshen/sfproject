<?php

namespace Bundles\FrontendBundle\Util;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Bundles\FrontendBundle\Util\EmailSendHelper;

/**
 * @Tool
 * ツールクラス
 */
class Tool
{

    /**
     * 現在日時取得 
     * @param date $date
     * @return DateTime
     */
    public static function getNowDateTime($date = NULL)
    {
        if (!$date) {
            return new \DateTime();
        } else {
            return new \DateTime($date);
        }
    }

    // CN-START
    /**
     * 判断变量值是否是正整数类型或能转换成正整数的字符串
     * @param mix $var
     * @return bool
     */
    // CN-END
    /**
     * 変数値は正数の整数のタイプなのか、それとも正数の整数に転換できる文字列なのかを判断する。
     * @param mix $var
     * @return bool
     */
    public static function isInt($var)
    {
        return preg_match('/^0$|(^[1-9]\d*$)/', $var) > 0 ? true : false;
    }

    // CN-START
    /**
     * 将对象转换成数组
     * @param mix $obj 对象
     * @return bool
     */
    // CN-END
    /**
     * [対象]を[配列]に転換する
     * @param mix $obj 対象
     * @return bool
     */
    public static function objectToArray($obj)
    {
        if (empty($obj)) {
            return array();
        }
        $result = array();
        foreach ($obj as $key => $value) {
            if (gettype($value) == 'array' || gettype($value) == 'object') {
                $result[$key] = Tool::objectToArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    // CN-START
    /**
     * 指定经纬度和半径，计算出覆盖的经纬度的范围
     * @param fload $lat
     * @param fload $lon
     * @param int $raidus
     * @return array
     */
    // CN-END
    /**
     * 経緯度と半径を指定して、覆った経緯度の範囲を計算する。
     * @param fload $lat
     * @param fload $lon
     * @param int $raidus
     * @return array
     */
    public static function getAround($lat, $lon, $radius)
    {
        $result = array('minLat' => 0, 'maxLat' => 0, '$minLon' => 0, 'maxLon' => 0);
        if (!is_numeric($lat) || !is_numeric($lon)) {
            return $result;
        }
        $PI = 3.14159265;
        $degree = (24901 * 1609) / 360.0;
        $dpmLat = 1 / $degree;
        $dpmLon = 1 / ($degree * cos($lat * ($PI / 180)));

        $radiusLat = $dpmLat * $radius;
        $radiusLon = $dpmLon * $radius;

        $minLat = $lat - $radiusLat;
        $maxLat = $lat + $radiusLat;
        $minLon = $lon - $radiusLon;
        $maxLon = $lon + $radiusLon;

        return array('minLat' => $minLat, 'maxLat' => $maxLat, 'minLon' => $minLon, 'maxLon' => $maxLon);
    }

    // CN-START
    /** 发送POST请求
     * 返回值为false或stdClass类实例
     */
    // CN-END
    /**
     * POSTリクエストを発送して、返す値はfalseかstdClassの実例
     * @param type $logger
     * @param type $uri
     * @param type $queryString
     * @return boolean
     */
    public static function sendCurlRequest($logger, $uri, $queryString)
    {
        $logger->alert('##################uri:' . $uri);
        $logger->alert('##################SendQuery:' . $queryString);
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/x-www-form-urlencoded; charset=utf-8"));
        $curl_result = curl_exec($ch);
        $curl_error = curl_errno($ch);
        curl_close($ch);

        if ($curl_error) {
            return false;
        } else {
            $logger->alert('##################CurlResult:' . $curl_result);
            return json_decode($curl_result);
        }
    }

    public static function sendGoogleAnalytics($apiName, $queryString)
    {
//        require_once 'GAT/index.php';
//        sendAnalytics($apiName, urlencode($queryString));
    }

    // CN-START
    /**
     * 判断优惠劵的曜日,返回指定类别
     * @param type $coupon
     * @return string
     */
    // CN-END
    /**
     * クーポンの曜日を判断して、指定類別に戻る。
     * @param type $coupon
     * @return string
     */
    public static function getCouponMode($coupon)
    {
        $sun = $coupon['validitySun'];
        $mon = $coupon['validityMon'];
        $tue = $coupon['validityTue'];
        $wed = $coupon['validityWed'];
        $thu = $coupon['validityThu'];
        $fri = $coupon['validityFri'];
        $sat = $coupon['validitySat'];
        $holiday = $coupon['validityHoliday'];
        if ($sun && $mon && $tue && $wed && $thu && $fri && $sat && $holiday) {
            return 'full';
        } else if ($mon && $tue && $wed && $thu && $fri && !$sun && !$sat && !$holiday) {
            return 'ordinary';
        } else if (!$mon && !$tue && !$wed && !$thu && !$fri && $sun && $sat && $holiday) {
            return 'rest';
        } else {
            return 'regular';
        }
    }

    /**
     * 有効曜日
     * @param Array $coupon
     * @param int $getColor
     * return String  有効曜日
     */
    public static function getCouponValidityDateText($coupon, $getColor = 0)
    {
        $sun = $coupon['validitySun'];
        $mon = $coupon['validityMon'];
        $tue = $coupon['validityTue'];
        $wed = $coupon['validityWed'];
        $thu = $coupon['validityThu'];
        $fri = $coupon['validityFri'];
        $sat = $coupon['validitySat'];
        $holiday = $coupon['validityHoliday'];
        $text = null;
        $bgColor = null;
        if ($sun && $mon && $tue && $wed && $thu && $fri && $sat && $holiday) {
            if ($getColor) {
                $bgColor = 'greenBox';
            } else {
                $text = '全日';
            }
        } else if (($mon && $tue && $wed && $thu && $fri) && (!($sat | $sun | $holiday))) {
            if ($getColor) {
                $bgColor = 'blueBox';
            } else {
                $text = '平日';
            }
        } else if (($sun && $sat && $holiday) && (!($mon | $tue | $wed | $thu | $fri))) {
            if ($getColor) {
                $bgColor = 'redBox redZindex';
            } else {
                $text = '土日祝';
            }
        } else {
            if ($getColor) {
                $bgColor = 'purpleBox';
            } else {
                $text = $mon ? '月,' : '';
                $text .= $tue ? '火,' : '';
                $text .= $wed ? '水,' : '';
                $text .= $thu ? '木,' : '';
                $text .= $fri ? '金,' : '';
                $text .= $sat ? '土,' : '';
                $text .= $sun ? '日,' : '';
                $text .= $holiday ? '祝 ' : '';
                $text = trim($text, ',');
            }
        }
        if ($getColor) {
            return $bgColor;
        }
        return $text;
    }

    /**
     * conversion time hhmm to hh:mm
     * @param string $time
     * @return string $time(hh:mm)
     */
    public static function conversionTime($time)
    {
        $time = str_pad($time, 4, '0', STR_PAD_LEFT);
        $newtime = preg_replace('/(\d{2})(\d{2})/', '\1:\2', $time);
        return $newtime;
    }

    // CN-START
    /* 使用内部扩展进行解压文件
     */
    // CN-END
    /**
     * PHPを使ったZipArchiveタイプの解凍ファイル
     * @param type $file
     * @param type $destination
     */
    public static function unzipFile($file, $destination)
    {
        // create object
        $zip = new \ZipArchive();
        // open archive
        if ($zip->open($file) !== TRUE) {
            die('Could not open archive');
        }
        // extract contents to destination directory
        $zip->extractTo($destination);
        // close archive
        $zip->close();
    }

    /**
     * make directory
     * @param string $folder
     */
    public static function mkFolder($folder)
    {

        if (!is_readable($folder)) {

            self::mkFolder(dirname($folder));

            if (!is_file($folder))
                mkdir($folder, 0777);
        }
    }

    /**
     * log追加
     * @param string $logDir ログファイルパス   $logDir = $controller->get('kernel')->getRootDir().'\logs\sample.log';
     * @param string $logContent ログファイル内容
     * @param integer $logType ログファイルタイプ 
     * $logType: Logger::WARNING、Logger::DEBUG、Logger::INFO、Logger::NOTICE、Logger::ERROR、Logger::CRITICAL、Logger::ALERT、Logger::EMERGENCY
     */
    public static function addLog($logDir, $logContent, $logType = Logger::INFO)
    {
        $log = new Logger('Logger');
        $log->pushHandler(new StreamHandler($logDir, $logType));
        // add records to the log
        switch ($logType) {
            case Logger::WARNING :
                $log->addWarning($logContent);
                break;
            case Logger::NOTICE :
                $log->addNotice($logContent);
                break;
            case Logger::DEBUG :
                $log->addDebug($logContent);
                break;
            case Logger::ERROR :
                $log->addError($logContent);
                break;
            case Logger::CRITICAL :
                $log->addCritical($logContent);
                break;
            case Logger::ALERT :
                $log->addAlert($logContent);
                break;
            case Logger::EMERGENCY :
                $log->addEmergency($logContent);
                break;
            default: $log->addInfo($logContent);
        }
    }

    /**
     * GPS_WGS_16 to GPS_WGS_10
     * @param $hexadecimal
     * return float
     */
    public static function hexToDec($hexadecimal)
    {
        return $hexadecimal ? round(hexdec(substr($hexadecimal, 0, -1)) / 921600, 6) : '';
    }

    /**
     * write contents to the file
     * "r"  - read only, read from the begin of the file
     * "r+" - read and write
     * "w"  - write only, write from the begin of the file. if file not exist, create a new one. if file exist, empty it.
     * "w+" - read and writew, write from the begin of the file. if file not exist, create a new one. if file exist, empty it.
     * "a"  - write only, write from the end of the file. if file not exist, create a new one.
     * "a+" - read and writew, write/read from the end of the file.  if file not exist, create a new one.
     * @param string $content
     * @param string $file (with full path)
     * @param string $mode
     */
    public static function writeFile($content, $fileWithPath, $mode = "w")
    {
        @$fp = fopen($fileWithPath, $mode);
        if (!$fp) {
            return 'ER1';
        }
        if (is_writable($fileWithPath)) {
            if (fwrite($fp, $content)) {
                return 'YES';
            }
        } else {
            if (chmod($fileWithPath, 0755)) {
                if (fwrite($fp, $content)) {
                    return 'YES';
                }
            }
            return 'ER2';
        }
        @fclose($fp);
    }

    /**
     * check weather is a availible date
     * @param string $date format:YYYY/MM/DD
     * return boolean
     */
    public static function validateDate($date)
    {
        $dateArr = explode('/', $date);
        if (count($dateArr) != 3) {
            return false;
        }
        foreach ($dateArr as $v) {
            if (!is_numeric($v)) {
                return false;
            }
        }
        return checkdate($dateArr[1], $dateArr[2], $dateArr[0]);
    }

    /**
     * string too length use "..."
     * @param string $pValue
     * @param integer $pLength
     * @return string
     */
    public static function getAdjustString($pValue, $pLength)
    {

        $sStr = $pValue;
        $adjust_flg = 0;
        $sEnc = 'UTF-8';
        $strWidthTotal = 0;
        $charaArr = array();

        while ($iLen = mb_strlen($sStr, $sEnc)) {

            $character = mb_substr($sStr, 0, 1, $sEnc);
            if (strlen($character) > 1) {
                // 日本語(マルチバイト文字)
                if (preg_match('/^[ｦ-ﾟ]+$/', $character)) {
                    //半角カナ
                    $strWidth = 1;
                } else {
                    //全角カナ 全角かな
                    $strWidth = 2;
                }
            } else {
                //アルファベット(英語)
                $strWidth = 1;
            }

            array_push($charaArr, mb_substr($sStr, 0, 1, $sEnc));
            $strWidthTotal += $strWidth;

            if ($strWidthTotal == $pLength) {
                //指定文字数ちょうどの場合
                $adjust_flg = 1;
                break;
            }
            if ($strWidthTotal > $pLength) {
                //指定文字数をオーバーした場合最後の一文字をカット
                array_pop($charaArr);
                $adjust_flg = 1;
                break;
            }

            $sStr = mb_substr($sStr, 1, $iLen, $sEnc);
        }

        if ($adjust_flg) {
            $retStr = implode($charaArr) . "…";
        } else {
            $retStr = $pValue;
        }

        return $retStr;
    }

    /**
     * get the app type by UserAgent
     */
    public static function getAppTypeByUserAgent($request)
    {
        $userAgent = $request->server->get("HTTP_USER_AGENT");
        $userAgentStr = strtolower($userAgent);
        if (strpos($userAgentStr, 'iphone') !== false || strpos($userAgentStr, 'ipad') !== false) {//iOS
            return 'iPhone';
        } else {//Android
            if (strpos($userAgentStr, 'newcoupark') !== false) {//new CouPark (coupark 1.1)
                return 'newCouPark';
            } else {//old CouPark (coupark 1.0)
                return 'oldCouPark';
            }
        }
    }

    /**
     * get QRscan-button Flag
     * hide QRscan-button in [android old CouPark] or in [hide_qrscan_host] list
     * 
     * @param Object $request
     * @param Array $hideHostArr
     * @return String 'yes'|'no'
     */
    public static function getQrscanFlag($request, $hideHostArr)
    {
        if (self::isInHostArray($request, $hideHostArr)) {
            return 'no';
        } else {
            $userAgent = $request->server->get("HTTP_USER_AGENT");
            $userAgentStr = strtolower($userAgent);
            if (strpos($userAgentStr, 'coupark') !== false) {
                if (strpos($userAgentStr, 'coupark-v') !== false || strpos($userAgentStr, 'newcoupark') !== false) {
                    return 'yes';
                } else {
                    return 'no';
                }
            } else {
                if (strpos($userAgentStr, 'iphone') !== false || strpos($userAgentStr, 'ipad') !== false || strpos($userAgentStr, 'ipod') !== false) {
                    return 'yes';
                } else {
                    return 'no';
                }
            }
        }
    }

    /**
     * get the APP is new version flag
     * 
     * @param Object $request
     * @return boolean
     */
    public static function getTheAppIsNewVersionFlag ($request)
    {
        $userAgent = $request->server->get("HTTP_USER_AGENT");
        $userAgent = strtolower($userAgent);
        $newAppBool = (strpos($userAgent, 'coupark') !== false) && (substr($userAgent, strpos($userAgent, 'coupark') + 9, 5) >= 1.3) ? true : false;

        return $newAppBool;
    }

    // CN-START
    /**
     * 根据域名判断是否需要显示二维码
     *
     * @param Object $request
     * @param Array $hostArr
     * @return Boolean TRUE|FALSE
     */
    // CN-END
    /**
     * ドメインによりバーコードの表示を判断する
     * @param Object $request
     * @param Array $hostArr
     * @return Boolean TRUE|FALSE
     */
    public static function isInHostArray($request, $hostArr)
    {
        $host = $request->server->get("HTTP_HOST");
        return in_array($host, $hostArr) ? TRUE : FALSE;
    }

    // CN-START
    /**
     * 将内容写入文件,
     * @param array $statusArr array('车场ID'=>'状态'，'车场ID'=>'状态')
     * @param string $filePath 文件路径
     * @param string $recordFileDir 履历文件路径
     * @param string $recordContent 履历文件内容
     * @return boolean
     */
    // CN-END
    /**
     * 内容をファイルに書き込む
     * @param array $statusArr array('駐車場ID'=>'ステータス'，'駐車場ID'=>'ステータス')
     * @param string $filePath ファイルパス
     * @param string $recordFileDir 履歴ファイルパス
     * @param string $recordContent
     * @return boolean
     */
    public static function saveMankuStatus($statusArr, $filePath, $recordFileDir = '', $recordContent = '')
    {
        if (empty($statusArr)) {
            return false;
        }

        // CN-START
        // 写入内容：直接以数组形式写入
        // CN-END
        // 書き込む内容：データの形
        $content = var_export($statusArr, true);

        if (!empty($recordFileDir)) {
            // CN-START
            // 履历文件写入 根据数据更新时间进行分组
            // CN-END
            // 履歴ファイル書き込み　データの更新時間により分化する
            $time = strtotime($statusArr['datetime']);
            $dir = $recordFileDir . date('Ymd', $time);
            if (!is_dir($dir)) {
                @mkdir($dir);
            }
            if (is_dir($dir)) {
                $recordFile = $dir . DIRECTORY_SEPARATOR . 'manku_' . date('Ymd_His', $time) . '.txt';
                @file_put_contents($recordFile, $recordContent);
            }
        }
        // CN-START
        // 写入文件
        // CN-END
        // ファイルを書き込む
        return file_put_contents($filePath, "<?php\nreturn " . $content . ";\n");
    }

    // CN-START
    /**
     * 读取文件
     * @param string $file
     * @param string $parkingId 停车场ID 当此项设置时 直接返回状态值
     * @return array array('车场ID'=>'状态'，'车场ID'=>'状态')
     */
    // CN-END
    /**
     * ファイルを読み取る
     * @param string $file
     * @param string $parkingId 駐車場ID 設定された場合、ステータス値が戻る
     * @return array array('駐車場ID'=>'ステータス'，'駐車場ID'=>'ステータス')
     */
    public static function getMankuStatus($file, $parkingId = NULL)
    {
        if (!file_exists($file)) {
            return;
        }
        $content = include $file;
        if (!empty($content)) {
            if (!empty($parkingId) && isset($content[$parkingId])) {
                return $content[$parkingId];
            }
            return $content;
        }
        return;
    }

    // CN-START
    /**
     * 获取指定网址的内容
     * @param string $url 带验证信息的网址
     */
    // CN-END
    /**
     * 指定されたアドレスの内容を取得する
     * @param string $url 認証情報付きのアドレス
     * @return array
     */
    public static function getUrlContent($url, $authUser = null, $authPassword = null)
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
        $options = array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POST => false,
            CURLOPT_USERAGENT => $user_agent,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0
        );

        // basic authentication
        if (!empty($authUser) && !empty($authPassword)) {
            $options[CURLOPT_USERPWD] = "$authUser:$authPassword";
            $options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
        }

        // CN-START
        /*
         * 执行读取
         */
        // CN-END
        // 読取開始
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        return array('content' => $result, 'header' => $header);
    }

    // CN-STAR
    /**
     * 记录log
     * @param string|array $key  001 or array('001','002')
     * @param string $logFile
     * @param int $return
     */
    // CN-END
    /**
     * ログ記録
     * @param string|array $key   001 or array('001','002')
     * @param string $logFile
     * @param int $return
     */
    public static function saveMankuLog($key, $logFile, $container = null)
    {
        $noteArr = array(
            '001' => "ファイル書き込み失敗",
            '002' => "データを取得できません",
            '003' => "データ更新済み",
            '004' => "データ変更なし、未更新",
            '005' => "サーバーが戻るHTTPヘッダーのデータフォーマットが正しくありません",
            '006' => "ベーシック認証が失敗しました",
            '007' => "HTTPヘッダーの関数\"ParkCount\"の値は実際データの行数と一致していません",
            '008' => "満空情報のデータフォーマットが正しくありません",
        );

        if (is_array($key)) {
            $errorStr = '';
            $errorStrArr = array();
            foreach ($key as $e) {
                if (isset($noteArr[$e])) {
                    $errorStrArr[] = '失敗:' . $noteArr[$e];
                }
            }
            if (!empty($errorStrArr)) {
                $errorStr.=implode("\r\n", $errorStrArr);
            }

            if ($container && $key != '003' && $key != '004') {
                $mailSender = $container->getParameter('manku_email_from');
                $mailReceiver = $container->getParameter('manku_email_to');
                $mailSubject = '[CouPark]満空情報取得エラー検知';
                $mailContent = "満空情報サーバへの接続エラーを検知いたしました。\r\n接続状況の確認をお願いいたします。\r\n\r\n";
                $mailContent.=$errorStr;
                EmailSendHelper::sendQmail($container, $mailSubject, $mailContent, $mailSender, $mailReceiver);
            }
        } else {
            if (isset($noteArr[$key])) {
                if ($key == '003' || $key == '004') {
                    $logContent = '成功:';
                } else {
                    $logContent = '失敗:';
                }
                $logContent.=$noteArr[$key];
                if (!file_exists($logFile)) {
                    @fclose(@fopen($logFile, 'w'));
                }
                if (file_exists($logFile)) {
                    self::addLog($logFile, $logContent);
                }
            }
        }
    }

    /**
     * set length to text
     * @param float $length
     * @return string $text
     */
    public static function setLengthToText($length = 0)
    {
        $lengthInt = round($length);
        if ($lengthInt < 1000) {
            return $lengthInt . 'm';
        }
        if ($lengthInt >= 1000) {
            $lengthInt = $lengthInt / 1000;
            $lengthInt = sprintf('%.1f', (float) $lengthInt);
            return $lengthInt . 'km';
        }
    }

    /**
     * get mankustatus icon class by mankustatus
     * @param interger $manku_status
     * @return string @manku_status_class
     */
    public static function getMankuStatusIconClass($manku_status = 7)
    {
        switch ($manku_status) {
            case 0: return 'null';
                break;
            case 1: return 'mix';
                break;
            case 2: return 'full';
                break;
            case 3: return 'p';
                break;
            case 7: return 'p';
                break;
            default: return 'p';
                break;
        }
    }

    /**
     * get mankustatus icon by having couponFlag
     * @param interger $couponFlag
     * @param interger $mankuStatus
     * @return string @mankuStatusClass
     */
    public static function getMankuStatusIconInMapMarker($couponFlag, $mankuStatus = 7)
    {
        if ($couponFlag) {
            switch ($mankuStatus) {
                case 0: return 'coupon_marker_k';
                    break; //空車
                case 1: return 'coupon_marker_h';
                    break; //混雑
                case 2: return 'coupon_marker_m';
                    break; //満車
                case 3: return 'coupon_marker';
                    break; //休止／閉鎖
                case 7: return 'coupon_marker';
                    break; //不明
                default: return 'coupon_marker';
                    break;
            }
        } else {
            switch ($mankuStatus) {
                case 0: return 'no_coupon_marker_k';
                    break;
                case 1: return 'no_coupon_marker_h';
                    break;
                case 2: return 'no_coupon_marker_m';
                    break;
                case 3: return 'no_coupon_marker';
                    break;
                case 7: return 'no_coupon_marker';
                    break;
                default: return 'no_coupon_marker';
                    break;
            }
        }
    }

    /**
     * get class of android or iphone share image 
     * @return string
     */
    public static function getDeviceShareImageClass($isApple)
    {
        if ($isApple) {
            return 'btn_icon_share';
        } else {
            return 'btn_icon_share_android';
        }
    }

}
