<!--File		: view_customer+prepared.php
	Anggota		:	-Dorino Baharson		            24060121130090
					-Varrel								24060121130062
					-Handhika Pranata Kusuma Wardana	24060121140112
					-Fitra Syamli Yudhisaputra		    24060121140124
					-Fa’iq Rindha Maulana				24060121130091
	Deskripsi	: menampilkan data customers (dengan tambahan implementasi prepared statement)
-->
<?php include('./book_handler/header.php') ?>
<br>
<div class="card">
	<div class="card-header">Customers Data</div>
	<div class="card-body">
		<br>
		<p>Contoh SQL Transaction</p>

		<?php
		// Include our login information
		require_once('./db.php');

		//start transaction
		$db->autocommit(FALSE);
		$db->begin_transaction();
		$query_ok = TRUE;

		//cek query
		$customerid = 1;
		$amount = 300;
		$date = '2022-06-01';
		$orderid = 1004;
		$books = array(
			'0-672-31697-8' => 1,
			'0-672-31769-9' => 2,
			'0-672-31509-2' => 3
		);

		//prepare
		$stmt1 = $db->prepare("INSERT INTO orders (orderid, customerid, amount, date) VALUES (?, ?, ?, ?)");
		if (!$stmt1) {
			$query_ok = FALSE;
			die("Could not prepare the statement: <br />" . $db->error);
		}
		//binding
		$stmt1->bind_param("iiis", $orderid, $customerid, $amount, $date);
		//execute
		$stmt1->execute();
		if (!$stmt1->execute()) {
			$query_ok = FALSE;
			die("Could not execute the statement: <br />" . $stmt1->error);
		}
		$result1 = $stmt1->get_result();


		foreach ($books as $isbn => $quantity) {
			//prepare
			$stmt2 = $db->prepare("INSERT INTO order_items (orderid, isbn, quantity) VALUES (?, ?, ?)");
			if (!$stmt2) {
				$query_ok = FALSE;
				die("Could not prepare the statement: <br />" . $db->error);
			}
			//binding
			$stmt2->bind_param("isi", $orderid, $isbn, $quantity);
			//execute
			$stmt2->execute();
			if (!$stmt2->execute()) {
				$query_ok = FALSE;
				die("Could not execute the statement: <br />" . $stmt2->error);
			}
			$result2 = $stmt2->get_result();
		}

		//commit the query
		if ($query_ok) {
			$db->commit();
			echo "Eksekusi berhasil!!!";
		} else {
			$db->rollback();
			echo "Eksekusi Gagal!!!";
		}

		//close connection
		$stmt1->close();
		$stmt2->close();

		?>
	</div>
</div>
<?php include('./book_handler/footer.php') ?>