<?php

$config = include 'config.php';

$to = $config['to_email'];
$subject = $config['subject'];
$message = $config['message'];

$smtpServer = $config['smtp_server'];
$smtpUsername = $config['smtp_username'];
$smtpPassword = $config['smtp_password'];
$smtpPort = $config['smtp_port'];

$servername = $config['db_server'];
$username = $config['db_username'];
$password = $config['db_password'];
$dbname = $config['db_name'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM cases WHERE email_sent = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $message .= "\nCase: " . $row['name'] . ", File Url: " . $row['fileUrl'] . ", Date: " . $row['date'];

    $updateSql = "UPDATE cases SET email_sent = 1 WHERE id = " . $row["id"];
    $conn->query($updateSql);
  }

  $headers = "From: " . $smtpUsername;
  echo"". $subject ."". $message ."";
  if (mail($to, $subject, $message, $headers)) {
    echo "E-mail successfully sent.";
  } else {
    echo "Error sending the e-mail.";
  }
} else {
  echo "There are no new cases.";
}

$conn->close();

?>