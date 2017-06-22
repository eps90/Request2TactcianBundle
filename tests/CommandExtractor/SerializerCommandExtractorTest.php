<?php
declare(strict_types=1);

namespace Eps\Req2CmdBundle\Tests\CommandExtractor;

use Eps\Req2CmdBundle\CommandExtractor\SerializerCommandExtractor;
use Eps\Req2CmdBundle\Tests\Fixtures\Command\DummyCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SerializerCommandExtractorTest extends TestCase
{
    /**
     * @var SerializerCommandExtractor
     */
    private $extractor;

    /**
     * @var SerializerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializer;

    /**
     * @var DecoderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $decoder;

    /**
     * @var DenormalizerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $denormalizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->decoder = $this->createMock(DecoderInterface::class);
        $this->denormalizer = $this->createMock(DenormalizerInterface::class);
        $this->extractor = new SerializerCommandExtractor($this->serializer, $this->decoder, $this->denormalizer);
    }

    /**
     * @test
     */
    public function itShouldDeserializeRequestUsingSerializer(): void
    {
        $commandClass = 'MyClass';
        $requestContent = json_encode([
            'name' => 'My command',
            'opts' => [
                'a' => 1,
                'b' => true
            ]
        ]);
        $request = new Request([], [], [], [], [], [], $requestContent);
        $requestedFormat = 'json';
        $request->setRequestFormat($requestedFormat);

        $mappedCommand = new DummyCommand('My class', ['a' => 1, 'b' => true]);
        $this->serializer->expects(static::once())
            ->method('deserialize')
            ->with($requestContent, $commandClass, $requestedFormat)
            ->willReturn($mappedCommand);

        $actualResult = $this->extractor->extractFromRequest($request, $commandClass);
        $expectedResult = $mappedCommand;

        static::assertEquals($expectedResult, $actualResult);
    }

    /**
     * @test
     */
    public function itShouldAllowToAddAdditionalProperties(): void
    {
        $commandClass = 'MyClass';
        $requestedContent = [
            'name' => 'My command'
        ];
        $encodedContent = json_encode($requestedContent);
        $requestedParams = [
            'id' => '312'
        ];
        $request = new Request([], [], $requestedParams, [], [], [], $encodedContent);
        $requestedFormat = 'json';
        $request->setRequestFormat($requestedFormat);

        $this->decoder->expects(static::once())
            ->method('decode')
            ->with($encodedContent, $requestedFormat)
            ->willReturn($requestedContent);

        $modifiedContent = [
            'name' => 'My command',
            'id' => '312'
        ];
        $resultCommand = new DummyCommand('My command', []);
        $this->denormalizer->expects(static::once())
            ->method('denormalize')
            ->with($modifiedContent, $commandClass, $requestedFormat)
            ->willReturn($resultCommand);

        $actualResult = $this->extractor->extractFromRequest($request, $commandClass, $requestedParams);

        static::assertEquals($resultCommand, $actualResult);
    }
}
