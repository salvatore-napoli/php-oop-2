<?php
	class Shop {
		protected $name;
		protected $founder;
		protected $foundationYear;

		private $buyers = [];
		private $sellers = [];
		private $items = [];

		public function __construct (string $name, string $founder, int $foundationYear) {
			$this->name = $name;
			$this->founder = $founder;
			$this->foundationYear = $foundationYear;
		}

		public function getName() {
			return $this->name;
		}

		public function getFounder() {
			return $this->founder;
		}

		public function getFoundationYear() {
			return $this->foundationYear;
		}

		public function getBuyers() {
			return $this->buyers;
		}

		public function getItems() {
			return $this->items;
		}

		public function addBuyer(Buyer $buyer) {
			$this->buyers[] = $buyer;
		}

		public function addSeller(Seller $seller) {
			$this->sellers[] = $seller;
		}

		public function addItem(Item $item) {
			$this->items[] = $item;
		}
	}


	class User {
		protected $name;
		protected $surname;
		protected $email;
		protected $dateOfBirth;

		private $password;

		public function __construct (string $name, string $surname, string $email, string $dateOfBirth) {
			$this->name = $name;
			$this->surname = $surname;
			$this->email = $email;
			$this->dateOfBirth = $dateOfBirth;
		}

		public function getName() {
			return $this->name;
		}

		public function getSurname() {
			return $this->surname;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getDateOfBirth() {
			return $this->dateOfBirth;
		}

		public function getPassword() {
			return $this->password;
		}
	}


	class Buyer extends User {
		protected $creditCard;

		private $transactions = [];

		public function __construct(string $name, string $surname, string $email, string $dateOfBirth, Object $creditCard) {
			parent::__construct($name, $surname, $email, $dateOfBirth);
			$this->creditCard = $creditCard;
		}

		public function buyItem($item) {
			$this->transactions[] = $item;
		}

		public function getCreditCard() {
			return $this->creditCard;
		}

		public function getTransactions() {
			return $this->transactions;
		}

		public function validateCreditCard(CreditCard $creditCard) {
			if ($creditCard->getExpiryDate() > date('m/y')) {
				echo ('ESEGUITA');
			} else {
				throw new Exception('FALLITA (La carta di credito è scaduta!)');
			}
		}
	}


	class Seller extends User {

	}

	class CreditCard {
		protected $number;
		protected $expiryDate;
		protected $secretCode;

		public function __construct (string $number, string $expiryDate, int $secretCode) {
			$this->number = $number;
			$this->expiryDate = $expiryDate;
			$this->secretCode = $secretCode;
		}

		public function getNumber() {
			return $this->number;
		}

		public function getExpiryDate() {
			return $this->expiryDate;
		}

		public function getSecretCode() {
			return $this->secretCode;
		}
	}


	class Item {
		protected $name;
		protected $price;
		protected $seller;

		public function __construct (string $name, string $price, Object $seller) {
			$this->name = $name;
			$this->price = $price;
			$this->seller = $seller;
		}

		public function getName() {
			return $this->name;
		}

		public function getPrice() {
			return $this->price;
		}

		public function getSeller() {
			return $this->seller;
		}
	}


	class Food extends Item {

	}

	class Tech extends Item {

	}

	class Jewelry extends Item {

	}



	$negozioACaso = new Shop('NegozioACaso', 'Pinco Pallo', '2012');

	$buyer1CreditCard = new CreditCard('1111111111111111', '04/23', '511');
	$buyer1 = new Buyer('Mario', 'Rossi', 'mariorossi@gmail.com', '11/1/1977', $buyer1CreditCard);

	$buyer2CreditCard = new CreditCard('2222222222222222', '02/21', '522');
	$buyer2 = new Buyer('Paolo', 'Bianchi', 'paolobianchi@gmail.com', '1/12/1990', $buyer2CreditCard);

	$seller1 = new Seller('Luigi', 'Neri', 'luigineri@gmail.com', '22/2/1988');
	$seller2 = new Seller('Marco', 'Verdi', 'marcoverdi@gmail.com', '30/3/1955');

	$negozioACaso->addBuyer($buyer1);
	$negozioACaso->addBuyer($buyer2);
	$negozioACaso->addSeller($seller1);
	$negozioACaso->addSeller($seller2);

	$food1 = new Food('Carote', '1.00€', $seller1);
	$tech1 = new Tech('Computer', '500.00€', $seller2);
	$jewel1 = new Jewelry('Anello', '200.00€', $seller1);

	$negozioACaso->addItem($food1);
	$negozioACaso->addItem($tech1);
	$negozioACaso->addItem($jewel1);

	$buyer1->buyItem($tech1);
	$buyer1->buyItem($food1);
	$buyer2->buyItem($jewel1);
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Shop</title>
	</head>
	<body>
		<pre>
			Benvenuti su <?php echo $negozioACaso->getName(); ?>!
			Su questo sito sono presenti i seguenti prodotti:
			<ul>
				<?php
					foreach ($negozioACaso->getItems() as $item) {
				?>
				<li><?php echo $item->getName(); ?> - Prezzo <?php echo $item->getPrice(); ?>, venduto da <?php echo $item->getSeller()->getName() . ' ' . $item->getSeller()->getSurname(); ?></li>
				<?php
					}
				?>
			</ul>

			<?php
				foreach ($negozioACaso->getBuyers() as $buyer) {
			?>
			L'utente <?php echo $buyer->getName() . ' ' . $buyer->getSurname(); ?> ha provato ad acquistare i prodotti:
			<ul>
				<?php
					foreach ($buyer->getTransactions() as $item) {
				?>
				<li><?php echo $item->getName(); ?> - Prezzo <?php echo $item->getPrice(); ?>, venduto da <?php echo $item->getSeller()->getName() . ' ' . $item->getSeller()->getSurname(); ?> con risultato:
				<?php
				try {
					$buyer->validateCreditCard($buyer->getCreditCard());
				} catch (Exception $expiredCard) {
					echo $expiredCard->getMessage();
				}
				?></li>
				<?php
					}
				?>
			</ul>
			<?php
				}
			?>
		</pre>
	</body>
</html>
