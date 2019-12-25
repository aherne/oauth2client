<?php
namespace Test\Lucinda\OAuth2;
    
use Lucinda\OAuth2\RedirectionExecutor;
use Lucinda\UnitTest\Result;

class RedirectionExecutorTest
{
    private $executor;
    
    public function __construct()
    {
        $this->executor = new RedirectionExecutor();
    }

    public function execute()
    {
        $this->executor->execute("http://www.example.com", ["x"=>"y"]);
        return new Result(true);
    }
        

    public function getRedirectURL()
    {
        return new Result($this->executor->getRedirectURL()=="http://www.example.com?x=y");
    }
        

}
