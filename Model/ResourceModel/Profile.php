<?php
namespace Techmail\Devops\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Profile extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('customer_profile', 'entity_id');
    }
}
