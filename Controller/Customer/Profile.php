<?php
namespace Techmail\Devops\Controller\Customer;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory as ImageAdapterFactory;
use Magento\MediaStorage\Helper\File\Storage\Database as StorageHelper;
use Techmail\Devops\Model\ProfileFactory;
use Magento\Framework\Controller\ResultFactory; // Add this line

class Profile extends Action
{
    protected $customerSession;
    protected $fileSystem;
    protected $mediaDirectory;
    protected $uploaderFactory;
    protected $imageAdapterFactory;
    protected $storageHelper;
    protected $profileFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory,
        ImageAdapterFactory $imageAdapterFactory,
        StorageHelper $storageHelper,
        ProfileFactory $profileFactory
    ) {
        $this->customerSession = $customerSession;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->imageAdapterFactory = $imageAdapterFactory;
        $this->storageHelper = $storageHelper;
        $this->mediaDirectory = $this->fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->profileFactory = $profileFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isPost() && isset($_FILES['profile_image'])) {
            try {
                $uploader = $this->uploaderFactory->create(['fileId' => 'profile_image']);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $uploader->setAllowCreateFolders(true);

                $result = $uploader->save($this->mediaDirectory->getAbsolutePath('customer_profiles'));

                $fileName = $result['file'];

                // Save file info in the database
                $customerId = $this->customerSession->getCustomer()->getId();
                $profileData = [
                    'customer_id' => $customerId,
                    'file_name' => $fileName,
                    'file_path' => 'customer_profiles/' . $fileName
                ];

                $profileModel = $this->profileFactory->create();
                $profileModel->setData($profileData);
                $profileModel->save();

                $this->messageManager->addSuccessMessage(__('Profile image uploaded successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Profile image upload failed: ' . $e->getMessage()));
            }
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('customer/account/edit');
    }
}
