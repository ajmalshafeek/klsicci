<?php  



class cleanto_login_check{

    public $conn;

    public $remember;

    public $cookie_passwords;

    /* check the admin */

    public function checkadmin($name,$password){

        $query = "select * from `ct_admin_info` where `email` = '".$name."' and `password` = '".$password."'";

        $result=mysqli_query($this->conn,$query);

        $value=mysqli_fetch_assoc($result);

        if($value['id']!=0){

            if($value['role']=="admin"){

				$_SESSION['ct_adminid'] = $value['id'];

				$_SESSION['ct_useremail'] = $value['email'];

			}else{

				$_SESSION['ct_staffid'] = $value['id'];

				$_SESSION['ct_useremail'] = $value['email'];				

			}

			

                if($this->remember == "true"){

                    setcookie('cleanto_username',$name, time() + (86400 * 30), "/");

                    setcookie('cleanto_password',$this->cookie_passwords, time() + (86400 * 30), "/");

                    setcookie('cleanto_remember',"checked", time() + (86400 * 30), "/");

                }

                else{

                    unset($_COOKIE['cleanto_username']);

                    unset($_COOKIE['cleanto_password']);

                    unset($_COOKIE['cleanto_remember']);

                    setcookie('cleanto_username',null, -1, '/');

                    setcookie('cleanto_password',null, -1, '/');

                    setcookie('cleanto_remember',null, -1, '/');

                }

           // echo "yesadmin";

        }else{

            $query = "select * from `ct_users` where `user_email` = '".$name."' and `user_pwd` = '".$password."'";

            $result=mysqli_query($this->conn,$query);

            $value=mysqli_fetch_assoc($result);

            if($value['id']!=0){

                $_SESSION['ct_login_user_id'] = $value['id'];

                $_SESSION['ct_useremail'] = $value['user_email'];

                    if($this->remember == "true"){

                        setcookie('cleanto_username',$name, time() + (86400 * 30), "/");

                        setcookie('cleanto_password',$this->cookie_passwords, time() + (86400 * 30), "/");

                        setcookie('cleanto_remember',"checked", time() + (86400 * 30), "/");

                    }

                    else{

                        unset($_COOKIE['cleanto_username']);

                        unset($_COOKIE['cleanto_password']);

                        unset($_COOKIE['cleanto_remember']);

                        setcookie('cleanto_username',null, -1, '/');

                        setcookie('cleanto_password',null, -1, '/');

                        setcookie('cleanto_remember',null, -1, '/');

                    }

              //  echo "yesuser";

            }else{

             //   echo 'no';

            }

        }

    }

    /* forgot password */

    public function getuserpassword($email){

        $query = "select `password` from `ct_admin_info` where `email` = '".$email."'";

        $result=mysqli_query($this->conn,$query);

        $value=mysqli_fetch_row($result);

        if($value[0]!=0){

 //          echo "yes";

        }

        else

        {

 //           echo "no";

        }

    }

    public function resetpassword($id,$newpassword){

        $query = "UPDATE `ct_users` SET `user_pwd` = '".$newpassword."' WHERE `id` = '".$id."'";

        $result=mysqli_query($this->conn,$query);

    }

}

?>