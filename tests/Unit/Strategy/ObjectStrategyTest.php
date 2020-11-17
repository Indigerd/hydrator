<?php

declare(strict_types=1);

namespace Indigerd\Hydrator\Test\Unit\Strategy;

use Indigerd\Hydrator\Hydrator;
use Indigerd\Hydrator\Strategy\ObjectStrategy;
use Indigerd\Hydrator\Test\Unit\Stub\DummyEntity;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ObjectStrategyTest extends TestCase
{
    /**
     * @var MockObject
     */
    protected $hydrator;
    /**
     * @var ObjectStrategy
     */
    protected $strategy;

    /**
     * @var string
     */
    protected $entityName = DummyEntity::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->createMock(Hydrator::class);
        $this->strategy = new ObjectStrategy($this->hydrator, $this->entityName);
    }

    public function testHydrateData()
    {
        $data = [
            'key' => 'value',
        ];

        $entity = $this->createMock($this->entityName);
        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo($this->entityName), $this->identicalTo($data))
            ->willReturn($entity);

        $actual = $this->strategy->hydrate($data);

        $this->assertSame($entity, $actual);
    }

    /**
     * @param $value
     *
     * @dataProvider emptyObjectDataProvider
     */
    public function testHydrateEmptyValue($value)
    {
        $this->assertNull($this->strategy->hydrate($value));
    }

    public function emptyObjectDataProvider(): array
    {
        return [
            [
                'value' => [],
            ],
            [
                'value' => null,
            ]
        ];
    }

    public function testMergeOldValue()
    {
        $oldValue = [
            'key' => 'value',
        ];
        $value = [
            'test' => 'value'
        ];

        $entity = $this->createMock($this->entityName);
        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo($this->entityName), $this->identicalTo(array_merge($oldValue, $value)))
            ->willReturn($entity);

        $actual = $this->strategy->hydrate($value, null, $oldValue);

        $this->assertSame($entity, $actual);
    }

    public function testExtractData()
    {
        $entity = $this->createMock($this->entityName);
        $expected = [
            'key' => 'value',
        ];
        $this->hydrator->expects($this->once())
            ->method('extract')
            ->with($this->identicalTo($entity))
            ->willReturn($expected);

        $actual = $this->strategy->extract($entity);
        $this->assertEquals($expected, $actual);
    }


    public function testExtractNullable()
    {
        $this->assertEquals([], $this->strategy->extract(null));
    }
}
