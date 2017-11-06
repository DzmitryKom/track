<?php
/**
 * Created by PhpStorm.
 * User: DKomsa
 * Date: 11/1/2017
 * Time: 11:15 AM
 */

namespace AppBundle\Api;


class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';

    private static $titles = [
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid Json sent',
    ];
    private $statusCode;
    private $type;
    private $title;
    private $extraData=[];

    /**
     * ApiProblem constructor.
     * @param $statusCode
     * @param $type
     */
    public function __construct($statusCode, $type)
    {
        $this->statusCode = $statusCode;
        $this->type = $type;
        if (!isset(self::$titles[$type])){
            throw new \InvalidArgumentException('Error: No title for type: '.$type);
        }
        $this->title = self::$titles[$type];
    }

    public function toArray(){
        return array_merge(
            $this->extraData,
            [
                'status'=>$this->statusCode,
                'type'=>$this->type,
                'title'=>$this->title
            ]
        );
    }
    public function set($name,$value){
        $this->extraData[$name]= $value;
    }

    public function getStatusCode(){
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return self::$titles[$this->type];
    }





}