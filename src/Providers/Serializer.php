<?php

namespace Khafidprayoga\PhpMicrosite\Providers;

use Khafidprayoga\PhpMicrosite\Utils\AppConfigParser;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer implements ProviderInterface
{
    private static ?SymfonySerializer $serializer = null;

    public static function getInstance(): SymfonySerializer
    {
        if (is_null(self::$serializer)) {

            $encoder = [new JsonEncoder()];
            $normalizer = [
                new ObjectNormalizer(),
            ];
            $serializer = new SymfonySerializer($normalizer, $encoder);

            self::$serializer = $serializer;
        }

        return self::$serializer;
    }
}
