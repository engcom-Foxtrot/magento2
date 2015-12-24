<?php
/**
 * Copyright � 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Vault\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Payment\Model\Config;
use Magento\Vault\Model\Ui\Adminhtml\TokensConfigProvider;
use Magento\Payment\Model\CcConfigProvider;

/**
 * Class Form
 */
class Form extends \Magento\Payment\Block\Form
{
    /**
     * @var TokensConfigProvider
     */
    private $tokensProvider;

    /**
     * @var CcConfigProvider
     */
    private $cardConfigProvider;

    /**
     * @param Context $context
     * @param TokensConfigProvider $tokensConfigProvider
     * @param CcConfigProvider $ccConfigProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        TokensConfigProvider $tokensConfigProvider,
        CcConfigProvider $ccConfigProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->tokensProvider = $tokensConfigProvider;
        $this->cardConfigProvider = $ccConfigProvider;
    }

    /**
     * Get payment provider method code
     * @return null|string
     */
    public function getProviderMethodCode()
    {
        return $this->tokensProvider->getProviderMethodCode();
    }

    /**
     * @inheritdoc
     */
    protected function _prepareLayout()
    {
        $this->createVaultBlocks();
        return $this;
    }

    /**
     * Create block for own configuration for each payment token
     */
    protected function createVaultBlocks()
    {
        $icons = $this->cardConfigProvider->getConfig()['payment']['ccform']['icons'];
        $payments = $this->tokensProvider->getConfig();
        foreach ($payments as $key => $payment) {
            $data = $payment['config'];
            $data['id'] = $key;
            $data['icons'] = $icons;
            $data['code'] = $this->getProviderMethodCode();
            $this->addChild($key, $payment['component'], $data);
        }
    }
}