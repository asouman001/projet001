<?php

if (isset($_POST['soumettre'])) {

    // Vérification des champs et validation
    if (empty($_POST['parcours'])) {
        $message = "Choisir un parcours valide.";
    } elseif (empty($_POST['mode'])) {
        $message = "Choisir un mode valide.";
    } elseif (empty($_POST['nom']) || !ctype_alpha($_POST['nom'])) {
        $message = "Votre nom est incorrect.";
    } elseif (empty($_POST['prenoms']) || !ctype_alpha($_POST['prenoms'])) {
        $message = "Votre prénom est incorrect.";
    } elseif (empty($_POST['date_naissance'])) {
        $message = "La date de naissance est requise.";
    } elseif (empty($_POST['contact'])) {
        $message = "Le contact est requis.";
    } elseif (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $message = "L'email est invalide.";
    } elseif (empty($_POST['mot_de_passe']) || !ctype_alnum($_POST['mot_de_passe'])) {
        $message = "Le mot de passe est invalide.";
    } elseif (empty($_FILES['curriculum']['name'])) {
        $message = "Choisir un document CV.";
    } elseif (empty($_FILES['lettre_motivation']['name'])) {
        $message = "Choisir une lettre de motivation.";
    } elseif (empty($_FILES['dernier_diplome']['name'])) {
        $message = "Choisir un dernier diplôme.";
    } elseif (empty($_POST['intitule_diplome']) || !ctype_alpha($_POST['intitule_diplome'])) {
        $message = "Saisir l'intitulé du diplôme.";
    } elseif (empty($_POST['derniere_annee_academique'])) {
        $message = "La dernière année académique est requise.";
    } else {

        // Vérification des extensions de fichiers
        $extensions = array('pdf');

        $curriculum_extension = pathinfo($_FILES['curriculum']['name'], PATHINFO_EXTENSION);
        $lettre_motivation_extension = pathinfo($_FILES['lettre_motivation']['name'], PATHINFO_EXTENSION);
        $dernier_diplome_extension = pathinfo($_FILES['dernier_diplome']['name'], PATHINFO_EXTENSION);

        if (!in_array($curriculum_extension, $extensions)) {
            $message = "Le fichier CV doit être au format PDF.";
        } elseif (!in_array($lettre_motivation_extension, $extensions)) {
            $message = "La lettre de motivation doit être au format PDF.";
        } elseif (!in_array($dernier_diplome_extension, $extensions)) {
            $message = "Le dernier diplôme doit être au format PDF.";
        } else {

            $message1 = "Nom du fichier1: " . $_FILES['curriculum']['name'];
            $message1 .= "Nom temporair fichier1: " . $_FILES['curriculum']['tmp_name'];
            move_uploaded_file($_FILES['curriculum']['tmp_name'], "upload/" . $_FILES['curriculum']['name']);

            $message2 = "Nom du fichier2: " . $_FILES['lettre_motivation']['name'];
            $message2 .= "Nom temporair fichier2: " . $_FILES['lettre_motivation']['tmp_name'];
            move_uploaded_file($_FILES['lettre_motivation']['tmp_name'], "upload/" . $_FILES['lettre_motivation']['name']);

            $message3 = "Nom du fichier3: " . $_FILES['dernier_diplome']['name'];
            $message1 .= "Nom temporair fichier3: " . $_FILES['dernier_diplome']['tmp_name'];
            move_uploaded_file($_FILES['dernier_diplome']['tmp_name'], "upload/" . $_FILES['dernier_diplome']['name']);


            // Connexion à la base de données
            require_once "base_de_donnee.php";


            // Insertion des données dans la base de données
            $requete = $bdb->prepare('INSERT INTO exo1.etudiant(parcours, mode, nom_etudiant, prenoms_etudiant, date_naissance, contact, email, mot_de_passe, curriculum, lettre_motivation, dernier_diplome, intitule_diplome, derniere_annee_academique) 
            VALUES(:parcours, :mode, :nom, :prenoms, :date_naissance, :contact, :email, :mot_de_passe, :curriculum, :lettre_motivation, :dernier_diplome, :intitule_diplome, :derniere_annee_academique)');

            $requete->bindValue(':parcours', $_POST['parcours']);
            $requete->bindValue(':mode', $_POST['mode']);
            $requete->bindValue(':nom', $_POST['nom']);
            $requete->bindValue(':prenoms', $_POST['prenoms']);
            $requete->bindValue(':date_naissance', $_POST['date_naissance']);
            $requete->bindValue(':contact', $_POST['contact']);
            $requete->bindValue(':email', $_POST['email']);
            $requete->bindValue(':mot_de_passe', password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT));
            $requete->bindValue(':curriculum', $_FILES['curriculum']['name']);
            $requete->bindValue(':lettre_motivation', $_FILES['lettre_motivation']['name']);
            $requete->bindValue(':dernier_diplome', $_FILES['dernier_diplome']['name']);
            $requete->bindValue(':intitule_diplome', $_POST['intitule_diplome']);
            $requete->bindValue(':derniere_annee_academique', $_POST['derniere_annee_academique']);

            if ($requete->execute()) {
                $succes_message = "Inscription réussie !";
            } else {
                $message = "Erreur lors de l'inscription !";
            }
            // Redirection après l'insertion
            header("Location: index.php");
        }
    }
}

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>

<body>
    <section class="photo">

        <form action="index.php" method="post" enctype="multipart/form-data" class="main">
            <hr>
            <h4><label for="">PREINSCRIPTION 2023 EN LICENCE|MASTER EN EFFICACITE ENERGETIQUE</label></h4>
            <hr>

            <?php if (isset($message)) echo $message; ?>
            <?php if (isset($succes_message)) echo $succes_message; ?>
            <br>
                <label for="parcours">PARCOURS(choisir):
                    <select name="parcours" id="parcours">
                        <option name="parcours" value="A">parcours1</option>
                        <option name="parcours" value="B">parcours2</option>
                    </select>
                </label>
            <br>
                <label for="mode">MODE(choisir):
                    <select name="mode" id="mode">
                        <option name="mode" value="1">mode1</option>
                        <option name="mode" value="2">mode2</option>
                    </select>
                </label>
            <br>
            <label for="nom">NOM</label>
            <input id="nom" type="text" name="nom" placeholder="entrez votre nom" required>

            <label for="prenoms">PRENOMS</label>
            <input id="prenoms" type="text" name="prenoms" placeholder="entrer votre prénom" required>

            <label for="date_naissance">DATE DE NAISSANCE</label>
            <input id="date_naissance" type="date" name="date_naissance" required>

            <label for="contact">CONTACT</label>
            <input id="contact" type="text" name="contact" placeholder="Ex: 07 XX XX XX XX" required>

            <label for="email">EMAIL</label>
            <input id="email" type="text" name="email" placeholder="Ex: votremailgmail.com" required>

            <label for="mot_de_passe">MOT DE PASSE</label>
            <input id="mot_de_passe" type="password" name="mot_de_passe" placeholder="Ex: entrer le mot de passe" required>
            <br>
            <label for="curriculum">Votre Curriculum Vitae(CV)PDF
                <input id="curriculum" type="file" name="curriculum" required></label>
            <br>
            <label for="lettre_motivation">Votre lettre de motivation au format PDF
                <input id="lettre_motivation" type="file" name="lettre_motivation" required></label>
            <br>
            <label for="dernier_diplome">Votre dernier diplome au format PDF
                <input id="dernier_diplome" type="file" name="dernier_diplome" required></label>
            <br>
            <label for="intitule_diplome">Intitulé du diplome</label>
            <input id="intitule_diplome" type="text" name="intitule_diplome" required>

            <label for="derniere_annee_academique">DERNIERE ANNEE ACADEMIQUE</label>
            <input id="derniere_annee_academique" type="date" name="derniere_annee_academique" required>

            <button name="soumettre">SOUMETTRE</button>
        </form>
    </section>

</body>

</html>
