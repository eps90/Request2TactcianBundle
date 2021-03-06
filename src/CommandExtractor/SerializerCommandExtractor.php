<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\CommandExtractor;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerCommandExtractor implements CommandExtractorInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * SerializerCommandExtractor constructor.
     * @param SerializerInterface $serializer
     * @param DecoderInterface $decoder
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(
        SerializerInterface $serializer,
        DecoderInterface $decoder,
        DenormalizerInterface $denormalizer
    ) {
        $this->serializer = $serializer;
        $this->decoder = $decoder;
        $this->denormalizer = $denormalizer;
    }

    /**
     * {@inheritdoc}
     * @throws \LogicException
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function extractFromRequest(Request $request, string $commandClass, array $additionalProps = [])
    {
        $requestContent = $request->getContent();
        $requestFormat = $request->getRequestFormat();

        if (empty($requestContent)) {
            return $this->denormalizer->denormalize($additionalProps, $commandClass, $requestFormat);
        }

        $decodedContent = $this->decoder->decode($requestContent, $requestFormat);
        $finalProps = array_merge($decodedContent, $additionalProps);

        return $this->denormalizer->denormalize($finalProps, $commandClass, $requestFormat);
    }
}
