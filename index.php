<?php
session_start();

require_once("connect.php");

$completed_signup = false;
$completed_login = false;
$completed_details = false;
$final = false;

$dsn = "mysql:dbname=".dbname.";host=".servername;
try {
    $con = new PDO($dsn, username, password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Proper error handling
} catch (PDOException $e) {
    printf("Failed: %s", $e->getMessage());
    exit();
}

class UserDetails {
    protected $username;
    protected $password;
    protected $nom_complet;
    protected $email;
    protected $phone;
    protected $address;
    protected $diplome;
    protected $specialisation;
    protected $university;
    protected $startyearstudy;
    protected $endyearstudy;
    protected $job_title;
    protected $company;
    protected $startyearwork;
    protected $endyearwork;
    protected $skill1;
    protected $skill2;
    protected $skill3;
    protected $skill4;
    protected $responsibilities;
    protected $bio;
    protected $myjob;
    protected $linkedin;

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setNomcomplet($nom_complet) {
        $this->nom_complet = $nom_complet;
    }

    public function setBio($bio) {
        $this->bio = $bio;
    }

    public function setResponsibilities($responsibilities) {
        $this->responsibilities = $responsibilities;
    }

    public function setMyjob($myjob) {
        $this->myjob = $myjob;
    }

    public function setLinkedin($linkedin) {
        $this->linkedin = $linkedin;
    }

    public function setSkilld($skill4) {
        $this->skill4 = $skill4;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setDiplome($diplome) {
        $this->diplome = $diplome;
    }

    public function setSpecialisation($specialisation) {
        $this->specialisation = $specialisation;
    }

    public function setUniversity($university) {
        $this->university = $university;
    }

    public function setStartyearstudy($start_yearstudy) {
        $this->startyearstudy = $start_yearstudy;
    }

    public function setEndyearstudy($end_yearstudy) {
        $this->endyearstudy = $end_yearstudy;
    }

    public function setJobtitle($job_title) {
        $this->job_title = $job_title;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

    public function setStartyearwork($start_yearwork) {
        $this->startyearwork = $start_yearwork;
    }

    public function setEndyearwork($end_yearwork) {
        $this->endyearwork = $end_yearwork;
    }

    public function setSkilla($skill1) {
        $this->skill1 = $skill1;
    }

    public function setSkillb($skill2) {
        $this->skill2 = $skill2;
    }

    public function setSkillc($skill3) {
        $this->skill3 = $skill3;
    }

    public function getStartyearstudy() {
        return $this->startyearstudy;
    }

    public function getEndyearstudy() {
        return $this->endyearstudy;
    }

    public function getStartyearwork() {
        return $this->startyearwork;
    }

    public function getEndyearwork() {
        return $this->endyearwork;
    }

    public function getSkilla() {
        return $this->skill1;
    }

    public function getSkillb() {
        return $this->skill2;
    }

    public function getSkillc() {
        return $this->skill3;
    }

    public function getBio() {
        return $this->bio;
    }

    public function getMyjob() {
        return $this->myjob;
    }

    public function getLinkedin() {
        return $this->linkedin;
    }

    public function getSkilld() {
        return $this->skill4;
    }

    public function getNomComplet() {
        return $this->nom_complet;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getDiplome() {
        return $this->diplome;
    }

    public function getSpecialisation() {
        return $this->specialisation;
    }

    public function getUniversity() {
        return $this->university;
    }

    public function getJobTitle() {
        return $this->job_title;
    }

    public function getCompany() {
        return $this->company;
    }

    public function getResponsibilities() {
        return $this->responsibilities;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }
}

class ManagerUser {
    private $con;

    public function __construct($con) {
        $this->setdb($con);
    }

    public function ajouter(UserDetails $userx, array $databasic) {
        global $completed_signup;
        try {
            $userx->setUsername($databasic['username']);
            $userx->setPassword($databasic['password']);
            $q = $this->con->prepare('insert into user_info(username, password) values (:username, :password)');
            $q->bindValue(':username', $userx->getUsername());
            $q->bindValue(':password', $userx->getPassword());
            $a = $q->execute();
            if ($a) {
                $completed_signup = true;
            } else {
                $completed_signup = false;
            }
            $_SESSION['userx'] = serialize($userx);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') { // Unique constraint violation error code
                // Handle the case when the username is already taken
                // For example, set a flag or throw a custom exception
                $completed_signup = false;
                $_SESSION['errortaken'] = "Username is already taken";
            }
        }
    }

    public function search(array $userData) {
        global $completed_login;
        global $completed_signup;
        global $completed_details;
        global $final;

        $query = $this->con->prepare('SELECT * FROM user_info WHERE username = :username AND password = :password');

        $query->bindValue(':username', $userData['username']);
        $query->bindValue(':password', $userData['password']);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $full_name = $result['nom_complet'];
        }

        if ($result && !is_null($full_name)) {
            $completed_login = true;
            $_SESSION['username'] = $userData['username'];
        } elseif ($result && is_null($full_name)) {
            $completed_signup = true;
            $userx = new UserDetails();
            $userx->setUsername($userData['username']);
            $userx->setPassword($userData['password']);
            $_SESSION['userx'] = serialize($userx);
        } else {
            $completed_login = false;
            $_SESSION['errorincorrect'] = "Utilisateur ou mot de passe incorrect";
        }
    }

    public function insertion(UserDetails $userx, array $userinfo) {

        foreach ($userinfo as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($userx, $method)) {
                $userx->$method($value);
            }
        }

        $photo_temp = $_FILES['photo']['tmp_name'];
        $photo_name = $_FILES['photo']['name'];
        $file_extension = pathinfo($photo_name, PATHINFO_EXTENSION); // Get the file extension
        $username_prefix = $userx->getUsername() . "_pic."; // Username prefix for the photo name
        $photo = 'uploads/' . $username_prefix . $file_extension; // Assuming you have an 'uploads' directory
        move_uploaded_file($photo_temp, $photo);

        $sql = "UPDATE user_info 
                SET nom_complet=?, email=?, phone=?, address=?, diplome=?, specialisation=?, university=?, startyearstudy=?, endyearstudy=?, job_title=?, company=?, yearstartwork=?, yearendwork=?,responsibilities=?,skill1=?,skill2=?,skill3=?,skill4=?,linkedin=?,bio=?,myjob=?, photo=?
                WHERE username=?";

        $stmt = $this->con->prepare($sql);
        $stmt->execute([$userx->getNomComplet(), $userx->getEmail(), $userx->getPhone(), $userx->getAddress(), $userx->getDiplome(), $userx->getSpecialisation(), $userx->getUniversity(), $userx->getStartyearstudy(), $userx->getEndyearstudy(), $userx->getJobTitle(), $userx->getCompany(), $userx->getStartyearwork(), $userx->getEndyearwork(), $userx->getResponsibilities(), $userx->getSkilla(), $userx->getSkillb(), $userx->getSkillc(), $userx->getSkilld(), $userx->getLinkedin(), $userx->getBio(), $userx->getMyjob(), $photo, $userx->getUsername()]);
        global $final;
        if ($stmt) {
            $final = true;
            $_SESSION['username'] = $userx->getUsername();
        } else {
            $final = false;
        }
    }

    public function deleteaccount() {
        $username = $_SESSION['username'];
        $query = $this->con->prepare('DELETE FROM user_info WHERE username = :username');
        $query->bindValue(':username', $username);
        $success = $query->execute();
        global $final;
        global $completed_login;
        global $completed_signup;
        if ($success) {
            $_SESSION['deletedacc'] = "Le compte a été supprimé";

            $completed_signup = false;
            $final = false;
            $completed_login = false;
        }
    }

    public function setdb(PDO $con) {
        $this->con = $con;
    }
}

$manager = new ManagerUser($con);
if (isset($_POST['signup']) && isset($_POST['username']) && isset($_POST['password'])) {
    $databasic = array('username' => $_POST['username'], 'password' => $_POST['password']);
    $userx = new UserDetails();
    $manager->ajouter($userx, $databasic);
}

if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
    $databasic = array('username' => $_POST['username'], 'password' => $_POST['password']);

    $manager->search($databasic);
}

if (isset($_POST['delete'])) {

    $manager->deleteaccount();
}

if (isset($_POST['save_info']) && isset($_POST['bio']) && isset($_POST['myjob']) && isset($_POST['nom_complet']) && isset($_POST['skill4']) && isset($_POST['linkedin']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address']) && isset($_POST['diplome']) && isset($_POST['specialisation']) && isset($_POST['university']) && isset($_POST['start_yearstudy']) && isset($_POST['end_yearstudy']) && isset($_POST['job_title']) && isset($_POST['company']) && isset($_POST['start_yearwork']) && isset($_POST['end_yearwork']) && isset($_POST['skill1']) && isset($_POST['skill2']) && isset($_POST['skill3']) && isset($_POST['responsibilities'])) {

    if (isset($_SESSION['userx'])) {
        $storedObj = unserialize($_SESSION['userx']);
        $userinfo = array(
            'nomcomplet' => ucfirst($_POST['nom_complet']),
            'email' => ucfirst($_POST['email']),
            'phone' => $_POST['phone'],
            'address' => ucfirst($_POST['address']),
            'diplome' => ucfirst($_POST['diplome']),
            'specialisation' => ucfirst($_POST['specialisation']),
            'university' => ucfirst($_POST['university']),
            'startyearstudy' => $_POST['start_yearstudy'],
            'endyearstudy' => $_POST['end_yearstudy'],
            'jobtitle' => ucfirst($_POST['job_title']),
            'company' => ucfirst($_POST['company']),
            'startyearwork' => $_POST['start_yearwork'],
            'endyearwork' => $_POST['end_yearwork'],
            'skilla' => ucfirst($_POST['skill1']),
            'skillb' => ucfirst($_POST['skill2']),
            'skillc' => ucfirst($_POST['skill3']),
            'skilld' => ucfirst($_POST['skill4']),
            'linkedin' => $_POST['linkedin'],
            'bio' => ucfirst($_POST['bio']),
            'myjob' => ucfirst($_POST['myjob']),
            'responsibilities' => ucfirst($_POST['responsibilities'])
        );
        $manager->insertion($storedObj, $userinfo);
    } else {
        // Handle error: UserDetails object not found in session
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up & Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
<?php if($final == true): ?>
<div class="cv-links-container">
    <a href="Templates\cv.php" target="_blank" class="cv-link">
        <img src="Files/CVEN.png" alt="View CV">
    </a>

    <a href="Templates\cv-fr.php" target="_blank" class="cv-link">
        <img src="Files/CVFR.png" alt="View CV">
    </a>
</div>

<div class="logout-container">
    <a href="logout.php" target="_blank" class="logout">
        <img src="Files/logout.png" alt="View CV" id="second">
    </a>
</div>

<form action="" method="post">
    <input type="submit" name="delete" id="first" value="">
</form>
</div>
<?php endif; ?>

<?php if($completed_login == true): ?>

<div class="cv-links-container">
    <a href="Templates\cv.php" target="_blank" class="cv-link">
        <img src="Files/CVEN.png" alt="View CV">
    </a>

    <a href="Templates\cv-fr.php" target="_blank" class="cv-link">
        <img src="Files/CVFR.png" alt="View CV">
    </a>
</div>

<div class="logout-container">
    <a href="logout.php" target="_blank" class="logout">
        <img src="Files/logout.png" id="second" alt="View CV">
    </a>
</div>

<form action="" method="post">
    <input type="submit" name="delete" id="first" value="">
</form>
</div>

<?php endif; ?>

<?php if ($completed_signup == false && $final == false && $completed_login == false): ?>
<form id="signup-form" method="post" action="">
    <div class="container">
        <div class="banner">

            <h1>Construisez votre CV facilement</h1>
            <h3>Créez un CV professionnel en quelques minutes</h3>
            <h3>Libérez votre potentiel professionnel dès aujourd'hui !</h3>

        </div>
        <div class="form">
            <img src="https://i.pinimg.com/236x/4d/a8/bb/4da8bb993057c69a85b9b6f2775c9df2.jpg" alt="profile" width="70"
                 id="profile-image">

            <input type="text" name="username" placeholder="Utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <?php

            if (isset($_SESSION['errortaken'])) {
                // Display error message with a class for styling
                echo '<div class="error">' . $_SESSION['errortaken'] . '</div>';
                // Unset the error session variable to clear it
                unset($_SESSION['errortaken']);
            }

            if (isset($_SESSION['errorincorrect'])) {
                // Display error message with a class for styling
                echo '<div class="error">' . $_SESSION['errorincorrect'] . '</div>';
                // Unset the error session variable to clear it
                unset($_SESSION['errorincorrect']);
            }

            if (isset($_SESSION['deletedacc'])) {
                // Display error message with a class for styling
                echo '<div class="error">' . $_SESSION['deletedacc'] . '</div>';
                // Unset the error session variable to clear it
                unset($_SESSION['deletedacc']);
                session_destroy();
            }

            ?>
            <br>
            <input type="submit" name="login" value="Connexion">
            <input type="submit" name="signup" value="Inscrivez-vous">


        </div>

    </div>

            

</form>

<?php endif; ?>

<?php if($completed_signup && !$completed_details && $final == false): ?>
<div id="user-details-form">

    <form id="signup-details" action="" method="POST" enctype="multipart/form-data">
        <legend><h2 id="basicInfo">Vos informations de base</h2></legend>

        <input type="text" id="nom_complet" name="nom_complet" required placeholder="Nom complet*">


        <input type="email" id="email" name="email" required placeholder="Email*">
        <input type="phone" id="phone" name="phone" required placeholder="Numéro tel*">


        <input type="text" id="address" name="address" required placeholder="Adresse*">
        <br>


        <input type="text" id="linkedin" name="linkedin" required placeholder="Linkedin*">

        <input type="text" id="myjob" name="myjob" required placeholder="Poste*">
        <br>

        <label for="bio">Bio*</label><br>
        <textarea id="bio" name="bio" rows="5" cols="30" required></textarea>
        <br>
        <br>
        <input type="text" id="diplome" name="diplome" required placeholder="Diplome*">


        <input type="text" id="specialisation" name="specialisation" required placeholder="Specialisation*">


        <input type="text" id="university" name="university" required placeholder="Université*">


        <input type="number" id="start_yearstudy" name="start_yearstudy" required placeholder="Année de début*">


        <input type="number" id="end_yearstudy" name="end_yearstudy" required placeholder="Année de fin*">

        <br>
        <input type="text" id="skill1" name="skill1" required placeholder="Skill1*">


        <input type="text" id="skill2" name="skill2" required placeholder="Skill2*">


        <input type="text" id="skill3" name="skill3" required placeholder="Skill3*">

        <input type="text" id="skill4" name="skill4" required placeholder="Skill4*">
        <br><br>

        <input type="text" id="job_title" name="job_title" required placeholder="Poste*">


        <input type="text" id="company" name="company" required placeholder="Entreprise*">


        <input type="number" id="start_yearwork" name="start_yearwork" required placeholder="Année de début*">


        <input type="number" id="end_yearwork" name="end_yearwork" required placeholder="Année de fin*">

        <br>
        <br>

        <label for="responsibilities">Responsabilités*</label><br>
        <textarea id="responsibilities" name="responsibilities" rows="5" cols="30" required></textarea>


        <input type="file" name="photo" accept="image/*" style="color: white;">

        <br>

        <input type="submit" name="save_info" value="Enregistrer les informations">

    </form>
</div>
<?php endif; ?>


</body>
</html>
