<?php 
	class User {

		private $username;
		private $password;
		private $password2;
		private $image;
		private $firstName;
		private $lastName;
		private $address;
		private $city;
		private $country;
		private $postcode;
		private $birthday;
		private $gender;
		private $email; 

		private $weight;
		private $height;


		function __construct($data) {
			$this->username 	= isset($data["username"]) 	? $data["username"] 	: "";
			$this->password 	= isset($data["password"]) ? $data["password"] 	: "";
			$this->password2 	= isset($data["password2"])	? $data["password2"] 	: "";
			$this->image 		= isset($data["image"]) 	? $data["image"] 	: "images/superthumb.jpg";
			$this->firstName 	= isset($data["firstName"])	? $data["firstName"] : "";
			$this->lastName 	= isset($data["lastName"]) 	? $data["lastName"] 	: "";
			$this->address 		= isset($data["address"]) 	? $data["address"] 	: "";
			$this->city 		= isset($data["city"]) 		? $data["city"] 		: "";
			$this->country		= isset($data["country"]) 	? $data["country"] 	: "";
			$this->postcode 	= isset($data["postcode"]) 	? $data["postcode"] 	: "";
			$this->birthday 	= isset($data["birthday"]) 	? $data["birthday"] 	: "";
			$this->gender 		= isset($data["gender"]) 	? $data["gender"] 	: "";
			$this->email 		= isset($data["email"]) 	? $data["email"] 		: "";
			$this->weight 		= isset($data["weight"]) 		? $data["weight"] 		: "";
			$this->height 		= isset($data["height"]) 		? $data["height"] 		: "";
		}

		public function getUsername() {
			return $this->username;
		}

		public function getPassword() {
			return $this->password;
		}

		public function getImage() {
			return $this->image;
		}

		public function getFirstName() {
			return $this->firstName;
		}
		
		public function getLastName() {
			return $this->lastName;
		}

		public function getAddress() {
			return $this->address;
		}

		public function getCity() {
			return $this->city;
		}

		public function getCountry() {
			return $this->country;
		}

		public function getPostcode() {
			return $this->postcode;
		}

		public function getBirthday() {
			return $this->birthday;
		}

		public function getGender() {
			return $this->gender;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getHeight() {
			return $this->height;
		}

		public function getWeight() {
			return $this->weight;
		}
		public function setUsername($new) {
			$this->username=$new;
		}

		public function setPassword($new) {
			$this->password=$new;
		}

		public function setImage($new) {
			$this->image=$new;
		}

		public function setFirstName($new) {
			$this->firstName=$new;
		}
		
		public function setLastName($new) {
			$this->lastName=$new;
		}

		public function setAddress($new) {
			$this->address=$new;
		}

		public function setCity($new) {
			$this->city=$new;
		}

		public function setCountry($new) {
			$this->country=$new;
		}

		public function setPostcode($new) {
			$this->postcode=$new;
		}

		public function setBirthday($new) {
			$this->birthday=$new;
		}

		public function setGender($new) {
			$this->gender=$new;
		}

		public function setEmail($new) {
			$this->email=$new;
		}

		public function setHeight($new) {
			$this->height=$new;
		}

		public function setWeight($new) {
			$this->weight=$new;
		}

		public function validate(& $errors = []) {
			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = "Uneti mejl nije dobrog formata.";
			}
			
			if ($this->password != $this->password2) {
				$errors[] = "Šifra i ponovljena šifra nisu iste.";
			}
			
			if (strlen($this->postcode) != 5) {
				$errors[] = "Poštanski broj mora imati pet cifara.";
			}

			return count($errors) == 0;
		}

		


		public function getFullName() {
			return $this->firstName." ".$this->lastName;
		}
	}

 ?>