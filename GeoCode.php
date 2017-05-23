<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 17.04.17
 * Time: 18:08
 */

namespace arkhipovandrei\yageocoder;


use yii\base\Component;
use yii\httpclient\Client;

/**
 * Class GeoCoder
 *
 * @property string $apiUrl
 * @property string $address
 * @property array $coordinate
 *
 */
class GeoCode extends Component
{
    public $apiUrl = 'https://geocode-maps.yandex.ru/1.x/';

    protected $_address;
    protected $_coordinates = [
        'lat' => 0,
        'lng' => 0
    ];

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->_address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * @return array|null
     */
    public function find()
    {
        $this->_coordinates = [];
        $featureMember = $this->fetchData();

        if (!empty($featureMember)) {
            //get cordinates
            if (key_exists('GeoObject', $featureMember)) {
                $coordinate = explode(' ', $featureMember['GeoObject']['Point']['pos']);
            } elseif (!empty($featureMember[0])) {
                $coordinate = explode(' ', $featureMember[0]->GeoObject->Point->pos);
            }

            if (!empty($coordinate)) {
                $this->_coordinates = ['lng' => $coordinate[0], 'lat' => $coordinate[1]];
            }
        }

        return $this->_coordinates;
    }

    public function findAll()
    {

        $this->_coordinates = [];
        $featureMember = $this->fetchData();


        if (!empty($featureMember)) {

            if (key_exists('GeoObject', $featureMember)) {
                $coordinate = explode(' ', $featureMember['GeoObject']['Point']['pos']);

                if(!empty($coordinate)) {
                    $this->_coordinates[] = ['lng' => $coordinate[0], 'lat' => $coordinate[1]];
                }

            } elseif (!empty($featureMember)) {
                foreach ($featureMember as $member) {
                    $coordinate = explode(' ',$member[0]->GeoObject->Point->pos);

                    if(!empty($coordinate)) {
                        $this->_coordinates[] = ['lng' => $coordinate[0], 'lat' => $coordinate[1]];
                    }
                }
            }
        }

        return $this->_coordinates;
    }

    /**
     * @return array
     */
    protected function fetchData()
    {
        $response = (new Client(['baseUrl' => $this->apiUrl]))
            ->createRequest()
            ->setData(['geocode' => $this->address])
            ->send();

        if ($response->isOk && !empty($response->data['GeoObjectCollection']) && !empty($response->data['GeoObjectCollection']['featureMember'])) {
            return $response->data['GeoObjectCollection']['featureMember'];
        }

        return [];
    }
}
