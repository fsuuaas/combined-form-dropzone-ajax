<?php
class App 
{

	/**
	 * @var will going contain database connection
	 */
	protected $_con;
	protected $files;

	/**
	 * App Constructor 
	 */
	public function __construct()
	{
		$db = new DBclass();
		$this->_con = $db->con;
		
	}

	/**
	 * Save method
	 */
    public function save($data)
    {
	    if(!empty($data['files'])) {
			$data['files'] = $this->restructureArray($data['files']);
			$image = new ImageUploader();
			$this->files = $image->upload($data['files']);
		}
		$userId = $this->saveUser($data['data']);
		if (!empty($this->files)){
			$this->saveUserImages($this->files, $userId);
		}
	}
	

	/**
	 * SaveUser method
	 * 
	 * @param array $data user information
	 * 
	 * @return int $userId 
	 */
	private function saveUser($data)
	{

		$stmt = $this->_con->prepare( 'INSERT INTO users(name, email, mobile) VALUES(:name, :email, :mobile)');

		$stmt->execute([
			':name' => $data['name'],
			':email' => $data['email'],
			':mobile' => $data['mobile']
		]);
		
		return $this->_con->lastInsertId();
	}

	/**
	 * SaveUserImages method
	 * 
	 * @param array images list of images
	 * 
	 * @param int $userId userid
	 * 
	 * @return null
	 */
	private function saveUserImages(array $images, $userId)
	{
		$stmt = $this->_con->prepare( 'INSERT INTO user_images(user_id, image) VALUES(:user_id, :image)');
		foreach($images as $image)
		{
			$stmt->execute([':user_id' => $userId, ':image' => $image['src']]);
		}
	}


	/**
	 * RestructureArray method
	 */
	public function restructureArray(array $images)
	{
		$result = array();
		foreach ($images as $key => $value) {
			foreach ($value as $k => $val) {
				for ($i = 0; $i < count($val); $i++) {
					$result[$i][$k] = $val[$i];
				}
			}
		}

		return $result;
	}

}