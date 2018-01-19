<?php
namespace Blog\Model;

use InvalidArgumentException;
use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;

class ZendDbSqlRepository implements PostRepositoryInterface
{
    /**
    * @var AdapterInterface
    */
    private $db;
    
    /**
    * @param AdapterInterface $db
    */
    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }
    
    /**
    * {@inheritDoc}
    */
    public function findAllPosts()
    {
    }
    
    /**
    * {@inheritDoc}
    * @throws InvalidArgumentException
    * @throws RuntimeException
    */
    public function findPost($id)
    {
    }
}