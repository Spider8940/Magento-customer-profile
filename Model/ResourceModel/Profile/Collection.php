<?php
namespace Techmail\Devops\Model\ResourceModel\Profile;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Techmail\Devops\Model\Profile as ProfileModel;
use Techmail\Devops\Model\ResourceModel\Profile as ProfileResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(ProfileModel::class, ProfileResourceModel::class);
    }
}
