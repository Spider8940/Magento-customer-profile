<?php
namespace Techmail\Devops\Block\Customer;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;
use Techmail\Devops\Model\ProfileFactory;
use Magento\Store\Model\StoreManagerInterface;

class ProfileImage extends Template
{
    protected $customerSession;
    protected $profileFactory;
    protected $storeManager;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        ProfileFactory $profileFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->profileFactory = $profileFactory;
        $this->storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve profile image file path
     *
     * @return string|false
     */
    public function getProfileImage()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $profile = $this->profileFactory->create()->loadLatestByCustomerId($customerId);
        if ($profile->getId()) {
            return $profile->getFilePath();
        }
        return false;
    }

    /**
     * Retrieve base media URL
     *
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
}
