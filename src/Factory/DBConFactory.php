<?php

declare(strict_types=1);

namespace App\Factory;

use Mysqli;

final class DBConFactory
{
    private $dbArray;
	private $db;
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

	public function getConnection(){
		try
		{			
		
			$host = $this->settings['host'];
			$username = $this->settings['username'];
			$password = $this->settings['password'];
			$database = $this->settings['database'];
			
		
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->db = new Mysqli($host, $username, $password, $database);	
			return $this->db;		
		} 		
		catch (Exception $e) {
			throw $e;
		} 
		
	}

    public function close()
    {
        if ($this->db instanceof Mysqli)
		{
			mysqli_close($this->db) ;
		}        
    }
	
	
}

?>