<?php declare(strict_types = 1);

namespace Abava\Container\Exception;

use Exception;

/**
 * Class CircularReferenceException
 *
 * @package Abava\Container\Exception
 */
class CircularReferenceException extends ContainerException
{
    /**
     * Container entry identifier.
     *
     * @var string
     */
    private $serviceId;

    /**
     * List of entries referred to cause a circular reference.
     *
     * @var array
     */
    private $referenceChain;

    /**
     * CircularReferenceException constructor.
     *
     * @param string $entryId
     * @param array $referenceChain
     * @param Exception|null $previous
     */
    public function __construct(string $entryId, array $referenceChain, Exception $previous = null)
    {
        $referenceChain[] = $entryId;
        parent::__construct(
            sprintf('Circular reference detected for "%s", path: "%s".',
                $entryId, implode(' -> ', $referenceChain)
            ),
            0,
            $previous
        );

        $this->serviceId = $entryId;
        $this->referenceChain = $referenceChain;
    }

    /**
     * Get container entry identifier which caused a circular reference error.
     *
     * @return string
     */
    public function getServiceId(): string
    {
        return $this->serviceId;
    }

    /**
     * Get reference chain.
     *
     * @return array
     */
    public function getReferenceChain(): array
    {
        return $this->referenceChain;
    }
}