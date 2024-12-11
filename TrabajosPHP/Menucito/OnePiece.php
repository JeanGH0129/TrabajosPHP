<?php include "footer.php";?>
<h1>One Piece</h1>
<?php
echo readfile("OnePiece.txt");
?>
<br> <br>
<?php
echo "<p>Copyright &copy; 2024-" . date("Y/m/d") . " JeanD.monkey</p>";
?>