<?php
session_start();
$host = "";
$nutzername = "";
$passwort = "";
if(isset($_POST['Submit']))
{
  $host = $_POST['server'];
  $nutzername = $_POST['nutzername'];
  $passwort = $_POST['passwort'];
  
  if(empty($host))
  {
    echo "<center></h1>Keine Serveradresse eingegebên!</h1></center>";   
  }
  elseif(empty($nutzername))
  {
    echo "<center></h1>Kein Nutzername eingegeben!</h1></center>";
  }
  else
  {
    $_SESSION['daten'] = true;
  }
}
if(!isset($_SESSION['daten']))
{
  echo "<center><h1>Datenbankdaten eingeben</h1>";
  echo "<form method='post' action=''>";
  echo "Serveradresse:<br><input type='text' name='server' value='$host'><br>";
  echo "Nutzername:<br><input type='text' name='nutzername' value='$nutzername'><br>";
  echo "Passwort:<br><input type='text' name='passwort' value='$passwort'><br><br>";
  echo "<input type='submit' value='Submit' name='Submit'></form></center>";
}
else
{
  $connection = new mysqli($host, $nutzername, $passwort);
  if(!$connection)
  {
    die("<center>Fehlgeschlagen: " . mysqli_connect_error() . "</center>");
  }
  else
  {
    echo("<center>Erfolgreich verbunden!</center><br>");
    $ergebnis = mysqli_query($connection, "SHOW DATABASES;");
    echo "<table border='1'>";
    while ($row = mysqli_fetch_assoc($ergebnis))
    {
      echo "<tr><th>";
      echo $row['Database'] . "</th></tr>";
      $res = mysqli_query($connection, "SHOW tables FROM " . $row['Database']);
      while($rowo = mysqli_fetch_assoc($res))
      {
        echo "<tr><td></td><td>" . $rowo['Tables_in_' . $row['Database']] . "</td></tr>";
        $befehl = "SHOW columns FROM " . $row['Database'] . "." . $rowo['Tables_in_' . $row['Database']];
        $reso = mysqli_query($connection, $befehl);
        echo "<tr><th></th><th></th><th>Feld</th><th>Typ</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($rowt = mysqli_fetch_assoc($reso))
        {
          echo "<tr><td></td><td></td>";
          echo "<td>" . $rowt['Field'] . "</td>";
          echo "<td>" . $rowt['Type'] . "</td>";
          echo "<td>" . $rowt['Null'] . "</td>";
          echo "<td>" . $rowt['Key'] . "</td>";
          echo "<td>" . $rowt['Default'] . "</td>";
          echo "<td>" . $rowt['Extra'] . "</td></tr>";  
        }
      }
    }
    echo "</table>";
  }
}
?>