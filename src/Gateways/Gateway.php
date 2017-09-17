<?php

namespace Gabievi\SMS\Gateways;

class Gateway
{
    /**
     * Credentials of chosen SMS provider
     * @type mixed
     */
    protected $credentials;

    /**
     * Gateway constructor.
     * @param $provider
     */
    public function __construct($provider)
    {
        $this->credentials = config('sms.providers.' . $provider);
    }

    /**
     * Generate http query for provider
     *
     * @param array $params
     *
     * @return string
     */
    public function buildQuery(array $params = [])
    {
        return '?' . http_build_query($params);
    }

    /**
     * cURL request with additional params
     *
     * @param string $method
     * @param string $action
     * @param array $params
     *
     * @return array
     */
    public function cURL($action = 'send', array $params = [], $method = 'get')
    {
        $ch = curl_init();

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_URL, $this->generateUrl($action));
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getParams($params));
        } else {
            curl_setopt($ch, CURLOPT_URL, $this->generateUrl($action, $params));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'info' => $info,
            'result' => $result,
        ];
    }
}
