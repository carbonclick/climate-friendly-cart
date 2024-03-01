<?php
namespace Carbonclick\CFC\Model\Quote\Item\SalesRule;

use Laminas\Validator\ValidatorInterface;
use Laminas\Validator\Exception\RuntimeException;

class Validator implements ValidatorInterface
{
    /**
     * @var \Carbonclick\CFC\Helper\Email
     */
    private $helper;

    /**
     * @param \Magento\SalesRule\Model\ResourceModel\Rule\Collection $rules
     */
    public function __construct(
        \Carbonclick\CFC\Helper\Email $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws RuntimeException If validation of $value is impossible.
     */
    public function isValid($value)
    {
        if (!$value instanceof \Magento\Quote\Model\Quote\Item\AbstractItem) {
            throw new RuntimeException("Invalid type provided for quote item: " . get_class($value));
        }

        if ($this->helper->getConfig('cfc/general/enable') == 1 && $this->helper->getConfig('cfc/general/product')) {
            if ($value->getProductId() == $this->helper->getConfig('cfc/general/product')) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns an array of messages that explain why the most recent isValid()
     * call returned false. The array keys are validation failure message identifiers,
     * and the array values are the corresponding human-readable message strings.
     *
     * If isValid() was never called or if the most recent isValid() call
     * returned true, then this method returns an empty array.
     *
     * @return array
     */
    public function getMessages()
    {
        return [
            "CarbonClick's carbon offset can't be discounted."
        ];
    }
}
