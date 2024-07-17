<?php
namespace Techmail\Devops\Model;

use Magento\Framework\Model\AbstractModel;

class Profile extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Techmail\Devops\Model\ResourceModel\Profile::class);
    }

    public function loadLatestByCustomerId($customerId)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('created_at', 'DESC') // Assuming you have a 'created_at' column
            ->setPageSize(1)
            ->setCurPage(1);

        if ($collection->getSize()) {
            $this->setData($collection->getFirstItem()->getData());
        }

        return $this;
    }

    public function getFilePath()
    {
        return $this->getData('file_path');
    }
}
