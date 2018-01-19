<?php
namespace Blog\Model;

use RuntimeException;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;

class ZendDbSqlCommand implements PostCommandInterface
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
    public function insertPost(Post $post)
    {
        $insert = new Insert('posts');
        $insert->values([
            'title' => $post->getTitle(),
            'text' => $post->getText(),
        ]);
        
        $sql = new Sql($this->db);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $result = $statement->execute();
        
        if (! $result instanceof ResultInterface) {
            throw new RuntimeException(
                'Database error occurred during blog post insert operation'
                );
        }
        
        $id = $result->getGeneratedValue();
        
        return new Post(
            $post->getTitle(),
            $post->getText(),
            $result->getGeneratedValue()
            );
    }
    
    /**
    * {@inheritDoc}
    */
    public function updatePost(Post $post)
    {
    }
    
    /**
    * {@inheritDoc}
    */
    public function deletePost(Post $post)
    {
    }
}