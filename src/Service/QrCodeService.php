<?php

namespace App\Service;

use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;



class QrCodeService
{
    /**
     * @var BuilderInterface
     */
    protected $builder;
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode($client)
    {
        $result = $this->builder
            ->data($client)
            ->encoding(new Encoding('UTF-8'))
            ->ErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->Size(250)
            ->Margin(10)
            ->ForegroundColor(new Color(0, 0, 0))
            ->BackgroundColor(new Color(255, 255, 255))
            ->logoPath('assets/img/logo.png')
            ->logoResizeToHeight(100)
            ->logoResizeToWidth(100)
            ->labelText('Wamidu QrCode')
            ->build();

        $namePng = uniqid('', '') . 'ClientQrCode.png';
        //$result->saveToFile('assets/QrCode/' . $namePng);
        return $result->getDataUri();
    }
}