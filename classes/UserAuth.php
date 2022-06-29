<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh
{
    private $db;

    public function __construct(){
        $this->db = new Dbh();
    }

  public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if($this->confirmPasswordMatch($password, $confirmPassword))
        {
            if($this->checkUserExist($email))
                die("Account Already in existence");
            $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
            if($conn->query($sql)){
               echo "USER SUCESSFULLY REGISTERED";
            } else {
                echo "Opps". $conn->error;
            }
           
        }
        else{
            echo "Password does not match";
        }
    }       

  public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $_SESSION['email'] = $email;
            header("Location: ../UserAuthOOPMYSQL/dashboard.php");
        } 
        else {
            header("Location:../UserAuthOOPMYSQL/forms/login.php");
        }
    }

  public function getUser($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE email = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function checkUserExist($email){
        $conn = $this->db->connect();
        $sql = "SELECT id FROM Students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result){
            if($result->num_rows > 0)
                return true;
            return false;
        }else{
            exit($conn->error);
        }
    }

 public function getAllUsers()
 {
        $conn = $this->db->connect();
        $sql = "SELECT * from students";
$result = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        table,th,td{
            border:1px solid black;
        }
    </style>
</head>
<body>
    
<center><h1><u> ZURI PHP STUDENTS </u></h1> </center>
    <table style= "background-color: magenta;" width = "100%" border-style ="none">
        <thead>
            <tr style='height: 40%'>
                <th>ID</th>
                <th>Full Names</th> 
                <th>Email</th>
                <th>Gender</th>
                <th>Country</th>
                <th>Action</th>
            </tr>
        </thead>
            <tbody>
         <?php
            if(mysqli_num_rows($result) > 0)
             {
               while($data = mysqli_fetch_assoc($result))
               {
            
            //show data
            ?>
             <tr style='height: 30px'>
                <td style= "background:blue;" width = "50px"><?php if(isset($data["id"])) echo $data['id'];?></td>
                <td><?php if(isset($data["Full_names"])) echo $data["Full_names"];?></td> 
                <td><?php if(isset($data["Email"])) echo $data["Email"];?></td>
                <td><?php if(isset($data["Gender"])) echo $data["Gender"];?></td>
                <td><?php if(isset($data["Country"])) echo $data["Country"];?></td>
                 <td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id' value="<?php if(isset($data['id'])) echo $data["id"];?>"/>
                    <button class='btn btn-danger' type='submit' name='delete'> DELETE </button> 
                </form> 
                </td>
             </tr>
        <?php
        }
    } 
    ?>
             </tbody>
            </table>
            </body>
            </html>
     <?php
} 
   public function deleteUser($id){
        $conn = $this->db->connect();
        $sql = "DELETE FROM students WHERE id = '$id'";
        if($conn->query($sql) == TRUE){
            header("location:/UserAuthOOPMYSQL/action.php?all");
        } else {
            // header("refresh:0.5; url=action.php?all=?message=Error");
            echo "ERROR OCCURED";
        }
    }

   public function updateUser($username, $password){
        $conn = $this->db->connect();
        $sql = "UPDATE students SET password = '$password' WHERE email = '$username'";
        if($conn->query($sql)){
            header("Location: forms/login.php?update=success");
        } else {
            echo mysqli_error($conn);
            // header("Location: forms/resetpassword.php?error=1");
        }
    }

  public function getUserByUsername($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

   public function logout($username){
        session_start();
        session_destroy();
        header('Location: index.php');
    }

  public function confirmPasswordMatch($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }
}
?>