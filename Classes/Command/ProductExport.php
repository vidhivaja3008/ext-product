<?php
declare(strict_types=1);

namespace Nitsan\NitsanProduct\Command;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use Symfony\Component\Console\Command\Command;
use TYPO3\CMS\Core\Resource\StorageRepository;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;  
use Nitsan\NitsanProduct\Domain\Repository\ProductRepository;

#[AsCommand(
    name: 'nitsanproduct:productexport',
    description: 'Export the details of product',
)]
final class ProductExport extends Command
{
    private ResourceFactory $resourceFactory;
    private StorageRepository $storageRepository;
    private ProductRepository $productRepository;

    public function __construct(
        ResourceFactory $resourceFactory,
        StorageRepository $storageRepository,ProductRepository $productRepository
    ) {
        parent::__construct();

        $this->resourceFactory = $resourceFactory;
        $this->storageRepository = $storageRepository;
        $this->productRepository = $productRepository;
    }

    // Add folder name beside the command 
    // protected function configure():void{
    //     $this->addArgument(
    //       'folderName'  ,
    //       InputArgument::REQUIRED,
    //        'Folder name where CSV should be stored'
    //     );
    // }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // $storageFolderName = $input->getArgument('folderName');
        // Ask user to enter folder name 
        $user_input = new SymfonyStyle($input, $output);

        $storageFolderName = $user_input->ask('Enter folder name to save CSV file', 'products_folder');

        // Replace space with _ id user add name with space 

        $storageFolderName = str_replace(' ', '_', trim($storageFolderName));
        $storage = $this->storageRepository->findByUid(1);

        if (!$storage) {
            $output->writeln('Storage not found');
            return Command::FAILURE;
        }

        // $storageFolderName = 'products_folder';
        // Create folder if not exits 
        if (!$storage->hasFolder($storageFolderName)) {
            $storage->createFolder($storageFolderName);
        }

        $targetFolder = $storage->getFolder($storageFolderName);
        // get data of products from database
        $produts = $this->productRepository->productDetails();
        
        $data = [
            ['Id', 'Product name','Description','Price','Brand name']
        ];

        foreach($produts as $product){
            $data[] = [
                $product['uid'],
                $product['name'],
                $product['description'],
                $product['price'],
                $product['brand_name'],
            ];
        }

        $csvContent = '';

        foreach ($data as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        $fileName = 'products_' . date('dmy_His') . '.csv';

        $targetFile = $targetFolder->createFile($fileName);
        $targetFile->setContents($csvContent);

        $output->writeln(
            'Export successful. File saved at: ' . $targetFile->getPublicUrl()
        );

        return Command::SUCCESS;
    }
}