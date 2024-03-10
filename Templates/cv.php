<?php
session_start();
require_once("../connect.php");

$dsn = "mysql:dbname=".dbname.";host=".servername;
try {
    $con = new PDO($dsn, username, password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Proper error handling
} catch (PDOException $e) {
    printf("Failed: %s", $e->getMessage());
    exit();
}


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Prepare and execute the query
    $query = $con->prepare('SELECT * FROM user_info WHERE username = :username');
    $query->bindValue(':username', $username);
    $query->execute();

    // Fetch user information
    $row = $query->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $full_name = $row['nom_complet'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
        $diplome = $row['diplome'];
        $specialisation = $row['specialisation'];
        $university = $row['university'];
        $startyearstudy = $row['startyearstudy'];
        $endyearstudy = $row['endyearstudy'];
        $job_title = $row['job_title'];
        $company = $row['company'];
        $yearstartwork = $row['yearstartwork'];
        $yearendwork = $row['yearendwork'];
        $responsibilities = $row['responsibilities'];

        $skill1 = $row['skill1'];
        $skill2 = $row['skill2'];
        $skill3 = $row['skill3'];
        $skill4 = $row['skill4'];
        $linkedin = $row['linkedin'];
        $myjob = $row['myjob'];
        $bio = $row['bio'];
	$username = $row['username'];
	$photo=$row['photo'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
 

    <link rel="shortcut icon" href="icon.ico">
     
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <script defer src="https://use.fontawesome.com/releases/v5.1.1/js/all.js" integrity="sha384-BtvRZcyfv4r0x/phJt9Y9HhnN5ur1Z+kZbKVgzVBAlQZX4jvAuImlIz+bG7TS00a" crossorigin="anonymous"></script>
    <link id="theme-style" rel="stylesheet" href="assets/css/pillar-1.css">
     <link rel="stylesheet" href="style.css">
    

   <style>
    .resume-header {
      background: <?php 
        if (isset($_POST['color'])) {
          switch ($_POST['color']) {
            case '548cdd':
              echo '#548cdd';
              break;
            case '434E5E':
              echo '#434E5E';
              break;
            case 'lightblack':
              echo '#333333';
              break;
            default:
              echo '#434E5E';
              break;
          }
        } else {
          echo '#434E5E';
        }
      ?>;
      color: rgba(255, 255, 255, 0.9);
      height: 220px;
}
  #changecolor {

background-size: 100% 100%;
margin-left: 197px;
  position: absolute;

    right: 1500px;

	min-width: 72px;
	min-height: 69px;
    
      
    
background-image: url('../Files/palette.png'); /* Specify the path to your image */
	

border: 0px solid transparent;
box-shadow: rgba(0, 0, 0, 0) 0px 0px 0px;
background-color: transparent;

     

 
}
    
  </style>
</head>
<body>
  
<article class="resume-wrapper text-center position-relative">
 <form action="" method="post">
    <input type="hidden" name="color" value="<?php 
      if (isset($_POST['color'])) {
        switch ($_POST['color']) {
          case '548cdd':
            echo '434E5E';
            break;
          case '434E5E':
            echo 'lightblack';
            break;
          case 'lightblack':
            echo '548cdd';
            break;
          default:
            echo '434E5E';
            break;
        }
      } else {
        echo '548cdd';
      }
    ?>">
         <button type="submit" name="changecolor" id="changecolor" ></button> 

  </form>
    <div class="resume-wrapper-inner mx-auto text-left bg-white shadow-lg">
        <header class="resume-header pt-4 pt-md-0">
            <div class="media flex-column flex-md-row">
               <img src='<?php echo "../" . $photo; ?>' alt="Profile Picture" border="0"width="220" height="220">





                <img class="mr-3 img-fluid picture mx-auto" src="assets/images/photo.jpg" alt="">
                <div class="media-body p-4 d-flex flex-column flex-md-row mx-auto mx-lg-0">
                    <div class="primary-info">
                        <h1 class="name mt-0 mb-1 text-white text-uppercase text-uppercase"><?php echo "$full_name"; ?></h1>
                        <div class="title mb-3"><?php echo "$myjob"; ?></div>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="mailto:<?php echo $email; ?>"><i class="far fa-envelope fa-fw mr-2" data-fa-transform="grow-3"></i><?php echo $email; ?></a></li>
	                                      
<li><a><i class="fas fa-mobile-alt fa-fw mr-2" data-fa-transform="grow-6"></i><?php echo $phone; ?></a></li> 
			    <li><a><i class="fas fa-home fa-fw mr-2" data-fa-transform="grow-6"></i><?php echo $address; ?></a></li>
                        </ul>
                    </div>
                    <div class="secondary-info ml-md-auto mt-2">
                        <ul class="resume-social list-unstyled">
    <li class="mb-3">
    <a href="https://<?php echo $linkedin; ?>" target="_blank">
        <span class="fa-container text-center mr-2"><i class="fab fa-linkedin fa-fw"></i></span>Linkedin
    </a>
</li>

        
   
</ul>

                    </div>
                </div>
            </div>
        </header>
        <div class="resume-body p-5">
            <section class="resume-section summary-section mb-5">
                <h2 class="resume-section-title text-uppercase font-weight-bold pb-3 mb-3">About Me</h2>
                <div class="resume-section-content">
                    <p class="mb-0"><?php echo "$bio"; ?></p>
                </div>
            </section>
            <div class="row">
                <div class="col-lg-9">
                    <section class="resume-section experience-section mb-5">
                        <h2 class="resume-section-title text-uppercase font-weight-bold pb-3 mb-3">Work Experience</h2>
                        <div class="resume-section-content">
                            <div class="resume-timeline position-relative">
                                <article class="resume-timeline-item position-relative pb-5">
                                    <div class="resume-timeline-item-header mb-2">
                                        <div class="d-flex flex-column flex-md-row">
                                            <h3 class="resume-position-title font-weight-bold mb-1"><?php echo "$job_title"; ?></h3>
                                            <div class="resume-company-name ml-auto">
                                                <?php echo "$company"; ?>
                                            </div>
                                        </div>
                                        <div class="resume-position-time"><?php echo "$yearstartwork"; ?>  — <?php echo "$yearendwork"; ?></div>
                                    </div><!--//resume-timeline-item-header-->
                                    <div class="resume-timeline-item-desc">
                                        <p><?php echo "$responsibilities"; ?></p>
                                     
                                    </div>
                                </article>
                            </div>
                        </div>
                    </section>
                    <section class="resume-section education-section mb-5">
                        <h2 class="resume-section-title text-uppercase font-weight-bold pb-3 mb-3">Education</h2>
                        <div class="resume-section-content">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <div class="resume-degree font-weight-bold"><?php echo "$specialisation"; ?><span class="resume-company-name ml-auto" style="
    border-left-width: 500px;
    margin-left: 600px;
    padding-left: 545px;
"> <?php echo "$university";?></span></div>
                                    <div class="resume-degree-org"><?php echo "$diplome"; ?></div>
                                    <div class="resume-degree-time"><?php echo "$startyearstudy"; ?> — <?php echo "$endyearstudy"; ?></div>
 				   
                                               
                                            
                                </li>
                             
                            </ul>
                        </div>
                    </section>

 <section class="resume-section education-section mb-5">
                        <h2 class="resume-section-title text-uppercase font-weight-bold pb-3 mb-3">Skills</h2>
                        <div class="resume-section-content">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <div class="resume-degree font-weight-bold"><?php echo "-$skill1"; ?></div>
                                   <div class="resume-degree font-weight-bold"><?php echo "-$skill2"; ?></div>
 				  <div class="resume-degree font-weight-bold"><?php echo "-$skill3"; ?></div>
 				 <div class="resume-degree font-weight-bold"><?php echo "-$skill4"; ?></div>
                                </li>
                             
                            </ul>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
</article>



</body>
</html>
