<?php
/**
 * Created by LHB
 * User: LHB
 * Date: 2019/4/15
 * Time: 15:17
 * Email:498807233@qq.com
 */

namespace LockDoor\device\HoneComb\Locks\BlueLocks;


use LockDoor\device\HoneComb\locks\HoneCombLocks;
use LockDoor\LockDoor;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter;

class HoneCombBlueLocks extends HoneCombLocks
{
    use LockDoor;

    function __construct($deviceID, $token)
    {
        parent::__construct($deviceID, $token);
    }

    /**
     * 添加密码
     * @param $type
     * @param $password
     * @param $startTime Formatter YYYY-MM-DD HH:mm:ss
     * @param $endTime Formatter YYYY-MM-DD HH:mm:ss
     * @param $userId
     * @param array $weeks
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addPasswords($type, $password, $startTime, $endTime, $userId, array $weeks)
    {
        /**
         * {
         * "code": 0,
         * "msg": "成功",
         * "data": {
         * "command": "3QEDMNm/GCPieI6zOejpko2UArZSv63I7ZmQLIqxL70j7zco+gP12U2dEF5KtngnoGyQqg==",
         * "source": {
         * "passwordId": 1,
         * "type": "PLAIN",
         * "password": "258147",
         * "userId": 1,
         * "startTime": "2019-04-22 15:42:00",
         * "endTime": "2019-04-22 15:50:00",
         * "weeks": [
         * "TUESDAY",
         * "WEDNESDAY"
         * ],
         * "status": "ADDING",
         * "cmdId": "5cbd70d07625f50001956d26",
         * "authId": "5cbd70d07625f50001956d25"
         * }
         * }
         * }
         */
        //$type PLAIN普通密码 CYCLE周期密码 MANAGER管理密码
        $this->uri = '/ble-locks/' . $this->deviceId . '/passwords';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $bodyArr = [];
        $bodyArr['type'] = $type;
        $bodyArr['password'] = $password;
        $bodyArr['startTime'] = $startTime;
        $bodyArr['endTime'] = $endTime;
        $bodyArr['userId'] = $userId ? $userId : 0;
        $bodyArr['weeks'] = $weeks ? $weeks : [];
        $requestParams['body'] = json_encode($bodyArr);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }

    /**
     * 添加卡片
     * @param $type
     * @param $cardNumber
     * @param $startTime
     * @param $endTime
     * @param $userId
     * @param array $weeks
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addCards($type, $cardNumber, $startTime, $endTime, $userId, array $weeks)
    {
        // TODO: Implement addCards() method.
        $this->uri = '/ble-locks/' . $this->deviceId . '/cards';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $bodyArr = [];
        $bodyArr['type'] = $type;
        $bodyArr['startTime'] = $startTime;
        $bodyArr['endTime'] = $endTime;
        $bodyArr['userId'] = $userId ? $userId : 0;
        $bodyArr['weeks'] = $weeks ? $weeks : [];
        $requestParams['body'] = json_encode($bodyArr);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }

    /**
     * 本地添加指纹
     * 通过接口获取门锁录制指纹指令数据包，然后通过手机蓝牙将该数据包传输到门锁，进行本地指纹采集操作。
     * @param $startTime
     * @param $endTime
     * @param int $userId
     * @param array $fingerprint
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addFingers($startTime, $endTime, $userId = 0, array $fingerprint = [])
    {
        // TODO: Implement addFingers() method.
        //蓝牙锁 指纹 和 NB 不一样
        $this->uri = '/ble-locks/' . $this->deviceId . '/fingers';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $bodyArr = [];
        $bodyArr['startTime'] = $startTime;
        $bodyArr['endTime'] = $endTime;
        $bodyArr['userId'] = $userId ? $userId : 0;
        $requestParams['body'] = json_encode($bodyArr);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }

    /**
     * 批量操作门锁权限
     * @param $operate
     * @param string $userId
     * @param string $authId
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function authorities($operate, $userId = '', $authId = '')
    {
        // TODO: Implement authorities() method.
        $this->uri = '/ble-locks/' . $this->deviceId . '/authorities';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $bodyArr = [];
        if ($userId || $userId = 0) {
            $bodyArr['userId'] = $userId ? $userId : 0;
        }
        if ($authId) {
            $bodyArr['authId'] = $authId;
        }
        $requestParams['body'] = json_encode($bodyArr);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }


    /**
     * 提交蓝牙指令执行结果
     * @param $commandId
     * @return array|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadBlueRes($commandId)
    {
        $this->uri = '/ble-locks/' . $this->deviceId . '/commands/' . $commandId;
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }

    /**
     * 蓝牙开门
     * @param int $userId
     * @param  $optType
     * @param  $delayTime
     * @return array|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function openLockDoor($userId = 0, $optType = '', $delayTime = '')
    {
        $this->uri = '/ble-locks/' . $this->deviceId . '/key';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $bodyArr = [];
        if ($userId || $userId === 0) {
            $bodyArr['userId'] = $userId;
        }
        if ($optType !== '') {
            $bodyArr['optType'] = $optType;
        }
        if ($delayTime !== '') {
            $bodyArr['delayTime'] = $delayTime;
        }
        $requestParams['query'] = $bodyArr;
        return $this->request($this->baseUri, $this->uri, $requestParams, 'GET');
    }

    /**
     * 蓝牙门锁设置
     * @param string $brdName
     * @param int $brdInterval
     * @param int $brdLevel
     * @param string $switchMac
     * @return array|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setting($brdName = '', int $brdInterval = 0, int $brdLevel = 100, $switchMac = '')
    {
        $this->uri = '/ble-locks/' . $this->deviceId . '/setting';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $bodyArr = [
            'brdInterval' => $brdInterval,
            'brdLevel' => $brdLevel
        ];
        if ($brdName) {
            $bodyArr['brdName'] = $brdName;
        }
        if ($switchMac) {
            $bodyArr['switchMac'] = $switchMac;
        }
        $requestParams['body'] = json_encode($bodyArr);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }

    /**
     * 同步蓝牙锁的时间
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function syncTime()
    {
        $this->uri = '/ble-locks/' . $this->deviceId . '/time';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        return $this->request($this->baseUri, $this->uri, $requestParams, 'GET');
    }

    /**
     *  本地门锁事件同步
     * @return array|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function syncEvents()
    {
        $this->uri = '/ble-locks/' . $this->deviceId . '/events';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        return $this->request($this->baseUri, $this->uri, $requestParams, 'GET');
    }

    /**
     *  提交门锁事件
     * @param string $eventData
     * @return array|mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadEvents(string $eventData)
    {
        $this->uri = '/ble-locks/' . $this->deviceId . '/events';
        $requestParams['headers'] = array_merge(HONE_COMB_IOT_HEADERS, $this->authorization);
        $requestParams['body'] = json_encode([
            'eventData' => $this->String2Hex($eventData)
        ]);
        return $this->request($this->baseUri, $this->uri, $requestParams);
    }

}