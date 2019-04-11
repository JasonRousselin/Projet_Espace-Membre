
<?php
//On démarre les sessions
session_start();
//On saisit les valeurs de notre Base de données
$bdd = new PDO ('mysql:host=localhost;port=3306;dbname=espace_membre','root', 'root');
//On vérifie que l'identifiant de l'utilisateur est défini
if(isset($_GET['id']) AND $_GET['id'] > 0) {
   $getid = intval($_GET['id']);
   $requser = $bdd->prepare('SELECT * FROM membres WHERE id = ?');
   $requser->execute(array($getid));
   $userinfo = $requser->fetch();
   //On affiche le formulaire
?>
<html>
   <head>
      <title>Projet Personnel</title>
      <meta charset="utf-8">
   </head>
   <body>
      <div align="center">
         <h2>Profil<?php echo $userinfo['pseudo']; ?></h2>
         <br /><br />
         Pseudo = <?php echo $userinfo['pseudo']; ?>
         <br />
         Mail = <?php echo $userinfo['mail']; ?>
         <br />
         <?php
         if(isset($_SESSION['id']) AND $userinfo['id'] == $_SESSION['id']) {
         ?>
         <br />
         <a href="editionprofil.php">Editer mon profil</a>
         <a href="deconnexion.php">Se déconnecter</a>
         <?php
         }
         ?>
      </div>
   </body>
</html>
<?php   
}
?>