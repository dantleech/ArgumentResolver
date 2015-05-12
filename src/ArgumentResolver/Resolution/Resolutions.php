<?php

namespace ArgumentResolver\Resolution;

class Resolutions implements \IteratorAggregate
{
    /**
     * @var Resolution[]
     */
    private $resolutions;

    /**
     * @param Resolution[] $resolutions
     */
    public function __construct(array $resolutions = [])
    {
        $this->resolutions = $resolutions;
    }

    /**
     * @param Resolution $resolution
     */
    public function add(Resolution $resolution)
    {
        $this->resolutions[] = $resolution;
    }

    /**
     * @param array $resolutions
     */
    public function addCollection(array $resolutions)
    {
        foreach ($resolutions as $resolution) {
            $this->add($resolution);
        }
    }

    /**
     * Sort `Resolution`s by priority.
     *
     * The higher the priority is, the first the `Resolution` will be.
     *
     * @return Resolutions
     */
    public function sortByPriority()
    {
        usort($this->resolutions, function (Resolution $left, Resolution $right) {
            return $left->priority() < $right->priority() ? 1 : -1;
        });

        return $this;
    }

    /**
     * @return array
     */
    public function toArgumentsArray()
    {
        $arguments = [];
        foreach ($this->resolutions as $resolution) {
            if (array_key_exists($resolution->position(), $arguments)) {
                continue;
            }

            $arguments[$resolution->position()] = $resolution->value();
        }

        ksort($arguments);
        return $arguments;
    }

    /**
     * {@inheritdoc).
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * @return Resolution[]
     */
    public function toArray()
    {
        return $this->resolutions;
    }

    /**
     * @return array
     */
    public function getMissingResolutionPositions()
    {
        $positions = [];

        foreach (range(0, $this->getHighestPosition()) as $position) {
            if (null === $this->getByPosition($position)) {
                $positions[] = $position;
            }
        }

        return $positions;
    }

    /**
     * @return int
     */
    private function getHighestPosition()
    {
        $position = 0;

        foreach ($this->resolutions as $resolution) {
            $position = max($position, $resolution->position());
        }

        return $position;
    }

    /**
     * @param $position
     * @return Resolution|null
     */
    private function getByPosition($position)
    {
        foreach ($this->resolutions as $resolution) {
            if ($resolution->position() === $position) {
                return $resolution;
            }
        }

        return null;
    }
}
