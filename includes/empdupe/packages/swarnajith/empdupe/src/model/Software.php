<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 15/02/2019
 * Time: 12:36 PM
 */

namespace Swarnajith\Empdupe\model;


class Software implements SoftwareInterface
{
    private $recordIdentifier;
    private $productType;


    public function __construct($recordIdentifier = 'SOFTWARE',$productType = 'INHOUSE')
    {
        $this->recordIdentifier = $recordIdentifier;
        $this->productType = $productType;
    }

    /**
     * @return string
     */
    public function getRecordIdentifier(): string
    {
        return $this->recordIdentifier;
    }

    /**
     * @param string $recordIdentifier
     */
    public function setRecordIdentifier(string $recordIdentifier): void
    {
        $this->recordIdentifier = $recordIdentifier;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * @param mixed $productType
     */
    public function setProductType($productType): void
    {
        $this->productType = $productType;
    }

}